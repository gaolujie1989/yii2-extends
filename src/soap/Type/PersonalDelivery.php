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
    public function getType() : int
    {
        return $this->type;
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
     * @param int $type
     * @return PersonalDelivery
     */
    public function withType(int $type) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->type = $type;

        return $new;
    }

    /**
     * @return string
     */
    public function getFloor() : string
    {
        return $this->floor;
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
     * @param string $floor
     * @return PersonalDelivery
     */
    public function withFloor(string $floor) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->floor = $floor;

        return $new;
    }

    /**
     * @return string
     */
    public function getBuilding() : string
    {
        return $this->building;
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
     * @param string $building
     * @return PersonalDelivery
     */
    public function withBuilding(string $building) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->building = $building;

        return $new;
    }

    /**
     * @return string
     */
    public function getDepartment() : string
    {
        return $this->department;
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
     * @param string $department
     * @return PersonalDelivery
     */
    public function withDepartment(string $department) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->department = $department;

        return $new;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
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
     * @param string $name
     * @return PersonalDelivery
     */
    public function withName(string $name) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return string
     */
    public function getPhone() : string
    {
        return $this->phone;
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
     * @param string $phone
     * @return PersonalDelivery
     */
    public function withPhone(string $phone) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->phone = $phone;

        return $new;
    }

    /**
     * @return string
     */
    public function getPersonId() : string
    {
        return $this->personId;
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

    /**
     * @param string $personId
     * @return PersonalDelivery
     */
    public function withPersonId(string $personId) : \lujie\dpd\soap\Type\PersonalDelivery
    {
        $new = clone $this;
        $new->personId = $personId;

        return $new;
    }


}

