<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mouton;

class MoutonRepository implements Repository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('sqlite:'.__DIR__.'/../../var/database.sqlite');
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->pdo->query(<<<SQL
CREATE TABLE IF NOT EXISTS Mouton ( 
    id              INTEGER     PRIMARY KEY AUTOINCREMENT,
    hunger          INTEGER,
    sleepiness      INTEGER,
    playfulness     INTEGER,
    life            INTEGER
);
SQL
        );
    }

    /**
     * @param Mouton $object
     */
    public function insert($object): void
    {
        $preparation = $this->pdo->prepare('INSERT INTO Mouton VALUES (null, :hunger, :sleepiness, :playfulness, :life)');
        $preparation->execute([
            ':life' => $object->getLife(),
            ':hunger' => $object->getHunger(),
            ':sleepiness' => $object->getSleepiness(),
            ':playfulness' => $object->getPlayfulness(),
        ]);

        $object->setId($this->pdo->lastInsertId());
    }

    /**
     * @param Mouton $object
     */
    public function delete($object): bool
    {
        $preparation = $this->pdo->prepare('DELETE FROM Mouton WHERE id = :id');

        return $preparation->execute([':id' => $object->getId()]);
    }

    /**
     * @param Mouton $object
     */
    public function update($object): void
    {
        $preparation = $this->pdo->prepare('UPDATE Mouton SET hunger = :hunger, sleepiness = :sleepiness, playfulness = :playfulness, life = :life WHERE id = :id');
        $preparation->execute([
            ':life' => $object->getLife(),
            ':hunger' => $object->getHunger(),
            ':sleepiness' => $object->getSleepiness(),
            ':playfulness' => $object->getPlayfulness(),
            ':id' => $object->getId(),
        ]);
    }

    public function findOne($id): Mouton
    {
        $preparation = $this->pdo->prepare('SELECT * FROM Mouton WHERE id = :id');
        $preparation->execute([':id' => $id]);

        return $preparation->fetchObject(Mouton::class);
    }
}
