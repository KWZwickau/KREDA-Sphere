<?php
namespace KREDA\Sphere;

use MOC\V\Component\Router\Component\IBridgeInterface;

interface IApplicationInterface
{
    public static function registerApplication( IBridgeInterface $Router );
}
