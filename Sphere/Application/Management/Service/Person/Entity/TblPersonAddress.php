<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPersonAddress")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPersonAddress extends AbstractEntity
{

    const ATTR_TBL_PERSON = 'tblPerson';
    const ATTR_SERVICE_MANAGEMENT_ADDRESS = 'serviceManagement_Address';
    /**
     * @Column(type="bigint")
     */
    protected $tblPerson;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Address;

    /**
     * @return bool|TblPerson
     */
    public function getTblPerson()
    {

        if (null === $this->tblPerson) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->tblPerson );
        }
    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setTblPerson( TblPerson $tblPerson = null )
    {

        $this->tblPerson = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @return bool|TblAddress
     */
    public function getTblAddress()
    {

        if (null === $this->serviceManagement_Address) {
            return false;
        } else {
            return Management::serviceAddress()->entityAddressById( $this->serviceManagement_Address );
        }
    }

    /**
     * @param null|TblAddress $tblAddress
     */
    public function setTblAddress( TblAddress $tblAddress = null )
    {

        $this->serviceManagement_Address = ( null === $tblAddress ? null : $tblAddress->getId() );
    }
}
