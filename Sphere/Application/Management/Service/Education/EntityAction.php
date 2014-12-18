<?php
namespace KREDA\Sphere\Application\Management\Service\Education;

use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Education
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Name
     * @param string $Acronym
     *
     * @return TblSubject
     */
    protected function actionCreateSubject( $Name, $Acronym )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblSubject' )
            ->findOneBy( array( TblSubject::ATTR_ACRONYM => $Acronym ) );
        if (null === $Entity) {
            $Entity = new TblSubject( $Acronym );
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblLevel
     */
    protected function entityLevelById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblLevel', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }
}
