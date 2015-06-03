<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormAspect;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextArea;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Basic
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Basic extends AbstractFrontend
{

    /**
     * @param TblPerson $tblPerson
     *
     * @return Layout
     */
    public static function layoutBasic( TblPerson $tblPerson )
    {

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn(
                        new LayoutPanel( 'Name', $tblPerson->getTblPersonSalutation()->getName()
                            .' '.$tblPerson->getTitle()
                            .' '.$tblPerson->getFullName()
                            , LayoutPanel::PANEL_TYPE_DANGER )
                        , 6 ),
                    new LayoutColumn(
                        new LayoutPanel( 'Art der Person', $tblPerson->getTblPersonType()->getName()
                            , LayoutPanel::PANEL_TYPE_WARNING )
                        , 6 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn(
                        new LayoutPanel( 'Geburtstag / Ort', $tblPerson->getBirthday().' '.$tblPerson->getBirthplace() )
                        , 3 ),
                    new LayoutColumn(
                        new LayoutPanel( 'Geschlecht', $tblPerson->getTblPersonGender()->getName() )
                        , 3 ),
                    new LayoutColumn(
                        new LayoutPanel( 'Staatsangehörigkeit', $tblPerson->getNationality() )
                        , 3 ),
                    new LayoutColumn(
                        new LayoutPanel( 'Konfession', $tblPerson->getDenomination() )
                        , 3 ),
                ) ),
                new LayoutRow( array(
                    new LayoutColumn(
                        new LayoutPanel( 'Bemerkungen',
                            ( $tblPerson->getRemark()
                                ? nl2br( $tblPerson->getRemark() )
                                : new Muted( '<small>Keine Bemerkungen</small>' )
                            )
                            , LayoutPanel::PANEL_TYPE_DEFAULT )
                    ),
                ) ),
            ), new LayoutTitle( 'Grunddaten' ) )
        );
    }

    /**
     * @param null|int   $Id
     * @param null|array $PersonName
     * @param null|array $PersonInformation
     * @param null|array $BirthDetail
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $PersonName, $PersonInformation, $BirthDetail )
    {

        $View = new Stage();
        $View->setTitle( 'Person' );
        $View->setDescription( 'Bearbeiten' );

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $FormBasic = Basic::formBasic( $tblPerson );
        $FormBasic->appendFormButton( new SubmitPrimary( 'Änderungen speichern' ) );

        $View->setContent(

            new Success(
                $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName()
            )
            .new Primary( 'Zurück zur Person', '/Sphere/Management/Person/Edit', null,
                array( 'Id' => $tblPerson->getId() ) )

            .Management::servicePerson()->executeChangePerson(
            $FormBasic, $tblPerson, $PersonName, $PersonInformation, $BirthDetail )
        );
        return $View;
    }

    /**
     * @param null|TblPerson $tblPerson
     *
     * @return Form
     */
    public static function formBasic( TblPerson $tblPerson = null )
    {

        if (null !== $tblPerson) {
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
        }

        $tblPersonSalutationAll = Management::servicePerson()->entityPersonSalutationAll();
        $tblPersonGenderAll = Management::servicePerson()->entityPersonGenderAll();
        $tblPersonTypeAll = Management::servicePerson()->entityPersonTypeAll();
        $tbPerson = Management::servicePerson()->entityPersonAll();

        return new Form( array(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'PersonInformation[Type]', 'Art der Person',
                            array( 'Name' => $tblPersonTypeAll ), new GroupIcon()
                        ), 4 )
                ) ),
            ), new FormTitle( 'Grunddaten' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'PersonName[Salutation]', 'Anrede',
                            array( 'Name' => $tblPersonSalutationAll ), new ConversationIcon()
                        ), 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Title]', 'Titel', 'Titel', new ConversationIcon()
                        ), 4 )
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'PersonName[First]', 'Vorname', 'Vorname', new NameplateIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Middle]', 'Zweitname', 'Zweitname', new NameplateIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'PersonName[Last]', 'Nachname', 'Nachname', new NameplateIcon() )
                        , 4 )
                ) ),
            ), new FormAspect( 'Name' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'BirthDetail[Gender]', 'Geschlecht',
                            array( 'Name' => $tblPersonGenderAll ), new ChildIcon()
                        ), 2 ),
                    new FormColumn(
                        new DatePicker( 'BirthDetail[Date]', 'Geburtstag', 'Geburtstag', new TimeIcon() )
                        , 2 ),
                    new FormColumn(
                        new AutoCompleter( 'BirthDetail[Place]', 'Geburtsort', 'Geburtsort',
                            array( 'BirthPlace' => $tbPerson ), new MapMarkerIcon()
                        ), 4 ),
                    new FormColumn(
                        new AutoCompleter( 'PersonInformation[Nationality]', 'Staatsangehörigkeit',
                            'Staatsangehörigkeit', array( 'Nationality' => $tbPerson ), new PersonIcon()
                        ), 4 ),
                ) ),
            ), new FormAspect( 'Geburtsdaten' ) ),
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new AutoCompleter( 'PersonInformation[Denomination]', 'Konfession',
                            'Konfession', array( 'Denomination' => $tbPerson ), new PersonIcon()
                        ), 4 ),
                    new FormColumn(
                        new TextArea( 'PersonInformation[Remark]', 'Bemerkungen',
                            'Bemerkungen', new PencilIcon()
                        ), 8 ),
                ) ),
            ), new FormAspect( 'Informationen' ) )
        ) );
    }
}
