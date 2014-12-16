<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\ViewToken;
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

        $this->setDatabaseHandler( 'Gatekeeper', 'Token' );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateToken( 'ccccccdilkui' );
    }

    /**
     * @param string $Value
     *
     * @return bool
     * @throws \Exception
     */
    public function apiValidateToken( $Value )
    {

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

        return parent::entityTokenById( $Id );
    }

    /**
     * @return bool|ViewToken[]
     */
    public function entityViewToken()
    {

        return parent::entityViewToken();
    }

    /**
     * @return Table
     */
    public function schemaTableToken()
    {

        return parent::getTableToken();
    }

    /**
     * @param $OTP
     *
     * @return TblToken
     */
    public function registerYubiKey( $OTP )
    {

        return parent::actionCreateToken( substr( $OTP, 0, 12 ) );
    }
}
