<?php
namespace KREDA\Sphere\Application\Demo;

use KREDA\Sphere\Application\Demo\Service\DemoService;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Complex\Element\ComplexMathJax;
use KREDA\Sphere\Common\Frontend\Form\Element\InputCompleter;
use KREDA\Sphere\Common\Frontend\Form\Element\InputDate;
use KREDA\Sphere\Common\Frontend\Form\Element\InputFile;
use KREDA\Sphere\Common\Frontend\Form\Element\InputPassword;
use KREDA\Sphere\Common\Frontend\Form\Element\InputSelect;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Element\InputTextArea;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

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

        $DemoText = new InputText( 'DemoText', 'DemoText', 'DemoText' );
        $DemoText->setDefaultValue( 'DefaultValue' );
        $View->setContent(

            new TableData( $tblDemoCompleterList )

            .

            Demo::serviceDemoService()->executeCreateDemo(
                new FormDefault(
                    new GridFormGroup(
                        new GridFormRow( array(
                            new GridFormCol( array(
                                    new InputCompleter( 'DemoCompleter', 'DemoCompleter', 'DemoCompleter',
                                        $tblDemoCompleterListSelect, new BookIcon() ),
                                    new InputDate( 'DemoDate', 'DemoDate', 'DemoDate' ),
                                    new InputFile( 'DemoFile', 'DemoFile', 'DemoFile' ),
                                    new InputPassword( 'DemoPassword', 'DemoPassword', 'DemoPassword' ),
                                    new InputSelect( 'DemoSelect', 'DemoSelect', $tblDemoCompleterListSelect ),
                                    $DemoText,
                                    new InputTextArea( 'DemoTextArea', 'DemoTextArea', 'DemoTextArea' )
                                )
                            )
                        ) )
                    )
                    , new ButtonSubmitPrimary( 'Speichern' ) )
                , $DemoCompleter, $DemoTextArea )

            .

            new ComplexMathJax( '`a != 0`, `ax^2 + bx + c = 0`' )
            .
            new ComplexMathJax( '`x = (-b +- sqrt(b^2-4ac))/(2a)`' )

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
