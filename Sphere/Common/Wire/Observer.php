<?php
namespace KREDA\Sphere\Common\Wire;

/**
 * Class Observer
 *
 * @package KREDA\Sphere\Common\Wire
 */
class Observer
{

    /** @var Plug[][] $Listener */
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

        if (!in_array( $Plug->getWire(), array_keys( self::$Listener[$this->getWire()] ) )) {
            self::$Listener[$this->getWire()][$Plug->getWire()] = $Plug;
        }
        return $this;
    }

    /**
     * @param Data $Data
     *
     * @return bool|Effect
     */
    public function sendWire( Data $Data )
    {

        $Switchboard = self::$Listener[$this->getWire()];
        $Return = array();
        /** @var Plug $Plug */
        foreach ((array)$Switchboard as $Plug) {
            $Return[$Plug->getWire()] = call_user_func_array(
                array( $Plug->getClass()->getName(), $Plug->getMethod()->getShortName() ),
                array( $Data )
            );
        }
        if (empty( $Return )) {
            return true;
        } else {
            if (in_array( false, $Return )) {
                /**
                 * Permission denied
                 */
                return false;
            } else {
                /**
                 * All green?
                 */
                foreach ($Return as $Wire => $Listener) {
                    if (true === $Listener) {
                        unset( $Return[$Wire] );
                    }
                }
                /**
                 * All green!
                 */
                if (empty( $Return )) {
                    return true;
                }
            }
        }
        /**
         * It's red! :-/ .. return content for Stage
         */
        return new Effect( $Return );
    }
}
