<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
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

        if (false !== ( $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession() )) {
            $Consumer = $tblConsumer->getDatabaseSuffix();
        } else {
            $Consumer = 'Annaberg';
        }
        $this->setDatabaseHandler( 'Management', 'Person', $Consumer );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

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
