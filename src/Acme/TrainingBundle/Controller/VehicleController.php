<?php

namespace Acme\TrainingBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use Acme\TrainingBundle\Entity\Vehicle;
use Acme\TrainingBundle\Entity\VehicleRepository;
use Acme\TrainingBundle\Entity\Brand;
use Acme\TrainingBundle\Entity\Image;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Collections\Criteria;

class VehicleController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager, Connection $connection, VehicleRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
        $this->repository = $repository;
    }

    public function sqlAction($id)
    {
        $connection = $this->entityManager->getConnection();

        return $connection->fetchAssoc("SELECT * FROM vehicle WHERE id = ?", [$id]);
    }

    public function viewAction($id)
    {
        return $this->entityManager->find(Vehicle::class, $id);
    }

    public function listItemsAction()
    {
        return $this->repository->findVehicleItems();
    }

    public function listAction()
    {
        $vehicles = $this->entityManager->getRepository(Vehicle::class)->findAll();

        $result = [];
        foreach ($vehicles as $vehicle) {
            $criteria = Criteria::create();
            $criteria->setFirstResult(0)->setMaxResults(10);

            if ($vehicle->brand && count($vehicle->images)) {
                $result[] = [
                    $vehicle->brand->name,
                    array_map(function ($image) { return $image->id; }, $vehicle->images->matching($criteria)->toArray())
                ];
            }
        }

        return $result;
    }

    public function updateAction($id)
    {
        $vehicle =  $this->entityManager->find(Vehicle::class, $id);
        $vehicle->colors = ["blue", "yellow"];

        $this->entityManager->flush();

        return $vehicle;
    }

    public function batchAction()
    {
        $batchSize = 5;
        $i = 0;

        $ids = range(1, 10);

        /*$batchChunks = array_chunk($ids, 5);

        foreach ($batchChunks as $chunk) {
            $vehicles = $this->entityManager->getRepository(Vehicle::class)->findBy(['id' => $chunk]);

            foreach ($vehicles as $vehicle) {
                $elastichsearch->update($vehicle);
            }

            $this->entityManager->clear();
        }*/

        foreach ($ids as $id) {
            $vehicle = $this->entityManager->find(Vehicle::class, $id);
            $vehicle->colors = ['yellow', 'orange'];
            $i++;

            if ($i % $batchSize === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        $this->entityManager->flush();

        return [];
    }

    public function identityAction($id)
    {
        $repository = $this->entityManager->getRepository(Vehicle::class);

        $a = $repository->findOneBy(['id' => $id]);
        $b = $repository->findOneBy(['id' => $id]);

        return ['a' => spl_object_hash($a), 'b' => spl_object_hash($b), 'equals' => ($a === $b)];
    }

    public function insertAction()
    {
        $brand = $this->entityManager->getRepository(Brand::class)->findOneByName(['name' => 'AUDI']);

        if (!$brand) {
            $brand = new Brand();
            $brand->name = 'AUDI';

            $this->entityManager->persist($brand);
        }

        $vehicle = new Vehicle('AUDI A8', $brand);
        $vehicle->colors = ['red', 'blue'];

        $image = new Image();
        $image->vehicle = $vehicle;
        $vehicle->images[] = $image;

        $this->entityManager->persist($vehicle);

        $this->entityManager->flush();

        return $vehicle;
    }

    public function metadataAction()
    {
        return $this->entityManager->getClassMetadata(Vehicle::class);
    }
}
