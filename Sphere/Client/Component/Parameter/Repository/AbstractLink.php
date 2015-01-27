<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\AbstractParameter;

/**
 * Class AbstractLink
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository
 */
abstract class AbstractLink extends AbstractParameter implements IParameterInterface
{

    /** @var null $ActiveBase */
    private static $ActiveBase = null;
    /** @var string $PatternLinkRoute */
    protected $PatternLinkRoute = '|^[a-z/]+$|is';
    /** @var string $PatternLinkName */
    protected $PatternLinkName = '|^[a-z\söäüß\-]+$|is';
    /** @var string $Value */
    protected $Value = '';

    /**
     * @return null|string
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param null|string $Value
     */
    protected function setValue( $Value )
    {

        $this->Value = $Value;
    }

    /**
     * @return bool|string
     */
    protected function getUrlBase()
    {

        if (null === self::$ActiveBase) {
            self::$ActiveBase = $this->extensionRequest()->getUrlBase();
        }
        return self::$ActiveBase;
    }
}
