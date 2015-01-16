<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form\Structure;

use KREDA\Sphere\Common\AbstractFrontend\Alert\AbstractElement as AbstractAlert;
use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractElement;
use KREDA\Sphere\Common\AbstractFrontend\Form\AbstractForm;
use KREDA\Sphere\Common\AbstractFrontend\IElementInterface;

/**
 * Class GridFormCol
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form\Structure
 */
class GridFormCol extends AbstractForm
{

    /** @var IElementInterface[]|AbstractAlert[] $GridElementList */
    private $GridElementList = array();
    /** @var string $GridTitle */
    private $GridSize = 12;

    /**
     * @param IElementInterface|IElementInterface[]|AbstractAlert|AbstractAlert[] $GridElementList
     * @param int                                                                 $GridSize
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
