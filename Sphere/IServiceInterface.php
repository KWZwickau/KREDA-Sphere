<?php
namespace KREDA\Sphere;

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
    public function setupDataStructure( $Simulate = true );

    /**
     * @return void
     */
    public function setupSystem();
}
