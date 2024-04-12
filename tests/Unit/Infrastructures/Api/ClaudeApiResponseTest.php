<?php

namespace Tests\Unit\Infrastructures\Api;

use App\Infrastructures\Api\ClaudeApiResponse;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ClaudeApiResponseTest extends TestCase
{
    /**
     * @dataProvider provideContent
     */
    public function test_content($expected, $input): void
    {
        Log::shouldReceive('info');
        Log::shouldReceive('debug');

        $target = new ClaudeApiResponse($input);
        $actual = $target->getContent()->getTextAsArray();
        $this->assertEquals($expected, $actual);
    }

    public function provideContent()
    {
        return [
            '正常系' => [
                '$expected' => [
                    'name' => '加瀬 薫',
                    'companyName' => 'マーチ株式会社',
                    'postCode' => '123-4567',
                    'address' => '青森県あおもり市北の浦1-2-3',
                    'phone' => '01-2345-6789',
                    'fax' => '01-2345-6789',
                    'email' => 'hello@subara shisaito.co.jp',
                ],
                '$input' => json_decode("{\"id\":\"msg_01MdZEQsH3dL57jQhUq6VxVT\",\"type\":\"message\",\"role\":\"assistant\",\"content\":[{\"type\":\"text\",\"text\":\"{\\n \\\"name\\\": \\\"\\u52a0\\u702c \\u85ab\\\",\\n \\\"companyName\\\": \\\"\\u30de\\u30fc\\u30c1\\u682a\\u5f0f\\u4f1a\\u793e\\\",\\n \\\"postCode\\\": \\\"123-4567\\\",\\n \\\"address\\\": \\\"\\u9752\\u68ee\\u770c\\u3042\\u304a\\u3082\\u308a\\u5e02\\u5317\\u306e\\u6d661-2-3\\\",\\n \\\"phone\\\": \\\"01-2345-6789\\\",\\n \\\"fax\\\": \\\"01-2345-6789\\\",\\n \\\"email\\\": \\\"hello@subara shisaito.co.jp\\\"\\n}\"}],\"model\":\"claude-3-opus-20240229\",\"stop_reason\":\"end_turn\",\"stop_sequence\":null,\"usage\":{\"input_tokens\":1193,\"output_tokens\":122}}", true),
            ]
        ];
    }
}
