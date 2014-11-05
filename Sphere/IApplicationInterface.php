<?php
namespace KREDA\Sphere;

use KREDA\Sphere\Client\Component\Element\Element;
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
    public static function setupApi( Configuration $Configuration );

    /**
     * @return void
     */
    public function setupModule();

    /**
     * @return Element
     */
    public function apiMain();
}
