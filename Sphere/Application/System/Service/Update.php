<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Demo\Demo;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Update extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        /**
         * Basics
         */
        $Protocol[] = System::serviceProtocol()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceToken()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceAccess()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceConsumer()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceAccount()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::servicePerson()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceAddress()->setupDatabaseSchema( $Simulate );
        /**
         * Demo
         */
        $Protocol[] = Demo::serviceDemoService()->setupDatabaseSchema( $Simulate );
        /**
         * Payload
         */

        if (!$Simulate) {
            /**
             * Basics
             */
            System::serviceProtocol()->setupDatabaseContent();
            Gatekeeper::serviceConsumer()->setupDatabaseContent();
            Management::serviceAddress()->setupDatabaseContent();
            Gatekeeper::serviceAccess()->setupDatabaseContent();
            Gatekeeper::serviceAccount()->setupDatabaseContent();
            Management::servicePerson()->setupDatabaseContent();
            Gatekeeper::serviceToken()->setupDatabaseContent();
            /**
             * Payload
             */
        }

        return implode( $Protocol );
    }

}
