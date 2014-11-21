<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema\TblToken;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
 */
class Schema extends Setup
{

    /**
     * @param string $Identifier
     *
     * @return bool|TblToken
     */
    protected function objectTokenByIdentifier( $Identifier )
    {

        $Entity = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblToken' )
            ->findOneBy( array( TblToken::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblToken
     */
    protected function objectTokenById( $Id )
    {

        $Entity = $this->EntityManager->getRepository( __NAMESPACE__.'\Schema\TblToken' )
            ->find( $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }
}
