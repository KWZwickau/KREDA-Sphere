<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Service\Grade\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Grade
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Grade extends EntityAction
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
        $this->setDatabaseHandler( 'Graduation', 'Grade', $Consumer );
    }

    public function setupDatabaseContent()
    {
    }
}
