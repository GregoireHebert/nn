<?php

declare(strict_types=1);

namespace App\Handlers;

interface HandlerInterface
{
    public function handle($command): void;

    public function support($command): bool;
}
