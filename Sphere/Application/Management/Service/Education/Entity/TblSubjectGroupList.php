<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;

/**
 * @Entity
 * @Table(name="tblSubjectGroupList")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubjectGroupList
{

    const ATTR_TBL_SUBJECT = 'tblSubject';
    const ATTR_TBL_GROUP = 'tblGroup';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="bigint")
     */
    private $tblSubject;
    /**
     * @Column(type="bigint")
     */
    private $tblGroup;

    /**
     * @return bool|TblSubject
     */
    public function getTblSubject()
    {

        return Management::serviceEducation()->entitySubjectById( $this->tblSubject );
    }

    /**
     * @param null|TblSubject $tblSubject
     */
    public function setTblSubject( TblSubject $tblSubject = null )
    {

        $this->tblSubject = ( null === $tblSubject ? null : $tblSubject->getId() );
    }

    /**
     * @return bool|TblGroup
     */
    public function getTblGroup()
    {

        return Management::serviceEducation()->entityGroupById( $this->tblGroup );
    }

    /**
     * @param null|TblGroup $tblGroup
     */
    public function setTblGroup( TblGroup $tblGroup = null )
    {

        $this->tblGroup = ( null === $tblGroup ? null : $tblGroup->getId() );
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
