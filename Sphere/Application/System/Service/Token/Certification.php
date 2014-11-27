<?php
namespace KREDA\Sphere\Application\System\Service\Token;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Certification
 *
 * @package KREDA\Sphere\Application\System\Service\YubiKey
 */
class Certification extends Element implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Certification.twig' );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @param array $TokenList
     */
    public function setTokenList( $TokenList )
    {

        krsort( $TokenList );
        $this->Template->setVariable( 'TokenList', $TokenList );
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
            '<span class="help-block text-left">Der von Ihnen angegebene YubiKey ist nicht gültig. <br/>Bitte verwenden Sie einen YubiKey um dieses Feld zu befüllen</span>' );
    }

    public function setErrorReplayedKey()
    {

        $this->Template->setVariable( 'CredentialKeyGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialKeyFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialKeyFeedbackMessage',
            '<span class="help-block text-left">Der von Ihnen angegebene YubiKey wurde bereits verwendet.<br/>Bitte verwenden Sie einen YubiKey um dieses Feld neu zu befüllen</span>' );
    }

    public function setErrorNetworkKey()
    {

        $this->Template->setVariable( 'CredentialKeyGroup', 'has-error has-feedback' );
        $this->Template->setVariable( 'CredentialKeyFeedbackIcon',
            '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' );
        $this->Template->setVariable( 'CredentialKeyFeedbackMessage',
            '<span class="help-block text-left">Der YubiKey konnte nicht überprüft werden.<br/>Bitte versuchen Sie es später noch einmal</span>' );
    }
}
