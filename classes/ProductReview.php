<?php
if (!defined('_PS_VERSION_'))
    exit;

class ProductReview extends ObjectModel
{
    public $id_review;
    public $id_shop;
    public $id_product;
    public $id_customer;
    public $rating;
    public $title;
    public $content;
    public $date_add;
    public $status;

    public static $definition = array(
        'table' => 'product_review',
        'primary' => 'id_review',
        'multilang' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'rating' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'content' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => false),
            'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool')
        )
    );

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DECLINED = 2;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function add($autodate = true, $null_values = false)
    {
        if ($autodate && property_exists($this, 'date_add')) {
            $this->date_add = date('Y-m-d H:i:s');
        }
        
        if (!isset($this->status) || $this->status === null) {
            $this->status = self::STATUS_PENDING;
        }
        
        return parent::add($autodate, $null_values);
    }

    public static function getReviewsByProduct($id_product, $approved_only = true)
    {
        $sql = new DbQuery();
        $sql->select('pr.*, c.firstname, c.lastname');
        $sql->from('product_review', 'pr');
        $sql->leftJoin('customer', 'c', 'c.id_customer = pr.id_customer');
        $sql->where('pr.id_product = '.(int)$id_product);
        
        if ($approved_only) {
            $sql->where('pr.status = ' . self::STATUS_APPROVED);
        }
        
        $sql->orderBy('pr.date_add DESC');
        
        return Db::getInstance()->executeS($sql);
    }

    public static function getCustomerReviewStatus($id_product, $id_customer)
    {
        $sql = new DbQuery();
        $sql->select('status');
        $sql->from('product_review');
        $sql->where('id_product = '.(int)$id_product);
        $sql->where('id_customer = '.(int)$id_customer);
        $sql->orderBy('date_add DESC');
        $sql->limit(1);
        
        $result = Db::getInstance()->getValue($sql);
        
        if ($result === false) {
            return false; // No review exists
        }
        
        return (int)$result;
    }

    public static function hasApprovedReview($id_product, $id_customer)
    {
        return self::getCustomerReviewStatus($id_product, $id_customer) === self::STATUS_APPROVED;
    }

    public static function hasPendingReview($id_product, $id_customer)
    {
        return self::getCustomerReviewStatus($id_product, $id_customer) === self::STATUS_PENDING;
    }

    public static function canWriteReview($id_product, $id_customer)
    {
        $status = self::getCustomerReviewStatus($id_product, $id_customer);
        return $status === false || $status === self::STATUS_DECLINED;
    }
}
