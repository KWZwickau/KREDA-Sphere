<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class LevelModule
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation
 */
class LevelModule extends Navigation implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var LevelModule\Link[] $MainLinkList */
    private $MainLinkList = array();

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/LevelModule/Main.twig' );
    }

    /**
     * @param LevelModule\Link $Link
     *
     * @return LevelModule
     */
    public function addLinkToMain( LevelModule\Link $Link )
    {

        if (!in_array( $Link->getContent(), $this->MainLinkList )) {
            array_push( $this->MainLinkList, $Link->getContent() );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'PositionMain', implode( '', $this->MainLinkList ) );
        return $this->Template->getContent();
    }

}
