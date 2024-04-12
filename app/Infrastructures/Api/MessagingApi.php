<?php

namespace App\Infrastructures\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Api\MessagingApiBlobApi;
use LINE\Clients\MessagingApi\ApiException;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Model\Message;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\MessageType;
use SplFileObject;

class MessagingApi
{
    private MessagingApiApi $apiClient;
    private MessagingApiBlobApi $blobApiClient;

    public function __construct()
    {
        $client = new Client();
        $config = new Configuration();
        $config->setAccessToken(config('services.line.channel_access_token'));

        $this->apiClient = new MessagingApiApi($client, $config);
        $this->blobApiClient = new MessagingApiBlobApi($client, $config);
    }

    /**
     * @param string $replyToken
     * @param Message[] $messages
     */
    public function replyMessage(string $replyToken, array $messages)
    {
        Log::info(json_encode([__METHOD__, '[START]']));
        Log::debug(json_encode([__METHOD__, '$replyToken', $replyToken]));
        Log::debug(json_encode([__METHOD__, '$messages', $messages]));

        $request = new ReplyMessageRequest();
        $request
            ->setReplyToken($replyToken)
            ->setMessages($messages);

        try {
            $this->apiClient->replyMessage($request);
        } catch (ApiException $e) {
            Log::error(json_encode([__METHOD__, 'リプライ時にエラーが発生しました']));
            Log::error(json_encode([__METHOD__, $e->getMessage()]));
            Log::error(json_encode([__METHOD__, $e]));

            $request = new ReplyMessageRequest();
            $request
                ->setReplyToken($replyToken)
                ->setMessages([
                    (new TextMessage())
                        ->setText("処理に失敗しました。\nお手数をおかけしますが、時間をおいて再度お試しください。")
                        ->setType(MessageType::TEXT)
                ]);
            $this->apiClient->replyMessage($request);
        } finally {
            Log::info(json_encode([__METHOD__, '[END]']));
        }
    }

    public function getContent(string $messageId): SplFileObject
    {
        return $this->blobApiClient->getMessageContent($messageId);
    }
}
