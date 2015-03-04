<?php
namespace KREDA\Sphere\Common\Updater\Component;

use Github\Api\Repo;
use KREDA\Sphere\Common\AbstractExtension;

/**
 * Class GitApi
 *
 * @package KREDA\Sphere\Common\Updater\Component
 */
class GitApi extends AbstractExtension
{

    /** @var null|string $Version */
    protected $Version = '0.0.0-Install';
    /** @var null|string $User */
    private $User = null;
    /** @var null|string $Repository */
    private $Repository = null;

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/GitHub.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['User'] )) {
                $this->User = $Setting['User'];
            }
            if (isset( $Setting['Repository'] )) {
                $this->Repository = $Setting['Repository'];
            }
            if (isset( $Setting['Version'] )) {
                $this->Version = $Setting['Version'];
            }
        } else {
            throw new \Exception( 'Missing Updater-Configuration for '.get_class( $this ) );
        }
    }


    /**
     * @return array
     */
    public function fetchTags()
    {

        /** @var Repo $Api */
        $Api = $this->extensionGitHub()->api( 'repo' );
        return $Api->tags( $this->User, $this->Repository );
    }


    /**
     * @return array
     */
    public function fetchReleases()
    {

        /** @var Repo $Api */
        $Api = $this->extensionGitHub()->api( 'repo' );
        return $Api->releases()->all( $this->User, $this->Repository );
    }

    /**
     * @param string $Identifier
     *
     * @return string
     */
    public function fetchMessage( $Identifier )
    {

        $Commit = $this->fetchCommit( $Identifier );
        return $Commit['commit']['message'];
    }

    /**
     * @param string $Identifier
     *
     * @return array
     */
    public function fetchCommit( $Identifier )
    {

        /** @var Repo $Api */
        $Api = $this->extensionGitHub()->api( 'repo' );
        return $Api->commits()->show( $this->User, $this->Repository, $Identifier );
    }
}
