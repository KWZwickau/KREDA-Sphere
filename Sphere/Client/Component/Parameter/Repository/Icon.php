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
    const ICON_HOME = 'glyphicon glyphicon-home';
    const ICON_STATISTIC = 'glyphicon glyphicon-stats';
    const ICON_GEAR = 'glyphicon glyphicon-cog';
    const ICON_QUESTION = 'glyphicon glyphicon-question-sign';
    const ICON_TIME = 'glyphicon glyphicon-time';
    const ICON_PERSON = 'glyphicon glyphicon-user';
    const ICON_TASK = 'glyphicon glyphicon-tasks';
    const ICON_OFF = 'glyphicon glyphicon-off';
    const ICON_BOOK = 'glyphicon glyphicon-book';
    const ICON_BRIEFCASE = 'glyphicon glyphicon-briefcase';

    const ICON_TILE_BIG = 'glyphicon glyphicon-th-large';
    const ICON_TILE_SMALL = 'glyphicon glyphicon-th';
    const ICON_TILE_LIST = 'glyphicon glyphicon-th-list';

    const ICON_TAG = 'glyphicon glyphicon-tag';
    const ICON_TAG_LIST = 'glyphicon glyphicon-tags';

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
