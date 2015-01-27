<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation\AbstractLink;
use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\IconParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;

/**
 * Class Link
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule
 */
class Link extends AbstractLink implements IElementInterface
{

    /**
     * @param UrlParameter  $Route
     * @param NameParameter $Name
     * @param IconParameter $Icon
     * @param bool          $ToggleActive
     */
    function __construct(
        UrlParameter $Route,
        NameParameter $Name,
        IconParameter $Icon = null,
        $ToggleActive = false
    ) {

        $this->TemplateDirectory = __DIR__;

        parent::__construct( $Route, $Name, $Icon, $ToggleActive );
    }

}
