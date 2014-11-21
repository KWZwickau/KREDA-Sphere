<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Access extends Schema
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->connectDatabase( 'Gatekeeper-Access' );
        parent::__construct();
    }

    public function setupSystem()
    {
        //$this->toolCreateAccessRight( '' );
    }

    /**
     * @return Table
     */
    public function schemaTableAccessRole()
    {

        return $this->getTableAccessRole();
    }
}
