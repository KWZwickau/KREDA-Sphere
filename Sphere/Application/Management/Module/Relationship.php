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
            '/Sphere/Management/Person/Relationship/Edit', __CLASS__.'::frontendRelationshipEdit'
        )
            ->setParameterDefault( 'tblRelationship', null )
            ->setParameterDefault( 'tblRelationshipType', null );

        self::registerClientRoute( $Configuration,
            '/Sphere/Management/Person/Relationship/Destroy', __CLASS__.'::frontendRelationshipDestroy'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Link', null )
            ->setParameterDefault( 'Confirm', false );

        /**
         * REST Service
         */
        self::registerClientRoute( $Configuration,
            '/Sphere/Management/Table/PersonRelationship', __CLASS__.'::restPersonListRelationship'
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
     * @param int $Id
     * @param null|int $tblRelationship
     * @param null|int $tblRelationshipType
     * @param bool|int $Remove
     *
     * @return Stage
     */
    public static function frontendRelationshipEdit( $Id, $tblRelationship, $tblRelationshipType, $Remove = false )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::stageRelationship( $Id, $tblRelationship, $tblRelationshipType, $Remove );
    }

    /**
     * @param null|int $Id
     * @param null|int $Link
     * @param bool     $Confirm
     *
     * @return Stage
     */
    public static function frontendRelationshipDestroy( $Id, $Link, $Confirm )
    {

        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return \KREDA\Sphere\Application\Management\Frontend\Person\Relationship::stageDestroy( $Id, $Link, $Confirm );
    }
}
