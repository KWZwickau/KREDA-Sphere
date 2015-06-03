<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutBadge
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutBadge extends AbstractType
{

    const BADGE_TYPE_NORMAL = '';
    const BADGE_TYPE_DEFAULT = 'badge-default';
    const BADGE_TYPE_PRIMARY = 'badge-primary';
    const BADGE_TYPE_SUCCESS = 'badge-success';
    const BADGE_TYPE_INFO = 'badge-info';
    const BADGE_TYPE_WARNING = 'badge-warning';
    const BADGE_TYPE_DANGER = 'badge-danger';

    /** @var string $Content */
    private $Content = '';
    /** @var string $Type */
    private $Type = '';

    /**
     * @param string $Content
     * @param string $Type
     */
    public function __construct( $Content, $Type = LayoutBadge::BADGE_TYPE_DEFAULT )
    {

        $this->Content = $Content;
        $this->Type = $Type;
    }

    /**
     * @return string
     */
    function __toString()
    {

        return '<span class="badge '.$this->Type.'">'.$this->Content.'</span>';
    }
}
