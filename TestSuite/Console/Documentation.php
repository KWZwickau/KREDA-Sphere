<?php
require_once( __DIR__.'/../../Library/MOC-V/Core/AutoLoader/AutoLoader.php' );
use MOC\V\Component\Documentation\Component\Parameter\Repository\DirectoryParameter;
use MOC\V\Component\Documentation\Documentation;
use MOC\V\Core\AutoLoader\AutoLoader;

AutoLoader::getNamespaceAutoLoader( 'MOC\V', __DIR__.'/../../Library/MOC-V', 'MOC\V' );

Documentation::getDocumentation(
    'KREDA',
    'Sphere',
    new DirectoryParameter( __DIR__.'/../../Sphere' ),
    new DirectoryParameter( __DIR__.'/../../../KREDA-Sphere-Api' )
);
