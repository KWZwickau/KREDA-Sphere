<?php
namespace KREDA\Sphere\Common\Frontend\Form\Structure;

use KREDA\Sphere\Common\Frontend\Form\AbstractElement;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;

/**
 * Class GridFormCol
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Structure
 */
class GridFormCol extends AbstractForm
{

    /** @var \KREDA\Sphere\Common\Frontend\IElementInterface[]|\KREDA\Sphere\Common\Frontend\Alert\AbstractElement[] $GridElementList */
    private $GridElementList = array();
    /** @var string $GridTitle */
    private $GridSize = 12;

    /**
     * @param \KREDA\Sphere\Common\Frontend\IElementInterface|\KREDA\Sphere\Common\Frontend\IElementInterface[]|\KREDA\Sphere\Common\Frontend\Alert\AbstractElement|\KREDA\Sphere\Common\Frontend\Alert\AbstractElement[] $GridElementList
     * @param int                                                                                                                                                                                                         $GridSize
     */
    function __construct( $GridElementList, $GridSize = 12 )
    {

        if (!is_array( $GridElementList )) {
            $GridElementList = array( $GridElementList );
        }
        /** @var AbstractElement $Object */
        foreach ((array)$GridElementList as $Index => $Object) {
            if (null !== $Object->getName()) {
                $GridElementList[$Object->getName()] = $Object;
                unset( $GridElementList[$Index] );
            }
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
