<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Balance\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Balance extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Balance', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }
}
