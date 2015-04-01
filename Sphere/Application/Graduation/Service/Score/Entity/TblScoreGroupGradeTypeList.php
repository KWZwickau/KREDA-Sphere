<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreGroupGradeTypeList")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreGroupGradeTypeList extends AbstractEntity
{

    const ATTR_TBL_SCORE_GROUP = 'tblScoreGroup';
    const ATTR_SERVICE_GRADUATION_GRADE = 'serviceGraduation_Grade';

    /**
     * @Column(type="bigint")
     */
    protected $serviceGraduation_Grade;
    /**
     * @Column(type="bigint")
     */
    protected $tblScoreGroup;
    /**
     * @Column(type="float")
     */
    protected $Multiplier;

    /**
     * @return bool|TblScoreGroup
     */
    public function getServiceGraduationGrade()
    {

        if (null === $this->serviceGraduation_Grade) {
            return false;
        } else {
            return Graduation::serviceGrade()->entityGradeTypeById( $this->serviceGraduation_Grade );
        }
    }

    /**
     * @param null|TblGradeType $serviceGraduation_Grade
     */
    public function setServiceGraduationGrade( TblGradeType $serviceGraduation_Grade = null )
    {

        $this->serviceGraduation_Grade = ( null === $serviceGraduation_Grade ? null : $serviceGraduation_Grade->getId() );
    }

    /**
     * @return bool|TblScoreGroup
     */
    public function getTblScoreGroup()
    {

        if (null === $this->tblScoreGroup) {
            return false;
        } else {
            return Graduation::serviceScore()->entityScoreGroupById( $this->tblScoreGroup );
        }
    }

    /**
     * @param null|TblScoreGroup $tblScoreGroup
     */
    public function setTblScoreGroup( TblScoreGroup $tblScoreGroup = null )
    {

        $this->tblScoreGroup = ( null === $tblScoreGroup ? null : $tblScoreGroup->getId() );
    }

    /**
     * @return float
     */
    public function getMultiplier()
    {

        return $this->Multiplier;
    }

    /**
     * @param float $Multiplier
     */
    public function setMultiplier( $Multiplier )
    {

        $this->Multiplier = $Multiplier;
    }
}
