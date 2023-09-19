<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class PersonalDelivery extends BaseObject
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
     * @param int $type
     * @return $this
     */
    public function setType(int $type) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->type = $type;
        return $this;
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
     * @param string $floor
     * @return $this
     */
    public function setFloor(string $floor) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->floor = $floor;
        return $this;
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
     * @param string $building
     * @return $this
     */
    public function setBuilding(string $building) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->building = $building;
        return $this;
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
     * @param string $department
     * @return $this
     */
    public function setDepartment(string $department) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->department = $department;
        return $this;
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
     * @param string $name
     * @return $this
     */
    public function setName(string $name) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->name = $name;
        return $this;
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
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->phone = $phone;
        return $this;
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

    /**
     * @param string $personId
     * @return $this
     */
    public function setPersonId(string $personId) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $this->personId = $personId;
        return $this;
    }
}

