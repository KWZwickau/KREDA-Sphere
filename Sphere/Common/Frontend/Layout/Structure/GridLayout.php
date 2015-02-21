<?php
namespace KREDA\Sphere\Common\Frontend\Layout\Structure;

use KREDA\Sphere\Common\Frontend\Layout\AbstractLayout;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class GridLayout
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
class GridLayout extends AbstractLayout
{

    /**
     * @param GridLayoutGroup|GridLayoutGroup[] $GridLayoutGroup
     *
     * @throws TemplateTypeException
     */
    function __construct( $GridLayoutGroup )
    {

        if (!is_array( $GridLayoutGroup )) {
            $GridLayoutGroup = array( $GridLayoutGroup );
        }
        $this->GridLayoutGroup = $GridLayoutGroup;

        $this->Template = $this->extensionTemplate( __DIR__.'/GridLayout.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridLayout', $this->GridLayoutGroup );
        return $this->Template->getContent();
    }

}
