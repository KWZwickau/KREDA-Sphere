<?php
namespace KREDA\Sphere\Application\Management\Service\Address\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(readOnly=true)
 * @Table(name="viewAddress")
 * @Cache(usage="READ_ONLY")
 */
class ViewAddress
{

    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAddress;
    /**
     * @Column(type="string")
     */
    private $AddressStreetName;
    /**
     * @Column(type="string")
     */
    private $AddressStreetNumber;
    /**
     * @Column(type="string")
     */
    private $AddressPostOfficeBox;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAddressState;
    /**
     * @Column(type="string")
     */
    private $StateName;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAddressCity;
    /**
     * @Column(type="string")
     */
    private $CityCode;
    /**
     * @Column(type="string")
     */
    private $CityName;
    /**
     * @Column(type="string")
     */
    private $CityDistrict;

    /**
     * @return mixed
     */
    public function getAddressStreetName()
    {

        return $this->AddressStreetName;
    }

    /**
     * @return mixed
     */
    public function getAddressStreetNumber()
    {

        return $this->AddressStreetNumber;
    }

    /**
     * @return mixed
     */
    public function getAddressPostOfficeBox()
    {

        return $this->AddressPostOfficeBox;
    }

    /**
     * @return mixed
     */
    public function getTblAddressState()
    {

        return $this->tblAddressState;
    }

    /**
     * @return mixed
     */
    public function getStateName()
    {

        return $this->StateName;
    }

    /**
     * @return mixed
     */
    public function getTblAddressCity()
    {

        return $this->tblAddressCity;
    }

    /**
     * @return mixed
     */
    public function getCityCode()
    {

        return $this->CityCode;
    }

    /**
     * @return mixed
     */
    public function getCityName()
    {

        return $this->CityName;
    }

    /**
     * @return mixed
     */
    public function getCityDistrict()
    {

        return $this->CityDistrict;
    }

    /**
     * @return mixed
     */
    public function getTblAddress()
    {

        return $this->tblAddress;
    }
}
