<?php
namespace KREDA\Sphere\TestSuite;

use MOC\V\Core\AutoLoader\AutoLoader;

require_once( __DIR__.'/../Library/MOC-V/Core/AutoLoader/AutoLoader.php' );

AutoLoader::getNamespaceAutoLoader( '\MOC\V', __DIR__.'/../Library/MOC-V' );

//set_include_path( get_include_path().PATH_SEPARATOR.__DIR__.'/../' );