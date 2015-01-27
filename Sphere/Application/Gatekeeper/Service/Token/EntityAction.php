<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\System\System;

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
    protected function entityTokenByIdentifier( $Identifier )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblToken' )
            ->findOneBy( array( TblToken::ATTR_IDENTIFIER => $Identifier ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return TblToken[]|bool
     */
    protected function entityTokenAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblToken' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblToken
     */
    protected function entityTokenById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblToken', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Identifier
     *
     * @return TblToken
     */
    protected function actionCreateToken( $Identifier )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblToken' )
            ->findOneBy( array( TblToken::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            $Entity = new TblToken( $Identifier );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }
}
