<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblContact;
use KREDA\Sphere\Application\Management\Service\Contact\Entity\TblMail;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Contact
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Contact extends AbstractFrontend
{

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $Email, $Phone )
    {

        $View = new Stage( 'Kontaktdaten', 'Bearbeiten' );

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $Form = self::formContact();
        $Form->appendFormButton( new SubmitPrimary( 'Kontaktdaten hinzufügen' ) );

        $View->setContent(
            new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() )
            .new Primary( 'Zurück zur Person', '/Sphere/Management/Person/Edit', null, array( 'Id' => $Id ) )
            .self::layoutContact( $tblPerson, true )
            .new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            $Form
                        )
                    ), new LayoutTitle( 'Kontaktdaten hinzufügen' )
                )
            )
        );

        return $View;
    }

    /**
     * @return Form
     */
    public static function formContact()
    {

        return new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'Email[Address]', 'E-Mail Adresse', 'E-Mail Adresse' )
                        , 2 ),
                ) ),
            ) )
        );
    }

    /**
     * @param TblPerson $tblPerson
     * @param bool      $hasRemove
     *
     * @return Layout
     */
    public static function layoutContact( TblPerson $tblPerson, $hasRemove = false )
    {
        //ToDo entityContactAllByPerson ?
//        $tblContactList = Management::serviceContact()->entityContactAllByPerson( $tblPerson );
        $tblMailList = Management::serviceContact()->entityMailAllByPerson( $tblPerson );

        if (!empty( $tblMailList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblMailList, function ( TblMail &$tblMail, $Index, $Data ) {

                /** @var bool[]|TblPerson[] $Data */
                $tblMail = new LayoutColumn(
                    new LayoutPanel(
                        new ConversationIcon().' '.$tblMail->getTblContact()->getName(), array( 'E-Mail Addresse', $tblMail->getAddress() ),
                        LayoutPanel::PANEL_TYPE_DEFAULT,
                        ( $Data[0]
                            ? new ButtonGroup( array(
                                new Danger(
                                    'Löschen', '/Sphere/Management/Person/Contact/Destroy', new RemoveIcon(),
                                    array( 'Id' => $Data[1]->getId(), 'Contact' => $tblMail->getTblContact()->getId() )
                                ),
                            ) )
                            : null
                        )
                    ), 4 );
            }, array( $hasRemove, $tblPerson ) );
        } else {
            $tblMailList = array(
                new LayoutColumn(
                    new Warning( 'Keine Kontaktdaten hinterlegt', new WarningIcon() )
                )
            );
        }

        return new Layout(
            new LayoutGroup( new LayoutRow( $tblMailList ), new LayoutTitle( 'Kontaktdaten' ) )
        );
    }

    /**
     * @param int  $Id
     * @param int  $Contact
     * @param bool $Confirm
     *
     * @return Stage
     */
    public static function stageDestroy( $Id, $Contact, $Confirm = false )
    {

        $View = new Stage();
        $View->setTitle( 'Kontaktdaten' );
        $View->setDescription( 'Löschen' );

        $tblContact = Management::serviceContact()->entityContactById( $Contact );
        if (!$Confirm) {
            $View->setContent(
                new Layout(
                    new LayoutGroup( array(
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Warning( $tblContact->getId() ),
                                new Warning( 'Wollen Sie die Kontaktdaten wirklich löschen?', new QuestionIcon() ),
                            ) )
                        ),
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Danger(
                                    'Ja', '/Sphere/Management/Person/Contact/Destroy', new OkIcon(),
                                    array( 'Id' => $Id, 'Contact' => $Contact, 'Confirm' => true )
                                ),
                                new Primary(
                                    'Nein', '/Sphere/Management/Person/Contact/Edit', new DisableIcon(),
                                    array( 'Id' => $Id )
                                )
                            ) )
                        )
                    ) )
                )
            );
        } else {
//            if (true !== ( $Wire = Management::serviceContact()->executeRemoveContact( $Id, $Contact ) )) {
//                return new Wire( $Wire );
//            }
            $View->setContent(
                new Layout( new LayoutGroup( array(
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Success( $tblContact->getId() ),
                            new Success( 'Die Kontaktdaten wurde erfolgreich gelöscht' ),
                        ) )
                    ),
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Redirect( '/Sphere/Management/Person/Contact/Edit', 1, array( 'Id' => $Id ) )
                        ) )
                    )
                ) ) ) );
        }
        return $View;
    }

}
