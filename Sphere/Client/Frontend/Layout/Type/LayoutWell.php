<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutWell
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutWell extends AbstractType
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

        return '<div class="well">'.$this->Content.'</div>';
    }
}
