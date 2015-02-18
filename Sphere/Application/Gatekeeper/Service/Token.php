<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Token
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Token extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Token' );
    }

    public function setupDatabaseContent()
    {

        /**
         * Create SystemAdmin (Token)
         */
        $tblToken = $this->actionCreateToken( 'ccccccdilkui' );
        Gatekeeper::serviceAccount()->executeChangeToken( $tblToken,
            Gatekeeper::serviceAccount()->entityAccountByUsername( 'System' )
        );
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
     * @return bool|TblToken[]
     */
    public function entityTokenAll()
    {

        return parent::entityTokenAll();
    }

    /**
     * @return Table
     */
    public function getTableToken()
    {

        return parent::getTableToken();
    }

    /**
     * @param string $CredentialKey
     *
     * @return bool|TblToken
     */
    public function executeCreateToken( $CredentialKey )
    {

        if ($this->checkIsValidToken( $CredentialKey )) {
            return parent::actionCreateToken( substr( $CredentialKey, 0, 12 ) );
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
    public function checkIsValidToken( $Value )
    {

        $YubiKey = new Token\Hardware\YubiKey\YubiKey( 19180, 'YJwU33GNiRiw1dE8/MfIMNm8w3Y=' );
        $Key = $YubiKey->parseKey( $Value );
        return $YubiKey->verifyKey( $Key );
    }

}
