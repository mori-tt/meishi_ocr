<?php

namespace App\UseCases;

use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\MessageType;

class BusinessCardDetailViewUseCase implements UseCaseInterface
{
    /**
     * @return Message[]
     */
    public function execute(): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $messages = [
            new TextMessage([
                'type' => MessageType::TEXT,
                'text' => "名刺を見る機能を実装してみましょう！",
            ]),
        ];

        Log::info(json_encode([__METHOD__, '[END]']));
        return $messages;
    }
}
