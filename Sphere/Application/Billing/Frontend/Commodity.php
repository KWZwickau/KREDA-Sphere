<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Commodity extends AbstractFrontend
{
    public static function stageCreate( $Name, $Description )
    {

        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Hinzufügen' );

        $View->setContent(new Form( array(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new TextField( 'Commodity[Name]', 'Name', 'Name', new ConversationIcon()
                        ), 6 ),
                    new FormColumn(
                        new TextField( 'Commodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                        ), 6 )
                ) )

        )))));

        return $View;
    }
}