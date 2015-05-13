<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutAspect
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutAspect extends AbstractType
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
            return '<h5>'.$this->Title.'</h5>';
        } else {
            return '<h5>'.$this->Title.' <small>'.$this->Description.'</small></h5>';
        }
    }
}
