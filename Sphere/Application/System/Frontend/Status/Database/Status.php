<?php
namespace KREDA\Sphere\Application\System\Frontend\Status\Database;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Status
 *
 * @package KREDA\Sphere\Application\System\Frontend\Status
 */
class Status extends Element implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /** @var string $Update */
    private $Update = '{ RouteUpdate }';

    /**
     * @param $Status
     *
     * @throws TemplateTypeException
     */
    function __construct( $Status )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Status.twig' );
        $this->Template->setVariable( 'Status', $Status );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
    }

    /**
     * @param string $Value
     *
     * @return Status
     */
    public function setRouteUpdate( $Value )
    {

        $this->Update = $Value;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'RouteUpdate', $this->Update );
        return $this->Template->getContent();
    }

}
