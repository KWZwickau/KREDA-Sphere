<?php
namespace KREDA\Sphere\Common\Frontend\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractElement
 *
 * @package KREDA\Sphere\Common\Frontend\Form
 */
abstract class AbstractElement extends AbstractFrontend implements IElementInterface
{

    /** @var string $Name */
    protected $Name;
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
    }

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

        $this->Template->setVariable( 'ElementGroup', 'has-error has-feedback' );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementFeedbackIcon',
                '<span class="'.$Icon->getValue().' form-control-feedback"></span>' );
        }
        $this->Template->setVariable( 'ElementFeedbackMessage',
            '<span class="help-block text-left">'.$Message.'</span>' );
    }

    /**
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setSuccess( $Message, AbstractIcon $Icon = null )
    {

        $this->Template->setVariable( 'ElementGroup', 'has-success has-feedback' );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementFeedbackIcon',
                '<span class="'.$Icon->getValue().' form-control-feedback"></span>' );
        }
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
