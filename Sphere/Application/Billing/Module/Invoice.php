<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Invoice
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Invoice extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed', 'Freigeben',  new OkIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice', __CLASS__.'::frontendInvoiceList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed', __CLASS__.'::frontendInvoiceIsNotConfirmedList'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Edit', __CLASS__.'::frontendInvoiceEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Data', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Show', __CLASS__.'::frontendInvoiceShow'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Confirm', __CLASS__.'::frontendInvoiceConfirm'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Data', null ) ;
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Cancel', __CLASS__.'::frontendInvoiceCancel'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Pay', __CLASS__.'::frontendInvoicePay'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Item/Edit', __CLASS__.'::frontendInvoiceItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'InvoiceItem', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Item/Remove', __CLASS__.'::frontendInvoiceItemRemove'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Address/Select', __CLASS__.'::frontendInvoiceAddressSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Address/Change', __CLASS__.'::frontendInvoiceAddressChange'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'AddressId', null);
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Payment/Type/Select', __CLASS__.'::frontendInvoicePaymentTypeSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/IsNotConfirmed/Payment/Type/Change', __CLASS__.'::frontendInvoicePaymentTypeChange'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PaymentTypeId', null);
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceList();
    }

    /**
     * @return Stage
     */
    public static function frontendInvoiceIsNotConfirmedList()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceIsNotConfirmedList();
    }

    /**
     * @param $Id
     * @param $Data
     *
     * @return Stage
     */
    public static function frontendInvoiceEdit( $Id, $Data )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceEdit( $Id, $Data );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceShow( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceShow( $Id );
    }

    /**
     * @param $Id
     * @param $Data
     *
     * @return Stage
     */
    public static function frontendInvoiceConfirm( $Id, $Data )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceConfirm( $Id, $Data );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoicePay( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoicePay( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceCancel( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceCancel( $Id );
    }

    /**
     * @param $Id
     * @param $InvoiceItem
     *
     * @return Stage
     */
    public static function frontendInvoiceItemEdit( $Id, $InvoiceItem )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceItemEdit( $Id, $InvoiceItem );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceItemRemove( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceItemRemove( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoiceAddressSelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceAddressSelect( $Id );
    }

    /**
     * @param $Id
     * @param $AddressId
     *
     * @return Stage
     */
    public static function frontendInvoiceAddressChange( $Id, $AddressId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoiceAddressChange( $Id, $AddressId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendInvoicePaymentTypeSelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoicePaymentTypeSelect( $Id );
    }

    /**
     * @param $Id
     * @param $PaymentTypeId
     *
     * @return Stage
     */
    public static function frontendInvoicePaymentTypeChange( $Id, $PaymentTypeId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendInvoicePaymentTypeChange( $Id, $PaymentTypeId );
    }
}
