<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Weight\Entity\TblWeightDimension;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblSubjectGroup")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubjectGroup extends AbstractEntity
{

    const ATTR_TBL_TERM = 'tblTerm';
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
    protected $tblTerm;
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
     * @return bool|TblTerm
     */
    public function getTblTerm()
    {

        if (null === $this->tblTerm) {
            return false;
        } else {
            return Management::serviceEducation()->entityTermById( $this->tblTerm );
        }
    }

    /**
     * @param null|TblTerm $tblTerm
     */
    public function setTblTerm( TblTerm $tblTerm = null )
    {

        $this->tblTerm = ( null === $tblTerm ? null : $tblTerm->getId() );
    }

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
     * @return bool|TblWeightDimension
     */
    public function getServiceGraduationDimension()
    {

        if (null === $this->serviceGraduation_Dimension) {
            return false;
        } else {
            return Graduation::serviceWeight()->entityWeightDimensionById( $this->serviceGraduation_Dimension );
        }
    }

    /**
     * @param null|TblWeightDimension $tblWeightDimension
     */
    public function setServiceGraduationDimension( TblWeightDimension $tblWeightDimension = null )
    {

        $this->serviceGraduation_Dimension = ( null === $tblWeightDimension ? null : $tblWeightDimension->getId() );
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
