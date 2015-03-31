<?php
namespace KREDA\Sphere\Application\Graduation;

use KREDA\Sphere\Application\Graduation\Service\Grade;
use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Score;
use KREDA\Sphere\Application\Graduation\Service\Weight;
use KREDA\Sphere\Client\Component\Element\Repository\Content;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagListIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitDanger;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitSuccess;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Common\AbstractApplication;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Graduation
 *
 * @package KREDA\Sphere\Application\Graduation
 */
class Graduation extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::setupApplicationAccess( 'Graduation' );

        self::$Configuration = $Configuration;

        self::addClientNavigationMain( self::$Configuration,
            '/Sphere/Graduation', 'Zensuren', new EducationIcon()
        );

        self::registerClientRoute( self::$Configuration, '/Sphere/Graduation',
            __CLASS__.'::frontendGrade' );
        self::registerClientRoute( self::$Configuration, '/Sphere/Graduation/Grade/Type',
            __CLASS__.'::frontendGradeType' );
        //self::registerClientRoute( self::$Configuration, '/Sphere/Graduation/Grade/Type/ChangeActiveState',
        //    __CLASS__.'::frontendGradeTypeActiveState' );
        $Route = self::registerClientRoute( self::$Configuration, '/Sphere/Graduation/Grade/Type/Create',
            __CLASS__.'::frontendGradeTypeCreate' );
        $Route->setParameterDefault( 'Acronym', null );
        $Route->setParameterDefault( 'Name', null );

        return $Configuration;
    }

    /**
     * @return Service\Score
     */
    public static function serviceScore()
    {

        return Score::getApi();
    }

    /**
     * @return Service\Weight
     */
    public static function serviceWeight()
    {

        return Weight::getApi();
    }

    /**
     * @return Stage
     */
    public static function frontendGrade()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Zensuren' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public static function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Graduation/Grade/Type', 'Zensurentypen', new TagListIcon()
        );
    }

    /**
     * @return Stage
     */
    public static function frontendGradeType()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Zensurentypen' );
        $View->setDescription( 'für Ihre Schule zugelassen' );
        $View->setMessage( 'Hier können Sie die Zensurentypen Ihrer Schule anpassen' );

        $GradeTypeList = Graduation::serviceGrade()->entityGradeTypeAll();
        if (!empty( $GradeTypeList )) {

            array_walk( $GradeTypeList, function ( TblGradeType &$Entity ) {

                $_POST['Id'] = $Entity->getId();

                // bei aktiven Zensurentypen "deaktivieren"-Button anzeigen; sonst "aktivieren"-Button
                $Entity->getActiveState() == true ? $myButton = new SubmitDanger( 'deaktivieren' ) : $myButton = new SubmitSuccess( 'aktivieren' );

                $Entity->Option = new FormDefault(
                    new GridFormGroup(
                        new GridFormRow( new GridFormCol( array(
                            new InputHidden( 'Id' ),
                            $myButton
                        ) ) )
                    ),
                    null,
                    '/Sphere/Graduation/Grade/Type'
                );

            }, null );
        }

        $View->setContent( new TableData( $GradeTypeList, null, array(
            //'Id' => 'Id',
            'Acronym' => 'Kürzel',
            'Name'    => 'Name',
            //'Active'     => 'Aktiv',
            'Option'  => 'Option'
        ) ) );
        $View->addButton( new Primary( 'Zensurentyp hinzufügen', '/Sphere/Graduation/Grade/Type/Create' ) );

        return $View;
    }

    /**
     * @return Service\Grade
     */
    public static function serviceGrade()
    {

        return Grade::getApi();
    }

    /**
     * @param null|string $Acronym
     * @param null|string $Name
     *
     * @return Stage
     */
    public static function frontendGradeTypeCreate( $Acronym, $Name )
    {

        $View = new Stage();
        $View->setTitle( 'Zensurentyp erstellen' );
        $View->setDescription( 'neuer Zensurentyp' );
        $View->setContent(
            Graduation::serviceGrade()->executeCreateGradeType(
                new FormDefault(
                    new GridFormGroup( array(
                        new GridFormRow( array(
                            new GridFormCol(
                                new InputText( 'Acronym', 'Kürzel', 'Kürzel', new ConversationIcon() )
                                , 5 ),
                            new GridFormCol(
                                new InputText( 'Name', 'Langform', 'Langform', new NameplateIcon() )
                                , 7 )
                        ) ),
                    ), new GridFormTitle( 'Grunddaten' ) ), array(
                        new SubmitPrimary( 'Anlegen' )
                    )
                )
                , $Acronym, $Name )
        );

        return $View;
    }

    public static function frontendGradeTypeActiveState( $Id )
    {

        // TODO: erstmal hier hin kommen
        Graduation::serviceGrade()->executeChangeGradeTypeActiveState( $Id );

    }

}
