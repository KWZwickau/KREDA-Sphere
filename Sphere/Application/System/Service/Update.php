<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Client as Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Update extends AbstractService
{

    /**
     * @return Landing
     */
    public function apiUpdate()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Landing
     */
    public function apiUpdateSimulation()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Simulation' );
        $View->setMessage( '' );
        $View->setContent( Update::getApi()->setupDatabaseSchema( true ) );
        return $View;
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Prevent Update-Timeout
         */
        set_time_limit( 0 );

        /**
         * Gatekeeper
         */

        $Protocol[] = Gatekeeper::serviceToken()->setupDatabaseSchema( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceToken()->setupDatabaseContent();
        }
        $Protocol[] = Gatekeeper::serviceAccess()->setupDatabaseSchema( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceAccess()->setupDatabaseContent();
        }
        $Protocol[] = Gatekeeper::serviceAccount()->setupDatabaseSchema( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceAccount()->setupDatabaseContent();
        }

        /**
         * System
         */

        $Protocol[] = Consumer::getApi()->setupDatabaseSchema( $Simulate );
        if (!$Simulate) {
            Consumer::getApi()->setupDatabaseContent();
        }

        return implode( $Protocol );
    }

    /**
     * @return Landing
     */
    public function apiUpdatePerform()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( '' );
        $View->setContent( Update::getApi()->setupDatabaseSchema( false ) );
        return $View;
    }
}
