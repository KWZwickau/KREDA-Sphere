<?php
namespace KREDA\Sphere\Client\Frontend\Layout;

use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractType
 *
 * @package KREDA\Sphere\Client\Frontend\Layout
 */
abstract class AbstractType extends AbstractFrontend
{

    /** @var LayoutGroup[] $LayoutGroup */
    protected $LayoutGroup = array();
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
     * @param LayoutGroup $Group
     */
    public function appendGroup( LayoutGroup $Group )
    {

        array_push( $this->LayoutGroup, $Group );
    }

    /**
     * @param LayoutGroup $Group
     */
    public function prependGroup( LayoutGroup $Group )
    {

        array_unshift( $this->LayoutGroup, $Group );
    }
}
