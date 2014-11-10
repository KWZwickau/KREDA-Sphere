<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\ComponentException;

class BadOTPException extends ComponentException
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
