<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EventHandlers\MessageEventHandler;
use App\Infrastructures\Api\MessagingApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\Constants\HTTPHeader;
use LINE\Parser\EventRequestParser;
use LINE\Parser\Exception\InvalidEventRequestException;
use LINE\Parser\Exception\InvalidSignatureException;
use LINE\Webhook\Model\MessageEvent;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LineController extends Controller
{
    public function post(Request $request)
    {
        try {
            Log::info(json_encode([__METHOD__, '[START]']));
            Log::debug(json_encode([__METHOD__, $request->getPayload()]));

            $requestBody = $request->getContent();
            Log::debug(json_encode([__METHOD__, 'requestBody', $requestBody]));

            if (empty($requestBody)) {
                return response('ok');
            }

            $channelSecret = config('services.line.channel_secret');
            $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
            Log::debug(json_encode([__METHOD__, 'channelSecret', substr($channelSecret, 0, 5) . '*****']));
            Log::debug(json_encode([__METHOD__, 'signature', substr($signature, 0, 5) . '*****']));

            $messagingApi = new MessagingApi();

            $parsedEvents = EventRequestParser::parseEventRequest($requestBody, $channelSecret, $signature);
            $events = $parsedEvents->getEvents();
            Log::debug(json_encode([__METHOD__, $events]));

            foreach ($events as $event) {
                if ($event instanceof MessageEvent) {
                    $eventHandler = new MessageEventHandler($event);

                    $replyToken = $event->getReplyToken();
                    $messages = $eventHandler->handle();
                    $messagingApi->replyMessage($replyToken, $messages);
                }
            }

            return response('ok');
        } catch (InvalidEventRequestException | InvalidSignatureException $e) {
            Log::info(json_encode([__METHOD__, $e->getMessage()]));
            Log::info(json_encode([__METHOD__, $e]));
            throw new BadRequestException($e->getMessage());
        } finally {
            Log::info(json_encode([__METHOD__, '[END]']));
        }
    }
}
