<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Common\AddOn\Debugger;
use MOC\V\Component\Document\Component\Bridge\Repository\DomPdf;
use MOC\V\Component\Document\Document;

/**
 * Class AbstractAddOn
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractAddOn
{


    /**
     * @return Debugger
     */
    final public static function getDebugger()
    {

        return new Debugger();
    }

    /**
     * @return DomPdf
     */
    final public static function getAddonDocumentPdf( $Location )
    {

        return Document::getPdfDocument( $Location );
    }
}
