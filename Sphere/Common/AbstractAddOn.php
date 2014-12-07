<?php
namespace KREDA\Sphere\Common;

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
