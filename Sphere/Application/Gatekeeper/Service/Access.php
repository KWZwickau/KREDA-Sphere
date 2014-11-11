<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Types\TextType;
use KREDA\Sphere\Application\Service;
use MOC\V\Component\Database\Component\Parameter\Repository\DriverParameter;

class Access extends Service
{

    public function __construct()
    {

        $this->registerDatabaseMaster( 'root', 'kuw', 'KredaAccess', DriverParameter::DRIVER_PDO_MYSQL,
            '192.168.100.204' );

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
        $Update->getTable('tblCredential')->addColumn('User1', TextType::TEXT);

        $this->writeData()->getSchemaManager()->createSchema()->getMigrateToSql(
            $Update, $this->writeData()->getConnection()->getDatabasePlatform()
        );

    }
}
