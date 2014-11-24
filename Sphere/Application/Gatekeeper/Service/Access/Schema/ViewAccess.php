<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(readOnly=true)
 * @Table(name="viewAccess")
 */
class ViewAccess
{

    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessRole;
    /**
     * @Column(type="string")
     */
    private $RoleName;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessPrivilegeRoleList;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessPrivilege;
    /**
     * @Column(type="string")
     */
    private $PrivilegeName;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessRightPrivilegeList;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessRight;
    /**
     * @Column(type="string")
     */
    private $RightRoute;

    /**
     * @return string
     */
    public function getPrivilegeName()
    {

        return $this->PrivilegeName;
    }

    /**
     * @return string
     */
    public function getRightRoute()
    {

        return $this->RightRoute;
    }

    /**
     * @return string
     */
    public function getRoleName()
    {

        return $this->RoleName;
    }

    /**
     * @return integer
     */
    public function getTblAccessPrivilege()
    {

        return $this->tblAccessPrivilege;
    }

    /**
     * @return integer
     */
    public function getTblAccessPrivilegeRoleList()
    {

        return $this->tblAccessPrivilegeRoleList;
    }

    /**
     * @return integer
     */
    public function getTblAccessRight()
    {

        return $this->tblAccessRight;
    }

    /**
     * @return integer
     */
    public function getTblAccessRightPrivilegeList()
    {

        return $this->tblAccessRightPrivilegeList;
    }

    /**
     * @return integer
     */
    public function getTblAccessRole()
    {

        return $this->tblAccessRole;
    }
}
