<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;

/**
 * @Entity
 * @Table(name="tblAccessRightList")
 */
class TblAccessRightList
{

    const ATTR_TBL_ACCESS_RIGHT = 'tblAccessRight';
    const ATTR_TBL_ACCESS_PRIVILEGE = 'tblAccessPrivilege';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="bigint")
     */
    private $tblAccessRight;
    /**
     * @Column(type="bigint")
     */
    private $tblAccessPrivilege;

    /**
     * @return bool|TblAccessRight
     */
    public function getTblAccessRight()
    {

        return Gatekeeper::serviceAccess()->entityAccessRightById( $this->tblAccessRight );
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

        return Gatekeeper::serviceAccess()->entityAccessPrivilegeById( $this->tblAccessPrivilege );
    }

    /**
     * @param null|TblAccessPrivilege $tblAccessPrivilege
     */
    public function setTblAccessPrivilege( TblAccessPrivilege $tblAccessPrivilege = null )
    {

        $this->tblAccessPrivilege = ( null === $tblAccessPrivilege ? null : $tblAccessPrivilege->getId() );
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
