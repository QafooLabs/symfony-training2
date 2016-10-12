<?php

namespace Acme\TrainingBundle\Controller;

use Doctrine\ORM\EntityManager;
use Acme\TrainingBundle\Entity\Vehicle;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VehicleController
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insertAction()
    {
        $vehicle = new Vehicle();
        $vehicle->name = 'AUDI A8';

        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return $vehicle;
    }
}
