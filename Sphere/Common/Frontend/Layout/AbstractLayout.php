<?php
namespace KREDA\Sphere\Common\Frontend\Layout;

use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractLayout
 *
 * @package KREDA\Sphere\Common\Frontend\Layout
 */
abstract class AbstractLayout extends AbstractFrontend
{

    /** @var GridLayoutGroup[] $GridLayoutGroup */
    protected $GridLayoutGroup = array();
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @param GridLayoutGroup $GridGroup
     */
    public function appendGridGroup( GridLayoutGroup $GridGroup )
    {

        array_push( $this->GridLayoutGroup, $GridGroup );
    }

    /**
     * @param GridLayoutGroup $GridGroup
     */
    public function prependGridGroup( GridLayoutGroup $GridGroup )
    {

        array_unshift( $this->GridLayoutGroup, $GridGroup );
    }
}
