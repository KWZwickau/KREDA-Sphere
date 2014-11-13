<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component;

/**
 * Class KeyValue
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey
 */
class KeyValue
{

    /** @var string $KeyOTP */
    private $KeyOTP = '';
    /** @var string $KeyNOnce */
    private $KeyNOnce = '';

    /**
     * @param string $KeyOTP
     */
    function __construct( $KeyOTP )
    {

        $this->KeyOTP = $KeyOTP;
    }

    /**
     * @return string
     */
    public function getKeyOTP()
    {

        return $this->KeyOTP;
    }

    /**
     * @return string
     */
    public function getKeyNOnce()
    {

        return $this->KeyNOnce;
    }

    /**
     * @param string $KeyNOnce
     */
    public function setKeyNOnce( $KeyNOnce )
    {

        $this->KeyNOnce = $KeyNOnce;
    }
}
