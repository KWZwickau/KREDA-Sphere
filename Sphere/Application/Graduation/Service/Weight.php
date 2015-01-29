<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Graduation\Service\Weight\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Weight
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Weight extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->setDatabaseHandler( 'Graduation', 'Weight', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {
    }
}
