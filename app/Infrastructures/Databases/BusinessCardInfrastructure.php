<?php

namespace App\Infrastructures\Databases;

use App\Domains\BusinessCard;
use App\Domains\Repositories\BusinessCardRepository;
use Illuminate\Support\Facades\Log;

class BusinessCardInfrastructure
{
    private BusinessCardRepository $businessCardRepository;

    public function __construct(BusinessCardRepository $businessCardRepository = null) {
        $this->businessCardRepository = $businessCardRepository ?? new BusinessCardRepository();
    }

    /**
     * @return BusinessCard[]
     */
    public function list(): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $businessCardEntities = $this->businessCardRepository->list();

        Log::info(json_encode([__METHOD__, '[END]']));

        $businessCardDomains = [];
        foreach ($businessCardEntities as $businessCardEntity) {
            $businessCardDomains[] = new BusinessCard($businessCardEntity->asArray());
        }

        return $businessCardDomains;
    }

    public function findByName(string $name): BusinessCard
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $businessCardEntity = $this->businessCardRepository->findByName($name);

        Log::info(json_encode([__METHOD__, '[END]']));

        return new BusinessCard($businessCardEntity->asArray());
    }

    public function create(BusinessCard $businessCardDomain)
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $this->businessCardRepository->create($businessCardDomain);

        Log::info(json_encode([__METHOD__, '[END]']));
    }
}
