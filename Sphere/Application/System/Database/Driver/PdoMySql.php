<?php
namespace KREDA\Sphere\Application\System\Database\Driver;

use KREDA\Sphere\Application\System\Database\AbstractDriver;

/**
 * Class PdoMySql
 *
 * @package KREDA\Sphere\Application\System\Database\Driver
 */
class PdoMySql extends AbstractDriver
{

    /**
     *
     */
    function __construct()
    {

        $this->setIdentifier( 'pdo_mysql' );
    }

}
