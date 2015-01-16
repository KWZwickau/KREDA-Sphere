<?php
namespace KREDA\Sphere\Application\Assistance\Frontend\Support;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\AbstractFrontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\AbstractFrontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputText;
use KREDA\Sphere\Common\AbstractFrontend\Form\Element\InputTextArea;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridFormRow;

/**
 * Class Support
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Support
 */
class Support extends AbstractFrontend
{

    /**
     * @param $TicketSubject
     * @param $TicketMessage
     *
     * @return Stage
     */
    static public function stageTicket( $TicketSubject, $TicketMessage )
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
                    ), 'Problembeschreibung'
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
                                new MessageWarning(
                                    'Bitte teilen Sie uns so genau wie möglich mit wie es zu diesem Problem kam'
                                ),
                                new MessageDanger(
                                    'Sollte Ihr Problem bereits gemeldet worden sein, eröffnen Sie bitte kein neues Ticket'
                                )
                            ) )
                        )
                    )
                )
            ), new ButtonSubmitPrimary( 'Ticket eröffnen' )
            ), $TicketSubject, $TicketMessage
        ) );
        return $View;
    }

}
