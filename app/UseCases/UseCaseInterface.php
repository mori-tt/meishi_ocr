<?php

namespace App\UseCases;

use LINE\Clients\MessagingApi\Model\Message;

interface UseCaseInterface {
    /**
     * @return Message[]
     */
    public function execute(): array;
}
