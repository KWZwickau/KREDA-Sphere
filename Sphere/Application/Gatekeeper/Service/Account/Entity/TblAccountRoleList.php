<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;

/**
 * @Entity
 * @Table(name="tblAccountRoleList")
 */
class TblAccountRoleList
{

    const ATTR_TBL_ACCOUNT_ROLE = 'tblAccountRole';
    const ATTR_SERVICE_GATEKEEPER_ACCESS = 'serviceGatekeeper_Access';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="bigint")
     */
    private $tblAccountRole;
    /**
     * @Column(type="bigint")
     */
    private $serviceGatekeeper_Access;

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param null|integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }

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
