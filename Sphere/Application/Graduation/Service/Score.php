<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreCondition;
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
     * @throws \Exception
     */
    public function __construct()
    {

        if (false !== ( $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession() )) {
            $Consumer = $tblConsumer->getDatabaseSuffix();
        } else {
            $Consumer = 'EGE';
        }
        $this->setDatabaseHandler( 'Graduation', 'Score', $Consumer );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateScoreRule(
            'KA 40:60 Rest',
            'Alle Klassenarbeiten werden 40:60 mit den restlichen Noten verrechnet.'
        );
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
     * @return bool|TblScoreCondition
     */
    public function entityScoreConditionById( $Id )
    {

        return parent::entityScoreConditionById( $Id );
    }
}
