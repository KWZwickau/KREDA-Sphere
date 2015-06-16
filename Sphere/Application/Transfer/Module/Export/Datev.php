<?php
namespace KREDA\Sphere\Application\Transfer\Module\Export;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Common\AbstractApplication;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use KREDA\Sphere\Client\Configuration;
use MOC\V\Component\Document\Document;

/**
 * Class FuxMedia
 *
 * @package KREDA\Sphere\Application\Transfer\Module\Import
 */
class Datev extends AbstractApplication
{
    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Export/Datev',
            __CLASS__.'::frontendExportDatev' )
            ->setParameterDefault( 'File', null );
    }

    /**
     * @return Stage
     */
    public static function frontendExportDatev()
    {
        $View = new Stage();
        $View->setTitle('Export');

        /** @var PhpExcel $export */
        $export = Document::getDocument("Datev.xls");
        $export->setValue($export->getCell("0","0"), "Schülernummer");
        $export->setValue($export->getCell("1","0"), "Schulgeld");
        $export->setValue($export->getCell("2","0"), "Mandat");
        $export->setValue($export->getCell("3","0"), "Belegdat");
        $export->setValue($export->getCell("4","0"), "Kto");
        $export->setValue($export->getCell("5","0"), "Kost1");
        $export->setValue($export->getCell("6","0"), "S/H");
        $export->setValue($export->getCell("7","0"), "GB");
        $export->setValue($export->getCell("8","0"), "Fälligkeit");
        $export->setValue($export->getCell("9","0"), "Buchungstext");

        $invoiceAllByIsConfirmedState = Billing::serviceInvoice()->entityInvoiceAllByIsConfirmedState(true);
        $invoiceAllByIsVoidState = Billing::serviceInvoice()->entityInvoiceAllByIsVoidState(true);
        $invoiceAllByIsPaidState = Billing::serviceInvoice()->entityInvoiceAllByIsPaidState(true);
        $invoiceHasFullPaymentAll = Billing::serviceBalance()->entityInvoiceHasFullPaymentAll();

        if ($invoiceAllByIsConfirmedState && $invoiceAllByIsVoidState)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceAllByIsVoidState,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }
        if ($invoiceAllByIsConfirmedState && $invoiceAllByIsPaidState)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceAllByIsPaidState,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }
        if ($invoiceAllByIsConfirmedState && $invoiceHasFullPaymentAll)
        {
            $invoiceAllByIsConfirmedState = array_udiff($invoiceAllByIsConfirmedState, $invoiceHasFullPaymentAll,
                function(TblInvoice $invoiceA, TblInvoice $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }
        $counting = count($invoiceAllByIsConfirmedState);

        $Billing[] = array();
        $DebtorNumber[] = array();
        $IBAN[] = array();
//        $Date = new \DateTime( 'now');
//        $ExportDate = $Date->format( 'd.m.Y H:i:s' );

        $InvoiceDate[] = array();
        $InvoiceNumber[] = array();
        $DebtorName[] = array();
        $StudentNumber[] = array();
        $Commodity[] = array();
        $CommodityPay[] = array();
        $BillDate[] = array();
        $PaymentDate[] = array();

        if (!empty($invoiceAllByIsConfirmedState))
        {
            $j = 0;
            foreach($invoiceAllByIsConfirmedState as $invoiceByIsConfirmedState)
            {
                $Billing[$j] = Billing::serviceInvoice()->sumPriceItemAllStringByInvoice( $invoiceByIsConfirmedState );
                $DebtorNumber[$j] = $invoiceByIsConfirmedState->getDebtorNumber();
                $IBANTemp = Billing::serviceBanking()->entityDebtorByDebtorNumber( $DebtorNumber[$j] )->getIBAN();
                $IBAN[$j] = substr($IBANTemp,12,10);
                $InvoiceDate[$j] = $invoiceByIsConfirmedState->getInvoiceDate();
                $InvoiceNumber[$j] = $invoiceByIsConfirmedState->getNumber();
                $DebtorName[$j] = $invoiceByIsConfirmedState->getDebtorFullName();

                $tblPerson = Management::servicePerson()->entityPersonById( $invoiceByIsConfirmedState->getServiceManagementPerson() );
                $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );
                $StudentNumber[$j] = $tblStudent->getStudentNumber();

                $tblInvoiceItemAll = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $invoiceByIsConfirmedState );
                $p = 0;
                $CommodityTemp[] = array();
                $CommodityPayTemp[] = array();
                foreach( $tblInvoiceItemAll as $tblInvoiceItem )
                {
                    $CommodityTemp[$p] = $tblInvoiceItem->getCommodityName();

                    $CommodityPayTemp[$p] = Billing::serviceCommodity()->entityItemByName( $tblInvoiceItem->getItemName() )->getCostUnit() ;
                    $p++;
                }
                $Commodity[$j] = $CommodityTemp[0];
                $CommodityPay[$j] = $CommodityPayTemp[0];

                $PaymentDate[$j] = $invoiceByIsConfirmedState->getPaymentDate();

                $j++;
            }
        }

        for($i = 0;$i < $counting; $i++)                                        //ToDo Vervollständigen
        {
            $Row = $i+1;
            $export->setValue($export->getCell("0",$Row), $StudentNumber[$i]);  //Schülernummer
            $export->setValue($export->getCell("1",$Row), $Billing[$i]);        //Schulgeld
            $export->setValue($export->getCell("2",$Row), "");                  //Mandant // Mandats Ref.
            $export->setValue($export->getCell("3",$Row), $InvoiceDate[$i]);    //Belegdatum Rechnungsdatum - Vorlaufzeit
            $export->setValue($export->getCell("4",$Row), $IBAN[$i]);           //Kontonummer
            $export->setValue($export->getCell("5",$Row), $CommodityPay[$i]);   // Kostenstelle (wird angefragt)
            $export->setValue($export->getCell("6",$Row), "");                  // S/H?(wird angefragt)
            $export->setValue($export->getCell("7",$Row), "");                  // GB?(wird angefragt)
            $export->setValue($export->getCell("8",$Row), $PaymentDate[$i]);    //Fälligkeitsdatum
            $export->setValue($export->getCell("9",$Row), $InvoiceNumber[$i].", ".$Commodity[$i] );  //Buchungstext

        }
        $export->saveFile();

        $View->setContent(
            new Primary('Zurück zur Übersicht','/Sphere/Transfer')
        );

        return $View;
    }
}