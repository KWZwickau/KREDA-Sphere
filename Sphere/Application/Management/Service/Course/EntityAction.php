<?php
namespace KREDA\Sphere\Application\Management\Service\Course;

use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Course
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Name
     * @param string $Description
     *
     * @return TblCourse
     */
    protected function actionCreateCourse( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblCourse' )
            ->findOneBy( array( TblCourse::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblCourse( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @return bool|TblCourse[]
     */
    protected function entityCourseAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblCourse' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblCourse
     */
    protected function entityCourseByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblCourse' )
            ->findOneBy( array( TblCourse::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblCourse
     */
    protected function entityCourseById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblCourse', $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }
}
