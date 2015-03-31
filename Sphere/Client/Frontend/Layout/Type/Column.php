<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Column
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class Column extends AbstractType
{

    /** @var string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend */
    private $AbstractFrontend = array();
    /** @var string $Size */
    private $Size = 12;

    /**
     * @param string|AbstractFrontend|AbstractFrontend[] $AbstractFrontend
     * @param int                                        $Size
     */
    function __construct( $AbstractFrontend, $Size = 12 )
    {

        if (!is_array( $AbstractFrontend )) {
            $AbstractFrontend = array( $AbstractFrontend );
        }
        $this->AbstractFrontend = $AbstractFrontend;
        $this->Size = $Size;
    }

    /**
     * @return string
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
