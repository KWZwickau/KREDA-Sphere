<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
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
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Basic
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Basic extends AbstractFrontend
{

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
