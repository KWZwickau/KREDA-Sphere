<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\System\Service\Consumer\Setup;

/**
 * Class Consumer
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Consumer extends Setup
{

    /**
     *
     */
    public function __construct()
    {

        $this->connectDatabase( 'Consumer' );
    }
}
