<?php

namespace App\UseCases;

use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\MessageType;

class EchoUseCase implements UseCaseInterface
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return Message[]
     */
    public function execute(): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $messages = [
            new TextMessage([
                'type' => MessageType::TEXT,
                'text' => '対応していないメッセージです。',
            ]),
            new TextMessage([
                'type' => MessageType::TEXT,
                'text' => $this->text, // NOTE: 送られたメッセージをそのまま返す（オウム返し）
            ]),
        ];

        Log::info(json_encode([__METHOD__, '[END]']));
        return $messages;
    }
}
