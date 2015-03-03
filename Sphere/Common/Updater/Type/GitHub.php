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
     * @throws \Exception
     */
    function __construct()
    {

        parent::__construct();
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
