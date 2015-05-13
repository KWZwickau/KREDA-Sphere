<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreConditionGroupList")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreConditionGroupList extends AbstractEntity
{

    const ATTR_TBL_SCORE_GROUP = 'tblScoreGroup';
    const ATTR_TBL_SCORE_CONDITION = 'tblScoreCondition';

    /**
     * @Column(type="bigint")
     */
    protected $tblScoreGroup;
    /**
     * @Column(type="bigint")
     */
    protected $tblScoreCondition;

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
