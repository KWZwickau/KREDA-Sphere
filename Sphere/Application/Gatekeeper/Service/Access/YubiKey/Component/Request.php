<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component;

class Request
{

    /** @var \resource[] $CurlHandler */
    private $CurlHandler = array();

    /**
     * @return \resource[]
     */
    public function getCurlHandler()
    {
        return $this->CurlHandler;
    }

    /**
     * @param \resource $CurlHandler
     */
    public function addCurlHandler($CurlHandler)
    {
        $this->CurlHandler[] = $CurlHandler;
    }

}
