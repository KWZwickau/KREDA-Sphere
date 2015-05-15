<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
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

        $FormBasic = Person\InputForm::formBasic();
        $FormBasic->appendFormButton( new SubmitPrimary( 'Anlegen' ) );

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            $FormBasic, $PersonName, $PersonInformation, $BirthDetail )
        );
        return $View;
    }


    /**
     * @param integer $Id
     *
     * @return Stage
     */
    public static function stageDestroy( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Löschen' );
        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {
                if (true !== ( $Effect = Management::servicePerson()->executeDestroyPerson( $tblPerson ) )) {
                    $View->setContent( $Effect );
                } else {
                    $View->setContent( self::getRedirect( '/Sphere/Management/Person', 2 ) );
                }
            }
        }
        return $View;
    }

    /**
     * @param integer    $Id
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @param null|int|array $State
     * @param null|array     $City
     * @param null|array     $Street
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $PersonName, $PersonInformation, $BirthDetail, $State, $City, $Street )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Bearbeiten' );
        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new Warning( 'Die Person konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['PersonName']['Salutation'] = $tblPerson->getTblPersonSalutation()->getId();
                $Global->POST['PersonName']['Title'] = $tblPerson->getTitle();
                $Global->POST['PersonName']['First'] = $tblPerson->getFirstName();
                $Global->POST['PersonName']['Middle'] = $tblPerson->getMiddleName();
                $Global->POST['PersonName']['Last'] = $tblPerson->getLastName();
                $Global->POST['BirthDetail']['Gender'] = $tblPerson->getTblPersonGender()->getId();
                $Global->POST['BirthDetail']['Date'] = $tblPerson->getBirthday();
                $Global->POST['BirthDetail']['Place'] = $tblPerson->getBirthplace();
                $Global->POST['PersonInformation']['Nationality'] = $tblPerson->getNationality();
                $Global->POST['PersonInformation']['Type'] = $tblPerson->getTblPersonType()->getId();
                $Global->POST['PersonInformation']['Remark'] = $tblPerson->getRemark();
                $Global->POST['PersonInformation']['Denomination'] = $tblPerson->getDenomination();
                $Global->savePost();

                $FormBasic = Person\InputForm::formBasic();
                $FormBasic->appendFormButton( new SubmitPrimary( 'Änderungen speichern' ) );

                /**
                 * Additional
                 */
                switch ($tblPerson->getTblPersonType()->getId()) {
                    case Management::servicePerson()->entityPersonTypeByName( 'Interessent' )->getId():
                        $FormStudent = '';
                        $FormRelationship = '';
                        $FormAddress = Person\InputForm::formAddress( $tblPerson, $State, $City, $Street );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Schüler' )->getId():
                        $FormStudent = Person\InputForm::formStudent( $tblPerson );
                        $FormRelationship = Person\InputForm::formRelationship( $tblPerson );
                        $FormAddress = Person\InputForm::formAddress( $tblPerson, $State, $City, $Street );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Sorgeberechtigter' )->getId():
                        $FormStudent = '';
                        $FormRelationship = Person\InputForm::formRelationship( $tblPerson );
                        $FormAddress = Person\InputForm::formAddress( $tblPerson, $State, $City, $Street );
                        break;
                    case Management::servicePerson()->entityPersonTypeByName( 'Lehrer' )->getId():
                        $FormStudent = '';
                        $FormRelationship = Person\InputForm::formRelationship( $tblPerson );
                        $FormAddress = Person\InputForm::formAddress( $tblPerson, $State, $City, $Street );
                        break;
                    default:
                        $FormStudent = '';
                        $FormRelationship = '';
                        $FormAddress = '';
                }

                $View->setContent(
                    new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() )
                    .Management::servicePerson()->executeChangePerson(
                        $FormBasic, $tblPerson, $PersonName, $PersonInformation, $BirthDetail
                    )
                    .$FormStudent
                    .$FormAddress
                    .$FormRelationship
                );
            }
        }
        return $View;
    }

}
