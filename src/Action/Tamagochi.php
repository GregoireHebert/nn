<?php

declare(strict_types=1);

namespace App\Action;

use App\Bus\Bus;
use App\Commands\LifeCommand;
use App\Entity\Mouton;
use App\Repository\Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;

class Tamagochi
{
    private $bus;
    private $repository;
    private $twig;

    public function __construct(Bus $bus, Repository $repository, Environment $twig)
    {
        $this->repository = $repository;
        $this->bus = $bus;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $action = $request->request->get('action', '');
        $mouton = $this->getMouton();

        $command = new LifeCommand($mouton, $action);
        $this->bus->execute($command);

        $mouton->setLastActionTaken($command->isTaken());


        if ($mouton->getLife() <= 0) {
            unset($_SESSION['id']);
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return new Response($serializer->serialize($mouton, 'json'));
    }

    private function getMouton()
    {
        if (!isset($_SESSION['id'])) {
            $this->repository->insert($mouton = new Mouton());
            $_SESSION['id'] = $mouton->getId();

            return $mouton;
        }

        return $this->repository->findOne($_SESSION['id']);
    }
}
