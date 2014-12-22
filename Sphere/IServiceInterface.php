<?php
namespace KREDA\Sphere;

use KREDA\Sphere\Common\Database\Handler;

/**
 * Interface IServiceInterface
 *
 * @package KREDA\Sphere
 */
interface IServiceInterface
{

    /**
     * @param string $BaseRoute Client-Application Route
     *
     * @return static Service Instance
     */
    public static function getApi( $BaseRoute );

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true );

    /**
     * @return void
     */
    public function setupDatabaseContent();

    /**
     * @return Handler|null
     */
    public function getDatabaseHandler();

    /**
     * @param string $Application
     * @param string $Service
     * @param string $Consumer
     */
    public function setDatabaseHandler( $Application, $Service = '', $Consumer = '' );
}
