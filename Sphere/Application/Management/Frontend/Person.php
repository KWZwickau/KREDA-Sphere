<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Frontend\Person\Address;
use KREDA\Sphere\Application\Management\Frontend\Person\Basic;
use KREDA\Sphere\Application\Management\Frontend\Person\Contact;
use KREDA\Sphere\Application\Management\Frontend\Person\Relationship;
use KREDA\Sphere\Application\Management\Frontend\Person\Student;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Person extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die Anzahl an Personen in den jeweiligen Personengruppen' );

        $tblPersonTypeAll = Management::servicePerson()->entityPersonTypeAll();
        $DataList = array(
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Alle',
                'Anzahl'   => Management::servicePerson()->countPersonAll()
            )
        );
        /** @var TblPersonType $tblPersonType */
        foreach ((array)$tblPersonTypeAll as $tblPersonType) {
            array_push( $DataList,
                array(
                    'Personen' => new GroupIcon().'&nbsp;&nbsp;'.$tblPersonType->getName(),
                    'Anzahl'   => Management::servicePerson()->countPersonAllByType( $tblPersonType )
                )
            );
        }
        $View->setContent( new TableData( $DataList, null, array(), false ) );

        return $View;
    }

    /**
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @return Stage
     */
    public static function stageCreate( $PersonName, $PersonInformation, $BirthDetail )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Hinzufügen' );

        $FormBasic = Basic::formBasic();
        $FormBasic->appendFormButton( new SubmitPrimary( 'Anlegen' ) );

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            $FormBasic, $PersonName, $PersonInformation, $BirthDetail )
        );
        return $View;
    }


//    /**
//     * @param integer $Id
//     *
//     * @return Stage
//     */
//    public static function stageDestroy( $Id )
//    {
//
//        $View = new Stage();
//        $View->setTitle( 'Person' );
//        $View->setDescription( 'Löschen' );
//        if (empty( $Id )) {
//            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
//        } else {
//            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
//            if (empty( $tblPerson )) {
//                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
//            } else {
//                if (true !== ( $Effect = Management::servicePerson()->executeDestroyPerson( $tblPerson ) )) {
//                    $View->setContent( $Effect );
//                } else {
//                    $View->setContent( self::getRedirect( '/Sphere/Management/Person', 2 ) );
//                }
//            }
//        }
//        return $View;
//    }

    /**
     * @param null|int $Id
     *
     * @return Stage
     */
    public static function stageEdit( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Datenblatt' );
        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {

                $LayoutStudent = '';
                $LayoutRelationship = '';

                /**
                 * Additional
                 */
                switch ($tblPerson->getTblPersonType()->getId()) {
                    case Management::servicePerson()->entityPersonTypeByName( 'Interessent' )->getId():
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId():
                        $LayoutStudent = Student::layoutStudent( $tblPerson );
                        $LayoutRelationship = Relationship::layoutRelationship( $tblPerson )
                            .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship/Edit', new PencilIcon(),
                                array( 'Id' => $tblPerson->getId() )
                            );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )->getId():
                        $LayoutRelationship = Relationship::layoutRelationship( $tblPerson )
                            .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship/Edit', new PencilIcon(),
                                array( 'Id' => $tblPerson->getId() )
                            );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Lehrer' )->getId():
                        $LayoutRelationship = Relationship::layoutRelationship( $tblPerson )
                            .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Relationship/Edit', new PencilIcon(),
                                array( 'Id' => $tblPerson->getId() )
                            );
                        break;
                    default:
                        $LayoutStudent = '';
                        $LayoutRelationship = '';
                }

                $View->setContent(
                    Basic::layoutBasic( $tblPerson )
                    .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Basic/Edit', new PencilIcon(),
                        array( 'Id' => $tblPerson->getId() )
                    )
                    .$LayoutStudent
                    .Address::layoutAddress( $tblPerson )
                    .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Address/Edit', new PencilIcon(),
                        array( 'Id' => $tblPerson->getId() )
                    )
                    .Contact::layoutContact( $tblPerson )
                    .new Primary( 'Bearbeiten', '/Sphere/Management/Person/Contact/Edit', new PencilIcon(),
                        array( 'Id' => $tblPerson->getId() )
                    )
                    .$LayoutRelationship
                );
            }
        }
        return $View;
    }

}
