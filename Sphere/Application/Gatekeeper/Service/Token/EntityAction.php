<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\ViewToken;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Identifier
     *
     * @return bool|TblToken
     */
    protected function objectTokenByIdentifier( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Entity\TblToken' )
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

        $Entity = $this->getEntityManager()->find( __NAMESPACE__.'\Entity\TblToken', $Id );
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

        $Entity = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Entity\TblToken' )
            ->findOneBy( array( TblToken::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            $Entity = new TblToken( $Identifier );
            $this->getEntityManager()->persist( $Entity );
            $this->getEntityManager()->flush();
        }
        return $Entity;
    }

    /**
     * @return ViewToken[]|bool
     */
    protected function objectViewToken()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->getEntityManager()->getRepository( __NAMESPACE__.'\Entity\ViewToken' )->findAll();
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }
}
