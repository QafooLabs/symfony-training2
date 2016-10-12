<?php


namespace Acme\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="VehicleRepository")
 */
class Vehicle
{
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="simple_array")
     */
    public $colors = [];

    /**
     * @ORM\ManyToOne(targetEntity="Brand", fetch="EAGER")
     */
    public $brand;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="vehicle", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    public $images = [];

    public function __construct($name, Brand $brand)
    {
        $this->name = $name;
        $this->brand = $brand;
    }

    public function getName()
    {
        return $this->name;
    }
}
