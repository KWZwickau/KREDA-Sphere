<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\AbstractParameter;

/**
 * Class AbstractIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository
 */
abstract class AbstractIcon extends AbstractParameter implements IParameterInterface
{

    const ICON_LOCK = 'glyphicon glyphicon-lock';
    const ICON_HOME = 'glyphicon glyphicon-home';
    const ICON_STATISTIC = 'glyphicon glyphicon-stats';
    const ICON_COG = 'halflings halflings-cog';
    const ICON_COGWHEELS = 'glyphicons glyphicons-cogwheels';
    const ICON_WRENCH = 'glyphicons glyphicons-wrench';
    const ICON_QUESTION = 'glyphicon glyphicon-question-sign';
    const ICON_TIME = 'glyphicon glyphicon-time';
    const ICON_GROUP = 'glyphicons glyphicons-group';
    const ICON_PERSON = 'glyphicon glyphicon-user';
    const ICON_PERSON_KEY = 'glyphicons glyphicons-user-key';
    const ICON_WHEELCHAIR = 'glyphicons glyphicons-person-wheelchair';
    const ICON_TASK = 'glyphicon glyphicon-tasks';
    const ICON_OFF = 'glyphicon glyphicon-off';
    const ICON_BOOK = 'glyphicon glyphicon-book';
    const ICON_BRIEFCASE = 'glyphicon glyphicon-briefcase';
    const ICON_HISTORY = 'glyphicons glyphicons-history';
    const ICON_CHILD = 'glyphicons glyphicons-child';

    const ICON_MONEY = 'glyphicons glyphicons-money';
    const ICON_MONEY_EURO = 'halflings halflings-euro';

    const ICON_TILE_BIG = 'glyphicon glyphicon-th-large';
    const ICON_TILE_SMALL = 'glyphicon glyphicon-th';
    const ICON_TILE_LIST = 'glyphicon glyphicon-th-list';

    const ICON_TAG = 'glyphicon glyphicon-tag';
    const ICON_TAG_LIST = 'glyphicon glyphicon-tags';

    const ICON_YUBIKEY = 'glyphicons glyphicons-keys';
    const ICON_CERTIFICATE = 'glyphicon glyphicon-certificate';
    const ICON_FLASH = 'glyphicon glyphicon-flash';
    const ICON_MAP_MARKER = 'glyphicon glyphicon-map-marker';

    const ICON_EDUCATION = 'halflings halflings-education';
    const ICON_EYE_OPEN = 'halflings halflings-eye-open';

    const ICON_CLUSTER = 'glyphicons glyphicons-cluster';
    const ICON_SERVER = 'glyphicons glyphicons-server';
    const ICON_DATABASE = 'glyphicons glyphicons-database';
    const ICON_SHARE = 'glyphicons glyphicons-share-alt';
    const ICON_BUILDING = 'glyphicons glyphicons-building';
    const ICON_CONVERSATION = 'glyphicons glyphicons-conversation';
    const ICON_NAMEPLATE = 'glyphicons glyphicons-nameplate';
    const ICON_REPEAT = 'glyphicons glyphicons-repeat';
    const ICON_WARNING = 'glyphicons glyphicons-warning-sign';
    const ICON_OK = 'glyphicons glyphicons-ok';
    const ICON_PENCIL = 'glyphicons glyphicons-pencil';
    const ICON_EDIT = 'glyphicons glyphicons-pencil';
    const ICON_REMOVE = 'glyphicons glyphicons-remove-2';
    const ICON_TRANSFER = 'glyphicons glyphicons-transfer';

    const ICON_DISABLE = 'halflings halflings-remove-circle';
    const ICON_ENABLE = 'halflings halflings-ok-circle';

    const ICON_BARCODE = 'halflings halflings-barcode';
    const ICON_QRCODE = 'halflings halflings-qrcode';

    const ICON_TEMPLE_CHURCH = 'glyphicons glyphicons-temple-christianity-church';

    const ICON_FILETYPE_XLS = 'filetypes filetypes-xls';

    const ICON_DOWNLOAD = 'glyphicons glyphicons-cloud-download';
    const ICON_UPLOAD = 'glyphicons glyphicons-cloud-upload';

    const ICON_PLUS = 'glyphicon glyphicon-plus';
    const ICON_MINUS = 'glyphicon glyphicon-minus';
    const ICON_LIST = 'glyphicon glyphicon-list';
    const ICON_CHEVRON_LEFT = 'glyphicon glyphicon-chevron-left';
    const ICON_CHEVRON_RIGHT = 'glyphicon glyphicon-chevron-right';
    const ICON_BASKET = 'glyphicon glyphicon-shopping-cart';
    const ICON_SELECT = 'glyphicon glyphicon-screenshot';
    const ICON_QUANTITY = 'glyphicon glyphicon-asterisk';
    const ICON_COMMODITY = 'glyphicon glyphicon-inbox';
    const ICON_COMMODITY_ITEM = 'glyphicon glyphicon-list-alt';
    const ICON_DOCUMENT = 'glyphicon glyphicon-file';
    const ICON_FOLDER_OPEN = 'glyphicon glyphicon-folder-open';
    const ICON_INFO = 'glyphicons glyphicons-circle-info';

    /** @var string $Value */
    private $Value = '';

    /**
     * @return string
     */
    function __toString()
    {

        return '<span class="'.$this->getValue().'"></span>';
    }

    /**
     * @return null|string
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param null|string $Value
     */
    protected function setValue( $Value )
    {

        $this->Value = $Value;
    }

}
