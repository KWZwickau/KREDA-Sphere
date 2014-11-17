<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Assistance\Service\Account;
use KREDA\Sphere\Application\Assistance\Service\Application;
use KREDA\Sphere\Application\Assistance\Service\Youtrack;
use KREDA\Sphere\Application\Gatekeeper\Service\Access;
use KREDA\Sphere\Application\Management\Service\People;
use KREDA\Sphere\Application\Management\Service\Property;
use KREDA\Sphere\Application\Service;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Update extends Service
{

    /**
     * @return string
     */
    public function setupDataStructure()
    {

        /**
         * Gatekeeper
         */
        $Protocol[] = Access::getApi()->setupDataStructure();
        /**
         * System
         */
        $Protocol[] = Database::getApi()->setupDataStructure();
        $Protocol[] = Consumer::getApi()->setupDataStructure();
        /**
         * Assistance
         */
        $Protocol[] = Application::getApi()->setupDataStructure();
        $Protocol[] = Youtrack::getApi()->setupDataStructure();
        $Protocol[] = Account::getApi()->setupDataStructure();
        /**
         * Management
         */
        $Protocol[] = People::getApi()->setupDataStructure();
        $Protocol[] = Property::getApi()->setupDataStructure();

        return implode( $Protocol );
    }
}
