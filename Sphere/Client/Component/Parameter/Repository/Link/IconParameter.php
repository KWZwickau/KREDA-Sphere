<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Link;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink;

/**
 * Class IconParameter
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Link
 */
class IconParameter extends AbstractLink implements IParameterInterface
{

    /**
     * @param AbstractIcon $Value
     */
    function __construct( AbstractIcon $Value )
    {

        $this->setValue( $Value->getValue() );
    }

}
