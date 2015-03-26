<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;

/**
 * Class GridLayoutRight
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayoutRight extends AbstractLayout
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
