<?php

declare(strict_types=1);

namespace App\Bus;

use App\Handlers\HandlerInterface;

interface Bus
{
    public function setHandlers(array $handlers);

    public function addHandler(HandlerInterface $handler);

    public function execute($command);
}
