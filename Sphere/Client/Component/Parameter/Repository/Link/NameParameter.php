<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Link;

use KREDA\Sphere\Client\Component\Exception\ComponentException;
use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link;

/**
 * Class NameParameter
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Link
 */
class NameParameter extends Link implements IParameterInterface
{

    /**
     * @param string $Value
     *
     * @throws ComponentException
     */
    function __construct( $Value )
    {

        $this->setValue( $Value );
    }

    /**
     * @param null|string $Value
     *
     * @throws ComponentException
     */
    protected function setValue( $Value )
    {

        if (preg_match( $this->PatternLinkName, $Value )) {
            $this->Value = $Value;
        } else {
            throw new ComponentException( $Value );
        }
    }
}
