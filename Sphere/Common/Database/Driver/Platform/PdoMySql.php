<?php
namespace KREDA\Sphere\Common\Database\Driver\Platform;

use KREDA\Sphere\Common\Database\Driver\AbstractDriver;

/**
 * Class PdoMySql
 *
 * @package KREDA\Sphere\Common\Database
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
