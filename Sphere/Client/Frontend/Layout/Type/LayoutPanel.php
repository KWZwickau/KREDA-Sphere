<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutPanel
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutPanel extends AbstractType
{

    const PANEL_TYPE_DEFAULT = 'panel-default';
    const PANEL_TYPE_PRIMARY = 'panel-primary';
    const PANEL_TYPE_SUCCESS = 'panel-success';
    const PANEL_TYPE_WARNING = 'panel-warning';
    const PANEL_TYPE_INFO = 'panel-info';
    const PANEL_TYPE_DANGER = 'panel-danger';

    /**
     * @param string       $Title
     * @param string|array $Content
     * @param string       $Type
     * @param null|string  $Footer
     */
    public function __construct( $Title, $Content, $Type = LayoutPanel::PANEL_TYPE_DEFAULT, $Footer = null )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/LayoutPanel.twig' );
        $this->Template->setVariable( 'Title', $Title );
        if (is_array( $Content )) {
            $this->Template->setVariable( 'Content', array_shift( $Content ) );
            $this->Template->setVariable( 'ContentList', $Content );
        } else {
            $this->Template->setVariable( 'Content', $Content );
            $this->Template->setVariable( 'ContentList', array() );
        }
        $this->Template->setVariable( 'Footer', $Footer );
        $this->Template->setVariable( 'Type', $Type );
    }
}
