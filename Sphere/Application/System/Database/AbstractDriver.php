<?php
namespace KREDA\Sphere\Application\System\Database;

use KREDA\Sphere\Common\AbstractAddOn;

/**
 * Class AbstractDriver
 *
 * @package KREDA\Sphere\Application\System\Database
 */
abstract class AbstractDriver extends AbstractAddOn
{

    /** @var string $Identifier */
    private $Identifier = '';

    /**
     * @return string
     */
    final public function getIdentifier()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    final public function setIdentifier( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->Identifier = $Identifier;
    }

}
