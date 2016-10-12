<?php

namespace Acme\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VehicleRepository extends EntityRepository
{
    public function updateColors(array $colorUpdates)
    {
        $connection = $this->getEntityManager()->getConnection();

        $connection->beginTransaction();

        try {
            foreach ($colorUpdates as $update) {
                $connection->update(
                    'vehicle',
                    ['colors' => $update->newColors],
                    ['id' => $update->id],
                    ['simple_array', 'integer']
                );
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }

    public function findVehicleItems()
    {
        $dql = 'SELECT new Acme\TrainingBundle\Entity\VehicleItem(v.id, v.name) FROM Acme\TrainingBundle\Entity\Vehicle v';
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }

    public function findImageList()
    {
        $sql = '';

        $connection = $this->getEntityManager()->getconnection();

        $rows = $connection->fetchAll($sql);

        return $list;
    }

    public function save(Vehicle $vehicle)
    {
        $this->getEntityManager()->persist($vehicle);
        $this->getEntityManager()->flush($vehicle);
    }
}








class ColorUpdate
{
    public $id;
    public $newColors = [];
}

class PriceUpdate
{
    public $id;
    public $newPrice;
}
