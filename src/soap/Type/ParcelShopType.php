<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ParcelShopType extends BaseObject
{

    /**
     * @var int
     */
    private $parcelShopId;

    /**
     * @var string
     */
    private $pudoId;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $houseNo;

    /**
     * @var string
     */
    private $country;

    /**
     * @var int
     */
    private $countryNum;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $coordinateX;

    /**
     * @var float
     */
    private $coordinateY;

    /**
     * @var float
     */
    private $coordinateZ;

    /**
     * @var float
     */
    private $distance;

    /**
     * @var string
     */
    private $expressPickupTime;

    /**
     * @var string
     */
    private $extraInfo;

    /**
     * @var \lujie\dpd\soap\Type\OpeningHoursType
     */
    private $openingHours;

    /**
     * @var \lujie\dpd\soap\Type\HolidayType
     */
    private $holiday;

    /**
     * @var \lujie\dpd\soap\Type\ServicesType
     */
    private $services;

    /**
     * @return int
     */
    public function getParcelShopId() : int
    {
        return $this->parcelShopId;
    }

    /**
     * @param int $parcelShopId
     * @return $this
     */
    public function setParcelShopId(int $parcelShopId) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->parcelShopId = $parcelShopId;
        return $this;
    }

    /**
     * @param int $parcelShopId
     * @return ParcelShopType
     */
    public function withParcelShopId(int $parcelShopId) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->parcelShopId = $parcelShopId;

        return $new;
    }

    /**
     * @return string
     */
    public function getPudoId() : string
    {
        return $this->pudoId;
    }

    /**
     * @param string $pudoId
     * @return $this
     */
    public function setPudoId(string $pudoId) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->pudoId = $pudoId;
        return $this;
    }

    /**
     * @param string $pudoId
     * @return ParcelShopType
     */
    public function withPudoId(string $pudoId) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->pudoId = $pudoId;

        return $new;
    }

    /**
     * @return string
     */
    public function getCompany() : string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param string $company
     * @return ParcelShopType
     */
    public function withCompany(string $company) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->company = $company;

        return $new;
    }

    /**
     * @return string
     */
    public function getStreet() : string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param string $street
     * @return ParcelShopType
     */
    public function withStreet(string $street) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->street = $street;

        return $new;
    }

    /**
     * @return string
     */
    public function getHouseNo() : string
    {
        return $this->houseNo;
    }

    /**
     * @param string $houseNo
     * @return $this
     */
    public function setHouseNo(string $houseNo) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->houseNo = $houseNo;
        return $this;
    }

    /**
     * @param string $houseNo
     * @return ParcelShopType
     */
    public function withHouseNo(string $houseNo) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->houseNo = $houseNo;

        return $new;
    }

    /**
     * @return string
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $country
     * @return ParcelShopType
     */
    public function withCountry(string $country) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->country = $country;

        return $new;
    }

    /**
     * @return int
     */
    public function getCountryNum() : int
    {
        return $this->countryNum;
    }

    /**
     * @param int $countryNum
     * @return $this
     */
    public function setCountryNum(int $countryNum) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->countryNum = $countryNum;
        return $this;
    }

    /**
     * @param int $countryNum
     * @return ParcelShopType
     */
    public function withCountryNum(int $countryNum) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->countryNum = $countryNum;

        return $new;
    }

    /**
     * @return string
     */
    public function getState() : string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $state
     * @return ParcelShopType
     */
    public function withState(string $state) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->state = $state;

        return $new;
    }

    /**
     * @return string
     */
    public function getZipCode() : string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return ParcelShopType
     */
    public function withZipCode(string $zipCode) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->zipCode = $zipCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $city
     * @return ParcelShopType
     */
    public function withCity(string $city) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }

    /**
     * @return string
     */
    public function getTown() : string
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return $this
     */
    public function setTown(string $town) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @param string $town
     * @return ParcelShopType
     */
    public function withTown(string $town) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->town = $town;

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
    public function setPhone(string $phone) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $phone
     * @return ParcelShopType
     */
    public function withPhone(string $phone) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->phone = $phone;

        return $new;
    }

    /**
     * @return string
     */
    public function getFax() : string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return $this
     */
    public function setFax(string $fax) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * @param string $fax
     * @return ParcelShopType
     */
    public function withFax(string $fax) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->fax = $fax;

        return $new;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $email
     * @return ParcelShopType
     */
    public function withEmail(string $email) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->email = $email;

        return $new;
    }

    /**
     * @return string
     */
    public function getHomepage() : string
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     * @return $this
     */
    public function setHomepage(string $homepage) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @param string $homepage
     * @return ParcelShopType
     */
    public function withHomepage(string $homepage) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->homepage = $homepage;

        return $new;
    }

    /**
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude(float $latitude) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param float $latitude
     * @return ParcelShopType
     */
    public function withLatitude(float $latitude) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->latitude = $latitude;

        return $new;
    }

    /**
     * @return float
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude(float $longitude) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float $longitude
     * @return ParcelShopType
     */
    public function withLongitude(float $longitude) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->longitude = $longitude;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateX() : float
    {
        return $this->coordinateX;
    }

    /**
     * @param float $coordinateX
     * @return $this
     */
    public function setCoordinateX(float $coordinateX) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->coordinateX = $coordinateX;
        return $this;
    }

    /**
     * @param float $coordinateX
     * @return ParcelShopType
     */
    public function withCoordinateX(float $coordinateX) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->coordinateX = $coordinateX;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateY() : float
    {
        return $this->coordinateY;
    }

    /**
     * @param float $coordinateY
     * @return $this
     */
    public function setCoordinateY(float $coordinateY) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->coordinateY = $coordinateY;
        return $this;
    }

    /**
     * @param float $coordinateY
     * @return ParcelShopType
     */
    public function withCoordinateY(float $coordinateY) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->coordinateY = $coordinateY;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateZ() : float
    {
        return $this->coordinateZ;
    }

    /**
     * @param float $coordinateZ
     * @return $this
     */
    public function setCoordinateZ(float $coordinateZ) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->coordinateZ = $coordinateZ;
        return $this;
    }

    /**
     * @param float $coordinateZ
     * @return ParcelShopType
     */
    public function withCoordinateZ(float $coordinateZ) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->coordinateZ = $coordinateZ;

        return $new;
    }

    /**
     * @return float
     */
    public function getDistance() : float
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return $this
     */
    public function setDistance(float $distance) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @param float $distance
     * @return ParcelShopType
     */
    public function withDistance(float $distance) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->distance = $distance;

        return $new;
    }

    /**
     * @return string
     */
    public function getExpressPickupTime() : string
    {
        return $this->expressPickupTime;
    }

    /**
     * @param string $expressPickupTime
     * @return $this
     */
    public function setExpressPickupTime(string $expressPickupTime) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->expressPickupTime = $expressPickupTime;
        return $this;
    }

    /**
     * @param string $expressPickupTime
     * @return ParcelShopType
     */
    public function withExpressPickupTime(string $expressPickupTime) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->expressPickupTime = $expressPickupTime;

        return $new;
    }

    /**
     * @return string
     */
    public function getExtraInfo() : string
    {
        return $this->extraInfo;
    }

    /**
     * @param string $extraInfo
     * @return $this
     */
    public function setExtraInfo(string $extraInfo) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->extraInfo = $extraInfo;
        return $this;
    }

    /**
     * @param string $extraInfo
     * @return ParcelShopType
     */
    public function withExtraInfo(string $extraInfo) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->extraInfo = $extraInfo;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\OpeningHoursType
     */
    public function getOpeningHours() : \lujie\dpd\soap\Type\OpeningHoursType
    {
        return $this->openingHours;
    }

    /**
     * @param \lujie\dpd\soap\Type\OpeningHoursType $openingHours
     * @return $this
     */
    public function setOpeningHours($openingHours) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->openingHours = $openingHours;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\OpeningHoursType $openingHours
     * @return ParcelShopType
     */
    public function withOpeningHours(\lujie\dpd\soap\Type\OpeningHoursType $openingHours) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->openingHours = $openingHours;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\HolidayType
     */
    public function getHoliday() : \lujie\dpd\soap\Type\HolidayType
    {
        return $this->holiday;
    }

    /**
     * @param \lujie\dpd\soap\Type\HolidayType $holiday
     * @return $this
     */
    public function setHoliday($holiday) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->holiday = $holiday;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\HolidayType $holiday
     * @return ParcelShopType
     */
    public function withHoliday(\lujie\dpd\soap\Type\HolidayType $holiday) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->holiday = $holiday;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ServicesType
     */
    public function getServices() : \lujie\dpd\soap\Type\ServicesType
    {
        return $this->services;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesType $services
     * @return $this
     */
    public function setServices($services) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ServicesType $services
     * @return ParcelShopType
     */
    public function withServices(\lujie\dpd\soap\Type\ServicesType $services) : \lujie\dpd\soap\Type\ParcelShopType
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }


}

