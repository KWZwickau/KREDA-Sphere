<?php
namespace KREDA\Sphere;

use MOC\V\Component\Router\Component\Bridge\Repository\UniversalRouter;
use MOC\V\Component\Router\Component\Parameter\Repository\RouteParameter;
use MOC\V\Component\Template\Template;
use MOC\V\Core\AutoLoader\AutoLoader;
use MOC\V\Core\HttpKernel\HttpKernel;

require_once(__DIR__ . '/Library/MOC-Framework-Mark-V/Core/AutoLoader/AutoLoader.php');
AutoLoader::getNamespaceAutoLoader('MOC\V', __DIR__ . '/Library/MOC-Framework-Mark-V/');

//$Router = new UniversalRouter();
//$Router->addRoute(new RouteParameter('/', 'KREDA\Sphere\Application\Gatekeeper\Controller::Method'));

$TemplateMain = Template::getTemplate(__DIR__ . '/Sphere/Client/Template/Main.twig');
$TemplateMain->setVariable('MainBody',
    Template::getTemplate(__DIR__ . '/Sphere/Client/Template/Navigation/Level-0.twig')
        ->getContent()
  //  . Template::getTemplate(__DIR__ . '/Sphere/Client/Template/Navigation/Level-1.twig')
  //      ->getContent()
    . Template::getTemplate(__DIR__ . '/Sphere/Client/Template/Navigation/Level-2.twig')
        ->getContent()
    //. $Router->getRoute()
);
ob_start();
var_dump(
    HttpKernel::getRequest()->getPathBase(),
    HttpKernel::getRequest()->getUrlBase(),
    HttpKernel::getRequest()->getPathInfo(),
    HttpKernel::getRequest()->getPort(),
    HttpKernel::getRequest()->getParameterArray()
)    ;
$Foot = ob_get_clean();
$TemplateMain->setVariable( 'MainFoot',
    $Foot
);
print $TemplateMain->getContent();
