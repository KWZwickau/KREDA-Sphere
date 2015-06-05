<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblInvoiceItem")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblInvoiceItem extends AbstractEntity
{

    const ATTR_TBL_INVOICE = 'tblInvoice';

    /**
     * @Column(type="string")
     */
    protected $CommodityDescription;

    /**
     * @Column(type="string")
     */
    protected $CommodityName;

    /**
     * @Column(type="string")
     */
    protected $ItemDescription;

    /**
     * @Column(type="string")
     */
    protected $ItemName;

    /**
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $ItemPrice;

    /**
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $ItemQuantity;

    /**
     * @Column(type="bigint")
     */
    protected $tblInvoice;

    /**
     * @return string
     */
    public function getCommodityDescription()
    {

        return $this->CommodityDescription;
    }

    /**
     * @param string $CommodityDescription
     */
    public function setCommodityDescription( $CommodityDescription )
    {

        $this->CommodityDescription = $CommodityDescription;
    }

    /**
     * @return string
     */
    public function getCommodityName()
    {

        return $this->CommodityName;
    }

    /**
     * @param string $CommodityName
     */
    public function  setCommodityName( $CommodityName )
    {

        $this->CommodityName = $CommodityName;
    }

    /**
     * @return string
     */
    public function getItemDescription()
    {

        return $this->ItemDescription;
    }

    /**
     * @param string $ItemDescription
     */
    public function setItemDescription( $ItemDescription )
    {

        $this->ItemDescription = $ItemDescription;
    }

    /**
     * @return string
     */
    public function getItemName()
    {

        return $this->ItemName;
    }

    /**
     * @param string $ItemName
     */
    public function  setItemName( $ItemName )
    {

        $this->ItemName = $ItemName;
    }

    /**
     * @return (type="decimal", precision=14, scale=4)
     */
    public function getItemPrice()
    {

        return $this->ItemPrice;
    }

    /**
     * @param (type="decimal", precision=14, scale=4) $ItemPrice
     */
    public function setItemPrice( $ItemPrice )
    {

        $this->ItemPrice = $ItemPrice;
    }

    /**
     * @return (type="decimal", precision=14, scale=4)
     */
    public function getItemQuantity()
    {

        return $this->ItemQuantity;
    }

    /**
     * @param (type="decimal", precision=14, scale=4) $ItemQuantity
     */
    public function setItemQuantity( $ItemQuantity )
    {

        $this->ItemQuantity = $ItemQuantity;
    }

    /**
     * @return bool|TblInvoice
     */
    public function getTblInvoice()
    {

        if (null === $this->tblInvoice) {
            return false;
        } else {
            return Billing::serviceInvoice()->entityInvoiceById( $this->tblInvoice );
        }
    }

    /**
     * @param null|TblInvoice $tblInvoice
     */
    public function setTblInvoice( TblInvoice $tblInvoice = null )
    {

        $this->tblInvoice = ( null === $tblInvoice ? null : $tblInvoice->getId() );
    }
}
