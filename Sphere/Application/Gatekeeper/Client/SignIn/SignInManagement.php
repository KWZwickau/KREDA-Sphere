<?php
namespace KREDA\Sphere\Application\Gatekeeper\Client\SignIn;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Template;

class SignInManagement extends Shell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInManagement.twig' );
        if (isset( $_REQUEST['CredentialName'] )) {
            $this->Template->setVariable( 'CredentialNameValue', $_REQUEST['CredentialName'] );
        }
        if (isset( $_REQUEST['CredentialLock'] )) {
            $this->Template->setVariable( 'CredentialLockValue', $_REQUEST['CredentialLock'] );
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    public function setErrorEmptyName()
    {

        $this->Template->setVariable( 'CredentialNameGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialNameFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialNameFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie einen gültigen Benutzernamen ein</span>' );
    }

    public function setErrorWrongName()
    {

        $this->Template->setVariable( 'CredentialNameGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialNameFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialNameFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie einen gültigen Benutzernamen ein</span>' );
    }

    public function setErrorEmptyLock()
    {

        $this->Template->setVariable( 'CredentialLockGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialLockFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialLockFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein gültiges Passwort ein</span>' );
    }

    public function setErrorWrongLock()
    {

        $this->Template->setVariable( 'CredentialLockGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialLockFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialLockFeedbackMessage',
            '<span class="help-block text-left">Bitte geben Sie ein gültiges Passwort ein</span>' );
    }

    public function setErrorEmptyKey()
    {

        $this->Template->setVariable( 'CredentialKeyGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialKeyFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialKeyFeedbackMessage',
            '<span class="help-block text-left">Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen</span>' );
    }

    public function setErrorWrongKey()
    {

        $this->Template->setVariable( 'CredentialKeyGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialKeyFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialKeyFeedbackMessage',
            '<span class="help-block text-left">Der von Ihnen angegebene YubiKey ist nicht gültig. <br/>Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen</span>' );
    }

    public function setErrorReplayedKey()
    {

        $this->Template->setVariable( 'CredentialKeyGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialKeyFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialKeyFeedbackMessage',
            '<span class="help-block text-left">Der von Ihnen angegebene YubiKey wurde bereits verwendet.<br/>Bitte verwenden Sie Ihren YubiKey um dieses Feld neu zu befüllen</span>' );
    }
}
