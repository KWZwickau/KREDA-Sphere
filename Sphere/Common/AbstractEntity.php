<?php
namespace KREDA\Sphere\Common;

/**
 * Class AbstractEntity
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractEntity extends AbstractExtension
{

    /**
     * @throws \Exception
     */
    final public function __toArray()
    {

        $Array = get_object_vars( $this );
        array_walk( $Array, function ( &$V ) {

            if (is_object( $V )) {
                if ($V instanceof \DateTime) {
                    $V = $V->format( 'd.m.Y H:i:s' );
                }
            }
        } );

        return $Array;
    }
}
