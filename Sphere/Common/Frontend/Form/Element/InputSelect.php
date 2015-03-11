<?php
namespace KREDA\Sphere\Common\Frontend\Form\Element;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractEntity;
use KREDA\Sphere\Common\Frontend\Form\AbstractElement;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class InputSelect
 *
 * @package KREDA\Sphere\Common\Frontend\Form\Element
 */
class InputSelect extends AbstractElement
{

    /**
     * @param string       $Name
     * @param null|string  $Label
     * @param array        $Data array( value => title )
     * @param AbstractIcon $Icon
     *
     *
     *
     * @throws TemplateTypeException
     */
    function __construct(
        $Name,
        $Label = '',
        $Data = array(),
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );

        $this->Template = $this->extensionTemplate( __DIR__.'/InputSelect.twig' );

        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );

        if (count( $Data ) == 1 && !is_numeric( key( $Data ) )) {
            $Attribute = key( $Data );
            $Convert = array();
            /** @var AbstractEntity $Entity */
            foreach ((array)$Data[$Attribute] as $Entity) {
                if (is_object( $Entity )) {
                    $Convert[$Entity->getId()] = $Entity->{'get'.$Attribute}();
                }
            }
            asort( $Convert );
            $this->Template->setVariable( 'ElementData', $Convert );
        } else {
            asort( $Data );
            $this->Template->setVariable( 'ElementData', $Data );
        }

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
