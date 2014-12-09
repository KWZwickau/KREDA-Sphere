<?php
namespace KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class SignInManagement
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Authentication\SignIn
 */
class SignInManagement extends Error implements IElementInterface
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInManagement.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        if (isset( $_REQUEST['CredentialName'] )) {
            $this->Template->setVariable( 'CredentialNameValue', $_REQUEST['CredentialName'] );
        }
        if (isset( $_REQUEST['CredentialLock'] )) {
            $this->Template->setVariable( 'CredentialLockValue', $_REQUEST['CredentialLock'] );
        }
    }

}
