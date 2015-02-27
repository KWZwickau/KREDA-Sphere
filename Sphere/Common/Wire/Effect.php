<?php
namespace KREDA\Sphere\Common\Wire;

/**
 * Class Effect
 *
 * @package KREDA\Sphere\Common\Wire
 */
class Effect
{

    private $Content = null;

    /**
     * @param $Content
     */
    function __construct( $Content )
    {

        $this->Content = $Content;
    }

    /**
     * @return string
     */
    function __toString()
    {

        return (string)implode( (array)$this->Content );
    }
}
