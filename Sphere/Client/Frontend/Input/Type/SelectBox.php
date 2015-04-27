<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;
use KREDA\Sphere\Common\AbstractEntity;
use MOC\V\Component\Template\Template;

/**
 * Class SelectBox
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class SelectBox extends AbstractType
{

    /**
     * @param string       $Name
     * @param null|string  $Label
     * @param array        $Data array( value => title )
     * @param AbstractIcon $Icon
     */
    public function __construct(
        $Name,
        $Label = '',
        $Data = array(),
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/SelectBox.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        // Data is Entity-List ?
        if (count( $Data ) == 1 && !is_numeric( key( $Data ) )) {
            $Attribute = key( $Data );
            $Convert = array();
            // Attribute is Twig-Template ?
            if (preg_match_all( '/\{\%\s*(.*)\s*\%\}|\{\{(?!%)\s*((?:[^\s])*)\s*(?<!%)\}\}/i',
                $Attribute,
                $Placeholder )
            ) {
                /** @var AbstractEntity $Entity */
                foreach ((array)$Data[$Attribute] as $Entity) {
                    if (is_object( $Entity )) {
                        $Template = Template::getTwigTemplateString( $Attribute );
                        foreach ((array)$Placeholder[2] as $Variable) {
                            $Chain = explode( '.', $Variable );
                            if (count( $Chain ) > 1) {
                                $Template->setVariable( $Chain[0], $Entity->{'get'.$Chain[0]}() );
                            } else {
                                if (method_exists( $Entity, 'get'.$Variable )) {
                                    $Template->setVariable( $Variable, $Entity->{'get'.$Variable}() );
                                } else {
                                    $Template->setVariable( $Variable, $Entity->{$Variable} );
                                }
                            }
                        }
                        $Convert[$Entity->getId()] = $Template->getContent();
                    }
                }
            } else {
                /** @var AbstractEntity $Entity */
                foreach ((array)$Data[$Attribute] as $Entity) {
                    if (is_object( $Entity )) {
                        if (method_exists( $Entity, 'get'.$Attribute )) {
                            $Convert[$Entity->getId()] = $Entity->{'get'.$Attribute}();
                        } else {
                            $Convert[$Entity->getId()] = $Entity->{$Attribute};
                        }
                    }
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
