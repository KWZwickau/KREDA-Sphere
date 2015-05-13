<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccessRightList")
 */
class TblAccessRightList extends AbstractEntity
{

    const ATTR_TBL_ACCESS_RIGHT = 'tblAccessRight';
    const ATTR_TBL_ACCESS_PRIVILEGE = 'tblAccessPrivilege';

    /**
     * @Column(type="bigint")
     */
    protected $tblAccessRight;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccessPrivilege;

    /**
     * @return bool|TblAccessRight
     */
    public function getTblAccessRight()
    {

        if (null === $this->tblAccessRight) {
            return false;
        } else {
            return Gatekeeper::serviceAccess()->entityRightById( $this->tblAccessRight );
        }
    }

    /**
     * @param null|TblAccessRight $tblAccessRight
     */
    public function setTblAccessRight( TblAccessRight $tblAccessRight = null )
    {

        $this->tblAccessRight = ( null === $tblAccessRight ? null : $tblAccessRight->getId() );
    }

    /**
     * @return bool|TblAccessPrivilege
     */
    public function getTblAccessPrivilege()
    {

        if (null === $this->tblAccessPrivilege) {
            return false;
        } else {
            return Gatekeeper::serviceAccess()->entityPrivilegeById( $this->tblAccessPrivilege );
        }
    }

    /**
     * @param null|TblAccessPrivilege $tblAccessPrivilege
     */
    public function setTblAccessPrivilege( TblAccessPrivilege $tblAccessPrivilege = null )
    {

        $this->tblAccessPrivilege = ( null === $tblAccessPrivilege ? null : $tblAccessPrivilege->getId() );
    }
}
