<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Wire;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\HiddenField;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutRight;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Account extends Token
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Account' )) {
            self::registerClientRoute( self::$Configuration,
                '/Sphere/Management/Account', __CLASS__.'::frontendAccount'
            )->setParameterDefault( 'Account', null )->setParameterDefault( 'Id', null );
            self::registerClientRoute( self::$Configuration,
                '/Sphere/Management/Account/Edit', __CLASS__.'::frontendAccountEdit'
            )->setParameterDefault( 'Id', null );
        }
    }

    /**
     * @param int $Id
     *
     * @return Stage
     */
    public static function frontendAccountEdit( $Id )
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Benutzerkonten' );
        $View->setDescription( 'Bearbeiten' );

        return $View;
    }

    /**
     * @param null|array $Account
     * @param null|int   $Id
     * @param bool       $Remove
     *
     * @return Stage
     */
    public static function frontendAccount( $Account, $Id = null, $Remove = false )
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Benutzerkonten' );

        $tblAccountTypeSelect = self::getAccountTypeSelectData();
        $tblAccountRoleSelect = self::getAccountRoleSelectData();

        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();

        /**
         * Form Create
         */
        $AccountName = new TextField( 'Account[Name]', 'Benutzername', 'Benutzername', new PersonIcon() );
        $AccountName->setPrefixValue( $tblConsumer->getDatabaseSuffix() );
        $Form = new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        $AccountName, 4
                    ),
                    new FormColumn(
                        new PasswordField( 'Account[Password]', 'Passwort', 'Passwort',
                            new LockIcon()
                        ), 4
                    ),
                    new FormColumn(
                        new PasswordField( 'Account[PasswordSafety]', 'Passwort wiederholen',
                            'Passwort wiederholen',
                            new RepeatIcon()
                        ), 4
                    )
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'Account[Type]', 'Authentifizierungstyp', $tblAccountTypeSelect,
                            new PersonKeyIcon()
                        ), 6
                    ),
                    new FormColumn(
                        new SelectBox( 'Account[Role]', 'Berechtigungsstufe', $tblAccountRoleSelect,
                            new PersonKeyIcon()
                        ), 6
                    )
                ) ),
            ), new FormTitle( 'Benutzer hinzufügen', 'Account' ) )
            , new SubmitPrimary( 'Hinzufügen' )
        );
        /**
         * Action Create
         */
        if (null !== $Account) {
            $Form = Gatekeeper::serviceAccount()->executeCreateAccount(
                $Form, $Account['Name'], $Account['Password'], $Account['PasswordSafety'],
                Gatekeeper::serviceAccount()->entityAccountTypeById( $Account['Type'] ),
                Gatekeeper::serviceAccount()->entityAccountRoleById( $Account['Role'] ),
                $tblConsumer
            );
        }
        /**
         * Action Destroy
         */
        if (null !== $Id && $Remove) {
            $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Id );
            if ($tblAccount && $tblAccount->getServiceGatekeeperConsumer() && $tblAccount->getServiceGatekeeperConsumer()->getId() == $tblConsumer->getId()) {
                if (true !== ( $Wire = Gatekeeper::serviceAccount()->executeDestroyAccount( $tblAccount ) )) {
                    return new Wire( $Wire );
                }
            }
        }

        $tblAccountList = self::getAccountList( $tblConsumer );
        $View->setContent(
            new LayoutTitle( 'Bestehende Benutzerkonten', 'Accounts' )
            .
            ( empty( $tblAccountList )
                ? new Warning( 'Keine Benutzer verfügbar' )
                : new TableData( $tblAccountList, null, array(
                    'Username' => 'Benutzername',
                    'AccountType' => 'Authentifizierungstyp',
                    'AccountRole' => 'Berechtigungsstufe',
                    'Person'   => 'Person',
                    'Token'    => 'Hardware-Schlüssel',
                    'Option'   => 'Option'
                ) )
            )
            .
            $Form
        );

        return $View;
    }

    /**
     * @return TblAccountType[]
     */
    private static function getAccountTypeSelectData()
    {

        $tblAccountTypeList = self::getAccountTypeList();
        $tblAccountTypeSelect = array();
        /** @var TblAccountType $tblAccountType */
        foreach ((array)$tblAccountTypeList as $tblAccountType) {
            $tblAccountTypeSelect[$tblAccountType->getId()] = $tblAccountType->getName();
        }
        return $tblAccountTypeSelect;
    }

    /**
     * @return TblAccountType[]
     */
    private static function getAccountTypeList()
    {

        $tblAccountTypeList = Gatekeeper::serviceAccount()->entityAccountTypeAll();
        array_walk( $tblAccountTypeList, function ( TblAccountType &$O ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $O->getId() == Gatekeeper::serviceAccount()->entityAccountTypeByName( 'System' )->getId()
            ) {
                $O = false;
            }
        } );
        return array_filter( $tblAccountTypeList );
    }

    /**
     * @return TblAccountRole[]
     */
    private static function getAccountRoleSelectData()
    {

        $tblAccountRoleList = self::getAccountRoleList();
        $tblAccountRoleSelect = array();
        /** @var TblAccountRole $tblAccountRole */
        foreach ((array)$tblAccountRoleList as $tblAccountRole) {
            $tblAccountRoleSelect[$tblAccountRole->getId()] = $tblAccountRole->getName();
        }
        return $tblAccountRoleSelect;
    }

    /**
     * @return TblAccountRole[]
     */
    private static function getAccountRoleList()
    {

        $tblAccountRoleList = Gatekeeper::serviceAccount()->entityAccountRoleAll();
        array_walk( $tblAccountRoleList, function ( TblAccountRole &$O ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $O->getId() == Gatekeeper::serviceAccount()->entityAccountRoleByName( 'System' )->getId()
            ) {
                $O = false;
            }
        } );
        return array_filter( $tblAccountRoleList );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return TblAccount[]
     */
    private static function getAccountList( TblConsumer $tblConsumer )
    {

        $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByConsumer( $tblConsumer );
        if (!$tblAccountList) {
            $tblAccountList = array();
        }
        array_walk( $tblAccountList, function ( TblAccount &$A ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $A->getTblAccountType()->getId() == Gatekeeper::serviceAccount()->entityAccountTypeByName( 'System' )->getId()
            ) {
                $A = false;
            } else {

                $tblAccountType = $A->getTblAccountType();
                $A->AccountType = $tblAccountType->getName();
                $tblAccountRole = $A->getTblAccountRole();
                $A->AccountRole = $tblAccountRole->getName();
                $tblPerson = $A->getServiceManagementPerson();
                if (empty( $tblPerson )) {
                    $A->Person = new Warning( 'Keine Daten verfügbar', new QuestionIcon() );
                } else {
                    $A->Person = $tblPerson->getFullName();
                }
                $tblToken = $A->getServiceGatekeeperToken();
                if (empty( $tblToken )) {
                    if ($A->getTblAccountType()->getId() == Gatekeeper::serviceAccount()->entityAccountTypeByName( 'Schüler' )->getId()) {
                        $A->Token = new Success( 'Keine Daten verfügbar', new LockIcon() );
                    } else {
                        $A->Token = new Danger( 'Keine Daten verfügbar', new WarningIcon() );
                    }
                } else {
                    $A->Token = new Success(
                        implode( ' ', str_split( str_pad( $tblToken->getSerial(), 8, '0', STR_PAD_LEFT ), 4 ) ),
                        new OkIcon()
                    );
                }

                $Id = new HiddenField( 'Id' );
                $Id->setDefaultValue( $A->getId(), true );
                $Remove = new HiddenField( 'Remove' );
                $Remove->setDefaultValue( 1, true );

                $FormDestroy = new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn( array( $Id, $Remove, new SubmitDanger( 'Löschen' ) ) )
                        )
                    )
                );

                $FormEdit = new Primary( 'Bearbeiten', '/Sphere/Management/Account/Edit', null, array(
                    'Id' => $A->getId()
                ) );
                $FormDestroy->setConfirm( 'Wollen Sie den Benutzer '.$A->getUsername().' wirklich löschen?' );
                $A->Option = new LayoutRight( $FormDestroy.$FormEdit );
            }
        } );
        return array_filter( $tblAccountList );
    }
}
