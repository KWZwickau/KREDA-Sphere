<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Wire;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitDanger;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Account', __CLASS__.'::frontendAccount'
        )->setParameterDefault( 'Account', null )->setParameterDefault( 'Id', null );

    }

    /**
     * @param null|array $Account
     * @param null|int   $Id
     *
     * @return Stage
     */
    public static function frontendAccount( $Account, $Id = null )
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Benutzerkonten' );

        $tblAccountTypeListSelect = self::getAccountTypeSelectData();
        $tblAccountRoleListSelect = self::getAccountRoleSelectData();

        $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();

        /**
         * Form Create
         */
        $AccountName = new InputText( 'Account[Name]', 'Benutzername', 'Benutzername', new PersonIcon() );
        $AccountName->setPrefixValue( $tblConsumer->getDatabaseSuffix() );
        $Form = new FormDefault(
            new GridFormGroup( array(
                new GridFormRow( array(
                    new GridFormCol(
                        $AccountName, 4
                    ),
                    new GridFormCol(
                        new InputPassword( 'Account[Password]', 'Passwort', 'Passwort',
                            new LockIcon()
                        ), 4
                    ),
                    new GridFormCol(
                        new InputPassword( 'Account[PasswordSafety]', 'Passwort wiederholen',
                            'Passwort wiederholen',
                            new RepeatIcon()
                        ), 4
                    )
                ) ),
                new GridFormRow( array(
                    new GridFormCol(
                        new InputSelect( 'Account[Type]', 'Authentifizierungstyp', $tblAccountTypeListSelect,
                            new PersonKeyIcon()
                        ), 6
                    ),
                    new GridFormCol(
                        new InputSelect( 'Account[Role]', 'Berechtigungsstufe', $tblAccountRoleListSelect,
                            new PersonKeyIcon()
                        ), 6
                    )
                ) ),
            ), new GridFormTitle( 'Benutzer hinzufügen', 'Account' ) )
            , new ButtonSubmitPrimary( 'Hinzufügen' )
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
        if (null !== $Id) {
            $tblAccount = Gatekeeper::serviceAccount()->entityAccountById( $Id );
            if ($tblAccount && $tblAccount->getServiceGatekeeperConsumer() && $tblAccount->getServiceGatekeeperConsumer()->getId() == $tblConsumer->getId()) {
                if (true !== ( $Wire = Gatekeeper::serviceAccount()->executeDestroyAccount( $tblAccount ) )) {
                    return new Wire( $Wire );
                }
            }
        }

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
                    $A->Person = new MessageWarning( 'Keine Daten verfügbar', new QuestionIcon() );
                } else {
                    $A->Person = $tblPerson->getFullName();
                }
                $tblToken = $A->getServiceGatekeeperToken();
                if (empty( $tblToken )) {
                    if ($A->getTblAccountType()->getId() == Gatekeeper::serviceAccount()->entityAccountTypeByName( 'Schüler' )->getId()) {
                        $A->Token = new MessageSuccess( 'Keine Daten verfügbar', new LockIcon() );
                    } else {
                        $A->Token = new MessageDanger( 'Keine Daten verfügbar', new WarningIcon() );
                    }
                } else {
                    $A->Token = $tblToken->getSerial();
                }

                $Id = new InputHidden( 'Id' );
                $Id->setDefaultValue( $A->getId(), true );

                $FormDestroy = new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol( array( $Id, new ButtonSubmitDanger( 'Löschen' ) ) )
                        )
                    )
                );
                $FormEdit = new FormDefault(
                    new GridFormGroup(
                        new GridFormRow(
                            new GridFormCol( array( $Id, new ButtonSubmitPrimary( 'Bearbeiten' ) ) )
                        )
                    )
                );
                $FormDestroy->setConfirm( 'Wollen Sie den Benutzer '.$A->getUsername().' wirklich löschen?' );
                $A->Option = '<div class="pull-right">'.$FormDestroy.'</div>'.'<div class="pull-right">'.$FormEdit.'</div>';
            }
        } );
        $tblAccountList = array_filter( $tblAccountList );

        $View->setContent(
            new GridLayoutTitle( 'Bestehende Benutzerkonten', 'Accounts' )
            .
            ( empty( $tblAccountList )
                ? new MessageWarning( 'Keine Benutzer verfügbar' )
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
        $tblAccountTypeListSelect = array();
        /** @var TblAccountType $tblAccountType */
        foreach ((array)$tblAccountTypeList as $tblAccountType) {
            $tblAccountTypeListSelect[$tblAccountType->getId()] = $tblAccountType->getName();
        }
        return $tblAccountTypeListSelect;
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
        $tblAccountRoleListSelect = array();
        /** @var TblAccountRole $tblAccountRole */
        foreach ((array)$tblAccountRoleList as $tblAccountRole) {
            $tblAccountRoleListSelect[$tblAccountRole->getId()] = $tblAccountRole->getName();
        }
        return $tblAccountRoleListSelect;
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
}
