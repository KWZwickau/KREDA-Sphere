<?php
namespace KREDA\Sphere\Client\Frontend\Input\Type;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Input\AbstractType;

/**
 * Class FileUpload
 *
 * @package KREDA\Sphere\Client\Frontend\Input\Type
 */
class FileUpload extends AbstractType
{

    /**
     * @param string       $Name
     * @param null|string  $Placeholder
     * @param null|string  $Label
     * @param AbstractIcon $Icon
     * @param null|array   $Option
     */
    public function __construct(
        $Name,
        $Placeholder = '',
        $Label = '',
        AbstractIcon $Icon = null,
        $Option = null
    ) {

        parent::__construct( $Name );
        $this->Template = $this->extensionTemplate( __DIR__.'/FileUpload.twig' );
        $this->Template->setVariable( 'ElementName', $Name );
        $this->Template->setVariable( 'ElementLabel', $Label );
        $this->Template->setVariable( 'ElementPlaceholder', $Placeholder );
        if (null !== $Icon) {
            $this->Template->setVariable( 'ElementIcon', $Icon );
        }
        $this->setPostValue( $this->Template, $Name, 'ElementValue' );
        if (is_array( $Option )) {
            $this->Template->setVariable( 'ElementOption', json_encode( $Option ) );
        }
    }
}
