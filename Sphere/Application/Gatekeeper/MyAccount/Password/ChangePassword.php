<?php
namespace KREDA\Sphere\Application\Gatekeeper\MyAccount\Password;

use KREDA\Sphere\Application\Gatekeeper\Authentication\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class ChangePassword
 *
 * @package KREDA\Sphere\Application\Gatekeeper\MyAccount\Password
 */
class ChangePassword extends Error implements IElementInterface
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/ChangePassword.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        if (isset( $_REQUEST['CredentialLock'] )) {
            $this->Template->setVariable( 'CredentialLockValue', $_REQUEST['CredentialLock'] );
        }
        if (isset( $_REQUEST['CredentialLockSafety'] )) {
            $this->Template->setVariable( 'CredentialLockSafetyValue', $_REQUEST['CredentialLockSafety'] );
        }
    }

}
