<?php
namespace KREDA\Sphere\Common;

/**
 * Class AbstractDebugger
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractDebugger
{


    /**
     * @return Debugger
     */
    final public static function getDebugger()
    {

        return new Debugger();
    }
}
