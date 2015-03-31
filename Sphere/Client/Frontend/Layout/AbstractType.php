<?php
namespace KREDA\Sphere\Client\Frontend\Layout;

use KREDA\Sphere\Client\Frontend\Layout\Type\Group;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractType
 *
 * @package KREDA\Sphere\Client\Frontend\Layout
 */
abstract class AbstractType extends AbstractFrontend
{

    /** @var Group[] $Group */
    protected $Group = array();
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
     * @param Group $Group
     */
    public function appendGroup( Group $Group )
    {

        array_push( $this->Group, $Group );
    }

    /**
     * @param Group $Group
     */
    public function prependGroup( Group $Group )
    {

        array_unshift( $this->Group, $Group );
    }
}
