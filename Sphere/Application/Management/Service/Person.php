<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\EntityAction;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Person extends EntityAction
{

    /**
     *
     */
    function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setDatabaseHandler( 'Management', 'Person', 'Annaberg' );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPerson
     */
    public function entityPersonById( $Id )
    {

        return parent::entityPersonById( $Id );
    }

}
