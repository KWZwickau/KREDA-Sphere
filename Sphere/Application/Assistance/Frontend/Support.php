<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Element\InputTextArea;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;

/**
 * Class Support
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Support
 */
class Support extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @param $TicketSubject
     * @param $TicketMessage
     *
     * @return Stage
     */
    public static function stageTicket( $TicketSubject, $TicketMessage )
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setDescription( 'Ticket erstellen' );
        $View->setMessage( '' );
        $View->setContent( Assistance::serviceYoutrack()->executeCreateTicket(
            new FormDefault( array(
                new GridFormGroup(
                    new GridFormRow(
                        new GridFormCol(
                            new InputText(
                                'TicketSubject', 'Thema', 'Thema'
                            )
                        )
                    ), new GridFormTitle( 'Problembeschreibung' )
                ),
                new GridFormGroup( array(
                        new GridFormRow(
                            new GridFormCol(
                                new InputTextArea(
                                    'TicketMessage', 'Mitteilung', 'Mitteilung'
                                )
                            )
                        ),
                        new GridFormRow(
                            new GridFormCol( array(
                                new Warning(
                                    'Bitte teilen Sie uns so genau wie möglich mit wie es zu diesem Problem kam'
                                ),
                                new Danger(
                                    'Sollte Ihr Problem bereits gemeldet worden sein, eröffnen Sie bitte kein neues Ticket'
                                )
                            ) )
                        )
                    )
                )
            ), new SubmitPrimary( 'Ticket eröffnen' )
            ), $TicketSubject, $TicketMessage
        ) );
        return $View;
    }

}
