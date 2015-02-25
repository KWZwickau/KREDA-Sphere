<?php
namespace KREDA\Sphere\Common\Proxy\Type;

use KREDA\Sphere\Common\Proxy\IProxyInterface;

/**
 * Class HttpProxy
 *
 * @package KREDA\Sphere\Common\Proxy
 */
class HttpProxy implements IProxyInterface
{

    /** @var null|string $Host */
    private $Host = null;
    /** @var null|string $Port */
    private $Port = null;
    /** @var null|string $Username */
    private $Username = null;
    /** @var null|string $Password */
    private $Password = null;

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/HttpProxy.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['Host'] )) {
                $this->Host = $Setting['Host'];
            }
            if (isset( $Setting['Port'] )) {
                $this->Port = $Setting['Port'];
            }
            if (isset( $Setting['Username'] )) {
                $this->Username = $Setting['Username'];
            }
            if (isset( $Setting['Password'] )) {
                $this->Password = $Setting['Password'];
            }
        } else {
            throw new \Exception( 'Missing Proxy-Configuration for '.get_class( $this ) );
        }

    }

    /**
     * @return null|string
     */
    public function getHost()
    {

        return $this->Host;
    }

    /**
     * @return null|string
     */
    public function getPort()
    {

        return $this->Port;
    }

    /**
     * @return null|string
     */
    public function getUsernamePasswort()
    {

        $UserPass = $this->getUsername().':'.$this->getPassword();
        if ($UserPass == ':') {
            return null;
        } else {
            return $UserPass;
        }
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {

        return $this->Username;
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {

        return $this->Password;
    }
}
