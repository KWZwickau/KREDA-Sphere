<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Parameter;

/**
 * Class Icon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository
 */
abstract class Icon extends Parameter implements IParameterInterface
{

    const ICON_LOCK = 'glyphicon glyphicon-lock';
    const ICON_STATISTIC = 'glyphicon glyphicon-stats';
    const ICON_GEAR = 'glyphicon glyphicon-cog';
    const ICON_QUESTION = 'glyphicon glyphicon-question-sign';

    /** @var null|string $Value */
    private $Value = null;

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
