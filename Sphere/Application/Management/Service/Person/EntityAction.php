<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Action\Destroy;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
 */
abstract class EntityAction extends Destroy
{

    /**
     * @return array|bool
     */
    protected function listPersonNationality()
    {

        $Query = $this->getEntityManager()->getEntity( 'TblPerson' )
            ->createQueryBuilder( 'p' )
            ->select( 'p.Nationality' )
            ->distinct( true )
            ->getQuery()
            ->useQueryCache( true );
        $EntityList = $Query->getArrayResult();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return array|bool
     */
    protected function listPersonDenomination()
    {

        $Query = $this->getEntityManager()->getEntity( 'TblPerson' )
            ->createQueryBuilder( 'p' )
            ->select( 'p.Denomination' )
            ->distinct( true )
            ->getQuery()
            ->useQueryCache( true );
        $EntityList = $Query->getArrayResult();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return array|bool
     */
    protected function listPersonBirthplace()
    {

        $Query = $this->getEntityManager()->getEntity( 'TblPerson' )
            ->createQueryBuilder( 'p' )
            ->select( 'p.Birthplace' )
            ->distinct( true )
            ->getQuery()
            ->useQueryCache( true );
        $EntityList = $Query->getArrayResult();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @return int
     */
    protected function countPersonAll()
    {

        return (int)$this->getEntityManager()->getEntity( 'TblPerson' )->count();
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return int
     */
    protected function countPersonAllByType( TblPersonType $tblPersonType )
    {

        return (int)$this->getEntityManager()->getEntity( 'TblPerson' )->countBy( array(
            TblPerson::ATTR_TBL_PERSON_TYPE => $tblPersonType->getId()
        ) );
    }

    /**
     * @param int $tblPerson
     *
     * @return string
     */
    protected function tablePersonRelationship( $tblPerson )
    {

        $tblPersonRelationshipType = Management::servicePerson()->entityPersonRelationshipTypeAll();

        return self::extensionDataTables(
            $this->getEntityManager()->getEntity( 'TblPerson' )
        )
            ->setCallbackFunction( function ( TblPerson $Entity, $Person ) {

                /** @noinspection PhpUndefinedFieldInspection */
                $Entity->Name = $Entity->getFullName();

                /** @noinspection PhpUndefinedFieldInspection */
                $Entity->Option = ( new Form(
                    new FormGroup(
                        new FormRow( array(
                            new FormColumn(
                                new SelectBox( 'tblRelationshipType', '',
                                    array( 'Name' => $Person[1] )
                                )
                                , 7 ),
                            new FormColumn(
                                new SubmitPrimary( 'Hinzufügen', new ShareIcon() )
                                , 5 )
                        ) )
                    ), null,
                    '/Sphere/Management/Person/Relationship/Edit', array(
                        'tblPerson'       => $Person[0],
                        'tblRelationship' => $Entity->getId()
                    )
                ) )->__toString();
                return $Entity;
            }, array( $tblPerson, $tblPersonRelationshipType ) )
            ->getResult();
    }

    /**
     * @param TblPersonType $tblPersonType
     *
     * @return string
     */
    protected function tablePersonAllByType( TblPersonType $tblPersonType )
    {

        return self::extensionDataTables(
            $this->getEntityManager()->getEntity( 'TblPerson' ), array(
                'tblPersonType' => $tblPersonType->getId()
            )
        )
            ->setCallbackFunction( function ( TblPerson $V ) {

                $V->Birthday = $V->getBirthday();

                /** @noinspection PhpUndefinedFieldInspection */
                $V->Option =
                    ( new Primary( 'Bearbeiten', '/Sphere/Management/Person/Edit', new PencilIcon(),
                        array( 'Id' => $V->getId() )
                    ) )->__toString();
//                    .( new Danger( 'Löschen', '/Sphere/Management/Person/Destroy', new RemoveIcon(),
//                        array( 'Id' => $V->getId() )
//                    ) )->__toString();
                return $V;
            } )
            ->getResult();
    }


}
