<?php
namespace KREDA\Sphere\Client;

use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication as ApplicationNavigation;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient as ClientNavigation;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule as ModuleNavigation;
use KREDA\Sphere\Common\Debugger;
use MOC\V\Component\Router\Component\IBridgeInterface as Router;

/**
 * Class Configuration
 *
 * @package KREDA\Sphere\Client
 */
class Configuration
{

    /** @var Router $Router */
    private $Router = null;
    /** @var ClientNavigation $ClientNavigation */
    private $ClientNavigation = null;
    /** @var ModuleNavigation $ModuleNavigation */
    private $ModuleNavigation = null;
    /** @var ApplicationNavigation $ApplicationNavigation */
    private $ApplicationNavigation = null;

    /**
     * @param Router           $Router
     * @param ClientNavigation $Navigation
     */
    function __construct( Router $Router, ClientNavigation $Navigation )
    {

        Debugger::addMethodCall( __METHOD__ );
        $this->Router = $Router;
        $this->ClientNavigation = $Navigation;
    }

    /**
     * @return Router
     */
    public function getClientRouter()
    {

        return $this->Router;
    }

    /**
     * @return ClientNavigation
     */
    public function getClientNavigation()
    {

        return $this->ClientNavigation;
    }

    /**
     * @return ModuleNavigation
     */
    public function getModuleNavigation()
    {

        if (!$this->hasModuleNavigation()) {
            $this->ModuleNavigation = new ModuleNavigation();
        }
        return $this->ModuleNavigation;
    }

    /**
     * @return bool
     */
    public function hasModuleNavigation()
    {

        return $this->ModuleNavigation !== null;
    }

    /**
     * @return ApplicationNavigation
     */
    public function getApplicationNavigation()
    {

        if (!$this->hasApplicationNavigation()) {
            $this->ApplicationNavigation = new ApplicationNavigation();
        }
        return $this->ApplicationNavigation;
    }

    /**
     * @return bool
     */
    public function hasApplicationNavigation()
    {

        return $this->ApplicationNavigation !== null;
    }
}
