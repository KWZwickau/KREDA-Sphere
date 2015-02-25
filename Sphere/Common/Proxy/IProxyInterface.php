<?php
namespace KREDA\Sphere\Common\Proxy;

/**
 * Interface IProxyInterface
 *
 * @package KREDA\Sphere\Common\Proxy
 */
interface IProxyInterface
{

    /**
     * @return null|string
     */
    public function getHost();

    /**
     * @return null|string
     */
    public function getPort();

    /**
     * @return null|string
     */
    public function getUsername();

    /**
     * @return null|string
     */
    public function getPassword();
}
