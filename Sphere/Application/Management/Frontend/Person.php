<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitSuccess;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
        $View->setContent( new TableData( array(
            array(
                'Personen' => new GroupIcon().'&nbsp;&nbsp;Alle',
                'Anzahl'   => count( Management::servicePerson()->entityPersonAll() )
            )
        ), null, array(), false ) );
        return $View;
    }

    /**
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     * @param null|array $Button
     *
     * @return Stage
     */
    public static function stageCreate( $PersonName, $PersonInformation, $BirthDetail, $Button )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Hinzufügen' );

        $tblAddressCityAll = Management::serviceAddress()->entityAddressCityAll();

        $View->setContent( Management::servicePerson()->executeCreatePerson(
            new FormDefault(
                new GridFormGroup( array(
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputSelect( 'PersonName[Salutation]', 'Anrede', array(
                                1 => 'Herr',
                                2 => 'Frau'
                            ), new ConversationIcon() )
                            , 4 )
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputText( 'PersonName[First]', 'Vorname', 'Vorname', new NameplateIcon() )
                            , 4 ),
                        new GridFormCol(
                            new InputText( 'PersonName[Middle]', 'Zweitname', 'Zweitname', new NameplateIcon() )
                            , 4 ),
                        new GridFormCol(
                            new InputText( 'PersonName[Last]', 'Nachname', 'Nachname', new NameplateIcon() )
                            , 4 )
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputSelect( 'BirthDetail[Gender]', 'Geschlecht', array(
                                1 => 'Männlich',
                                2 => 'Weiblich'
                            ), new ChildIcon()
                            ), 4 ),
                        new GridFormCol(
                            new InputDate( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                            , 4 ),
                        new GridFormCol(
                            new InputCompleter( 'BirthDetail[City]', 'Geburtsort', 'Geburtsort', array(
                                'Name' => $tblAddressCityAll
                            ), new MapMarkerIcon()
                            ), 4 ),
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputCompleter( 'PersonInformation[Nationality]', 'Staatsangehörigkeit',
                                'Staatsangehörigkeit',
                                array(
                                    'Deutsch'
                                ), new PersonIcon()
                            ), 4 ),
                        new GridFormCol(
                            new InputSelect( 'PersonInformation[Type]', 'Art der Person', array(
                                1 => 'Interresent',
                                2 => 'Schüler',
                                3 => 'Personal',
                                4 => 'Sorgeberechtigter',
                                5 => 'Spender'
                            ), new GroupIcon()
                            ), 4 )
                    ) )
                ), new GridFormTitle( 'Grunddaten' ) ), array(
                    new ButtonSubmitSuccess( 'Anlegen' )
                ,
                    new ButtonSubmitPrimary( 'Anlegen & Weiter' )
                )
            ), $PersonName, $BirthDetail, $PersonInformation, $Button )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageListInterest()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Interessenten' );
        $tblPersonList = Management::servicePerson()->entityPersonAll();
        if (empty( $tblPersonList )) {
            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
        } else {
            $View->setContent( new TableData( $tblPersonList ) );
        }
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageListStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $tblPersonList = Management::servicePerson()->entityPersonAll();
        if (empty( $tblPersonList )) {
            $View->setContent( new MessageWarning( 'Keine Daten verfügbar' ) );
        } else {
            $View->setContent( new TableData( $tblPersonList ) );
        }
        return $View;
    }

    /**
     * @param integer $Id
     *
     * @return Stage
     */
    public static function stageEditStudent( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $View->setMessage( 'Deteildaten' );
        if (empty( $Id )) {
            $View->setContent( new MessageWarning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblPerson = Management::servicePerson()->entityPersonById( $Id );
            if (empty( $tblPerson )) {
                $View->setContent( new MessageWarning( 'Die Person konnten nicht abgerufen werden' ) );
            } else {

            }
        }
        return $View;
    }
}
