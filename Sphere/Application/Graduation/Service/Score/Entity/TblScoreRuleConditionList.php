<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblScoreRuleConditionList")
 * @Cache(usage="READ_ONLY")
 */
class TblScoreRuleConditionList extends AbstractEntity
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="bigint")
     */
    protected $tblScoreRule;
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
     * @return bool|TblScoreRule
     */
    public function getTblScoreRule()
    {

        if (null === $this->tblScoreRule) {
            return false;
        } else {
            return Graduation::serviceScore()->entityScoreRuleById( $this->tblScoreRule );
        }
    }

    /**
     * @param null|TblScoreRule $tblScoreRule
     */
    public function setTblScoreRule( TblScoreRule $tblScoreRule = null )
    {

        $this->tblScoreRule = ( null === $tblScoreRule ? null : $tblScoreRule->getId() );
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
