<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
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

        $Protocol[] = Token::getApi()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Token::getApi()->setupSystem();
        }
        $Protocol[] = Access::getApi()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Access::getApi()->setupSystem();
        }
        $Protocol[] = Account::getApi()->setupDataStructure( $Simulate );
        if (!$Simulate) {
            Account::getApi()->setupSystem();
        }

        /**
         * System
         */

        $Protocol[] = Consumer::getApi()->setupDataStructure( $Simulate );

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
