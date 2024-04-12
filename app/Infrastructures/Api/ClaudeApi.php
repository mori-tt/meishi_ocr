<?php

namespace App\Infrastructures\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeApi
{
    const string URL = 'https://api.anthropic.com/v1/messages';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.claude.api_key');
    }

    public function messages(ClaudeApiRequest $request): ClaudeApiResponse
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $requestBody = $request->getBody();

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json'
        ])->post(self::URL, $requestBody);

        $body = json_decode($response->body(), true);

        Log::debug(json_encode([__METHOD__, '$body', json_encode($body)]));
        Log::info(json_encode([__METHOD__, '[END]']));

        return new ClaudeApiResponse($body);
    }
}
