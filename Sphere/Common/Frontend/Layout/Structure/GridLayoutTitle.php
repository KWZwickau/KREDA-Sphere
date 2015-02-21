<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;

/**
 * Class GridLayoutTitle
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayoutTitle extends AbstractLayout
{

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';

    /**
     * @param string $Title
     * @param string $Description
     */
    function __construct( $Title, $Description = '' )
    {

        $this->Title = $Title;
        $this->Description = $Description;
    }

    /**
     * @return string
     */
    function __toString()
    {

        if (empty( $this->Description )) {
            return '<h4>'.$this->Title.'</h4>';
        } else {
            return '<h4>'.$this->Title.' <small>'.$this->Description.'</small></h4>';
        }
    }
}
