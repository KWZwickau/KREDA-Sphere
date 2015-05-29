<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblInvoice")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblInvoice extends AbstractEntity
{
    /**
     * @Column(type="boolean")
     */
    protected $IsPaid;

    /**
     * @Column(type="string")
     */
    protected $Number;

    /**
     * @Column(type="boolean")
     */
    protected $IsVoid;

    /**
     * @Column(type="date")
     */
    protected $InvoiceDate;

    /**
     * @Column(type="date")
     */
    protected $PaymentDate;

    /**
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $Discount;

    /**
     * @Column(type="string")
     */
    protected $PersonFirstName;

    /**
     * @Column(type="string")
     */
    protected $PersonLastName;

    /**
     * @Column(type="string")
     */
    protected $PersonSalutation;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Address;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;

    /**
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->IsPaid;
    }

    /**
     * @param boolean $IsPaid
     */
    public function setIsPaid( $IsPaid )
    {
        $this->IsPaid = $IsPaid;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->Number;
    }

    /**
     * @param string $Number
     */
    public function setNumber( $Number )
    {
        $this->Number = $Number;
    }

    /**
     * @return boolean
     */
    public function getIsVoid()
    {
        return $this->IsVoid;
    }

    /**
     * @param boolean $IsVoid
     */
    public function setIsVoid( $IsVoid )
    {
        $this->IsVoid = $IsVoid;
    }

    /**
     * @return string
     */
    public function getInvoiceDate()
    {

        if (null === $this->InvoiceDate) {
            return false;
        }
        /** @var \DateTime $InvoiceDate */
        $InvoiceDate = $this->InvoiceDate;
        if ($InvoiceDate instanceof \DateTime) {
            return $InvoiceDate->format( 'd.m.Y' );
        } else {
            return (string)$InvoiceDate;
        }
    }

    /**
     * @param \DateTime $InvoiceDate
     */
    public function setInvoiceDate(\DateTime $InvoiceDate )
    {
        $this->InvoiceDate = $InvoiceDate;
    }

    /**
     * @return string
     */
    public function getPaymentDate()
    {

        if (null === $this->PaymentDate) {
            return false;
        }
        /** @var \DateTime $PaymentDate */
        $PaymentDate = $this->PaymentDate;
        if ($PaymentDate instanceof \DateTime) {
            return $PaymentDate->format( 'd.m.Y' );
        } else {
            return (string)$PaymentDate;
        }
    }

    /**
     * @param \DateTime $PaymentDate
     */
    public function setPaymentDate(\DateTime $PaymentDate )
    {
        $this->PaymentDate = $PaymentDate;
    }

    /**
     * @return (type="decimal", precision=14, scale=4)
     */
    public function getDiscount()
    {
        return $this->Discount;
    }

    /**
     * @param (type="decimal", precision=14, scale=4) $Price
     */
    public function setDiscount( $Discount )
    {
        $this->Discount = $Discount;
    }

    /**
     * @return string
     */
    public function getPersonFirstName()
    {
        return $this->PersonFirstName;
    }

    /**
     * @param string $PersonFirstName
     */
    public function setPersonFirstName( $PersonFirstName )
    {
        $this->PersonFirstName = $PersonFirstName;
    }

    /**
     * @return string
     */
    public function getPersonLastName()
    {
        return $this->PersonLastName;
    }

    /**
     * @param string $PersonLastName
     */
    public function setPersonLastName( $PersonLastName )
    {
        $this->PersonLastName = $PersonLastName;
    }

    /**
     * @return string
     */
    public function getPersonSalutation()
    {
        return $this->PersonSalutation;
    }

    /**
     * @param string $PersonSalutation
     */
    public function setPersonSalutation( $PersonSalutation )
    {
        $this->PersonSalutation = $PersonSalutation;
    }

    /**
     * @return bool|TblAddress
     */
    public function getServiceManagementAddress()
    {
        if (null === $this->serviceManagement_Address) {
            return false;
        } else {
            return Management::serviceAddress()->entityAddressById($this->serviceManagement_Address);
        }
    }

    /**
     * @param TblAddress $tblAddress
     */
    public function setServiceManagementAddress( TblAddress $tblAddress = null )
    {
        $this->serviceManagement_Address = ( null === $tblAddress ? null : $tblAddress->getId() );
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
}
