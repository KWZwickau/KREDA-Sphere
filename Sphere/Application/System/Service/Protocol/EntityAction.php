<?php
namespace KREDA\Sphere\Application\System\Service\Protocol;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;
use KREDA\Sphere\Common\AbstractEntity;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableDefault;

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

    /**
     * @return string
     */
    protected function tableProtocolAll()
    {

        return self::extensionDataTables(
            $this->getEntityManager()->getEntity( 'TblProtocol' )
        )
            ->setCallback( function ( TblProtocol &$V ) {

                $Editor = new TableDefault(
                    new GridTableHead( new GridTableRow(
                        new GridTableCol( '', 2 )
                    ) ),
                    new GridTableBody( array(
                        new GridTableRow( array(
                            new GridTableCol( 'Database' ),
                            new GridTableCol( $V->getProtocolDatabase() )
                        ) ),
                        new GridTableRow( array(
                            new GridTableCol( 'Consumer' ),
                            new GridTableCol( $V->getConsumerName().' '.$V->getConsumerSuffix() )
                        ) ),
                        new GridTableRow( array(
                            new GridTableCol( 'Login' ),
                            new GridTableCol( $V->getAccountUsername() )
                        ) ),
                        new GridTableRow( array(
                            new GridTableCol( 'Person' ),
                            new GridTableCol( $V->getPersonFirstName().' '.$V->getPersonLastName() )
                        ) ),
                        new GridTableRow( array(
                            new GridTableCol( 'Time' ),
                            new GridTableCol( date( 'd.m.Y H:i:s', $V->getProtocolTimestamp() ) )
                        ) )
                    ) )
                );
                $DataOrigin = self::fixObject( unserialize( $V->getEntityFrom() ) );
                $DataCommit = self::fixObject( unserialize( $V->getEntityTo() ) );

                if ($DataOrigin && $DataCommit) {
                    $Data = (array)$DataOrigin;
                    array_walk( $Data, function ( &$V, $I ) {

                        $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                    } );
                    $TableOrigin = new TableDefault(
                        new GridTableHead( new GridTableRow(
                            new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataOrigin ) ), 2 )
                        ) ), new GridTableBody( $Data )
                    );
                    $Data = (array)$DataCommit;
                    array_walk( $Data, function ( &$V, $I ) {

                        $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                    } );
                    $TableCommit = new TableDefault(
                        new GridTableHead( new GridTableRow(
                            new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataCommit ) ), 2 )
                        ) ), new GridTableBody( $Data )
                    );

                    $V = array(
                        'Id'     => $V->getId(),
                        'Editor' => $Editor->__toString(),
                        'Origin' => $TableOrigin->__toString(),
                        'Commit' => $TableCommit->__toString()
                    );
                } elseif ($DataOrigin) {
                    $Data = (array)$DataOrigin;
                    array_walk( $Data, function ( &$V, $I ) {

                        $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                    } );
                    $Table = new TableDefault(
                        new GridTableHead( new GridTableRow(
                            new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataOrigin ) ), 2 )
                        ) ), new GridTableBody( $Data )
                    );
                    $V = array(
                        'Id'     => $V->getId(),
                        'Editor' => $Editor->__toString(),
                        'Origin' => $Table->__toString(),
                        'Commit' => ''
                    );
                } elseif ($DataCommit) {
                    $Data = (array)$DataCommit;
                    array_walk( $Data, function ( &$V, $I ) {

                        $V = new GridTableRow( array( new GridTableCol( $I ), new GridTableCol( $V ) ) );
                    } );
                    $Table = new TableDefault(
                        new GridTableHead( new GridTableRow(
                            new GridTableCol( str_replace( '\\', '\\&shy;', get_class( $DataCommit ) ), 2 )
                        ) ), new GridTableBody( $Data )
                    );
                    $V = array(
                        'Id'     => $V->getId(),
                        'Editor' => $Editor->__toString(),
                        'Origin' => '',
                        'Commit' => $Table->__toString()
                    );
                }
                return $V;
            } )
            ->getResult();
    }

    /**
     * Takes an __PHP_Incomplete_Class and casts it to a stdClass object.
     * All properties will be made public in this step.
     *
     * @since  1.1.0
     *
     * @param  object $object __PHP_Incomplete_Class
     *
     * @return object
     */
    private static function fixObject( $object )
    {

        if (!is_object( $object ) && gettype( $object ) == 'object') {
            // preg_replace_callback handler. Needed to calculate new key-length.
            $fix_key = create_function(
                '$matches',
                'return ":" . strlen( $matches[1] ) . ":\"" . $matches[1] . "\"";'
            );
            // 1. Serialize the object to a string.
            $dump = serialize( $object );
            // 2. Change class-type to 'stdClass'.
            preg_match( '/^O:\d+:"[^"]++"/', $dump, $match );
            $dump = preg_replace( '/^O:\d+:"[^"]++"/', 'O:8:"stdClass"', $dump );
            // 3. Make private and protected properties public.
            $dump = preg_replace_callback( '/:\d+:"\0.*?\0([^"]+)"/', $fix_key, $dump );
            // 4. Unserialize the modified object again.
            $dump = unserialize( $dump );
            $dump->ERROR = new MessageDanger( "Structure mismatch!<br/>".$match[0]."<br/>Please delete this Item" );
            return $dump;
        } else {
            return $object;
        }
    }
}
