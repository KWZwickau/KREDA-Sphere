<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblAccessPrivilegeRoleList")
 */
class TblAccessPrivilegeRoleList
{

    const ATTR_TBL_ACCESS_PRIVILEGE = 'tblAccessPrivilege';
    const ATTR_TBL_ACCESS_ROLE = 'tblAccessRole';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="bigint")
     */
    private $tblAccessPrivilege;
    /**
     * @Column(type="bigint")
     */
    private $tblAccessRole;

    /**
     * @return integer
     */
    public function getTblAccessPrivilege()
    {

        return $this->tblAccessPrivilege;
    }

    /**
     * @param integer $tblAccessPrivilege
     */
    public function setTblAccessPrivilege( $tblAccessPrivilege )
    {

        $this->tblAccessPrivilege = $tblAccessPrivilege;
    }

    /**
     * @return integer
     */
    public function getTblAccessRole()
    {

        return $this->tblAccessRole;
    }

    /**
     * @param integer $tblAccessRole
     */
    public function setTblAccessRole( $tblAccessRole )
    {

        $this->tblAccessRole = $tblAccessRole;
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
