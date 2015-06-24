<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutLabel
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutLabel extends AbstractType
{

    const LABEL_TYPE_NORMAL = '';
    const LABEL_TYPE_DEFAULT = 'label-default';
    const LABEL_TYPE_PRIMARY = 'label-primary';
    const LABEL_TYPE_SUCCESS = 'label-success';
    const LABEL_TYPE_INFO = 'label-info';
    const LABEL_TYPE_WARNING = 'label-warning';
    const LABEL_TYPE_DANGER = 'label-danger';

    /** @var string $Content */
    private $Content = '';
    /** @var string $Type */
    private $Type = '';

    /**
     * @param string $Content
     * @param string $Type
     */
    public function __construct( $Content, $Type = LayoutLabel::LABEL_TYPE_DEFAULT )
    {

        $this->Content = $Content;
        $this->Type = $Type;
    }

    /**
     * @return string
     */
    function __toString()
    {

        return '<span class="label '.$this->Type.'">'.$this->Content.'</span>';
    }
}
