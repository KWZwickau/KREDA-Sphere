<?php
namespace KREDA\Sphere\Application\Management\PersonalData;

use KREDA\Sphere\Application\Management\PersonalData\Student\Identity;
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
            new Identity()
        );
        return $View;
    }
}
