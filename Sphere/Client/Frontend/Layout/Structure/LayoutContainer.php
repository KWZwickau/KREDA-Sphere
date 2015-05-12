<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Structure;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutContainer
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Structure
 */
class LayoutContainer extends AbstractType
{

    /** @var string $Content */
    private $Content = '';

    /**
     * @param string $Content
     */
    public function __construct( $Content )
    {

        $this->Content = $Content;
    }

    /**
     * @return string
     */
    function __toString()
    {

        return '<div>'.$this->Content.'</div>';
    }
}
