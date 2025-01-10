<?php
class AdminProductReviewsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'product_review';
        $this->identifier = 'id_review';
        $this->className = 'ProductReview';
        $this->lang = false;
        
        parent::__construct();
        
        $this->addRowAction('approve');
        $this->addRowAction('decline');
        $this->addRowAction('delete');
        
        $this->fields_list = array(
            'id_review' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'id_product' => array(
                'title' => $this->l('Product'),
                'callback' => 'getProductName'
            ),
            'title' => array(
                'title' => $this->l('Review Title'),
                'width' => 'auto'
            ),
            'rating' => array(
                'title' => $this->l('Rating'),
                'class' => 'fixed-width-sm',
                'align' => 'center',
                'callback' => 'displayRating'
            ),
            'content' => array(
                'title' => $this->l('Content'),
                'width' => 'auto',
                'callback' => 'getPreviewContent'
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'type' => 'datetime'
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'callback' => 'getStatus',
                'class' => 'fixed-width-sm'
            )
        );
        
        $this->bulk_actions = array(
            'approve' => array(
                'text' => $this->l('Approve selected'),
                'icon' => 'icon-check'
            ),
            'decline' => array(
                'text' => $this->l('Decline selected'),
                'icon' => 'icon-times'
            ),
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
    }
    
    public function getProductName($id_product)
    {
        $product = new Product($id_product, false, $this->context->language->id);
        return $product->name;
    }

    public function getPreviewContent($content)
    {
        return Tools::substr($content, 0, 100) . (Tools::strlen($content) > 100 ? '...' : '');
    }

    public function displayRating($rating)
    {
        $stars = '';
        for ($i = 0; $i < 5; $i++) {
            $stars .= $i < $rating ? '★' : '☆';
        }
        return '<span style="color: #f4c100;">'.$stars.'</span>';
    }

    public function getStatus($status)
    {
        switch ($status) {
            case ProductReview::STATUS_PENDING:
                return '<span class="label label-warning">'.$this->l('Pending').'</span>';
            case ProductReview::STATUS_APPROVED:
                return '<span class="label label-success">'.$this->l('Approved').'</span>';
            case ProductReview::STATUS_DECLINED:
                return '<span class="label label-danger">'.$this->l('Declined').'</span>';
            default:
                return '';
        }
    }
    
    public function renderList()
    {
        $this->initToolbar();
        return parent::renderList();
    }

    public function displayApproveLink($token, $id)
    {
        $review = new ProductReview($id);
        if (!Validate::isLoadedObject($review)) {
            return;
        }

        if ($review->status == ProductReview::STATUS_PENDING) {
            return '<a href="'.$this->context->link->getAdminLink('AdminProductReviews').'&approve'.$this->table.'&'.$this->identifier.'='.$id.'" class="btn btn-success" title="'.$this->l('Approve').'">
                <i class="icon-check"></i> '.$this->l('Approve').'
            </a>';
        }
    }

    public function displayDeclineLink($token, $id)
    {
        $review = new ProductReview($id);
        if (!Validate::isLoadedObject($review)) {
            return;
        }

        if ($review->status == ProductReview::STATUS_PENDING) {
            return '<a href="'.$this->context->link->getAdminLink('AdminProductReviews').'&decline'.$this->table.'&'.$this->identifier.'='.$id.'" class="btn btn-danger" title="'.$this->l('Decline').'">
                <i class="icon-times"></i> '.$this->l('Decline').'
            </a>';
        }
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('approve'.$this->table))
        {
            $object = $this->loadObject();
            if ($object && $object->status == ProductReview::STATUS_PENDING)
            {
                $object->status = ProductReview::STATUS_APPROVED;
                if ($object->save()) {
                    $this->confirmations[] = $this->l('Review approved successfully');
                }
            }
        }
        elseif (Tools::isSubmit('decline'.$this->table))
        {
            $object = $this->loadObject();
            if ($object && $object->status == ProductReview::STATUS_PENDING)
            {
                $object->status = ProductReview::STATUS_DECLINED;
                if ($object->save()) {
                    $this->confirmations[] = $this->l('Review declined successfully');
                }
            }
        }
        elseif (Tools::isSubmit('submitBulkapprove'.$this->table))
        {
            $this->processBulkApprove();
        }
        elseif (Tools::isSubmit('submitBulkdecline'.$this->table))
        {
            $this->processBulkDecline();
        }
        
        return parent::postProcess();
    }
    
    public function processBulkApprove()
    {
        if (is_array($this->boxes) && !empty($this->boxes))
        {
            $result = Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'product_review`
                SET status = '.(int)ProductReview::STATUS_APPROVED.'
                WHERE id_review IN ('.implode(',', array_map('intval', $this->boxes)).')
                AND status = '.(int)ProductReview::STATUS_PENDING
            );
            
            if ($result) {
                $this->confirmations[] = $this->l('Selected reviews approved successfully');
            }
        }
    }

    public function processBulkDecline()
    {
        if (is_array($this->boxes) && !empty($this->boxes))
        {
            $result = Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'product_review`
                SET status = '.(int)ProductReview::STATUS_DECLINED.'
                WHERE id_review IN ('.implode(',', array_map('intval', $this->boxes)).')
                AND status = '.(int)ProductReview::STATUS_PENDING
            );
            
            if ($result) {
                $this->confirmations[] = $this->l('Selected reviews declined successfully');
            }
        }
    }
}
