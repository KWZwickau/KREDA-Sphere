<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreCondition;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreGroup;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreRule;
use KREDA\Sphere\Application\Graduation\Service\Score\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Score
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Score extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Graduation', 'Score', $this->getConsumerSuffix( $tblConsumer ) );
    }

    public function setupDatabaseContent()
    {

        $tblScoreRule = $this->actionCreateScoreRule(
            'KA 40:60 Rest',
            'Alle Klassenarbeiten werden 40:60 mit den restlichen Noten verrechnet.'
        );
        $tblScoreCondition = $this->actionCreateScoreCondition( 'Voreinstellung', 1, null );
        $tblScoreGroup = $this->actionCreateScoreGroup( 'Voreinstellung', 1, null );
        $this->actionAddRuleCondition( $tblScoreRule, $tblScoreCondition );
        $this->actionAddConditionGroup( $tblScoreCondition, $tblScoreGroup );

        $tblGradeType = Graduation::serviceGrade()->entityGradeTypeByAcronym( 'LK' );
        $this->actionAddGroupGradeType( $tblScoreGroup, $tblGradeType, 0.6 );
        $tblGradeType = Graduation::serviceGrade()->entityGradeTypeByAcronym( 'KA' );
        $this->actionAddGroupGradeType( $tblScoreGroup, $tblGradeType, 0.4 );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreRule
     */
    public function entityScoreRuleById( $Id )
    {

        return parent::entityScoreRuleById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreGroup
     */
    public function entityScoreGroupById( $Id )
    {

        return parent::entityScoreGroupById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreCondition
     */
    public function entityScoreConditionById( $Id )
    {

        return parent::entityScoreConditionById( $Id );
    }
}
