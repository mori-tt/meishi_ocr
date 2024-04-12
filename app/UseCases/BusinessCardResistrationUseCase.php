<?php

namespace App\UseCases;

use App\Domains\BusinessCard;
use App\Domains\Repositories\BusinessCardRepository;
use App\Infrastructures\Api\ClaudeApi;
use App\Infrastructures\Api\ClaudeApiRequest;
use App\Infrastructures\Api\MessagingApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\MessageType;
use LINE\Webhook\Model\ImageMessageContent;

class BusinessCardResistrationUseCase implements UseCaseInterface
{
    private ImageMessageContent $imageMessageContent;
    private ClaudeApi $claudeApi;
    private MessagingApi $messagingApi;
    private BusinessCardRepository $businessCardRepository;

    public function __construct(
        ImageMessageContent $imageMessageContent,
        ClaudeApi $claudeApi = null,
        MessagingApi $messagingApi = null,
        BusinessCardRepository $businessCardRepository = null,
    ) {
        $this->imageMessageContent = $imageMessageContent;
        $this->claudeApi = $claudeApi ?? new ClaudeApi();
        $this->messagingApi = $messagingApi ?? new MessagingApi();
        $this->businessCardRepository = $businessCardRepository ?? new BusinessCardRepository();
    }

    /**
     * @return Message[]
     */
    public function execute(): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        // 送信された画像をダウンロードする
        // @SEE https://github.com/line/line-bot-sdk-php/blob/master/examples/KitchenSink/src/LINEBot/KitchenSink/EventHandler/MessageHandler/ImageMessageHandler.php#L82
        $messageId = $this->imageMessageContent->getId();
        $sfo = $this->messagingApi->getContent($messageId);
        $image = $sfo->fread($sfo->getSize());
        $imageName = $messageId . '.jpeg';
        $imagePath = 'public/' . $imageName;

        // ストレージに保存する
        Storage::put($imagePath, $image);

        // Claude API で文字起こし&構造化
        $prompt = ClaudeApiRequest::getPrompt();
        $imageType = 'image/jpeg';
        $claudeRequest = new ClaudeApiRequest($prompt, $imageType, $imagePath);
        // NOTE: 上位モデルを使う場合は、第4引数にモデルを指定する
        // $claudeRequest = new ClaudeApiRequest($prompt, $imageType, $imagePath, ClaudeApiRequest::MODEL_CLAUDE_3_SONNET);
        // $claudeRequest = new ClaudeApiRequest($prompt, $imageType, $imagePath, ClaudeApiRequest::MODEL_CLAUDE_3_OPUS);
        $claudeResponse = $this->claudeApi->messages($claudeRequest);

        $businessCardContent = $claudeResponse->getContent()->getTextAsArray();
        $businessCardContent['image'] = $imageName;
        $businessCard = new BusinessCard($businessCardContent);

        // 名刺の読み取りに失敗した場合、メッセージを返す
        if ($businessCard->isEmpty()) {
            $messages = [
                new TextMessage([
                    'type' => MessageType::TEXT,
                    'text' => '名刺の読み取りに失敗しました。別の写真で再度お試しください。',
                ]),
                new TextMessage([
                    'type' => MessageType::TEXT,
                    'text' => $businessCard->getContent(),
                ]),
            ];

            Log::info(json_encode([__METHOD__, '[END]']));
            return $messages;
        }

        // DB に保存
        $this->businessCardRepository->create($businessCard);

        // LINE に返すメッセージを作成
        $messages = [
            new TextMessage([
                'type' => MessageType::TEXT,
                'text' => '名刺を登録しました。',
            ]),
            new TextMessage([
                'type' => MessageType::TEXT,
                'text' => $businessCard->getContent(),
            ]),
        ];

        Log::info(json_encode([__METHOD__, '[END]']));
        return $messages;
    }
}
