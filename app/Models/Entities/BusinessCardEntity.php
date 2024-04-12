<?php

namespace App\Models\Entities;

use DateTime;

class BusinessCardEntity
{
    private int $id;
    private string $name;
    private string $companyName;
    private string $postCode;
    private string $address;
    private string $phone;
    private string $fax;
    private string $email;
    private string $image;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $company_name,
        string $post_code,
        string $address,
        string $phone,
        string $fax,
        string $email,
        string $image,
        string $created_at,
        string $updated_at,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->companyName = $company_name;
        $this->postCode = $post_code;
        $this->address = $address;
        $this->phone = $phone;
        $this->fax = $fax;
        $this->email = $email;
        $this->image = $image;
        $this->createdAt = new DateTime($created_at);
        $this->updatedAt = new DateTime($updated_at);
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function asArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "companyName" => $this->companyName,
            "postCode" => $this->postCode,
            "address" => $this->address,
            "phone" => $this->phone,
            "fax" => $this->fax,
            "email" => $this->email,
            "image" => $this->image,
            "createdAt" => $this->createdAt,
            "updatedAt" => $this->updatedAt,
        ];
    }
}
