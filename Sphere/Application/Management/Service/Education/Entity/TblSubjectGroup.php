<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblSubjectGroup")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubjectGroup extends AbstractEntity
{

    const ATTR_TBL_SUBJECT = 'tblSubject';
    const ATTR_TBL_GROUP = 'tblGroup';
    const ATTR_SERVICE_GRADUATION_DIMENSION = 'serviceGraduation_Dimension';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="bigint")
     */
    protected $tblSubject;
    /**
     * @Column(type="bigint")
     */
    protected $tblGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGraduation_Dimension;

    /**
     * @return bool|TblSubject
     */
    public function getTblSubject()
    {

        if (null === $this->tblSubject) {
            return false;
        } else {
            return Management::serviceEducation()->entitySubjectById( $this->tblSubject );
        }
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

        if (null === $this->tblGroup) {
            return false;
        } else {
            return Management::serviceEducation()->entityGroupById( $this->tblGroup );
        }
    }

    /**
     * @param null|TblGroup $tblGroup
     */
    public function setTblGroup( TblGroup $tblGroup = null )
    {

        $this->tblGroup = ( null === $tblGroup ? null : $tblGroup->getId() );
    }

    /**
     * @return bool|TblDimension
     */
    public function getServiceGraduationDimension()
    {

        if (null === $this->serviceGraduation_Dimension) {
            return false;
        } else {
            return Graduation::serviceWeight()->entityDimensionById( $this->serviceGraduation_Dimension );
        }
    }

    /**
     * @param null|TblDimension $tblDimension
     */
    public function setServiceGraduationDimension( TblDimension $tblDimension = null )
    {

        $this->serviceGraduation_Dimension = ( null === $tblDimension ? null : $tblDimension->getId() );
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
