<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Structure;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class LayoutColumn
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Structure
 */
class LayoutColumn extends AbstractType
{

    /** @var string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend */
    private $AbstractFrontend = array();
    /** @var int $Size */
    private $Size = 12;

    /**
     * @param string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend
     * @param int                                        $Size
     */
    public function __construct( $AbstractFrontend, $Size = 12 )
    {

        if (!is_array( $AbstractFrontend )) {
            $AbstractFrontend = array( $AbstractFrontend );
        }
        $this->AbstractFrontend = $AbstractFrontend;
        $this->Size = $Size;
    }

    /**
     * @return int
     */
    public function getSize()
    {

        return $this->Size;
    }

    /**
     * @return AbstractFrontend[]
     */
    public function getAbstractFrontend()
    {

        return $this->AbstractFrontend;
    }
}
