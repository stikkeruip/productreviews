<?php
class ProductreviewsSubmitModuleFrontController extends ModuleFrontController
{
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
        $rating_effectiveness   = (int)Tools::getValue('rating_effectiveness');
        $rating_texture         = (int)Tools::getValue('rating_texture');
        $rating_absorption      = (int)Tools::getValue('rating_absorption');
        $rating_scent           = (int)Tools::getValue('rating_scent');
        $rating_value_for_money = (int)Tools::getValue('rating_value_for_money');

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

        // 4) Validate the rating fields
        if ($rating_effectiveness < 1 || $rating_effectiveness > 5 ||
            $rating_texture < 1     || $rating_texture > 5     ||
            $rating_absorption < 1  || $rating_absorption > 5  ||
            $rating_scent < 1       || $rating_scent > 5       ||
            $rating_value_for_money < 1 || $rating_value_for_money > 5
        ) {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('Please provide valid ratings for all fields')
            )));
        }

        // 5) Create the review (no `$review->rating = $rating`)
        $review = new ProductReview();
        $review->id_shop                = (int)$this->context->shop->id;
        $review->id_product            = $id_product;
        $review->id_customer           = (int)$this->context->customer->id;
        
        // Only these 5 rating fields
        $review->rating_effectiveness   = $rating_effectiveness;
        $review->rating_texture         = $rating_texture;
        $review->rating_absorption      = $rating_absorption;
        $review->rating_scent           = $rating_scent;
        $review->rating_value_for_money = $rating_value_for_money;

        $review->title   = $title;
        $review->content = $content;
        $review->status  = ProductReview::STATUS_PENDING;

        // 6) Save
        if ($review->add()) {
            die(json_encode(array(
                'success' => true,
                'message' => $this->module->l('Your review has been submitted and is pending approval'),
                'status'  => 'pending'
            )));
        } else {
            die(json_encode(array(
                'success' => false,
                'message' => $this->module->l('An error occurred while saving your review')
            )));
        }
    }
}
