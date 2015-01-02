<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Application\System\Service\Protocol\EntityAction;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class Protocol
 *
 * @package KREDA\Sphere\Application\System\Service
 */
class Protocol extends EntityAction
{

    /**
     *
     */
    function __construct()
    {

        $this->setDatabaseHandler( 'System', 'Protocol' );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param string              $DatabaseName
     * @param null|AbstractEntity $EntityFrom
     * @param null|AbstractEntity $EntityTo
     */
    public function executeCreateEntry(
        $DatabaseName,
        AbstractEntity $EntityFrom = null,
        AbstractEntity $EntityTo = null
    ) {

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        if ($tblAccount) {
            $tblPerson = $tblAccount->getServiceManagementPerson();
            $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
        } else {
            $tblPerson = null;
            $tblConsumer = null;
        }

        System::serviceProtocol();
        parent::actionCreateProtocolEntry(
            $DatabaseName,
            ( $tblAccount ? $tblAccount : null ),
            ( $tblPerson ? $tblPerson : null ),
            ( $tblConsumer ? $tblConsumer : null ),
            $EntityFrom,
            $EntityTo
        );
    }

    /**
     * @return bool|TblProtocol[]
     */
    public function entityProtocol()
    {

        return parent::entityProtocolAll();
    }
}
