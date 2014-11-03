<?php
namespace KREDA\Sphere\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication as Menu;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient as Navigation;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule as Module;
use MOC\V\Component\Router\Component\IBridgeInterface as Router;

class Configuration
{

    /** @var Router $Router */
    private $Router = null;
    /** @var Navigation $NavigationClient */
    private $Navigation = null;

    /** @var null|Module $Module */
    private $Module = null;
    /** @var null|Menu $Menu */
    private $Menu = null;

    /**
     * @param Router     $Router
     * @param Navigation $Navigation
     */
    function __construct( Router $Router, Navigation $Navigation )
    {

        $this->Router = $Router;
        $this->Navigation = $Navigation;
    }

    /**
     * @return bool
     */
    public function hasModuleNavigation()
    {

        return $this->Module !== null;
    }

    /**
     * @return bool
     */
    public function hasMenuNavigation()
    {

        return $this->Menu !== null;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {

        return $this->Router;
    }

    /**
     * @return Navigation
     */
    public function getNavigation()
    {

        return $this->Navigation;
    }

    /**
     * @return Module|null
     */
    public function getModuleNavigation()
    {

        if (!$this->hasModuleNavigation()) {
            $this->Module = new Module();
        }
        return $this->Module;
    }

    /**
     * @return Menu|null
     */
    public function getMenuNavigation()
    {

        if (!$this->hasMenuNavigation()) {
            $this->Menu = new Menu();
        }
        return $this->Menu;
    }
}
