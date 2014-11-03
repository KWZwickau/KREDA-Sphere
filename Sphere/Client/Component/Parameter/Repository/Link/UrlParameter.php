<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Link;

use KREDA\Sphere\Client\Component\Exception\ComponentException;
use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class UrlParameter
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Link
 */
class UrlParameter extends Link implements IParameterInterface
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
            $this->Value = HttpKernel::getRequest()->getUrlBase().$Value;
        } else {
            throw new ComponentException( $Value );
        }
    }
}
