<?php

declare(strict_types=1);

namespace App\Repository;

interface Repository
{
    public function insert($object);

    public function delete($object): bool;

    public function update($object);

    public function findOne($id);
}
