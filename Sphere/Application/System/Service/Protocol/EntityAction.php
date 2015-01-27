<?php
namespace KREDA\Sphere\Application\System\Service\Protocol;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\System\Service\Protocol
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string              $DatabaseName
     * @param null|TblAccount     $tblAccount
     * @param null|TblPerson      $tblPerson
     * @param null|TblConsumer    $tblConsumer
     * @param null|AbstractEntity $FromEntity
     * @param null|AbstractEntity $ToEntity
     *
     * @return TblProtocol
     */
    protected function actionCreateProtocolEntry(
        $DatabaseName,
        TblAccount $tblAccount = null,
        TblPerson $tblPerson = null,
        TblConsumer $tblConsumer = null,
        AbstractEntity $FromEntity = null,
        AbstractEntity $ToEntity = null
    ) {

        $Manager = $this->getEntityManager();

        $Entity = new TblProtocol();
        $Entity->setProtocolDatabase( $DatabaseName );
        $Entity->setProtocolTimestamp( time() );
        if ($tblAccount) {
            $Entity->setServiceGatekeeperAccount( $tblAccount->getId() );
            $Entity->setAccountUsername( $tblAccount->getUsername() );
        }
        if ($tblPerson) {
            $Entity->setServiceManagementPerson( $tblPerson->getId() );
            $Entity->setPersonFirstName( $tblPerson->getFirstName() );
            $Entity->setPersonLastName( $tblPerson->getLastName() );
        }
        if ($tblConsumer) {
            $Entity->setServiceGatekeeperConsumer( $tblConsumer->getId() );
            $Entity->setConsumerName( $tblConsumer->getName() );
            $Entity->setConsumerSuffix( $tblConsumer->getDatabaseSuffix() );
        }
        $Entity->setEntityFrom( ( $FromEntity ? serialize( $FromEntity ) : null ) );
        $Entity->setEntityTo( ( $ToEntity ? serialize( $ToEntity ) : null ) );

        $Manager->saveEntity( $Entity );

        return $Entity;
    }

    /**
     * @return TblProtocol[]|bool
     */
    protected function entityProtocolAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblProtocol' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
