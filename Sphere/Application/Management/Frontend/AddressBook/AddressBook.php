<?php
namespace KREDA\Sphere\Application\Management\Frontend\AddressBook;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class AddressBook
 *
 * @package KREDA\Sphere\Application\Management\AddressBook
 */
class AddressBook extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageAddressBook()
    {

        $View = new Stage();
        $View->setTitle( 'Adressen' );
        $View->setDescription( '' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        $View->setContent( '' );
        $View->addButton( '/Sphere/Management/Address/State', 'Bundesland' );
        $View->addButton( '/Sphere/Management/Address/City', 'Stadt' );
        $View->addButton( '/Sphere/Management/Address/Address', 'Addresse' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageAddressBookState()
    {

        $View = new Stage();
        $View->setTitle( 'Adressen' );
        $View->setDescription( 'Bundesland' );
        $View->setMessage( '' );
        $View->setContent( new TableData(
            Management::serviceAddress()->entityAddressState()
        ) );

        return $View;
    }
}
