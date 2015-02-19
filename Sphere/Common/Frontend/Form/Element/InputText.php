<?php
namespace KREDA\Sphere\Common\Frontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\Frontend\Form\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class InputText
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Element
 */
class InputText extends AbstractElement
{

    /**
     * @param string       $Name
     * @param null|string  $Placeholder
     * @param null|string  $Label
     * @param AbstractIcon $Icon
     *
     * @throws TemplateTypeException
     */
    function __construct(
        $Name,
        $Placeholder = '',
        $Label = '',
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/InputText.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }

    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->setPostValue( $this->Template, $this->getName(), 'ElementValue' );
        return parent::getContent();
    }
    
}
