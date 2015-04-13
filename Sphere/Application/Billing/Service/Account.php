<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Account\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Account extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Account', $this->getConsumerSuffix() );
    }
}
