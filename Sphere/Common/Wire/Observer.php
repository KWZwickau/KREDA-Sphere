<?php
namespace KREDA\Sphere\Common\Wire;

/**
 * Class Observer
 *
 * @package KREDA\Sphere\Common\Wire
 */
class Observer
{

    /** @var Plug[] $Listener */
    private static $Listener = array();
    /** @var null|Plug $Plug */
    private $Plug = null;

    /**
     * @param Plug $Plug
     */
    private function __construct( Plug $Plug )
    {

        $this->Plug = $Plug;
        if (!isset( self::$Listener[$this->getWire()] )) {
            self::$Listener[$this->getWire()] = array();
        }
    }

    /**
     * @return string
     */
    private function getWire()
    {

        return $this->Plug->getWire();
    }

    /**
     * @param Plug $Plug
     *
     * @return Observer
     */
    public static function initWire( Plug $Plug )
    {

        return new Observer( $Plug );
    }

    /**
     * @param Plug $Plug
     *
     * @return Observer
     */
    public function plugWire( Plug $Plug )
    {

        self::$Listener[$this->getWire()][] = $Plug;
        return $this;
    }

    public function sendWire( $Data )
    {

        $Switchboard = self::$Listener[$this->getWire()];
        $Return = array();
        /** @var Plug $Plug */
        foreach ((array)$Switchboard as $Plug) {
            $Return[] = call_user_func_array( array( $Plug->getClass()->getName(), $Plug->getMethod()->getShortName() ),
                $Data );
        }
        return $Return;
    }
}
