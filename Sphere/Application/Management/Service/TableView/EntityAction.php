<?php
namespace KREDA\Sphere\Application\Management\Service\TableView;

use KREDA\Sphere\Application\Management\Service\TableView\Entity\TblView;
use KREDA\Sphere\Application\Management\Service\TableView\Entity\TblViewColumn;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Student
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param $TypeName
     * @param $Name
     *
     * @return TblView
     */
    protected function actionCreateView( $TypeName, $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblView' )
            ->findOneBy( array( TblView::ATTR_TYPE_NAME => $TypeName,
                                TblView::ATTR_NAME => $Name ));
        if (null === $Entity) {
            $Entity = new TblView();
            $Entity->setTypeName( $TypeName );
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param $Name
     * @param $DataType
     * @param TblView $tblView
     *
     * @return TblViewColumn|null|object
     */
    protected function actionCreateViewColumn(
        $Name,
        $DataType,
        TblView $tblView
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblViewColumn' )
            ->findOneBy( array(
                TblViewColumn::ATTR_TBL_VIEW => $tblView->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblViewColumn();
            $Entity->setName( $Name );
            $Entity->setDataType( $DataType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param $Id
     *
     * @return bool|TblView
     */
    protected function entityViewById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblView', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $Id
     *
     * @return bool|TblViewColumn
     */
    protected function entityViewColumnById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblViewColumn', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param TblView $tblView
     *
     * @return TblViewColumn[]|bool
     */
    protected function entityViewColumnAllByView( TblView $tblView )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblViewColumn' )
            ->findBy( array( TblViewColumn::ATTR_TBL_VIEW => $tblView->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }
}