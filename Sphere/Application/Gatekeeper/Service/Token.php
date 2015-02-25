<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\EntityAction;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\ComponentException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\BadOTPException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\ReplayedOTPException;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Frontend\Redirect;

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
        Gatekeeper::serviceAccount()->executeChangeToken( $tblToken,
            Gatekeeper::serviceAccount()->entityAccountByUsername( 'Administrator' )
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
     * @param TblConsumer $tblConsumer
     *
     * @return bool|Token\Entity\TblToken[]
     */
    public function entityTokenAllByConsumer( TblConsumer $tblConsumer )
    {

        return parent::entityTokenAllByConsumer( $tblConsumer );
    }

    /**
     * @return Table
     */
    public function getTableToken()
    {

        return parent::getTableToken();
    }

    /**
     * @param TblToken $tblToken
     */
    public function executeDestroyToken( TblToken $tblToken )
    {

        $this->actionDestroyToken( $tblToken );
    }

    /**
     * @param AbstractForm $View
     * @param string       $CredentialKey
     * @param TblConsumer  $tblConsumer
     *
     * @return bool|TblToken
     */
    public function executeCreateToken( AbstractForm $View, $CredentialKey, TblConsumer $tblConsumer = null )
    {

        try {
            if (null !== $CredentialKey && !empty( $CredentialKey )) {
                $this->checkIsValidToken( $CredentialKey );
                if (false === $this->entityTokenByIdentifier( substr( $CredentialKey, 0, 12 ) )) {
                    if (parent::actionCreateToken( substr( $CredentialKey, 0, 12 ), $tblConsumer )) {
                        $View->setSuccess( 'CredentialKey',
                            'Der YubiKey wurde hinzugefügt'.new Redirect( '/Sphere/Management/Token', 5 )
                        );
                    }
                } else {
                    $View->setError( 'CredentialKey', 'Der von Ihnen angegebene YubiKey wurde bereits registriert' );
                }
            } elseif (null !== $CredentialKey && empty( $CredentialKey )) {
                $View->setError( 'CredentialKey', 'Bitte verwenden Sie Ihren YubiKey um dieses Feld zu befüllen' );
            }
            return $View;
        } catch( BadOTPException $E ) {
            $View->setError( 'CredentialKey',
                'Der von Ihnen angegebene YubiKey ist nicht gültig<br/>Bitte verwenden Sie einen YubiKey um dieses Feld zu befüllen'
            );
            return $View;
        } catch( ReplayedOTPException $E ) {
            $View->setError( 'CredentialKey',
                'Der von Ihnen angegebene YubiKey wurde bereits verwendet<br/>Bitte verwenden Sie einen YubiKey um dieses Feld neu zu befüllen'
            );
            return $View;
        } catch( ComponentException $E ) {
            $View->setError( 'CredentialKey',
                'Der YubiKey konnte nicht überprüft werden<br/>Bitte versuchen Sie es später noch einmal'
            );
            return $View;
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
