<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Link;

use KREDA\Sphere\Client\Component\Exception\ComponentException;
use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink;

/**
 * Class UrlParameter
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Link
 */
class UrlParameter extends AbstractLink implements IParameterInterface
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

        if (preg_match( $this->PatternLinkRoute, $Value )) {
            $this->Value = $this->getUrlBase().$Value;
        } else {
            throw new ComponentException( $Value );
        }
    }
}
