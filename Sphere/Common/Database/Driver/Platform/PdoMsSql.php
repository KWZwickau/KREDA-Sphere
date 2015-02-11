<?php
namespace KREDA\Sphere\Common\Database\Driver\Platform;

use KREDA\Sphere\Common\Database\Driver\AbstractDriver;

/**
 * Class PdoMsSql
 *
 * @package KREDA\Sphere\Common\Database
 */
class PdoMsSql extends AbstractDriver
{

    /**
     *
     */
    function __construct()
    {

        $this->setIdentifier( 'pdo_sqlsrv' );
    }

}
