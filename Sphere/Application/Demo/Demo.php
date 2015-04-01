<?php
namespace KREDA\Sphere\Application\Demo;

use KREDA\Sphere\Application\Demo\Service\DemoService;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\FileUpload;
use KREDA\Sphere\Client\Frontend\Input\Type\PasswordField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextArea;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Client\Frontend\Text\Type\MathJax;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Demo
 *
 * @package KREDA\Sphere\Application\Demo
 */
class Demo extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        self::addClientNavigationMain( self::$Configuration, '/Sphere/Demo', 'Demo-Modul', new TaskIcon() );

        self::registerClientRoute( self::$Configuration, '/Sphere/Demo', __CLASS__.'::frontendDemo' )
            ->setParameterDefault( 'DemoCompleter', null )
            ->setParameterDefault( 'DemoTextArea', null );
    }

    /**
     * @param $DemoCompleter
     * @param $DemoTextArea
     *
     * @return Stage
     */
    public static function frontendDemo( $DemoCompleter, $DemoTextArea )
    {

        $View = new Stage();
        $View->setTitle( 'Demo Modul' );
        $View->setDescription( 'Beispiel' );
        $View->setMessage( 'Test' );

        $tblDemoCompleterList = self::serviceDemoService()->entityDemoCompleterAll();
        if (empty( $tblDemoCompleterList )) {
            $tblDemoCompleterList = array();
        }

        $tblDemoCompleterListSelect = array();
        /** @var DemoService\Entity\TblDemoCompleter $tblDemoCompleter */

        foreach ((array)$tblDemoCompleterList as $tblDemoCompleter) {
            $tblDemoCompleterListSelect[$tblDemoCompleter->getId()] = $tblDemoCompleter->getName();
        }

        $DemoText = new TextField( 'DemoText', 'DemoText', 'DemoText' );
        $DemoText->setDefaultValue( 'DefaultValue' );
        $View->setContent(

            new TableData( $tblDemoCompleterList )

            .

            Demo::serviceDemoService()->executeCreateDemo(
                new Form(
                    new FormGroup(
                        new FormRow( array(
                            new FormColumn( array(
                                    new AutoCompleter( 'DemoCompleter', 'DemoCompleter', 'DemoCompleter',
                                        $tblDemoCompleterListSelect, new BookIcon() ),
                                    new DatePicker( 'DemoDate', 'DemoDate', 'DemoDate' ),
                                    new FileUpload( 'DemoFile', 'DemoFile', 'DemoFile' ),
                                    new PasswordField( 'DemoPassword', 'DemoPassword', 'DemoPassword' ),
                                    new SelectBox( 'DemoSelect', 'DemoSelect', $tblDemoCompleterListSelect ),
                                    $DemoText,
                                    new TextArea( 'DemoTextArea', 'DemoTextArea', 'DemoTextArea' )
                                )
                            )
                        ) )
                    )
                    , new SubmitPrimary( 'Speichern' ) )
                , $DemoCompleter, $DemoTextArea )

            .
            new Layout(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            new MathJax( '`a != 0,`' )
                            , 2 ),
                        new LayoutColumn(
                            new MathJax( '`ax^2 + bx + c = 0`' )
                            , 3 ),
                        new LayoutColumn(
                            new MathJax( '`a != 0,`' )
                            , 2 ),
                        new LayoutColumn(
                            new MathJax( '`ax^2 + bx + c = 0`' )
                            , 3 )
                    ) ),
                    new LayoutRow(
                        new LayoutColumn( '&nbsp;' )
                    ),
                    new LayoutRow(
                        new LayoutColumn(
                            new MathJax( '`x = (-b +- sqrt(b^2-4ac))/(2a)`' )
                            , 6 )
                    )
                ) )
            )

        );
        return $View;
    }

    /**
     * @return DemoService
     */
    public static function serviceDemoService()
    {

        return DemoService::getApi();
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

    }
}
