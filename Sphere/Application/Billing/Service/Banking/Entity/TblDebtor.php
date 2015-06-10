<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking\Entity;

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
    protected $Description;

    /**
     * @return integer $LeadTimeFirst
     */
    public function getLeadTimeFirst()
    {
        return $this->LeadTimeFirst;
    }

    /**
     * @param integer $leadTimeFirst
     */
    public function setLeadTimeFirst($leadTimeFirst)
    {
        $this->LeadTimeFirst = $leadTimeFirst;
    }

    /**
     * @return integer $LeadTimeFollow
     */
    public function getLeadTimeFollow()
    {
        return $this->LeadTimeFollow;
    }

    /**
     * @param integer $leadTimeFollow
     */
    public function setLeadTimeFollow($leadTimeFollow)
    {
        $this->LeadTimeFollow = $leadTimeFollow;
    }

    /**
     * @return string $DebtorNumber
     */
    public function getDebtorNumber()
    {
        return $this->DebtorNumber;
    }

    /**
     * @param string $debtorNumber
     */
    public function setDebtorNumber($debtorNumber)
    {
        $this->DebtorNumber = $debtorNumber;
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
     * @param null|TblPerson $serviceManagementPerson
     */
    public function setServiceManagementPerson( TblPerson $serviceManagementPerson )
    {
        $this->ServiceManagementPerson = ( null === $serviceManagementPerson ? null : $serviceManagementPerson->getId() );
    }

    /**
     * @return string $BankName
     */
    public function getBankName()
    {
        return $this->BankName;
    }

    /**
     * @param string $bankName
     */
    public function setBankName( $bankName )
    {
        $this->BankName = $bankName;
    }

    /**
     * @return string $IBAN
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * @param string $iBAN
     */
    public function setIBAN($iBAN)
    {
        $this->IBAN = $iBAN;
    }

    /**
     * @return string $bIC
     */
    public function getBIC()
    {
        return $this->BIC;
    }

    /**
     * @param string $bIC
     */
    public function setBIC( $bIC )
    {
        $this->BIC = $bIC;
    }

    /**
     * @return string $owner
     */
    public function getOwner()
    {
        return $this->Owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner( $owner )
    {
        $this->Owner = $owner;
    }

    /**
     * @return string $Description
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->Description = $description;
    }

}