<?php
namespace KREDA\Sphere\Application\Transfer\Module\Import;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\FileUpload;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use KREDA\Sphere\Common\AbstractApplication;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Payment
 *
 * @package KREDA\Sphere\Application\Transfer\Module\Import
 */
class Payment extends AbstractApplication
{
    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Import/Payment',
            __CLASS__.'::frontendImport' )
            ->setParameterDefault( 'File', null );
        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Import/Payment/Column/Select',
            __CLASS__.'::frontendImportColumnSelect' )
            ->setParameterDefault( 'FilePath', null )
            ->setParameterDefault( 'Data', null );
    }

    /**
     * @param $File
     *
     * @return Stage
     * @throws \MOC\V\Component\Document\Exception\DocumentTypeException
     */
    public static function frontendImport( $File )
    {
        $View = new Stage();
        $View->setTitle( 'Import' );
        $View->setDescription( 'Bezahldaten' );
        $View->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn( array(
                            $Form = new Form(
                                new FormGroup(
                                    new FormRow(
                                        new FormColumn(
                                            new FileUpload( 'File', 'Datei auswählen', 'Datei auswählen', null,
                                                array( 'showPreview' => false ) )
                                        )
                                    )
                                )
                                , new SubmitPrimary( 'Hochladen' ) )
                        ,
                            new Warning( 'Erlaubte Dateitypen: Excel (XLS,XLSX)' )
                        ) )
                    )
                )
            )
        );

        /** @var UploadedFile $File */
        if (null !== $File) {
            if ($File->getError()) {
                $Form->setError( 'File', 'Fehler' );
            } else {

                /**
                 * Prepare
                 */
                $File = $File->move( $File->getPath(), $File->getFilename().'.'.$File->getClientOriginalExtension() );
                /**
                 * Read
                 */
                /** @var PhpExcel $Document */
                $Document = Document::getDocument( $File->getPathname() );

                $X = $Document->getSheetColumnCount();
                $Y = $Document->getSheetRowCount();

                /**
                 * Header -> Location
                 */
                $Location = array(
                    'Datum'          => null,
                    'Betrag'                   => null,
                    'Buchungstext'                => null,
                );
                for ($RunX = 0; $RunX < $X; $RunX++) {
                    $Value = $Document->getValue( $Document->getCell( $RunX, 0 ) );
                    if (array_key_exists( $Value, $Location )) {
                        $Location[$Value] = $RunX;
                    }
                }

                /**
                 * Import
                 */
                if (!in_array( null, $Location, true ))
                {
                    for ($RunY = 1; $RunY < $Y; $RunY++)
                    {
                        $postingText = trim( $Document->getValue(
                            $Document->getCell( $Location['Buchungstext'], $RunY )
                        ) );
                        $invoiceNumber = substr($postingText, strlen('RN:'), strpos($postingText, ' ') - strlen('RN:'));
                        $tblInvoice = Billing::serviceInvoice()->entityInvoiceByNumber($invoiceNumber);

                        if ($tblInvoice)
                        {
                            $tblBalance = Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice );

                            $date = trim( $Document->getValue(
                                $Document->getCell( $Location['Datum'], $RunY )
                            ) );

                            $value = trim( $Document->getValue(
                                $Document->getCell( $Location['Betrag'], $RunY )
                            ) );

                            Billing::serviceBalance()->actionCreatePayment($tblBalance, $value, new \DateTime($date));
                        }
                    }
                }
                else
                {
                    //ToDo map columns
                    $View->setContent( new Redirect( '/Sphere/Transfer/Import/Payment/Column/Select', 2, array(
                        'FilePath' => $File->getPathname()
                    ) ) );
                }
            }
        }

        return $View;
    }

    /**
     * @param $FilePath
     * @param $Data
     *
     * @return Stage
     */
    public static function frontendImportColumnSelect( $FilePath, $Data )
    {
        $View = new Stage();
        $View->setTitle( 'Import' );
        $View->setDescription( 'Bezahldaten' );

        print_r($FilePath);

        /** @var UploadedFile $File */
        if (null !== $FilePath )
        {
            /**
             * Read
             */
            /** @var PhpExcel $Document */
            $Document = Document::getDocument( $FilePath );

            $X = $Document->getSheetColumnCount();
            $Y = $Document->getSheetRowCount();

            $Location = array(
                'Datum'          => null,
                'Betrag'                   => null,
                'Buchungstext'                => null,
            );

            $positionList = array();
            for ($RunX = 0; $RunX < $X; $RunX++)
            {
                $Value = $Document->getValue( $Document->getCell( $RunX, 0 ) );
                if (array_key_exists( $Value, $Location ))
                {
                    $Location[$Value] = $RunX;
                }
                $item = new TblCompany();
                $item->setId($RunX+1); // 0 gleich nicht ausgewählt
                $item->setName($Value);
                array_push($positionList, $item);
                //array_push($positionList, array('Id' => $RunX, 'Name' => $Value));
            }

            print_r($Location);
            print_r('<br>');

            $rowList = array();

            foreach ($Location as $Key => $Value)
            {
                $row = new FormRow(  array(
                    new FormColumn(
                        new Muted($Key),4
                    ),
                    new FormColumn(
                        new SelectBox( 'Data['. $Key .']', null,
                            array('Name'  => $positionList )), 4
                    )
                ));
                array_push($rowList, $row);
            }

            $formGroup = new FormGroup($rowList); //, new FormTitle(" "));

            $View->setContent(
                self::executeCheckColumns(
                    new Form( array(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new Muted('Spaltenname'),4
                                    ),
                                    new FormColumn(
                                        new Muted('Spaltenname'),4
                                    ),
                                ) )
                            ) ),
                            $formGroup)
                        , new SubmitPrimary( 'Spalten zuordnen' ) )
                    , $FilePath, $Data
                )
            );
        }

        return $View;
    }

    /**
     * @param AbstractType $View
     * @param $FilePath
     * @param $Data
     *
     * @return AbstractType
     */
    private static function executeCheckColumns(
        AbstractType &$View = null,
        $FilePath,
        $Data
    )
    {
        /**
         * Skip to Frontend
         */
        if (null === $Data
        ) {
            return $View;
        }

        if (null !== $FilePath )
        {
            print_r($Data);
            /**
             * Read
             */
            /** @var PhpExcel $Document */
            $Document = Document::getDocument( $FilePath );

            $Y = $Document->getSheetRowCount();

            $Location = array(
                'Datum' => $Data['Datum'] !== 0 ? $Data['Datum'] - 1: null,
                'Betrag' => $Data['Betrag'] !== 0 ? $Data['Betrag'] - 1: null,
                'Buchungstext' => $Data['Buchungstext'] !== 0 ? $Data['Buchungstext'] - 1: null,
            );

            /**
             * Import
             */
            if (!in_array( null, $Location, true ))
            {
                print_r("Success");
                for ($RunY = 1; $RunY < $Y; $RunY++)
                {
                    $postingText = trim( $Document->getValue(
                        $Document->getCell( $Location['Buchungstext'], $RunY )
                    ) );
                    $invoiceNumber = substr($postingText, strlen('RN:'), strpos($postingText, ' ') - strlen('RN:'));
                    $tblInvoice = Billing::serviceInvoice()->entityInvoiceByNumber($invoiceNumber);

                    if ($tblInvoice)
                    {
                        $tblBalance = Billing::serviceBalance()->entityBalanceByInvoice( $tblInvoice );

                        $date = trim( $Document->getValue(
                            $Document->getCell( $Location['Datum'], $RunY )
                        ) );

                        $value = trim( $Document->getValue(
                            $Document->getCell( $Location['Betrag'], $RunY )
                        ) );

                        Billing::serviceBalance()->actionCreatePayment($tblBalance, $value, new \DateTime($date));
                    }
                }
            }
        }

        return $View;
    }
}
