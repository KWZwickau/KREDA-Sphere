<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Account\EntityAction;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Error;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKey;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountType;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Account extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Account', $this->getConsumerSuffix() );
    }

    /**
     * @param AbstractType $View
     * @param $Account
     * @return AbstractType|string
     */
    public function executeAddAccount(
        AbstractType &$View = null,
        $Account )
    {
        if ( null === $Account){
            return $View;
        }
        $Error = false;
        if (isset($Account['Description']) && empty( $Account['Description'])) {
            $View->setError( '$Account[Description]', 'Bitte geben sie eine Beschreibung an' );
            $Error = true;
        }
        if (isset($Account['IsActive']) && empty( $Account['IsActive'])) {
            $View->setError( '$Account[IsActive]', 'Bitte geben sie die Aktivität an' ); //// spätere Kontrolle
        }
        if (isset($Account['Number']) && empty( $Account['Number'])) {
            $View->setError( '$Account[Number]', 'Bitte geben sie die Nummer an' );
            $Error = true;
        }
//        if (isset($Account['Key']) && empty( $Account['Key'])) {
//            $View->setError( '$Account[Key]', 'Bitte geben sie den Schlüssel an' );
//        }
//        if (isset($Account['Type']) && empty( $Account['Type'])) {
//            $View->setError( '$Account[Type]', 'Bitte geben sie den Typ an' );
//        }

        print_r($Account['Description']);
        if (!$Error) {
            $this->actionAddAccount( $Account['Number'],$Account['Description'],$Account['IsActive'], $this->entityAccountKeyById( $Account['Key'] ), $this->entityAccountTypeById( $Account['Type'] ) );
            return new Success( 'Der Account ist erfasst worden' )
                .new Redirect( '/Sphere/Billing/Account', 2 );
        }

        return $View;

    }

    /**
     * @param AbstractType $View
     * @param $Debitor
     * @return AbstractType|string
     */
    public function executeAddDebitor(
        AbstractType &$View = null,
        $Debitor )
    {
        /**
        * Skip to Frontend
        */
        if ( null === $Debitor ){
            return $View;
        }
        $Error = false;
        if (isset($Debitor['ZeitEins']) && empty( $Debitor['ZeitEins'] )) {
            $View->setError( 'Debitor[ZeitEins]', 'Bitte geben sie die erste Vorlaufzeit an' );
            $Error = true;
        }
        if (isset($Debitor['ZeitZwei']) && empty( $Debitor['ZeitZwei'] )) {
            $View->setError( 'Debitor[ZeitZwei]', 'Bitte geben sie die folge Vorlaufzeit an' );
            $Error = true;
        }
        if (isset($Debitor['Nummer']) && empty( $Debitor['Nummer'] )) {
            $View->setError( 'Debitor[Nummer]', 'Bitte geben sie die Debitorennummer an' );
            $Error = true;
        }

        if (!$Error) {
             $this->actionAddDebitor( $Debitor['First'], $Debitor['Second'], $Debitor['Number']);
            return new Success( 'Der Debitor ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Account', 2);
        }
        return $View;
    }

    /**
     * @return bool|TblAccountKey[]
     */
    public function entityKeyValueAll()
    {

        return parent::entityKeyValueAll();
    }

    /**
     * @return bool|TblAccountType[]
     */
    public function entityTypeValueAll()
    {

        return parent::entityTypeValueAll();
    }

    /**
     * @param $Id
     * @return bool|TblAccount
     */
    public function entityAccountById($Id)
    {
        return parent::entityAccountById($Id);
    }

    /**
     * @return bool|TblAccount[]
     */
    public function entityAccountActiveAll()
    {
        return parent::entityAccountActiveAll();
    }
}
