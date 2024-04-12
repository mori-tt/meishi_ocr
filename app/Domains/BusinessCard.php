<?php

namespace App\Domains;

use Illuminate\Support\Facades\Log;

class BusinessCard
{
    private int $id;
    private string $name;
    private string $companyName;
    private string $role;
    private string $postCode;
    private string $address;
    private string $phone;
    private string $fax;
    private string $email;
    private string $image;

    public function __construct(array $data) {
        Log::info(json_encode([__METHOD__, '[START]']));
        Log::info(json_encode([__METHOD__, '$data', json_encode($data, JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE));

        $this->id = $data['id'] ?? -1;
        $this->name = $data['name'] ?? '';
        $this->companyName = $data['companyName'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->postCode = $data['postCode'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->fax = $data['fax'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->image = $data['image'] ?? '';

        Log::info(json_encode([__METHOD__, '[START]']));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getImageFullUrl(): string
    {
        return implode("/", [
            config('app.url'),
            "storage",
            $this->image,
        ]);
    }

    public function getContent(): string
    {
        return
            '・名前：' . $this->name . "\n" .
            '・会社：' . $this->companyName . "\n" .
            '・役職：' . $this->role . "\n" .
            '・郵便番号：' . $this->postCode . "\n" .
            '・住所：' . $this->address . "\n" .
            '・電話番号：' . $this->phone . "\n" .
            '・FAX：' . $this->fax . "\n" .
            '・メールアドレス：' . $this->email;
    }

    public function isEmpty(): bool
    {
        return empty($this->name);
    }

    public function asEntityArray(): array
    {
        return [
            "name" => $this->name,
            "company_name" => $this->companyName,
            "post_code" => $this->postCode,
            "address" => $this->address,
            "phone" => $this->phone,
            "fax" => $this->fax,
            "email" => $this->email,
            "image" => $this->image,
        ];
    }
}
