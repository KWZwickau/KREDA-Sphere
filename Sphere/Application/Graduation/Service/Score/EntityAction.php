<?php
namespace KREDA\Sphere\Application\Graduation\Service\Score;

use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreCondition;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreConditionGradeTypeList;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreConditionGroupList;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreGroup;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreGroupGradeTypeList;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreRule;
use KREDA\Sphere\Application\Graduation\Service\Score\Entity\TblScoreRuleConditionList;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Graduation\Service\Score
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreRule
     */
    protected function entityScoreRuleById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblScoreRule', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreGroup
     */
    protected function entityScoreGroupById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblScoreGroup', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblScoreCondition
     */
    protected function entityScoreConditionById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblScoreCondition', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     * @param string $Description
     *
     * @return TblScoreRule
     */
    protected function actionCreateScoreRule( $Name, $Description = '' )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreRule' )
            ->findOneBy( array( TblScoreRule::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblScoreRule( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string    $Name
     * @param float     $Multiplier
     * @param null|bool $Round
     *
     * @return TblScoreGroup
     */
    protected function actionCreateScoreGroup( $Name, $Multiplier = 1.0, $Round = null )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreGroup' )
            ->findOneBy( array( TblScoreGroup::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblScoreGroup( $Name );
            $Entity->setMultiplier( $Multiplier );
            $Entity->setRound( $Round );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string    $Name
     * @param int       $Priority
     * @param null|bool $Round
     *
     * @return TblScoreCondition
     */
    protected function actionCreateScoreCondition( $Name, $Priority = 1, $Round = null )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreCondition' )
            ->findOneBy( array( TblScoreCondition::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblScoreCondition( $Name );
            $Entity->setPriority( $Priority );
            $Entity->setRound( $Round );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }


    /**
     * @param TblScoreRule      $tblScoreRule
     * @param TblScoreCondition $tblScoreCondition
     *
     * @return TblScoreRuleConditionList
     */
    protected function actionAddRuleCondition(
        TblScoreRule $tblScoreRule,
        TblScoreCondition $tblScoreCondition
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreRuleConditionList' )
            ->findOneBy( array(
                TblScoreRuleConditionList::ATTR_TBL_SCORE_RULE      => $tblScoreRule->getId(),
                TblScoreRuleConditionList::ATTR_TBL_SCORE_CONDITION => $tblScoreCondition->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblScoreRuleConditionList();
            $Entity->setTblScoreRule( $tblScoreRule );
            $Entity->setTblScoreCondition( $tblScoreCondition );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblScoreCondition $tblScoreCondition
     * @param TblScoreGroup     $tblScoreGroup
     *
     * @return TblScoreConditionGroupList
     */
    protected function actionAddConditionGroup(
        TblScoreCondition $tblScoreCondition,
        TblScoreGroup $tblScoreGroup
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreConditionGroupList' )
            ->findOneBy( array(
                TblScoreConditionGroupList::ATTR_TBL_SCORE_CONDITION => $tblScoreCondition->getId(),
                TblScoreConditionGroupList::ATTR_TBL_SCORE_GROUP     => $tblScoreGroup->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblScoreConditionGroupList();
            $Entity->setTblScoreCondition( $tblScoreCondition );
            $Entity->setTblScoreGroup( $tblScoreGroup );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblScoreCondition $tblScoreCondition
     * @param TblGradeType      $tblGradeType
     *
     * @return TblScoreConditionGradeTypeList
     */
    protected function actionAddConditionGradeType(
        TblScoreCondition $tblScoreCondition,
        TblGradeType $tblGradeType
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreConditionGradeTypeList' )
            ->findOneBy( array(
                TblScoreConditionGradeTypeList::ATTR_TBL_SCORE_CONDITION      => $tblScoreCondition->getId(),
                TblScoreConditionGradeTypeList::ATTR_SERVICE_GRADUATION_GRADE => $tblGradeType->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblScoreConditionGradeTypeList();
            $Entity->setTblScoreCondition( $tblScoreCondition );
            $Entity->setServiceGraduationGrade( $tblGradeType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblScoreCondition $tblScoreCondition
     * @param TblGradeType      $tblGradeType
     *
     * @return bool
     */
    protected function actionRemoveConditionGradeType(
        TblScoreCondition $tblScoreCondition,
        TblGradeType $tblGradeType
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblScoreConditionGradeTypeList $Entity */
        $Entity = $Manager->getEntity( 'TblScoreConditionGradeTypeList' )
            ->findOneBy( array(
                TblScoreConditionGradeTypeList::ATTR_TBL_SCORE_CONDITION      => $tblScoreCondition->getId(),
                TblScoreConditionGradeTypeList::ATTR_SERVICE_GRADUATION_GRADE => $tblGradeType->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblScoreGroup $tblScoreGroup
     * @param TblGradeType  $tblGradeType
     * @param float         $Multiplier
     *
     * @return TblScoreGroupGradeTypeList
     */
    protected function actionAddGroupGradeType(
        TblScoreGroup $tblScoreGroup,
        TblGradeType $tblGradeType,
        $Multiplier = 1.0
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblScoreGroupGradeTypeList' )
            ->findOneBy( array(
                TblScoreGroupGradeTypeList::ATTR_TBL_SCORE_GROUP          => $tblScoreGroup->getId(),
                TblScoreGroupGradeTypeList::ATTR_SERVICE_GRADUATION_GRADE => $tblGradeType->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblScoreGroupGradeTypeList();
            $Entity->setTblScoreGroup( $tblScoreGroup );
            $Entity->setServiceGraduationGrade( $tblGradeType );
            $Entity->setMultiplier( $Multiplier );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblScoreGroup $tblScoreGroup
     * @param TblGradeType  $tblGradeType
     *
     * @return bool
     */
    protected function actionRemoveGroupGradeType(
        TblScoreGroup $tblScoreGroup,
        TblGradeType $tblGradeType
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblScoreGroupGradeTypeList $Entity */
        $Entity = $Manager->getEntity( 'TblScoreGroupGradeTypeList' )
            ->findOneBy( array(
                TblScoreGroupGradeTypeList::ATTR_TBL_SCORE_GROUP          => $tblScoreGroup->getId(),
                TblScoreGroupGradeTypeList::ATTR_SERVICE_GRADUATION_GRADE => $tblGradeType->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
