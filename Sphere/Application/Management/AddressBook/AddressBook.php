<?php
namespace KREDA\Sphere\Application\Management\AddressBook;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

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
    public static function guiAddressBook()
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
    public static function guiAddressBookState()
    {

        $View = new Stage();
        $View->setTitle( 'Adressen' );
        $View->setDescription( 'Bundesland' );
        $View->setMessage( '' );
        $View->setContent( '' );

        var_dump( Management::serviceAddress()->entityAddressState() );

        return $View;
    }
}
