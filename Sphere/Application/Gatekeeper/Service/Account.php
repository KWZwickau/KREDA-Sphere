<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Account extends Schema
{

    const API_SIGN_IN_ERROR_CREDENTIAL = 21;
    const API_SIGN_IN_ERROR_TOKEN = 22;
    const API_SIGN_IN_ERROR = 23;
    const API_SIGN_IN_SUCCESS = 11;

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->connectDatabase( 'Gatekeeper-Account' );
        parent::__construct();
    }

    public function setupSystem()
    {

        $this->toolCreateAccount( 'Root', 'OvdZ2üA!Lz{AFÖFp' );
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param bool   $TokenString
     *
     * @return int
     */
    public function apiSignIn( $Username, $Password, $TokenString = false )
    {

        /**
         * Credentials
         */
        if (false === ( $Account = $this->objectAccountByCredential( $Username, $Password ) )) {
            /**
             * Invalid
             */
            return self::API_SIGN_IN_ERROR_CREDENTIAL;
        } else {
            /**
             * Typ
             */
            if (false === $TokenString) {
                /**
                 * Valid
                 */
                session_regenerate_id();
                $this->toolCreateSession( session_id(), $Account->getId() );
                return self::API_SIGN_IN_SUCCESS;
            } else {
                /**
                 * Token
                 */
                try {
                    if (Token::getApi()->apiValidateToken( $TokenString )) {
                        /**
                         * Certification
                         */
                        if (false === ( $Token = Token::getApi()->schemaTokenById( $Account->getTblToken() ) )) {
                            var_dump( $Token );
                            /**
                             * Invalid
                             */
                            return self::API_SIGN_IN_ERROR_TOKEN;
                        } else {
                            if ($Token->getIdentifier() == substr( $TokenString, 0, 12 )) {
                                /**
                                 * Valid
                                 */
                                session_regenerate_id();
                                $this->toolCreateSession( session_id(), $Account->getId() );
                                return self::API_SIGN_IN_SUCCESS;
                            } else {
                                /**
                                 * Invalid
                                 */
                                return self::API_SIGN_IN_ERROR_TOKEN;
                            }
                        }
                    } else {
                        /**
                         * Invalid
                         */
                        return self::API_SIGN_IN_ERROR_TOKEN;
                    }
                } catch( \Exception $E ) {
                    /**
                     * Invalid
                     */
                    return self::API_SIGN_IN_ERROR_TOKEN;
                }
            }
        }
    }

    /**
     *
     */
    public function apiSignOut()
    {

        $this->toolDestroySession();
        session_regenerate_id();
    }

    /**
     * @return bool
     */
    public function apiIsValidSession()
    {

        if (false === ( $Account = $this->objectAccountBySession() )) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return Table
     */
    public function schemaTableAccount()
    {

        return $this->getTableAccount();
    }
}
