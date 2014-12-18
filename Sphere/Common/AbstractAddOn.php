<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Common\AddOn\Debugger;

/**
 * Class AbstractAddOn
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractAddOn
{


    /**
     * @return Debugger
     */
    final public static function getDebugger()
    {

        return new Debugger();
    }
}
