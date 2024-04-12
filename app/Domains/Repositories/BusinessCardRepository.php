<?php

namespace App\Domains\Repositories;

use App\Models\Entities\BusinessCardEntity;
use App\Models\BusinessCard as BusinessCardModel;
use App\Domains\BusinessCard as BusinessCardDomain;
use Illuminate\Support\Facades\Log;

class BusinessCardRepository
{
    /**
     * @return BusinessCardEntity[]
     */
    public function list(int $limit = 5): array
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $businessCards = BusinessCardModel::orderBy('id', 'desc')->take($limit)->get();

        Log::info(json_encode([__METHOD__, '[END]']));

        $businessCardEntities = [];
        foreach ($businessCards as $businessCard) {
            $businessCardEntities[] = new BusinessCardEntity(
                $businessCard->id,
                $businessCard->name,
                $businessCard->company_name,
                $businessCard->post_code,
                $businessCard->address,
                $businessCard->phone,
                $businessCard->fax,
                $businessCard->email,
                $businessCard->image,
                $businessCard->created_at,
                $businessCard->updated_at,
            );
        }

        return $businessCardEntities;
    }

    public function findByName(string $name): BusinessCardEntity
    {
        Log::info(json_encode([__METHOD__, '[START]']));

        $businessCard = BusinessCardModel::where('name', 'LIKE', '%' . $name . '%')->first();

        Log::info(json_encode([__METHOD__, '[END]']));

        return new BusinessCardEntity(
            $businessCard->id,
            $businessCard->name,
            $businessCard->company_name,
            $businessCard->post_code,
            $businessCard->address,
            $businessCard->phone,
            $businessCard->fax,
            $businessCard->email,
            $businessCard->image,
            $businessCard->created_at,
            $businessCard->updated_at,
        );
    }

    public function create(BusinessCardDomain $businessCardDomain)
    {
        Log::info(json_encode([__METHOD__, '[START]']));
        Log::debug(json_encode([__METHOD__, json_encode($businessCardDomain->asEntityArray(), JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE));

        $businessCardModel = new BusinessCardModel();
        $businessCardModel->name = $businessCardDomain->getName();
        $businessCardModel->company_name = $businessCardDomain->getCompanyName();
        $businessCardModel->post_code = $businessCardDomain->getPostCode();
        $businessCardModel->address = $businessCardDomain->getAddress();
        $businessCardModel->phone = $businessCardDomain->getPhone();
        $businessCardModel->fax = $businessCardDomain->getFax();
        $businessCardModel->email = $businessCardDomain->getEmail();
        $businessCardModel->image = $businessCardDomain->getImage();

        $businessCardModel->save();

        Log::info(json_encode([__METHOD__, '[END]']));
    }
}
