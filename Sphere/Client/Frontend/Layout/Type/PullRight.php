<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class PullRight
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class PullRight extends AbstractType
{

    /** @var string $Content */
    private $Content = '';

    /**
     * @param string $Content
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

        return '<div class="pull-right">'.$this->Content.'</div>';
    }
}
