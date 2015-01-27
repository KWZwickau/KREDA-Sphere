<?php
namespace KREDA\Sphere;

use KREDA\Sphere\Client\Configuration;

/**
 * Interface IApplicationInterface
 *
 * @package KREDA\Sphere
 */
interface IApplicationInterface
{

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration );
}
