<?php
namespace KREDA\Sphere\Application\System\Service;

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
         * Prevent Update-Timeout
         */
        set_time_limit( 0 );

        /**
         * Gatekeeper
         */

        $Protocol[] = System::serviceProtocol()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceToken()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceAccess()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceConsumer()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Gatekeeper::serviceAccount()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::servicePerson()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceEducation()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceAddress()->setupDatabaseSchema( $Simulate );

        if (!$Simulate) {

            System::serviceProtocol()->setupDatabaseContent();
            Management::serviceAddress()->setupDatabaseContent();
            Management::servicePerson()->setupDatabaseContent();

            Gatekeeper::serviceAccount()->setupDatabaseContent();
            Gatekeeper::serviceToken()->setupDatabaseContent();
            Gatekeeper::serviceAccess()->setupDatabaseContent();
            Gatekeeper::serviceConsumer()->setupDatabaseContent();
            Management::serviceEducation()->setupDatabaseContent();
        }

        return implode( $Protocol );
    }

}
