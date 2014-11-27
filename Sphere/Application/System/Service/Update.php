<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Client as Gatekeeper;
use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Update extends Service
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
        $View->setContent( Update::getApi()->setupDataStructure( true ) );
        return $View;
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDataStructure( $Simulate = true )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        /**
         * Prevent Update-Timeout
         */
        set_time_limit( 0 );

        /**
         * Gatekeeper
         */

        $Protocol[] = Gatekeeper::serviceToken()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceToken()->setupSystem();
        }
        $Protocol[] = Gatekeeper::serviceAccess()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceAccess()->setupSystem();
        }
        $Protocol[] = Gatekeeper::serviceAccount()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Gatekeeper::serviceAccount()->setupSystem();
        }

        /**
         * System
         */

        $Protocol[] = Consumer::getApi()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Consumer::getApi()->setupSystem();
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
        $View->setContent( Update::getApi()->setupDataStructure( false ) );
        return $View;
    }
}
