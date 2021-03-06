<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccountAccessList")
 */
class TblAccountAccessList extends AbstractEntity
{

    const ATTR_TBL_ACCOUNT_ROLE = 'tblAccountRole';
    const ATTR_SERVICE_GATEKEEPER_ACCESS = 'serviceGatekeeper_Access';

    /**
     * @Column(type="bigint")
     */
    protected $tblAccountRole;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Access;

    /**
     * @return bool|TblAccess
     */
    public function getServiceGatekeeperAccess()
    {

        if (null === $this->serviceGatekeeper_Access) {
            return false;
        } else {
            return Gatekeeper::serviceAccess()->entityAccessById( $this->serviceGatekeeper_Access );
        }
    }

    /**
     * @param null|TblAccess $tblAccess
     */
    public function setServiceGatekeeperAccess( TblAccess $tblAccess = null )
    {

        $this->serviceGatekeeper_Access = ( null === $tblAccess ? null : $tblAccess->getId() );
    }

    /**
     * @return bool|TblAccountRole
     */
    public function getTblAccountRole()
    {

        if (null === $this->tblAccountRole) {
            return false;
        } else {
            return Gatekeeper::serviceAccount()->entityAccountRoleById( $this->tblAccountRole );
        }
    }

    /**
     * @param null|TblAccountRole $tblAccountRole
     */
    public function setTblAccountRole( TblAccountRole $tblAccountRole )
    {

        $this->tblAccountRole = ( null === $tblAccountRole ? null : $tblAccountRole->getId() );
    }
}
