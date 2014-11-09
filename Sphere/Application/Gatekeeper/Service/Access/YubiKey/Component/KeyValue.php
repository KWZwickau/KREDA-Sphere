<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component;

/**
 * Class KeyValue
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey
 */
class KeyValue
{
    /** @var string $KeyPassword */
    private $KeyPassword = '';
    /** @var string $KeyOTP */
    private $KeyOTP = '';
    /** @var string $KeyPrefix */
    private $KeyPrefix = '';
    /** @var string $KeyCipher */
    private $KeyCipher = '';
    /** @var string $KeyNOnce */
    private $KeyNOnce = '';

    /**
     * @param string $KeyPassword
     * @param string $KeyOTP
     * @param string $KeyPrefix
     * @param string $KeyCipher
     */
    function __construct($KeyPassword, $KeyOTP, $KeyPrefix, $KeyCipher)
    {
        $this->KeyPassword = $KeyPassword;
        $this->KeyOTP = $KeyOTP;
        $this->KeyPrefix = $KeyPrefix;
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
