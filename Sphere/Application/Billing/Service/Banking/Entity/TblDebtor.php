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
    const ATTR_DEBTOR_SERVICE_MANAGEMENT_PERSON = 'ServiceManagement_Person';

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
    protected $ServiceManagement_Person;

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
     * @return bool|TblPerson $ServiceManagement_Person
     */
    public function getServiceManagement_Person()
    {
        if (null === $this->ServiceManagement_Person) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->ServiceManagement_Person );
        }
    }

    /**
     * @param null|TblPerson $serviceManagement_Person
     */
    public function setServiceManagement_Person( TblPerson $serviceManagement_Person )
    {
        $this->ServiceManagement_Person = ( null === $serviceManagement_Person ? null : $serviceManagement_Person->getId() );
    }

}