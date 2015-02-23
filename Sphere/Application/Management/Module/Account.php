<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
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
        );
    }

    /**
     * @throws \Exception
     * @return Stage
     */
    public function frontendAccount()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Benutzerkonten' );

        $tblAccountList = Gatekeeper::serviceAccount()->entityAccountAllByConsumer(
            $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession()
        );

        if (!$tblAccountList) {
            $tblAccountList = array();
        }

        array_walk( $tblAccountList, function ( TblAccount &$A ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $A->getTblAccountTyp()->getId() == Gatekeeper::serviceAccount()->entityAccountTypByName( 'System' )->getId()
            ) {
                $A = false;
            } else {

                $tblAccountTyp = $A->getTblAccountTyp();
                $A->AccountTyp = $tblAccountTyp->getName();
                $tblAccountRole = $A->getTblAccountRole();
                $A->AccountRole = $tblAccountRole->getName();
                $tblPerson = $A->getServiceManagementPerson();
                if (empty( $tblPerson )) {
                    $A->Person = new MessageWarning( 'Keine Daten verfügbar' );
                } else {
                    $A->Person = $tblPerson->getFullName();
                }
            }
        } );
        $tblAccountList = array_filter( $tblAccountList );

        $tblAccountTypeList = Gatekeeper::serviceAccount()->entityAccountTypeAll();
        array_walk( $tblAccountTypeList, function ( TblAccountType &$T ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $T->getId() == Gatekeeper::serviceAccount()->entityAccountTypByName( 'System' )->getId()
            ) {
                $T = false;
            }
        } );
        $tblAccountTypeList = array_filter( $tblAccountTypeList );

        $tblAccountTypeListSelect = array();
        /** @var TblAccountType $tblAccountType */
        foreach ((array)$tblAccountTypeList as $tblAccountType) {
            $tblAccountTypeListSelect[$tblAccountType->getId()] = $tblAccountType->getName();
        }

        $tblAccountRoleList = Gatekeeper::serviceAccount()->entityAccountRoleAll();
        array_walk( $tblAccountRoleList, function ( TblAccountRole &$R ) {

            /**
             * Filter: No "System"-Accounts !
             */
            if (
                $R->getId() == Gatekeeper::serviceAccount()->entityAccountRoleByName( 'System' )->getId()
            ) {
                $R = false;
            }
        } );
        $tblAccountRoleList = array_filter( $tblAccountRoleList );

        $tblAccountRoleListSelect = array();
        /** @var TblAccountRole $tblAccountRole */
        foreach ((array)$tblAccountRoleList as $tblAccountRole) {
            $tblAccountRoleListSelect[$tblAccountRole->getId()] = $tblAccountRole->getName();
        }

        $View->setContent(
            new GridLayoutTitle( 'Bestehende Benutzerkonten', 'Accounts' )
            .
            ( empty( $tblAccountList )
                ? new MessageWarning( 'Keine Benutzer verfügbar' )
                : new TableData( $tblAccountList, null, array(
                    'Id'          => 'Account-Id',
                    'Username'    => 'Anmeldename',
                    'AccountTyp'  => 'Authentifizierungstyp',
                    'AccountRole' => 'Berechtigungsstufe',
                    'Person'      => 'Benutzer'
                ) )
            )
            .
            new FormDefault(
                new GridFormGroup( array(
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputText( 'AccountName', 'Benutzername', 'Benutzername', new PersonIcon() ), 4
                        ),
                        new GridFormCol(
                            new InputSelect( 'AccountTyp', 'Authentifizierungstyp', $tblAccountTypeListSelect,
                                new PersonKeyIcon() ), 4
                        ),
                        new GridFormCol(
                            new InputSelect( 'AccountRole', 'Berechtigungsstufe', $tblAccountRoleListSelect,
                                new PersonKeyIcon() ), 4
                        )
                    ) ),
                    new GridFormRow( array(
                        new GridFormCol(
                            new InputPassword( 'AccountPassword', 'Passwort', 'Passwort', new LockIcon() ), 6
                        ),
                        new GridFormCol(
                            new InputPassword( 'AccountPasswordSafety', 'Passwort wiederholen',
                                'Passwort wiederholen',
                                new RepeatIcon() ), 6
                        )
                    ) ),
                ), new GridFormTitle( 'Benutzer hinzufügen', 'Account' ) )
                , new ButtonSubmitPrimary( 'Hinzufügen' )
            )
        );

        return $View;
    }
}
