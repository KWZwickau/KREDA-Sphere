<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblTempInvoice")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblTempInvoice extends AbstractEntity
{
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';
    const ATTR_SERVICE_BILLING_DEBTOR = 'serviceBilling_Debtor';

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;

    /**
     * @Column(type="bigint")
     */
    protected $serviceBilling_Debtor;

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
     * @return bool|TblDebtor
     */
    public function getServiceBillingDebtor()
    {
        if (null === $this->serviceBilling_Debtor) {
            return false;
        } else {
            return Billing::serviceBanking()->entityDebtorById($this->serviceBilling_Debtor);
        }
    }

    /**
     * @param TblDebtor $tblDebtor
     */
    public function setServiceBillingDebtor( TblDebtor $tblDebtor = null )
    {
        $this->serviceBilling_Debtor = ( null === $tblDebtor ? null : $tblDebtor->getId() );
    }
}
