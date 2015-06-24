<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Contact
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Contact extends AbstractFrontend
{
    /**
     * @return Form
     */
    public static function formPhone()
    {
        return new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                       new SelectBox('Phone[Contact]', 'Typ',
                           array(
                               '{{ Name }}'
                               .'{% if( Description is not empty) %} - {{ Description }}{% endif %}'
                               => Management::serviceContact()->entityContactAll()
                           )
                       ), 4),
                    new FormColumn(
                        new TextField( 'Phone[Number]', 'Telefonnummer', 'Telefonnummer', new ConversationIcon() )
                    , 4 ),
                    new FormColumn(
                        new TextField( 'Phone[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon() )
                    , 4 ),
                ) ),
            ) )
        );
    }

    /**
     * @return Form
     */
    public static function formMail()
    {
        return new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new SelectBox('Mail[Contact]', 'Typ',
                            array(
                                '{{ Name }}'
                                .'{% if( Description is not empty) %} - {{ Description }}{% endif %}'
                                => Management::serviceContact()->entityContactAll()
                            )
                        ), 4),
                    new FormColumn(
                        new TextField( 'Mail[Address]', 'Emailadresse', 'Emailadresse', new ConversationIcon() )
                        , 4 ),
                    new FormColumn(
                        new TextField( 'Mail[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon() )
                        , 4 ),
                ) ),
            ) )
        );
    }
}
