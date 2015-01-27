<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreConditionGradeTypeList")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreConditionGradeTypeList extends AbstractEntity
{

    const ATTR_TBL_SCORE_CONDITION = 'tblScoreCondition';
    const ATTR_SERVICE_GRADUATION_GRADE = 'serviceGraduation_Grade';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGraduation_Grade;
    /**
     * @Column(type="bigint")
     */
    protected $tblScoreCondition;

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
     * @return bool|TblScoreCondition
     */
    public function getTblScoreCondition()
    {

        if (null === $this->tblScoreCondition) {
            return false;
        } else {
            return Graduation::serviceScore()->entityScoreConditionById( $this->tblScoreCondition );
        }
    }

    /**
     * @param null|TblScoreCondition $tblScoreCondition
     */
    public function setTblScoreCondition( TblScoreCondition $tblScoreCondition = null )
    {

        $this->tblScoreCondition = ( null === $tblScoreCondition ? null : $tblScoreCondition->getId() );
    }
}
