<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractElement
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form
 */
abstract class AbstractElement extends AbstractFrontend
{

    /** @var string $Name */
    protected $Name;
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setError( $Message, AbstractIcon $Icon = null )
    {

        if (null === $Icon) {
            // glyphicon glyphicon-remove
//            $Icon = new QuestionIcon();
        }
        $this->Template->setVariable( 'ElementGroup', 'has-error has-feedback' );
//        $this->Template->setVariable( 'ElementFeedbackIcon',
//            '<span class="'.$Icon->getValue().' form-control-feedback"></span>' );
        $this->Template->setVariable( 'ElementFeedbackMessage',
            '<span class="help-block text-left">'.$Message.'</span>' );
    }

    /**
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setSuccess( $Message, AbstractIcon $Icon = null )
    {

        if (null === $Icon) {
            // glyphicon glyphicon-ok
//            $Icon = new QuestionIcon();
        }
        $this->Template->setVariable( 'ElementGroup', 'has-success has-feedback' );
//        $this->Template->setVariable( 'ElementFeedbackIcon',
//            '<span class="'.$Icon->getValue().' form-control-feedback"></span>' );
        $this->Template->setVariable( 'ElementFeedbackMessage',
            '<span class="help-block text-left">'.$Message.'</span>' );
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }
}
