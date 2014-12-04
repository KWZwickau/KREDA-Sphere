<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblAccessRightPrivilegeList")
 */
class TblAccessRightPrivilegeList
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
     * @return integer
     */
    public function getTblAccessRight()
    {

        return $this->tblAccessRight;
    }

    /**
     * @param integer $tblAccessRight
     */
    public function setTblAccessRight( $tblAccessRight )
    {

        $this->tblAccessRight = $tblAccessRight;
    }

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
