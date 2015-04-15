<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;

/**
 * Class Period
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Period extends Common
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Period', __CLASS__.'::frontendPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear', __CLASS__.'::frontendSchoolYear'
        )
            ->setParameterDefault( 'Name', null )
            ->setParameterDefault( 'FirstTerm', null )
            ->setParameterDefault( 'SecondTerm', null );
    }

    /**
     * @return Stage
     */
    public static function frontendPeriod()
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        $View = new Stage();
        $View->setTitle( 'Zeiten' );
        return $View;
    }

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Period/SchoolYear', 'Schuljahre', new EducationIcon()
        );
    }

    /**
     * @param null|string $Name
     * @param null|array  $FirstTerm
     * @param null|array  $SecondTerm
     *
     * @return Stage
     */
    public static function frontendSchoolYear( $Name, $FirstTerm, $SecondTerm )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        $View = new Stage();
        $View->setTitle( 'Zeiten' );
        $View->setDescription( 'Schuljahre' );

        $View->setContent(
            new TableData( Management::serviceEducation()->entityTermAll(), null, array(
                'Name'           => 'Name',
                'FirstDateFrom'  => 'Vom',
                'FirstDateTo'    => 'Bis',
                'SecondDateFrom' => 'Vom',
                'SecondDateTo'   => 'Bis',
            ) )
            .
            Management::serviceEducation()->executeCreateTerm(
                new Form(
                    new FormGroup( array(
                        new FormRow( array(
                            new FormColumn(
                                new TextField( 'Name', 'Name', 'Name des Schuljahres' )
                            ),
                            new FormColumn( array(
                                new Info( '1. Halbjahr' ),
                                new DatePicker( 'FirstTerm[DateFrom]', 'Von', 'Von', new TimeIcon() ),
                                new DatePicker( 'FirstTerm[DateTo]', 'Bis', 'Bis', new TimeIcon() )
                            ), 6 ),
                            new FormColumn( array(
                                new Info( '2. Halbjahr' ),
                                new DatePicker( 'SecondTerm[DateFrom]', 'Von', 'Von', new TimeIcon() ),
                                new DatePicker( 'SecondTerm[DateTo]', 'Bis', 'Bis', new TimeIcon() )
                            ), 6 ),
                        ) ),
                        new FormRow( array(
                            new FormColumn(
                                new SubmitPrimary( 'Hinzufügen' )
                            )
                        ) )
                    ), new FormTitle( 'Schuljahr', 'Hinzufügen' ) )
                ), $Name, $FirstTerm, $SecondTerm )
        );
        return $View;
    }
}
