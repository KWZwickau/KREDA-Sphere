<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\Relationship as Frontend;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Relationship
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Relationship extends Person
{

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::registerClientRoute( $Configuration,
            '/Sphere/Management/Person/Relationship', __CLASS__.'::frontendRelationship'
        )
            ->setParameterDefault( 'tblRelationship', null )
            ->setParameterDefault( 'tblRelationshipType', null );

        self::registerClientRoute( $Configuration,
            '/Sphere/Management/REST/PersonListRelationship', __CLASS__.'::restPersonListRelationship'
        );

    }

    /**
     * @param int $tblPerson
     */
    public static function restPersonListRelationship( $tblPerson )
    {

        print Management::servicePerson()->tablePersonRelationship( $tblPerson );
    }

    /**
     * @param int      $tblPerson
     * @param null|int $tblRelationship
     * @param null|int $tblRelationshipType
     *
     * @return Stage
     */
    public static function frontendRelationship( $tblPerson, $tblRelationship, $tblRelationshipType )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageRelationship( $tblPerson, $tblRelationship, $tblRelationshipType );
    }
}
