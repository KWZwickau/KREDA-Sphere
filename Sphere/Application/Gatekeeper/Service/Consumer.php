<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\EntityAction;

/**
 * Class Consumer
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Consumer extends EntityAction
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setDatabaseHandler( 'Gatekeeper', 'Consumer' );
    }

    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->actionCreateConsumer( 'Demo' );

    }
}
