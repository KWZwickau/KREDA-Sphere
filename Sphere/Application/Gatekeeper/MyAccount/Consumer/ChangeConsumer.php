<?php
namespace KREDA\Sphere\Application\Gatekeeper\MyAccount\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Error;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class ChangeConsumer
 *
 * @package KREDA\Sphere\Application\Gatekeeper\MyAccount\Consumer
 */
class ChangeConsumer extends Error implements IElementInterface
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/ChangeConsumer.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        var_dump( Gatekeeper::serviceConsumer()->entityConsumerAll() );
    }

}
