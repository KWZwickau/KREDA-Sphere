<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Person extends AbstractFrontend
{

    /**
     * @param array $PersonName
     * @param array $PersonInformation
     * @param array $BirthDetail
     *
     * @return Stage
     */
    public static function stageCreate( $PersonName, $PersonInformation, $BirthDetail )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Hinzufügen' );
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
                            ), new PersonIcon()
                            ), 4 ),
                        new GridFormCol(
                            new InputDate( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                            , 4 ),
                        new GridFormCol(
                            new InputCompleter( 'BirthDetail[City]', 'Geburtsort', 'Geburtsort', array(
                                'Alabama',
                                'Alaska',
                                'Arizona'
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
                            new InputCompleter( 'PersonInformation[State]', 'Bundesland', 'Bundesland', array(
                                'Sachsen'
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
                    new ButtonSubmitPrimary( 'Anlegen' )
                )
            ), $PersonName, $BirthDetail, $PersonInformation )
        );
        return $View;
    }
}
