<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use KREDA\Sphere\Application\Service;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;

class Access extends Service
{

    public function __construct()
    {

        $this->registerDatabaseMaster( 'root', 'kuw', 'KredaAccess', DriverParameter::DRIVER_PDO_MYSQL,
            '192.168.100.204' );

        $this->setupDataStructure();

    }

    protected function setupDataStructure()
    {

        $SchemaManager = $this->writeData()->getSchemaManager();
        $BaseSchema = $SchemaManager->createSchema();
        $EditSchema = clone $BaseSchema;

        /**
         * Table tblYubiKey
         */
        if ($this->dbHasTable( 'tblYubiKey' )) {
            // Upgrade
            $tblYubiKey = $EditSchema->getTable( 'tblYubiKey' );
            if (!$this->dbTableHasColumn( 'tblYubiKey', 'Id' )) {
                $Column = $tblYubiKey->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $tblYubiKey->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblYubiKey', 'YubiKeyId' )) {
                $tblYubiKey->addColumn( 'YubiKeyId', 'string' );
            }
        } else {
            // Install
            $tblYubiKey = $EditSchema->createTable( 'tblYubiKey' );
            $Column = $tblYubiKey->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $tblYubiKey->setPrimaryKey( array( 'Id' ) );
            $tblYubiKey->addColumn( 'YubiKeyId', 'string' );
        }
        /**
         * Table tblAccountRole
         */
        if ($this->dbHasTable( 'tblAccountRole' )) {
            // Upgrade
            $tblAccountRole = $EditSchema->getTable( 'tblAccountRole' );
            if (!$this->dbTableHasColumn( 'tblAccountRole', 'Id' )) {
                $Column = $tblAccountRole->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $tblAccountRole->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccountRole', 'Name' )) {
                $tblAccountRole->addColumn( 'Name', 'string' );
            }
        } else {
            // Install
            $tblAccountRole = $EditSchema->createTable( 'tblAccountRole' );
            $Column = $tblAccountRole->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $tblAccountRole->setPrimaryKey( array( 'Id' ) );
            $tblAccountRole->addColumn( 'Name', 'string' );
        }
        /**
         * Table tblAccount
         */
        if ($this->dbHasTable( 'tblAccount' )) {
            // Upgrade
            $tblAccount = $EditSchema->getTable( 'tblAccount' );
            if (!$this->dbTableHasColumn( 'tblAccount', 'Id' )) {
                $Column = $tblAccount->addColumn( 'Id', 'bigint' );
                $Column->setAutoincrement( true );
                $tblAccount->setPrimaryKey( array( 'Id' ) );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'Username' )) {
                $tblAccount->addColumn( 'Username', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'Password' )) {
                $tblAccount->addColumn( 'Password', 'string' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'tblYubiKey' )) {
                $tblAccount->addColumn( 'tblYubiKey', 'bigint' );
                if ($SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                    $tblAccount->addForeignKeyConstraint( $tblYubiKey, array( 'tblYubiKey' ), array( 'Id' ) );
                }
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'apiHumanResources_Person' )) {
                $tblAccount->addColumn( 'apiHumanResources_Person', 'bigint' );
            }
            if (!$this->dbTableHasColumn( 'tblAccount', 'apiCampus_Client' )) {
                $tblAccount->addColumn( 'apiCampus_Client', 'bigint' );
            }
        } else {
            // Install
            $tblAccount = $EditSchema->createTable( 'tblAccount' );
            $Column = $tblAccount->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $tblAccount->setPrimaryKey( array( 'Id' ) );
            $tblAccount->addColumn( 'Username', 'string' );
            $tblAccount->addColumn( 'Password', 'string' );
            $tblAccount->addColumn( 'tblYubiKey', 'bigint' );
            if ($SchemaManager->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $tblAccount->addForeignKeyConstraint( $tblYubiKey, array( 'tblYubiKey' ), array( 'Id' ) );
            }
            $tblAccount->addColumn( 'apiHumanResources_Person', 'bigint' );
            $tblAccount->addColumn( 'apiCampus_Client', 'bigint' );
        }
        /**
         * Migration
         */
        $Statement = $BaseSchema->getMigrateToSql( $EditSchema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        /**
         * Upgrade
         */
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                var_dump( $Query );
                $this->writeData()->prepareStatement( $Query )->executeWrite();
            }
        }

    }

    /**
     * @return bool
     */
    public function apiSessionIsValid()
    {

        if (isset( $_SESSION['Gatekeeper-Valid'] )) {
            return $_SESSION['Gatekeeper-Valid'];
        } else {
            return false;
        }
    }

    /**
     * @param string $Value
     *
     * @return bool
     * @throws \Exception
     */
    public function apiValidateYubiKey( $Value )
    {

        $YubiKey = new Access\YubiKey\YubiKey( 19180, 'YJwU33GNiRiw1dE8/MfIMNm8w3Y=' );
        return $YubiKey->verifyKey(
            $YubiKey->parseKey( $Value )
        );
    }

    public function apiValidateCredentials( $CredentialUser, $CredentialLock )
    {

    }
}
