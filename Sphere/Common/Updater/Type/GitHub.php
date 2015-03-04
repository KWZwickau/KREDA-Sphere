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
     * @return bool
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
                    $result = copy(
                        $source.DIRECTORY_SEPARATOR.$file,
                        $destination.DIRECTORY_SEPARATOR.$file
                    );
                    unlink( $source.DIRECTORY_SEPARATOR.$file );
                }

                if (!$result) {
                    break;
                }
            }
        }

        rmdir( $source );

        return $result;
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
     * @return string
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
