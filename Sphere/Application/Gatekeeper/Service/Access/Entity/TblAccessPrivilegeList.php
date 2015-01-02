<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccessPrivilegeList")
 */
class TblAccessPrivilegeList extends AbstractEntity
{

    const ATTR_TBL_ACCESS_PRIVILEGE = 'tblAccessPrivilege';
    const ATTR_TBL_ACCESS = 'tblAccess';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccessPrivilege;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccess;

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

    /**
     * @return bool|TblAccess
     */
    public function getTblAccess()
    {

        if (null === $this->tblAccess) {
            return false;
        } else {
            return Gatekeeper::serviceAccess()->entityAccessById( $this->tblAccess );
        }
    }

    /**
     * @param null|TblAccess $tblAccess
     */
    public function setTblAccess( TblAccess $tblAccess = null )
    {

        $this->tblAccess = ( null === $tblAccess ? null : $tblAccess->getId() );
    }

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }
}
