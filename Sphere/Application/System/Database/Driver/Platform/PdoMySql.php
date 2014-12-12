<?php
namespace KREDA\Sphere\Application\System\Database\Driver\Platform;

use KREDA\Sphere\Application\System\Database\Driver\AbstractDriver;

/**
 * Class PdoMySql
 *
 * @package KREDA\Sphere\Application\System\Database\Driver\Platform
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
