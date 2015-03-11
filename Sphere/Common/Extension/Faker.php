<?php
namespace KREDA\Sphere\Common\Extension;

use Faker\Factory;
use MOC\V\Core\AutoLoader\AutoLoader;

/**
 * Class Faker
 *
 * @package KREDA\Sphere\Common\Extension
 */
class Faker
{

    private $Instance = null;

    /**
     * @param string $Locale en_US
     */
    function __construct( $Locale = Factory::DEFAULT_LOCALE )
    {

        AutoLoader::getNamespaceAutoLoader( 'Faker', __DIR__.'/Faker', 'Faker' );
        $this->Instance = Factory::create( $Locale );

    }

    /**
     * @return string
     */
    public function getFirstName()
    {

        return $this->Instance->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {

        return $this->Instance->lastName;
    }

    /**
     * @return string
     */
    public function getDate()
    {

        return $this->Instance->date();
    }

    /**
     * @return string
     */
    public function getCityName()
    {

        return $this->Instance->city;
    }
}
