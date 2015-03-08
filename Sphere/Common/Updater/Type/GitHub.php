<?php
namespace KREDA\Sphere\Common\Updater\Type;

use KREDA\Sphere\Common\Updater\Component\CurlHandler;
use KREDA\Sphere\Common\Updater\Component\ZipArchive;
use KREDA\Sphere\Common\Updater\IUpdaterInterface;

/**
 * Class GitHub
 *
 * @package KREDA\Sphere\Common\Updater\Type
 */
class GitHub extends CurlHandler implements IUpdaterInterface
{

    /**
     * @param null|string $CacheLocation
     *
     * @throws \Exception
     */
    function __construct( $CacheLocation = null )
    {

        parent::__construct( $CacheLocation );
    }

    /**
     * @param $Location
     *
     * @return string
     * @throws \KREDA\Sphere\Common\Updater\Exception\ExtractException
     */
    public function extractArchive( $Location )
    {

        $Archive = new ZipArchive( $Location );
        return $Archive->extractTo( $this->getCache() );
    }

    /**
     * @param $source
     * @param $destination
     *
     * @return integer
     */
    public function moveFilesRecursive( $source, $destination )
    {

        $result = true;

        if (file_exists( $source ) && is_dir( $source )) {
            if (!file_exists( $destination )) {
                mkdir( $destination );
            }

            $files = scandir( $source );
            foreach ($files as $file) {
                if (in_array( $file, array( ".", ".." ) )) {
                    continue;
                }

                if (is_dir( $source.DIRECTORY_SEPARATOR.$file )) {
                    $result = $this->moveFilesRecursive(
                        $source.DIRECTORY_SEPARATOR.$file,
                        $destination.DIRECTORY_SEPARATOR.$file
                    );
                } else {
                    if (pathinfo( $destination.DIRECTORY_SEPARATOR.$file, PATHINFO_EXTENSION ) == 'ini'
                        && file_exists( $destination.DIRECTORY_SEPARATOR.$file )
                    ) {
                        $New = parse_ini_file( $source.DIRECTORY_SEPARATOR.$file, true );
                        $Current = parse_ini_file( $destination.DIRECTORY_SEPARATOR.$file, true );
                        $Ini = $this->mergeIni( $Current, $New );
                        $Ini = $this->joinIni( $Ini );
                        $result = ( file_put_contents( $destination.DIRECTORY_SEPARATOR.$file, $Ini ) ? 1 : 0 );
                    } else {
                        $result = copy(
                            $source.DIRECTORY_SEPARATOR.$file,
                            $destination.DIRECTORY_SEPARATOR.$file
                        );
                    }
                    unlink( $source.DIRECTORY_SEPARATOR.$file );
                }

                if (!$result) {
                    break;
                }
            }
        }

        $this->removeDir( $source );

        return ( $result ? 1 : 0 );
    }

    /**
     * @param array $Current
     * @param array $New
     *
     * @return array
     */
    private function mergeIni( $Current, $New )
    {

        foreach ($New AS $Key => $Value) {
            if (is_array( $Value )) {
                $Current[$Key] = $this->mergeIni( $Current[$Key], $New[$Key] );
            } else {
                if (!isset( $Current[$Key] )) {
                    $Current[$Key] = $Value;
                }
            }
        }
        return $Current;
    }

    /**
     * @param array $Ini
     * @param array $Parent
     *
     * @return string
     */
    private function joinIni( $Ini, $Parent = array() )
    {

        $Result = '';
        foreach ($Ini as $Key => $Value) {
            if (is_array( $Value )) {
                $Section = array_merge( (array)$Parent, (array)$Key );
                $Result .= '['.join( '.', $Section ).']'.PHP_EOL;
                $Result .= $this->joinIni( $Value, $Section );
            } else {
                $Result .= "$Key=\"$Value\"".PHP_EOL;
            }
        }
        return $Result;
    }

    /**
     * @param $Dir
     */
    private function removeDir( $Dir )
    {

        if (is_dir( $Dir )) {
            $ObjectList = scandir( $Dir );
            foreach ($ObjectList as $Object) {
                if ($Object != "." && $Object != "..") {
                    if (filetype( $Dir."/".$Object ) == "dir") {
                        $this->removeDir( $Dir."/".$Object );
                    } else {
                        unlink( $Dir."/".$Object );
                    }
                }
            }
            reset( $ObjectList );
            rmdir( $Dir );
        }
    }

    /**
     * @return string
     */
    public function getNextVersion()
    {

        $Tags = $this->fetchTags();
        $NextVersion = $this->getCurrentVersion();
        foreach ((array)$Tags as $Tag) {
            if ($this->compareVersion( $Tag['name'], $this->getCurrentVersion() )) {
                $NextVersion = $Tag['name'];
            }
        }
        return $NextVersion;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {

        return $this->Version;
    }

    /**
     * @param string $Current
     * @param string $New
     *
     * @return bool
     */
    public function compareVersion( $New, $Current )
    {

        return version_compare( $New, $Current, '>' );
    }

    /**
     * @return string
     */
    public function getLatestVersion()
    {

        $Tags = $this->fetchTags();
        foreach ((array)$Tags as $Tag) {
            if ($this->compareVersion( $Tag['name'], $this->getCurrentVersion() )) {
                return $Tag['name'];
            }
        }
        return $this->getCurrentVersion();
    }

    /**
     * @return string|array
     */
    public function getAvailableVersions()
    {

        $Tags = $this->fetchTags();
        foreach ((array)$Tags as $Index => $Tag) {
            if (!$this->compareVersion( $Tag['name'], $this->getCurrentVersion() )) {
                $Tags[$Index] = false;
            } else {
                $Message = $this->fetchMessage( $Tag['commit']['sha'] );
                $Tags[$Index] = array( 'name' => $Tag['name'], 'message' => $Message );
            }
        }
        $Tags = array_filter( $Tags );
        if (empty( $Tags )) {
            return $this->getCurrentVersion();
        } else {
            return $Tags;
        }
    }
}
