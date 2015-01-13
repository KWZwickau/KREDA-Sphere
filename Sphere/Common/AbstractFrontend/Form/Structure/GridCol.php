<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractElement;
use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;

/**
 * Class GridCol
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridCol extends AbstractForm
{

    /** @var AbstractElement[] $GridElementList */
    private $GridElementList = array();
    /** @var string $GridTitle */
    private $GridSize = 12;

    /**
     * @param AbstractElement|AbstractElement[] $GridElementList
     * @param int                               $GridSize
     */
    function __construct( $GridElementList, $GridSize = 12 )
    {

        if (!is_array( $GridElementList )) {
            $GridElementList = array( $GridElementList );
        }
        /** @var AbstractElement $Object */
        foreach ((array)$GridElementList as $Index => $Object) {
            $GridElementList[$Object->getName()] = $Object;
            unset( $GridElementList[$Index] );
        }
        $this->GridElementList = $GridElementList;
        $this->GridSize = $GridSize;
    }

    /**
     * @return string
     */
    public function getSize()
    {

        return $this->GridSize;
    }

    /**
     * @return AbstractElement[]
     */
    public function getElementList()
    {

        return $this->GridElementList;
    }
}
