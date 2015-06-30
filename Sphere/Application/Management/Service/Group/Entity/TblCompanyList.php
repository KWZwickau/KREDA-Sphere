<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblCompanyList")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblCompanyList extends AbstractEntity
{

    const ATTR_TBL_GROUP = 'tblGroup';
    const ATTR_SERVICE_MANAGEMENT_COMPANY = 'serviceManagement_Company';

    /**
     * @Column(type="bigint")
     */
    protected $tblGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Company;

    /**
     * @return bool|TblGroup
     */
    public function getTblGroup()
    {

        if (null === $this->tblGroup) {
            return false;
        } else {
            return Management::serviceGroup()->fetchGroupById( $this->tblGroup );
        }
    }

    /**
     * @param null|TblGroup $tblGroup
     */
    public function setTblGroup( TblGroup $tblGroup = null )
    {

        $this->tblGroup = ( null === $tblGroup ? null : $tblGroup->getId() );
    }

    /**
     * @return bool|TblCompany
     */
    public function getServiceManagementCompany()
    {

        if (null === $this->serviceManagement_Company) {
            return false;
        } else {
            return Management::serviceCompany()->entityCompanyById( $this->serviceManagement_Company );
        }
    }

    /**
     * @param TblCompany|null $tblCompany
     */
    public function setServiceManagementCompany( TblCompany $tblCompany = null )
    {

        $this->serviceManagement_Company = ( null === $tblCompany ? null : $tblCompany->getId() );
    }
}
