<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKey;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKeyType;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Billing\Service\Account\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Client\Frontend\Text\Type\Danger;
use KREDA\Sphere\Common\Database\Handler;

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
     *
     */
    public function setupDatabaseContent()
    {
        /**
         * CommodityType
         */
        $this->actionCreateKeyType('U','Umsatzsteuer');
        $this->actionCreateKey( '01.01.2007', '19', '01.01.2030', 'Mehrwertsteuer', '3', $this->entityAccountKeyTypeById( '1' ) );
        $this->actionCreateType( 'Erlöskonto','Dient zum erfassen des Erlöses' );
        $this->actionCreateType( 'Umsatzsteuer','Dient zum erfassen der Umsatzsteuer' );
    }

    /**
     * @param \KREDA\Sphere\Client\Frontend\Form\AbstractType $View
     * @param $Account
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType
     */
    public function executeAddAccount(
        AbstractType &$View = null,
        $Account )
    {

        /**
         * Skip to Frontend
         */
        if ( null === $Account){
            return $View;
        }
        $Error = false;
        if (isset($Account['Description']) && empty( $Account['Description'])) {
            $View->setError( 'Account[Description]', 'Bitte geben sie eine Beschreibung an' );
            $Error = true;
        }
        if (isset($Account['Number']) && empty( $Account['Number'])) {
            $View->setError( 'Account[Number]', 'Bitte geben sie die Nummer an' );
            $Error = true;
        }
        $Account['IsActive'] = 1;

        if (!$Error) {
            $this->actionAddAccount(
                $Account['Number'],
                $Account['Description'],
                $Account['IsActive'],
                $this->entityAccountKeyById( $Account['Key'] ),
                $this->entityAccountTypeById( $Account['Type'] ) );
            return new Success( 'Das Konto ist erfasst worden' )
                .new Redirect( '/Sphere/Billing/Account', 2 );
        }

        return $View;

    }

    /**
     * @param AbstractType $View
     * @param TblAccount $tblAccount
     * @param $Account
     * @return AbstractType|string
     */
    public function executeEditAccount(
        AbstractType &$View = null,
        TblAccount $tblAccount,
        $Account
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Account
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Account['Description'] ) && empty( $Account['Description'] )) {
            $View->setError( 'Account[Description]', 'Bitte geben Sie eine Refferenznummer an' );
            $Error = true;
        }
        if (isset($Account['Number'] ) && empty( $Account['Number'] )) {
            $View->setError( 'Account[Number]', 'Bitte geben Sie eine Refferenznummer an' );
            $Error = true;
        }
        if (isset($Account['IsActive'] ) && empty( $Account['IsActive'] )) {
            $View->setError( 'Account[IsActive]', 'Bitte geben sie an ob der Account Aktiv ist' );
            $Error = true;
        }

        if (!$Error) {
            if ($this->actionEditAccount(
                $tblAccount,
                $Account['Description'],
                $Account['Number'],
                $Account['IsActive'],
                $this->entityAccountKeyById($Account['tblAccountKey']),
                $this->entityAccountTypeById($Account['tblAccountType'])
            )) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Account', 2);
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' );
            };
        }
        return $View;
    }

    /**
     * @param $Id
     * @return string
     */
    public function setFibuActivate( $Id )
    {

        if($this->actionActivateAccount( $Id ))
        {
            return new Success( 'Die Aktivierung ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Account', 2 );
        }
        else
        {
            return new Danger( 'Die Aktivierung konnte nicht erfasst werden' )
            .new Redirect( '/Sphere/Billing/Account', 2 );
        }

    }

    /**
     * @param $Id
     * @return string
     */
    public function setFibuDeactivate( $Id )
    {

        if($this->actionDeactivateAccount( $Id ))
        {
            return new Success( 'Die Deaktivierung ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Account', 2 );
        }
        else
        {
            return ( 'Die Deaktivierung konnte nicht erfasst werden' )
            .new Danger( '/Sphere/Billing/Account', 2 );
        }

    }

    /**
     * @param $Id
     *
     * @return bool|TblAccountKeyType
     */
    public function entityAccountKeyTypeById( $Id )
    {

        return parent::entityAccountKeyTypeById( $Id );
    }

    /**
     * @param $Id
     *
     * @return bool|TblAccountKey
     */
    public function entityAccountKeyById($Id)
    {

        return parent::entityAccountKeyById( $Id);
    }

    /**
     * @param int $Id
     *
     * @return bool|TblAccountType
     */
    public function entityAccountTypeById( $Id )
    {

        return parent::entityAccountTypeById( $Id );
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
     * @param bool $IsActive
     *
     * @return bool|TblAccount[]
     */
    public function entityAccountAllByActiveState( $IsActive = true)
    {
        return parent::entityAccountAllByActiveState( $IsActive );
    }

    /**
     * @return bool|TblAccount[]
     */
    public function entityAccountAll()
    {

        return parent::entityAccountAll();
    }

    /**
     * @return array|bool|TblAccountKey[]
     */
    public function entityAccountKeyAll()
    {

        return parent::entityAccountKeyAll();
    }

    /**
     * @return array|bool|TblAccountType[]
     */
    public function entityAccountTypeAll()
    {

        return parent::entityAccountTypeAll();
    }

}
