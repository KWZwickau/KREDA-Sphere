<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Billing\Billing;
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
         * Payload
         */
        $Protocol[] = Graduation::serviceGrade()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Graduation::serviceScore()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Graduation::serviceWeight()->setupDatabaseSchema( $Simulate );

        $Protocol[] = Management::serviceEducation()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceCourse()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceStudent()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceCompany()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Management::serviceTableView()->setupDatabaseSchema( $Simulate );

        $Protocol[] = Billing::serviceAccount()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Billing::serviceBanking()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Billing::serviceCommodity()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Billing::serviceBasket()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Billing::serviceInvoice()->setupDatabaseSchema( $Simulate );
        $Protocol[] = Billing::serviceBalance()->setupDatabaseSchema( $Simulate );

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
            Graduation::serviceGrade()->setupDatabaseContent();
            Graduation::serviceScore()->setupDatabaseContent();
            Graduation::serviceWeight()->setupDatabaseContent();

            Management::serviceEducation()->setupDatabaseContent();
            Management::serviceCourse()->setupDatabaseContent();
            Management::serviceStudent()->setupDatabaseContent();
            Management::serviceCompany()->setupDatabaseContent();

            Billing::serviceAccount()->setupDatabaseContent();
            Billing::serviceBanking()->setupDatabaseContent();
            Billing::serviceCommodity()->setupDatabaseContent();
            Billing::serviceBasket()->setupDatabaseContent();
            Billing::serviceInvoice()->setupDatabaseContent();
            Billing::serviceBalance()->setupDatabaseContent();

        }

        return implode( $Protocol );
    }

}
