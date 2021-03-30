<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class PersonalDelivery implements RequestInterface
{

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $floor;

    /**
     * @var string
     */
    private $building;

    /**
     * @var string
     */
    private $department;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $personId;

    /**
     * Constructor
     *
     * @var int $type
     * @var string $floor
     * @var string $building
     * @var string $department
     * @var string $name
     * @var string $phone
     * @var string $personId
     */
    public function __construct($type, $floor, $building, $department, $name, $phone, $personId)
    {
        $this->type = $type;
        $this->floor = $floor;
        $this->building = $building;
        $this->department = $department;
        $this->name = $name;
        $this->phone = $phone;
        $this->personId = $personId;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return PersonalDelivery
     */
    public function withType($type)
    {
        $new = clone $this;
        $new->type = $type;

        return $new;
    }

    /**
     * @return string
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param string $floor
     * @return PersonalDelivery
     */
    public function withFloor($floor)
    {
        $new = clone $this;
        $new->floor = $floor;

        return $new;
    }

    /**
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param string $building
     * @return PersonalDelivery
     */
    public function withBuilding($building)
    {
        $new = clone $this;
        $new->building = $building;

        return $new;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     * @return PersonalDelivery
     */
    public function withDepartment($department)
    {
        $new = clone $this;
        $new->department = $department;

        return $new;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PersonalDelivery
     */
    public function withName($name)
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return PersonalDelivery
     */
    public function withPhone($phone)
    {
        $new = clone $this;
        $new->phone = $phone;

        return $new;
    }

    /**
     * @return string
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param string $personId
     * @return PersonalDelivery
     */
    public function withPersonId($personId)
    {
        $new = clone $this;
        $new->personId = $personId;

        return $new;
    }
}
