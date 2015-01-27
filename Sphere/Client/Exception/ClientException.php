<?php
namespace KREDA\Sphere\Client\Exception;

use Exception;

/**
 * Class ClientException
 *
 * @package KREDA\Sphere\Client\Exception
 */
class ClientException extends Exception
{

    /**
     * @param string $Message
     * @param int    $Code
     * @param null   $Previous
     */
    public function __construct( $Message = "", $Code = 0, $Previous = null )
    {

        parent::__construct( $Message, $Code, $Previous );
    }
}
