<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Link;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link;

/**
 * Class IconParameter
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Link
 */
class IconParameter extends Link implements IParameterInterface
{

    /**
     * @param Icon $Value
     */
    function __construct( Icon $Value )
    {

        $this->setValue( $Value->getValue() );
    }

}
