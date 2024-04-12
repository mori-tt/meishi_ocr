<?php

namespace App\UseCases;

use App\Infrastructures\Databases\BusinessCardInfrastructure;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Model\CarouselColumn;
use LINE\Clients\MessagingApi\Model\CarouselTemplate;
use LINE\Clients\MessagingApi\Model\MessageAction;
use LINE\Clients\MessagingApi\Model\TemplateMessage;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\ActionType;
use LINE\Constants\MessageType;
use LINE\Constants\TemplateType;

class BusinessCardListViewUseCase implements UseCaseInterface
{
    private BusinessCardInfrastructure $businessCardInfrastructure;

    public function __construct(BusinessCardInfrastructure $businessCardInfrastructure = null)
    {
        $this->businessCardInfrastructure = $businessCardInfrastructure ?? new BusinessCardInfrastructure();
    }

    /**
     * @return Message[]
     */
    public function execute(): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $businessCardDomains = $this->businessCardInfrastructure->list();

        if (count($businessCardDomains) === 0) {
            $messages = [
                new TextMessage([
                    'type' => MessageType::TEXT,
                    'text' => "名刺がまだ登録されていません。\n名刺の写真を送って登録してください。",
                ]),
            ];

            Log::info(json_encode([__METHOD__, '[END]']));
            return $messages;
        }

        $carouselColumns = [];
        foreach ($businessCardDomains as $businessCardDomain) {
            $carouselColumns[] = new CarouselColumn([
                'title' => $businessCardDomain->getName(),
                'text' => $businessCardDomain->getCompanyName(),
                'thumbnailImageUrl' => $businessCardDomain->getImageFullUrl(),
                'actions' => [
                    new MessageAction([
                        'type' => ActionType::MESSAGE,
                        'label' => '名刺を見る',
                        'text' => '名刺を見る:' . $businessCardDomain->getId(),
                    ]),
                ],
            ]);
        }
        $templateMessage = new TemplateMessage([
            'type' => MessageType::TEMPLATE,
            'altText' => '名刺一覧',
            'template' => new CarouselTemplate([
                'type' => TemplateType::CAROUSEL,
                'columns' => $carouselColumns,
            ]),
        ]);
        Log::debug(json_encode([__METHOD__, '$templateMessage', json_encode($templateMessage)]));

        $messages = [$templateMessage];

        Log::info(json_encode([__METHOD__, '[END]']));
        return $messages;
    }
}
