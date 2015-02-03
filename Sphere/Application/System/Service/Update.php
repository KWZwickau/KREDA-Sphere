<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Graduation;
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

        $Protocol[] = Graduation::serviceGrade()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Graduation::serviceScore()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Graduation::serviceWeight()->setupDatabaseSchema( $Simulate );

        if (!$Simulate) {

            System::serviceProtocol()->setupDatabaseContent();

            Gatekeeper::serviceConsumer()->setupDatabaseContent();

            Management::serviceAddress()->setupDatabaseContent();

            Gatekeeper::serviceAccount()->setupDatabaseContent();
            Management::servicePerson()->setupDatabaseContent();

            Gatekeeper::serviceToken()->setupDatabaseContent();
            Gatekeeper::serviceAccess()->setupDatabaseContent();

            Management::serviceEducation()->setupDatabaseContent();

            Graduation::serviceGrade()->setupDatabaseContent();
            Graduation::serviceScore()->setupDatabaseContent();
            Graduation::serviceWeight()->setupDatabaseContent();
        }

        return implode( $Protocol );
    }

}
