<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema;

/**
 * Class Token
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Token extends Schema
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->connectDatabase( 'Gatekeeper-Token' );
        parent::__construct();
    }

    public function setupSystem()
    {

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
     * @return bool|Schema\TblToken
     */
    public function schemaTokenById( $Id )
    {

        return $this->objectTokenById( $Id );
    }

    /**
     * @return Table
     */
    public function schemaTableToken()
    {

        return $this->getTableToken();
    }
}
