<?php
namespace KREDA\Sphere\Application\System\Database\Driver\Platform;

use KREDA\Sphere\Application\System\Database\Driver\AbstractDriver;

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
