<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend;

use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractError
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend
 */
abstract class AbstractError extends AbstractFrontend implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        $this->setRequestValues( $this->Template );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
        return $this->Template->getContent();
    }

    public function setErrorEmptyName()
    {

        $this->setFormError( $this->Template, 'CredentialName',
            'Bitte geben Sie einen gültigen Benutzernamen ein'
        );
    }

    public function setErrorWrongName()
    {

        $this->setFormError( $this->Template, 'CredentialName',
            'Bitte geben Sie einen gültigen Benutzernamen ein'
        );
    }

    public function setErrorEmptyLock()
    {

        $this->setFormError( $this->Template, 'CredentialLock',
            'Bitte geben Sie ein Passwort ein'
        );
    }

    public function setErrorEmptyLockSafety()
    {

        $this->setFormError( $this->Template, 'CredentialLockSafety',
            'Bitte geben Sie das Passwort erneut ein'
        );
    }

    public function setErrorWrongLock()
    {

        $this->setFormError( $this->Template, 'CredentialLock',
            'Bitte geben Sie ein gültiges Passwort ein'
        );
    }

    public function setErrorWrongLockSafety()
    {

        $this->setFormError( $this->Template, 'CredentialLockSafety',
            'Die beiden Passwörter stimmen nicht überein'
        );
    }

    public function setErrorEmptyKey()
    {

        $this->setFormError( $this->Template, 'CredentialKey',
            'Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen'
        );
    }

    public function setErrorWrongKey()
    {

        $this->setFormError( $this->Template, 'CredentialKey',
            'Der von Ihnen angegebene YubiKey ist nicht gültig.'
            .'<br/>Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen'
        );
    }

    public function setErrorReplayedKey()
    {

        $this->setFormError( $this->Template, 'CredentialKey',
            'Der von Ihnen angegebene YubiKey wurde bereits verwendet.'
            .'<br/>Bitte verwenden Sie Ihren YubiKey um dieses Feld neu zu befüllen'
        );
    }

    public function setErrorNetworkKey()
    {

        $this->setFormError( $this->Template, 'CredentialKey',
            'Der YubiKey konnte nicht überprüft werden.'
            .'<br/>Bitte versuchen Sie es später noch einmal'
        );
    }

}
