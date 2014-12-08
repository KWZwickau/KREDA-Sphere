<?php
namespace KREDA\Sphere\Application\System\Database\Driver;

use KREDA\Sphere\Application\System\Database\AbstractDriver;

/**
 * Class PdoMySql
 *
 * @package KREDA\Sphere\Application\System\Database\Driver
 */
class PdoSQLite extends AbstractDriver
{

    /**
     *
     */
    function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setIdentifier( 'pdo_sqlite' );
    }

}
