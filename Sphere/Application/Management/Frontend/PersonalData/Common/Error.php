<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData\Common;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractShell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractError
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Common
 */
abstract class Error extends AbstractShell implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    protected $Template = null;

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
}
