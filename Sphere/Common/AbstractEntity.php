<?php
namespace KREDA\Sphere\Common;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * Class AbstractEntity
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractEntity extends AbstractExtension
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;

    /**
     * @return integer
     */
    final public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    final public function setId( $Id )
    {

        $this->Id = $Id;
    }

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
