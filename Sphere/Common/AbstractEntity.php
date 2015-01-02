<?php
namespace KREDA\Sphere\Common;

/**
 * Class AbstractEntity
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractEntity
{

    /**
     * @throws \Exception
     */
    final public function __toArray()
    {

        return get_object_vars( $this );
    }
}
