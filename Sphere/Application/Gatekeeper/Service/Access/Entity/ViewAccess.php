<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(readOnly=true)
 * @Table(name="viewAccess")
 * @Cache(usage="READ_ONLY")
 */
class ViewAccess
{

    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccess;
    /**
     * @Column(type="string")
     */
    private $AccessName;
    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblAccessPrivilegeList;
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
    private $tblAccessRightList;
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
    public function getAccessName()
    {

        return $this->AccessName;
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
    public function getTblAccessPrivilegeList()
    {

        return $this->tblAccessPrivilegeList;
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
    public function getTblAccessRightList()
    {

        return $this->tblAccessRightList;
    }

    /**
     * @return integer
     */
    public function getTblAccess()
    {

        return $this->tblAccess;
    }
}
