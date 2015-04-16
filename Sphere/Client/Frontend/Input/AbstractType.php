<?php
namespace KREDA\Sphere\Client\Frontend\Input;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\IElementInterface;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractType
 *
 * @package KREDA\Sphere\Client\Frontend\Input
 */
abstract class AbstractType extends AbstractFrontend implements IElementInterface
{

    /** @var string $Name */
    protected $Name;
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @param string $Name
     */
    public function __construct( $Name )
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
     * @param mixed $Value
     * @param bool  $Force
     */
    public function setDefaultValue( $Value, $Force = false )
    {

        if ($Force || !isset( $_POST[$this->getName()] )) {
            $_POST[$this->getName()] = $Value;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param mixed $Value
     */
    public function setPrefixValue( $Value )
    {

        $this->Template->setVariable( 'ElementPrefix', $Value );
    }
}
