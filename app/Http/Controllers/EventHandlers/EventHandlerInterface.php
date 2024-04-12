<?php

namespace App\Http\Controllers\EventHandlers;

interface EventHandlerInterface {
    /**
     * @return Message[]
     */
    public function handle(): array;
}
