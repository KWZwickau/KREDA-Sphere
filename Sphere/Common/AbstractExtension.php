<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Extension\ModHex;
use Markdownify\Converter;
use MOC\V\Component\Database\Database;
use MOC\V\Component\Database\Exception\DatabaseException;
use MOC\V\Component\Document\Component\Bridge\Repository\DomPdf;
use MOC\V\Component\Document\Document;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class AbstractExtension
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractExtension
{


    /** @var null|\MOC\V\Core\HttpKernel\Component\IBridgeInterface $extensionRequestCache */
    private static $extensionRequestCache = null;

    /**
     * @param string $String
     *
     * @return ModHex
     */
    final public static function extensionModHex( $String )
    {

        return ModHex::withString( $String );
    }

    /**
     * @return Debugger
     */
    final public static function extensionDebugger()
    {

        return new Debugger();
    }

    /**
     * @param string $Location
     *
     * @return DomPdf
     */
    final public static function extensionDocumentPdf( $Location )
    {

        return Document::getPdfDocument( $Location );
    }

    /**
     * @param $Location
     *
     * @return \MOC\V\Component\Template\Component\IBridgeInterface
     * @throws TemplateTypeException
     */
    final public static function extensionTemplate( $Location )
    {

        return Template::getTemplate( $Location );
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param string $Database
     * @param int    $Driver
     * @param string $Host
     * @param null   $Port
     *
     * @return \MOC\V\Component\Database\Component\IBridgeInterface
     * @throws DatabaseException
     */
    final public static function extensionDatabase( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        try {
            return Database::getDatabase( $Username, $Password, $Database, $Driver, $Host, $Port );
        } catch( \Exception $E ) {
            throw new DatabaseException( $E->getMessage(), $E->getCode(), $E );
        }
    }

    /**
     * @return \MOC\V\Core\HttpKernel\Component\IBridgeInterface
     */
    final public static function extensionRequest()
    {

        if (null === self::$extensionRequestCache) {
            self::$extensionRequestCache = HttpKernel::getRequest();
        }
        return self::$extensionRequestCache;
    }

    /**
     * @return Converter
     */
    final public static function extensionMarkdownify()
    {

        return new Converter();
    }
}
