<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Structure;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutTitle
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Structure
 */
class LayoutTitle extends AbstractType
{

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';

    /**
     * @param string $Title
     * @param string $Description
     */
    public function __construct( $Title, $Description = '' )
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
