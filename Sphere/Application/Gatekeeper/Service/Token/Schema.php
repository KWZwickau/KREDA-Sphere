<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema\ViewToken;

/**
 * Class Schema
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
 */
abstract class Schema extends Setup
{

    /**
     * @param string $Identifier
     *
     * @return bool|TblToken
     */
    protected function objectTokenByIdentifier( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblToken' )
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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->find( __NAMESPACE__.'\Schema\TblToken', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param string $Identifier
     *
     * @return TblToken
     */
    protected function actionCreateToken( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\TblToken' )
            ->findOneBy( array( TblToken::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            $Entity = new TblToken( $Identifier );
            $this->loadEntityManager()->persist( $Entity );
            $this->loadEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @return ViewToken[]|bool
     */
    protected function objectViewToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->loadEntityManager()->getRepository( __NAMESPACE__.'\Schema\ViewToken' )->findAll();
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }
}
