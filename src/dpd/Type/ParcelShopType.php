<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ParcelShopType implements RequestInterface
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
     * @var \dpd\Type\OpeningHoursType
     */
    private $openingHours;

    /**
     * @var \dpd\Type\HolidayType
     */
    private $holiday;

    /**
     * @var \dpd\Type\ServicesType
     */
    private $services;

    /**
     * Constructor
     *
     * @var int $parcelShopId
     * @var string $pudoId
     * @var string $company
     * @var string $street
     * @var string $houseNo
     * @var string $country
     * @var int $countryNum
     * @var string $state
     * @var string $zipCode
     * @var string $city
     * @var string $town
     * @var string $phone
     * @var string $fax
     * @var string $email
     * @var string $homepage
     * @var float $latitude
     * @var float $longitude
     * @var float $coordinateX
     * @var float $coordinateY
     * @var float $coordinateZ
     * @var float $distance
     * @var string $expressPickupTime
     * @var string $extraInfo
     * @var \dpd\Type\OpeningHoursType $openingHours
     * @var \dpd\Type\HolidayType $holiday
     * @var \dpd\Type\ServicesType $services
     */
    public function __construct($parcelShopId, $pudoId, $company, $street, $houseNo, $country, $countryNum, $state, $zipCode, $city, $town, $phone, $fax, $email, $homepage, $latitude, $longitude, $coordinateX, $coordinateY, $coordinateZ, $distance, $expressPickupTime, $extraInfo, $openingHours, $holiday, $services)
    {
        $this->parcelShopId = $parcelShopId;
        $this->pudoId = $pudoId;
        $this->company = $company;
        $this->street = $street;
        $this->houseNo = $houseNo;
        $this->country = $country;
        $this->countryNum = $countryNum;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->town = $town;
        $this->phone = $phone;
        $this->fax = $fax;
        $this->email = $email;
        $this->homepage = $homepage;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;
        $this->coordinateZ = $coordinateZ;
        $this->distance = $distance;
        $this->expressPickupTime = $expressPickupTime;
        $this->extraInfo = $extraInfo;
        $this->openingHours = $openingHours;
        $this->holiday = $holiday;
        $this->services = $services;
    }

    /**
     * @return int
     */
    public function getParcelShopId()
    {
        return $this->parcelShopId;
    }

    /**
     * @param int $parcelShopId
     * @return ParcelShopType
     */
    public function withParcelShopId($parcelShopId)
    {
        $new = clone $this;
        $new->parcelShopId = $parcelShopId;

        return $new;
    }

    /**
     * @return string
     */
    public function getPudoId()
    {
        return $this->pudoId;
    }

    /**
     * @param string $pudoId
     * @return ParcelShopType
     */
    public function withPudoId($pudoId)
    {
        $new = clone $this;
        $new->pudoId = $pudoId;

        return $new;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return ParcelShopType
     */
    public function withCompany($company)
    {
        $new = clone $this;
        $new->company = $company;

        return $new;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return ParcelShopType
     */
    public function withStreet($street)
    {
        $new = clone $this;
        $new->street = $street;

        return $new;
    }

    /**
     * @return string
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * @param string $houseNo
     * @return ParcelShopType
     */
    public function withHouseNo($houseNo)
    {
        $new = clone $this;
        $new->houseNo = $houseNo;

        return $new;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return ParcelShopType
     */
    public function withCountry($country)
    {
        $new = clone $this;
        $new->country = $country;

        return $new;
    }

    /**
     * @return int
     */
    public function getCountryNum()
    {
        return $this->countryNum;
    }

    /**
     * @param int $countryNum
     * @return ParcelShopType
     */
    public function withCountryNum($countryNum)
    {
        $new = clone $this;
        $new->countryNum = $countryNum;

        return $new;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return ParcelShopType
     */
    public function withState($state)
    {
        $new = clone $this;
        $new->state = $state;

        return $new;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return ParcelShopType
     */
    public function withZipCode($zipCode)
    {
        $new = clone $this;
        $new->zipCode = $zipCode;

        return $new;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return ParcelShopType
     */
    public function withCity($city)
    {
        $new = clone $this;
        $new->city = $city;

        return $new;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return ParcelShopType
     */
    public function withTown($town)
    {
        $new = clone $this;
        $new->town = $town;

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
     * @return ParcelShopType
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
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return ParcelShopType
     */
    public function withFax($fax)
    {
        $new = clone $this;
        $new->fax = $fax;

        return $new;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ParcelShopType
     */
    public function withEmail($email)
    {
        $new = clone $this;
        $new->email = $email;

        return $new;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     * @return ParcelShopType
     */
    public function withHomepage($homepage)
    {
        $new = clone $this;
        $new->homepage = $homepage;

        return $new;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return ParcelShopType
     */
    public function withLatitude($latitude)
    {
        $new = clone $this;
        $new->latitude = $latitude;

        return $new;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return ParcelShopType
     */
    public function withLongitude($longitude)
    {
        $new = clone $this;
        $new->longitude = $longitude;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateX()
    {
        return $this->coordinateX;
    }

    /**
     * @param float $coordinateX
     * @return ParcelShopType
     */
    public function withCoordinateX($coordinateX)
    {
        $new = clone $this;
        $new->coordinateX = $coordinateX;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateY()
    {
        return $this->coordinateY;
    }

    /**
     * @param float $coordinateY
     * @return ParcelShopType
     */
    public function withCoordinateY($coordinateY)
    {
        $new = clone $this;
        $new->coordinateY = $coordinateY;

        return $new;
    }

    /**
     * @return float
     */
    public function getCoordinateZ()
    {
        return $this->coordinateZ;
    }

    /**
     * @param float $coordinateZ
     * @return ParcelShopType
     */
    public function withCoordinateZ($coordinateZ)
    {
        $new = clone $this;
        $new->coordinateZ = $coordinateZ;

        return $new;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return ParcelShopType
     */
    public function withDistance($distance)
    {
        $new = clone $this;
        $new->distance = $distance;

        return $new;
    }

    /**
     * @return string
     */
    public function getExpressPickupTime()
    {
        return $this->expressPickupTime;
    }

    /**
     * @param string $expressPickupTime
     * @return ParcelShopType
     */
    public function withExpressPickupTime($expressPickupTime)
    {
        $new = clone $this;
        $new->expressPickupTime = $expressPickupTime;

        return $new;
    }

    /**
     * @return string
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * @param string $extraInfo
     * @return ParcelShopType
     */
    public function withExtraInfo($extraInfo)
    {
        $new = clone $this;
        $new->extraInfo = $extraInfo;

        return $new;
    }

    /**
     * @return \dpd\Type\OpeningHoursType
     */
    public function getOpeningHours()
    {
        return $this->openingHours;
    }

    /**
     * @param \dpd\Type\OpeningHoursType $openingHours
     * @return ParcelShopType
     */
    public function withOpeningHours($openingHours)
    {
        $new = clone $this;
        $new->openingHours = $openingHours;

        return $new;
    }

    /**
     * @return \dpd\Type\HolidayType
     */
    public function getHoliday()
    {
        return $this->holiday;
    }

    /**
     * @param \dpd\Type\HolidayType $holiday
     * @return ParcelShopType
     */
    public function withHoliday($holiday)
    {
        $new = clone $this;
        $new->holiday = $holiday;

        return $new;
    }

    /**
     * @return \dpd\Type\ServicesType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \dpd\Type\ServicesType $services
     * @return ParcelShopType
     */
    public function withServices($services)
    {
        $new = clone $this;
        $new->services = $services;

        return $new;
    }


}

