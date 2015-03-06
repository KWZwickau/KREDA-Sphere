<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;

/**
 * Class GridLayoutCol
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayoutCol extends AbstractLayout
{

    /** @var string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend */
    private $AbstractFrontend = array();
    /** @var string $GridTitle */
    private $GridLayoutSize = 12;

    /**
     * @param string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend
     * @param int                                        $GridLayoutSize
     */
    function __construct( $AbstractFrontend, $GridLayoutSize = 12 )
    {

        if (!is_array( $AbstractFrontend )) {
            $AbstractFrontend = array( $AbstractFrontend );
        }
        $this->AbstractFrontend = $AbstractFrontend;
        $this->GridLayoutSize = $GridLayoutSize;
    }

    /**
     * @return string
     */
    public function getSize()
    {

        return $this->GridLayoutSize;
    }

    /**
     * @return AbstractFrontend[]
     */
    public function getAbstractFrontend()
    {

        return $this->AbstractFrontend;
    }
}
