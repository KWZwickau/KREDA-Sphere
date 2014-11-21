<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\ComponentException;

/**
 * Class ReplayedOTPException
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository
 */
class ReplayedOTPException extends ComponentException
{

    /**
     * @param string             $Message
     * @param int                $Code
     * @param ComponentException $Previous
     */
    public function __construct( $Message = "", $Code = 0, ComponentException $Previous = null )
    {

        parent::__construct( $Message, $Code, $Previous );
    }

}
