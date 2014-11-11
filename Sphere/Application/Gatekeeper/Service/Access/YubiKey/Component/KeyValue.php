<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component;

/**
 * Class KeyValue
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
     * @param string $KeyCipher
     */
    function __construct($KeyOTP, $KeyCipher)
    {
        $this->KeyOTP = $KeyOTP;
        $this->KeyCipher = $KeyCipher;
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
    public function setKeyNOnce($KeyNOnce)
    {
        $this->KeyNOnce = $KeyNOnce;
    }
}
