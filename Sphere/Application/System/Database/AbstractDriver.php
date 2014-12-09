<?php
namespace KREDA\Sphere\Application\System\Database;

/**
 * Class AbstractDriver
 *
 * @package KREDA\Sphere\Application\System\Database
 */
abstract class AbstractDriver
{

    /** @var string $Identifier */
    private $Identifier = '';

    /**
     * @return string
     */
    final public function getIdentifier()
    {

        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    final public function setIdentifier( $Identifier )
    {

        $this->Identifier = $Identifier;
    }

}
