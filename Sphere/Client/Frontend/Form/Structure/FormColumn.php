<?php
namespace KREDA\Sphere\Client\Frontend\Form\Structure;

use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Input\AbstractType as AbstractInput;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class FormColumn
 *
 * @package KREDA\Sphere\Client\Frontend\Form\Structure
 */
class FormColumn extends AbstractType
{

    /** @var AbstractFrontend|AbstractFrontend[] $AbstractFrontend */
    private $AbstractFrontend = array();
    /** @var int $Size */
    private $Size = 12;

    /**
     * @param AbstractFrontend|AbstractFrontend[] $AbstractFrontend
     * @param int                                 $Size
     */
    function __construct( $AbstractFrontend, $Size = 12 )
    {

        if (!is_array( $AbstractFrontend )) {
            $AbstractFrontend = array( $AbstractFrontend );
        }
        /** @var AbstractInput $Object */
        foreach ((array)$AbstractFrontend as $Index => $Object) {
            if (null !== $Object->getName()) {
                $AbstractFrontend[$Object->getName()] = $Object;
                unset( $AbstractFrontend[$Index] );
            }
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
