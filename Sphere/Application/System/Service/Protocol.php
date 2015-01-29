<?php
namespace KREDA\Sphere\Application\System\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Application\System\Service\Protocol\EntityAction;
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
     * @param string         $DatabaseName
     * @param AbstractEntity $Entity
     */
    public function executeCreateInsertEntry(
        $DatabaseName,
        AbstractEntity $Entity
    ) {

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        if ($tblAccount) {
            $tblPerson = $tblAccount->getServiceManagementPerson();
            $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
        } else {
            $tblPerson = null;
            $tblConsumer = null;
        }

        parent::actionCreateProtocolEntry(
            $DatabaseName,
            ( $tblAccount ? $tblAccount : null ),
            ( $tblPerson ? $tblPerson : null ),
            ( $tblConsumer ? $tblConsumer : null ),
            null,
            $Entity
        );
    }

    /**
     * @param string         $DatabaseName
     * @param AbstractEntity $From
     * @param AbstractEntity $To
     */
    public function executeCreateUpdateEntry(
        $DatabaseName,
        AbstractEntity $From,
        AbstractEntity $To
    ) {

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        if ($tblAccount) {
            $tblPerson = $tblAccount->getServiceManagementPerson();
            $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
        } else {
            $tblPerson = null;
            $tblConsumer = null;
        }

        parent::actionCreateProtocolEntry(
            $DatabaseName,
            ( $tblAccount ? $tblAccount : null ),
            ( $tblPerson ? $tblPerson : null ),
            ( $tblConsumer ? $tblConsumer : null ),
            $From,
            $To
        );
    }

    /**
     * @param string              $DatabaseName
     * @param null|AbstractEntity $Entity
     */
    public function executeCreateDeleteEntry(
        $DatabaseName,
        AbstractEntity $Entity = null
    ) {

        $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession();
        if ($tblAccount) {
            $tblPerson = $tblAccount->getServiceManagementPerson();
            $tblConsumer = $tblAccount->getServiceGatekeeperConsumer();
        } else {
            $tblPerson = null;
            $tblConsumer = null;
        }

        parent::actionCreateProtocolEntry(
            $DatabaseName,
            ( $tblAccount ? $tblAccount : null ),
            ( $tblPerson ? $tblPerson : null ),
            ( $tblConsumer ? $tblConsumer : null ),
            $Entity,
            null
        );
    }

    /**
     * @return bool|TblProtocol[]
     */
    public function entityProtocolAll()
    {

        return parent::entityProtocolAll();
    }
}
