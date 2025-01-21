<?php
if (!defined('_PS_VERSION_'))
    exit;

require_once(dirname(__FILE__).'/classes/ProductReview.php');

class Productreviews extends Module
{
    public function __construct()
    {
        $this->name = 'productreviews';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Uipko';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Reviews');
        $this->description = $this->l('Allows customers to leave reviews on products');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        // Register AdminProductReviewsController
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminProductReviews';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = 'Product Reviews';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminCatalog');
        $tab->module = $this->name;
        $tab->add();

        // Update database to include new status value
        $sql = "ALTER TABLE `" . _DB_PREFIX_ . "product_review` MODIFY `status` tinyint(1) unsigned NOT NULL DEFAULT '0'";
        Db::getInstance()->execute($sql);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('productFooter') &&
            $this->createTables();
    }

    public function uninstall()
    {
        // Remove AdminProductReviewsController
        $id_tab = (int)Tab::getIdFromClassName('AdminProductReviews');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }

        return parent::uninstall() && $this->dropTables();
    }

    private function createTables()
    {
        $sql_create = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "product_review` (
            `id_review` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_shop` int(10) unsigned NOT NULL DEFAULT '1',
            `id_product` int(10) unsigned NOT NULL,
            `id_customer` int(10) unsigned NOT NULL,
            /* Removed the old rating column */
    
            `rating_effectiveness` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rating_texture` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rating_absorption` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rating_scent` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            `rating_value_for_money` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    
            `title` varchar(128) NOT NULL,
            `content` text NOT NULL,
            `date_add` datetime NOT NULL,
            `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (`id_review`),
            KEY `id_product` (`id_product`),
            KEY `id_customer` (`id_customer`),
            KEY `id_shop` (`id_shop`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";
        
        // 1) Create table if not exists
        $result1 = Db::getInstance()->execute($sql_create);
    
        // 2) In case the columns aren’t added yet (they should be from the create above),
        //    you can attempt an ALTER if needed. This typically won't be necessary
        //    unless you're upgrading an existing install.
        $sql_alter = 'ALTER TABLE `'._DB_PREFIX_.'product_review`
            ADD `rating_effectiveness` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            ADD `rating_texture` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            ADD `rating_absorption` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            ADD `rating_scent` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
            ADD `rating_value_for_money` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0';
    
        try {
            $result2 = Db::getInstance()->execute($sql_alter);
        } catch (Exception $e) {
            // Possibly columns already exist
            $result2 = true;
        }
    
        return ($result1 && $result2);
    }
    


    private function dropTables()
    {
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "product_review`";
        return Db::getInstance()->execute($sql);
    }

    public function hookHeader($params)
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product')
            return;
            
        // Add CSS and JS
        $this->context->controller->addCSS($this->_path.'views/css/productreviews.css', 'all');
        $this->context->controller->addJS($this->_path.'views/js/productreviews.js');
    }

    public function hookProductFooter($params)
    {
        if (!isset($params['product']))
            return;

        $product = $params['product'];
        $customer_id = $this->context->customer->id;

        // Get reviews for this product using the model class
        $reviews = ProductReview::getReviewsByProduct((int)$product->id, true);

        // Get customer's review status for this product
        $can_write_review = ProductReview::canWriteReview((int)$product->id, $customer_id);
        $has_pending_review = ProductReview::hasPendingReview((int)$product->id, $customer_id);
        $has_approved_review = ProductReview::hasApprovedReview((int)$product->id, $customer_id);

        $this->context->smarty->assign(array(
            'reviews' => $reviews,
            'is_logged' => $this->context->customer->isLogged(),
            'product_id' => (int)$product->id,
            'secure_key' => $this->context->customer->secure_key,
            'lang_iso' => $this->context->language->iso_code,
            'can_write_review' => $can_write_review,
            'has_pending_review' => $has_pending_review,
            'has_approved_review' => $has_approved_review,
            'ajax_url' => $this->context->link->getModuleLink('productreviews', 'submit')
        ));

        return $this->display(__FILE__, 'reviews.tpl');
    }
}
