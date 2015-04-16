<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class AutoCompleter
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class AutoCompleter extends AbstractType
{

    /**
     * @param string       $Name
     * @param string       $Label
     * @param string       $Placeholder
     * @param array        $Data array( value, value, .. )
     * @param AbstractIcon $Icon
     */
    public function __construct(
        $Name,
        $Label = '',
        $Placeholder = '',
        $Data = array(),
        AbstractIcon $Icon = null
    ) {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/AutoCompleter.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (count( $Data ) == 1 && !is_numeric( key( $Data ) )) {
            $Attribute = key( $Data );
            $Convert = array();
            /** @var AbstractEntity $Entity */
            foreach ((array)$Data[$Attribute] as $Entity) {
                $Convert[$Entity->getId()] = $Entity->{'get'.$Attribute}();
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
        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
    }
}
