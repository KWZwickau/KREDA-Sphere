<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\Birthday;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\City;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\FirstName;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\Gender;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\LastName;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\MiddleName;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\Nationality;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Form\State;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\Student\Form;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class PersonalData
 *
 * @package KREDA\Sphere\Application\Management\PersonalData
 */
class PersonalData extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function guiPerson()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Alle Personen' );
        $View->setContent( '' );
        $View->addButton( '/Sphere/Management/Person/Create', 'Person hinzufügen' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function guiPersonStudent()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler' );
        $View->setMessage( 'Alle Schüler' );
        $View->setContent(
            '{viewStudent}[]'
        );
        $View->addButton( '/Sphere/Management/Person/Student/Create', 'Schüler hinzufügen' );
        return $View;
    }


    /**
     * @return Stage
     */
    public static function guiPersonStudentCreate()
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( 'Schüler hinzufügen' );
        $View->setMessage( '' );
        $View->setContent(
            new Form(
                array(
                    'Grunddaten'       => array(
                        array(
                            new FirstName(),
                            new MiddleName(),
                        ),
                        array(
                            new LastName(),
                            new Birthday(),
                        ),
                        array(
                            new Gender(),
                            new City()
                        ),
                        array(
                            '&nbsp;',
                            '&nbsp;'
                        ),
                        array(
                            new Nationality(),
                            new State()
                        ),
                    ),
                    'Sorgeberechtigte' => array(),
                    'Schulinterna'     => array()
                )
            )
        );
        return $View;
    }
}
