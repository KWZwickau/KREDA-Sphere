<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
 */
abstract class EntitySchema extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        /**
         * Table
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        /**
         * Migration
         */
        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->getDatabaseHandler()->addProtocol( $Query );
                if (!$Simulate) {
                    $this->getDatabaseHandler()->setStatement( $Query );
                }
            }
        }
        /**
         * View
         */

        /**
         * Protocol
         */
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

}
