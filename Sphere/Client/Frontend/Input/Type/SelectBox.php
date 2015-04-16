<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;
use KREDA\Sphere\Common\AbstractEntity;

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
        if (count( $Data ) == 1 && !is_numeric( key( $Data ) )) {
            $Attribute = key( $Data );
            $Convert = array();
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
