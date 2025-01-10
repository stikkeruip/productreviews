<?php
class ProductreviewsSubmitModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        header('Content-Type: application/json');
    }

    public function initContent()
    {
        parent::initContent();
        
        // Check if user is logged in
        if (!$this->context->customer->isLogged()) {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('Please sign in to write a review')
            )));
        }

        // Get POST data
        $id_product = (int)Tools::getValue('id_product');
        $secure_key = Tools::getValue('secure_key');
        $rating = (int)Tools::getValue('rating');
        $title = Tools::getValue('title');
        $content = Tools::getValue('content');

        // Validate secure key
        if ($secure_key !== $this->context->customer->secure_key) {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('Invalid security token')
            )));
        }

        // Check if customer can write a review
        if (!ProductReview::canWriteReview($id_product, $this->context->customer->id)) {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('You cannot submit a review at this time')
            )));
        }

        // Validate data
        if (!$rating || $rating < 1 || $rating > 5 || !$title || !$content) {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('Please fill in all required fields')
            )));
        }

        // Create new review
        $review = new ProductReview();
        $review->id_shop = (int)$this->context->shop->id;
        $review->id_product = $id_product;
        $review->id_customer = (int)$this->context->customer->id;
        $review->rating = $rating;
        $review->title = $title;
        $review->content = $content;
        $review->status = ProductReview::STATUS_PENDING;

        // Save review
        if ($review->add()) {
            die(json_encode(array(
                'success' => true,
                'message' => $this->module->l('Your review has been submitted and is pending approval'),
                'status' => 'pending'
            )));
        } else {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('An error occurred while saving your review')
            )));
        }
    }

    public function displayAjaxSubmitReview()
    {
        return $this->initContent();
    }
}
