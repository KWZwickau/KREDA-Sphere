<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception;

use Exception;

/**
 * Class ComponentException
 *
 * @package KREDA\Sphere\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception
 */
class ComponentException extends \Exception
{

    /**
     * @param string    $Message
     * @param int       $Code
     * @param Exception $Previous
     */
    public function __construct( $Message = "", $Code = 0, Exception $Previous = null )
    {

        parent::__construct( $Message, $Code, $Previous );
    }

}
