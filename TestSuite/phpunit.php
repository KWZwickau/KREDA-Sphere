<?php
namespace KREDA\Sphere\TestSuite;

use MOC\V\Core\AutoLoader\AutoLoader;

require_once( __DIR__.'/../Library/MOC-V/Core/AutoLoader/AutoLoader.php' );

AutoLoader::getNamespaceAutoLoader( 'MOC\V', __DIR__.'/../Library/MOC-V', 'MOC\V' );
AutoLoader::getNamespaceAutoLoader( 'KREDA\Sphere', __DIR__.'/../', 'KREDA' );
AutoLoader::getNamespaceAutoLoader( 'Markdownify', __DIR__.'/../Library/Markdownify/2.1.6/src' );

set_include_path( get_include_path().PATH_SEPARATOR.__DIR__.'/../' );

date_default_timezone_set( 'Europe/Berlin' );
