<?php
namespace KREDA\Sphere\Application\Billing\Service\Basket\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblBasketCommodity")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblBasketCommodity extends AbstractEntity
{
    const ATTR_TBL_BASKET = 'tblBasket';
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';
    const ATTR_SERVICE_BILLING_COMMODITY = 'serviceBilling_Commodity';

    /**
     * @Column(type="bigint")
     */
    protected $tblBasket;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Commodity;

    /**
     * @param null|TblBasket $tblBasket
     */
    public function setTblBasket($tblBasket = null)
    {
        $this->tblBasket = ( null === $tblBasket ? null : $tblBasket->getId() );
    }

    /**
     * @return bool|TblBasket
     */
    public function getTblBasket()
    {
        if (null === $this->tblBasket)
        {
            return false;
        } else {
            return Billing::serviceBasket()->entityBasketById( $this->tblBasket );
        }
    }

    /**
     * @return bool|TblPerson
     */
    public function getServiceManagementPerson()
    {
        if (null === $this->serviceManagement_Person) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById($this->serviceManagement_Person);
        }
    }

    /**
     * @param TblPerson $tblPerson
     */
    public function setServiceManagementPerson( TblPerson $tblPerson = null )
    {
        $this->serviceManagement_Person = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @param null|TblCommodity $tblCommodity
     */
    public function setServiceBillingCommodity($tblCommodity = null)
    {
        $this->serviceBilling_Commodity = ( null === $tblCommodity ? null : $tblCommodity->getId() );
    }

    /**
     * @return bool|TblCommodity
     */
    public function getServiceBillingCommodity()
    {
        if (null === $this->serviceBilling_Commodity) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityCommodityById( $this->serviceBilling_Commodity );
        }
    }
}