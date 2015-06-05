<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class ListTable
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class ListTable extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageListInterest()
    {

        return self::listPersonTable( 'Interessenten', '/Sphere/Management/Table/PersonInterest' );
    }

    /**
     * @param $Name
     * @param $RestUri
     *
     * @return Stage
     */
    private static function listPersonTable( $Name, $RestUri )
    {

        $View = new Stage();
        $View->setTitle( 'Personen' );
        $View->setDescription( $Name );
        $View->setContent(
            new TableData( $RestUri, null,
                array(
                    'FirstName'  => 'Vorname',
                    'MiddleName' => 'Zweitname',
                    'LastName'   => 'Nachname',
                    'Birthday'   => 'Geburtstag',
                    'Birthplace' => 'Geburtsort',
                    'Option'     => 'Option'
                )
            )
        );
        return $View;

    }

    /**
     * @return Stage
     */
    public static function stageListStudent()
    {

        return self::listPersonTable( 'Schüler', '/Sphere/Management/Table/PersonStudent' );
    }

    /**
     * @return Stage
     */
    public static function stageListGuardian()
    {

        return self::listPersonTable( 'Sorgeberechtigte', '/Sphere/Management/Table/PersonGuardian' );
    }

    /**
     * @return Stage
     */
    public static function stageListTeacher()
    {

        return self::listPersonTable( 'Lehrer', '/Sphere/Management/Table/PersonTeacher' );
    }

    /**
     * @return Stage
     */
    public static function stageListStaff()
    {

        return self::listPersonTable( 'Verwaltungspersonal', '/Sphere/Management/Table/PersonStaff' );
    }

}
