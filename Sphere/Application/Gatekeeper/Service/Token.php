<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\EntityAction;

/**
 * Class Token
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Token extends EntityAction
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->setDatabaseHandler( 'Gatekeeper', 'Token' );
    }

    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $tblToken = $this->actionCreateToken( 'ccccccdilkui' );
        Gatekeeper::serviceAccount()->actionSetAccountToken( Gatekeeper::serviceAccount()->entityAccountByUsername( 'Root' ),
            $tblToken );
    }

    /**
     * @param string $Value
     *
     * @return bool
     * @throws \Exception
     */
    public function apiValidateToken( $Value )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $YubiKey = new Token\Hardware\YubiKey\YubiKey( 19180, 'YJwU33GNiRiw1dE8/MfIMNm8w3Y=' );
        $Key = $YubiKey->parseKey( $Value );
        return $YubiKey->verifyKey( $Key );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblToken
     */
    public function entityTokenById( $Id )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return parent::entityTokenById( $Id );
    }

    /**
     * @return mixed
     */
    public function entityViewToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return parent::entityViewToken();
    }

    /**
     * @return Table
     */
    public function schemaTableToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return parent::getTableToken();
    }

    /**
     * @param $OTP
     *
     * @return TblToken
     */
    public function registerYubiKey( $OTP )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return parent::actionCreateToken( substr( $OTP, 0, 12 ) );
    }
}
