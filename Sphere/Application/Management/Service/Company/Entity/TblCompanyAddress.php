<?php
namespace KREDA\Sphere\Application\Management\Service\Company\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblCompanyAddress")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblCompanyAddress extends AbstractEntity
{
    const ATTR_TBL_Company = 'tblCompany';
    const ATTR_SERVICE_MANAGEMENT_ADDRESS = 'serviceManagement_Address';

    /**
     * @Column(type="bigint")
     */
    protected $tblCompany;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Address;

    /**
     * @param null|TblCompany $tblCompany
     */
    public function setTblCompany($tblCompany = null)
    {
        $this->tblCompany = ( null === $tblCompany ? null : $tblCompany->getId() );
    }

    /**
     * @return bool|tblCompany
     */
    public function getTblCompany()
    {
        if (null === $this->tblCompany)
        {
            return false;
        } else {
            return Management::serviceCompany()->entityCompanyById( $this->tblCompany );
        }
    }

    /**
     * @param null|TblAddress $serviceManagement_Address
     */
    public function setServiceManagementAddress($serviceManagement_Address = null)
    {
        $this->serviceManagement_Address = ( null === $serviceManagement_Address ? null : $serviceManagement_Address->getId() );
    }

    /**
     * @return bool|TblAddress
     */
    public function getServiceManagementAddress()
    {
        if (null === $this->serviceManagement_Address) {
            return false;
        } else {
            return Management::serviceAddress()->entityAddressById( $this->serviceManagement_Address );
        }
    }

}
