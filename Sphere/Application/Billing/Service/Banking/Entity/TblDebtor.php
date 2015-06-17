<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblDebtor")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblDebtor extends AbstractEntity
{

    const ATTR_DEBTOR_NUMBER = 'DebtorNumber';
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'ServiceManagementPerson';

    /**
     * @Column(type="string")
     */
    protected $DebtorNumber;
    /**
     * @Column(type="integer")
     */
    protected $LeadTimeFirst;
    /**
     * @Column(type="integer")
     */
    protected $LeadTimeFollow;
    /**
     * @Column(type="bigint")
     */
    protected $ServiceManagementPerson;
    /**
     * @Column(type="string")
     */
    protected $BankName;
    /**
     * @Column(type="string")
     */
    protected $IBAN;
    /**
     * @Column(type="string")
     */
    protected $BIC;
    /**
     * @Column(type="string")
     */
    protected $Owner;
    /**
     * @Column(type="string")
     */
    protected $CashSign;
    /**
     * @Column(type="string")
     */
    protected $Description;
    /**
     * @Column(type="bigint")
     */
    protected $tblPaymentType;

    /**
     * @return integer $LeadTimeFirst
     */
    public function getLeadTimeFirst()
    {
        return $this->LeadTimeFirst;
    }

    /**
     * @param integer $LeadTimeFirst
     */
    public function setLeadTimeFirst($LeadTimeFirst)
    {
        $this->LeadTimeFirst = $LeadTimeFirst;
    }

    /**
     * @return integer $LeadTimeFollow
     */
    public function getLeadTimeFollow()
    {
        return $this->LeadTimeFollow;
    }

    /**
     * @param integer $LeadTimeFollow
     */
    public function setLeadTimeFollow($LeadTimeFollow)
    {
        $this->LeadTimeFollow = $LeadTimeFollow;
    }

    /**
     * @return string $DebtorNumber
     */
    public function getDebtorNumber()
    {
        return $this->DebtorNumber;
    }

    /**
     * @param string $DebtorNumber
     */
    public function setDebtorNumber($DebtorNumber)
    {
        $this->DebtorNumber = $DebtorNumber;
    }

    /**
     * @return bool|TblPerson $ServiceManagementPerson
     */
    public function getServiceManagementPerson()
    {
        if (null === $this->ServiceManagementPerson) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->ServiceManagementPerson );
        }
    }

    /**
     * @param null|TblPerson $ServiceManagementPerson
     */
    public function setServiceManagementPerson( TblPerson $ServiceManagementPerson )
    {
        $this->ServiceManagementPerson = ( null === $ServiceManagementPerson ? null : $ServiceManagementPerson->getId() );
    }

    /**
     * @return string $BankName
     */
    public function getBankName()
    {
        return $this->BankName;
    }

    /**
     * @param string $BankName
     */
    public function setBankName( $BankName )
    {
        $this->BankName = $BankName;
    }

    /**
     * @return string $IBAN
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * @param string $IBAN
     */
    public function setIBAN($IBAN)
    {
        $this->IBAN = $IBAN;
    }

    /**
     * @return string $BIC
     */
    public function getBIC()
    {
        return $this->BIC;
    }

    /**
     * @param string $BIC
     */
    public function setBIC( $BIC )
    {
        $this->BIC = $BIC;
    }

    /**
     * @return string $Owner
     */
    public function getOwner()
    {
        return $this->Owner;
    }

    /**
     * @param string $Owner
     */
    public function setOwner( $Owner )
    {
        $this->Owner = $Owner;
    }

    /**
     * @return string $CashSign
     */
    public function getCashSign()
    {
        return $this->CashSign;
    }

    /**
     * @param string $CashSign
     */
    public function setCashSign( $CashSign )
    {
        $this->CashSign = $CashSign;
    }

    /**
     * @return string $Description
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return string $tblPaymentType
     */
    public function getPaymentType()
    {

        if (null === $this->tblPaymentType) {
            return false;
        } else {
            return Billing::serviceBanking()->entityPaymentTypeById( $this->tblPaymentType );
        }
    }

    /**
     * @param TblPaymentType $PaymentType
     */
    public function setPaymentType( TblPaymentType $PaymentType)
    {
        $this->tblPaymentType = ( null === $PaymentType ? null : $PaymentType->getId() );
    }

}