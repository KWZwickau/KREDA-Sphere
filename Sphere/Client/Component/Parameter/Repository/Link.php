<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Parameter;

abstract class Link extends Parameter implements IParameterInterface
{

    /** @var string $PatternLinkRoute */
    protected $PatternLinkRoute = '|^[a-z/]+$|is';
    /** @var string $PatternLinkName */
    protected $PatternLinkName = '|^[a-z\söäüß]+$|is';

    /** @var null|string $Value */
    protected $Value = null;

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
}
