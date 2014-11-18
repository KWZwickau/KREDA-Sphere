<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Assistance\Service\Youtrack;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
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

        /**
         * Gatekeeper
         */
        $Protocol[] = Access::getApi()->setupDataStructure( $Simulate );
        Access::getApi()->setupSystem();
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

        $View = new Landing();
        $View->setTitle( 'KREDA Update' );
        $View->setMessage( '' );
        $View->setContent( Update::getApi()->setupDataStructure( false ) );
        return $View;
    }
}
