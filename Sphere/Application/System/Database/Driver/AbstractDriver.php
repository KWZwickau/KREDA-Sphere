<?php
namespace KREDA\Sphere\Application\System\Database\Driver;

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
