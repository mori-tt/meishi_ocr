<?php

namespace App\Infrastructures\Api;

use Illuminate\Support\Facades\Storage;

class ClaudeApiRequest
{
    const MODEL_CLAUDE_3_OPUS = 'claude-3-opus-20240229';
    const MODEL_CLAUDE_3_SONNET = 'claude-3-sonnet-20240229';
    const MODEL_CLAUDE_3_HAIKU = 'claude-3-haiku-20240307';

    private $model;
    private $maxTokens;
    private $prompt;
    private $imageType;
    private $imagePath;

    public function __construct(
        string $prompt,
        string $imageType,
        string $imagePath,
        string $model = self::MODEL_CLAUDE_3_HAIKU,
        int $maxTokens = 1024,
    )
    {
        $this->model = $model;
        $this->maxTokens = $maxTokens;
        $this->prompt = $prompt;
        $this->imageType = $imageType;
        $this->imagePath = $imagePath;
    }

    public function getBody(): array
    {
        return [
            "model" => $this->model,
            "max_tokens" => $this->maxTokens,
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "image",
                            "source" => [
                                "type" => "base64",
                                "media_type" => $this->imageType,
                                "data" => $this->encodeBase64($this->imagePath),
                            ],
                        ],
                        [
                            "type" => "text",
                            "text" => $this->prompt,
                        ]
                    ]
                ]
            ]
        ];
    }

    private function encodeBase64(string $imagePath): string
    {
        $binaryData = Storage::get($imagePath);
        return base64_encode($binaryData);
    }

    public static function getPrompt(): string
    {
        return '
            ## 指示
            名刺に書いてある情報を下記のフォーマットで出力して

            ## 補足
            - JSON以外の文字は一切出力禁止
            - 郵便番号フォーマット例：123-4567
            - 電話番号フォーマット例：012-3456-7890
            - 読み取れなかったものは空文字のまま

            ## フォーマット
            {
                "name": "",
                "companyName": "",
                "postCode": "",
                "address": "",
                "phone": "",
                "fax": "",
                "email": ""
            }
        ';
    }
}
