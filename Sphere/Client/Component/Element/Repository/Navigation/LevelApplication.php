<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

/**
 * Class LevelApplication
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation
 */
class LevelApplication extends Navigation implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var LevelApplication\Link[] $MainLinkList */
    private $MainLinkList = array();

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/LevelApplication/Main.twig' );
    }

    /**
     * @param LevelApplication\Link $Link
     *
     * @return LevelApplication
     */
    public function addLinkToMain( LevelApplication\Link $Link )
    {

        array_push( $this->MainLinkList, $Link->getContent() );
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
