<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Types\TextType;
use KREDA\Sphere\Application\Service;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;

class Access extends Service
{

    public function __construct()
    {

        $this->registerDatabaseMaster( 'root', 'kuw', 'KredaAccess', DriverParameter::DRIVER_MYSQLI,
            '192.168.100.204' );

        //$this->setupDataStructure();

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

        $Schema = $this->writeData()->getSchemaManager()->createSchema();

        $Update = clone $Schema;
        $Update->getTable( 'tblCredential' )->addColumn( 'User1', TextType::TEXT );

        $this->writeData()->getSchemaManager()->createSchema()->getMigrateToSql(
            $Update, $this->writeData()->getConnection()->getDatabasePlatform()
        );

    }

    protected function setupDataStructure()
    {

        $SchemaManager = $this->writeData()->getSchemaManager();

        $BaseSchema = $SchemaManager->createSchema();
        var_dump( $BaseSchema );
        $EditSchema = clone $BaseSchema;
        /**
         * YubiKey
         */
        if ($EditSchema->hasTable( 'YubiKey' )) {
            $Table = $EditSchema->getTable( 'YubiKey' );
        } else {
            $Table = $EditSchema->createTable( 'YubiKey' );
        }
        if (!$Table->hasColumn( 'Id' )) {
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        if (!$Table->hasColumn( 'KeyId' )) {
            $Table->addColumn( 'KeyId', 'string' );
        }

        $Statement = $BaseSchema->getMigrateToSql( $EditSchema,
            $this->writeData()->getConnection()->getDatabasePlatform()
        );
        if (!empty( $Statement )) {
            var_dump( $Statement );
//            $this->writeData()->prepareStatement( $Statement )->executeWrite();
        }
    }
}
