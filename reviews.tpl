{if $lang_iso == 'el'}

<div id="product-reviews" class="product-reviews">
    <h3 class="page-product-heading">Κριτικές</h3>

    {if $is_logged}
        {if $has_pending_review}
            <div class="alert alert-warning">
                <p>Η κριτική σας έχει υποβληθεί και βρίσκεται σε αναμονή έγκρισης. Παρακαλώ περιμένετε την έγκριση πριν υποβάλετε νέα κριτική.</p>
            </div>
        {elseif $has_approved_review}
            <div class="alert alert-info">
                <p>Έχετε ήδη υποβάλει κριτική για αυτό το προϊόν.</p>
            </div>
        {elseif $can_write_review}
            <form id="product-review-form" method="post" class="review-form" data-ajax-url="{$ajax_url}">
                <h4>Γράψτε μια κριτική</h4>
                <input type="hidden" name="id_product" value="{$product_id}" />
                <input type="hidden" name="action" value="submitReview" />
                <input type="hidden" name="secure_key" value="{$secure_key}" />

                <div class="form-group">
                    <label for="review_title">Τίτλος</label>
                    <input type="text" name="title" id="review_title" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="review_rating">Βαθμολογία</label>
                    <select name="rating" id="review_rating" class="form-control rating-select" required>
                        <option value="5" class="rating-option">★★★★★</option>
                        <option value="4" class="rating-option">★★★★☆</option>
                        <option value="3" class="rating-option">★★★☆☆</option>
                        <option value="2" class="rating-option">★★☆☆☆</option>
                        <option value="1" class="rating-option">★☆☆☆☆</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review_content">Η κριτική σας</label>
                    <textarea name="content" id="review_content" class="form-control" rows="6" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Υποβολή Κριτικής
                </button>
            </form>
        {/if}
    {else}
        <p class="alert alert-warning">
            Παρακαλώ συνδεθείτε για να γράψετε μια κριτική
        </p>
    {/if}

    <div class="reviews-list">
        {if $reviews}
            {foreach from=$reviews item=review}
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">{$review.firstname|escape:'html':'UTF-8'} {$review.lastname|substr:0:1|escape:'html':'UTF-8'}.</span>
                        <span class="review-date">{dateFormat date=$review.date_add full=0}</span>
                        <div class="review-rating">
                            {section name="rating" start=0 loop=5 step=1}
                                {if $smarty.section.rating.index < $review.rating}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                    </div>
                    <h4 class="review-title">{$review.title|escape:'html':'UTF-8'}</h4>
                    <div class="review-content">
                        {$review.content|escape:'html':'UTF-8'|nl2br}
                    </div>
                </div>
            {/foreach}
        {else}
            <p class="alert alert-info">Δεν υπάρχουν ακόμα κριτικές. Γίνετε ο πρώτος που θα γράψει!</p>
        {/if}
    </div>
</div>

{else}

<!-- Default (English or existing translations) -->
<div id="product-reviews" class="product-reviews">
    <h3 class="page-product-heading">{l s='Reviews' mod='productreviews'}</h3>

    {if $is_logged}
        {if $has_pending_review}
            <div class="alert alert-warning">
                <p>{l s='Your review has been submitted and is pending approval. Please wait for approval before submitting a new review.' mod='productreviews'}</p>
            </div>
        {elseif $has_approved_review}
            <div class="alert alert-info">
                <p>{l s='You have already submitted a review for this product.' mod='productreviews'}</p>
            </div>
        {elseif $can_write_review}
            <form id="product-review-form" method="post" class="review-form" data-ajax-url="{$ajax_url}">
                <h4>{l s='Write a review' mod='productreviews'}</h4>
                <input type="hidden" name="id_product" value="{$product_id}" />
                <input type="hidden" name="action" value="submitReview" />
                <input type="hidden" name="secure_key" value="{$secure_key}" />

                <div class="form-group">
                    <label for="review_title">{l s='Title' mod='productreviews'}</label>
                    <input type="text" name="title" id="review_title" class="form-control" required />
                </div>

                <div class="form-group">
                    <label for="review_rating">{l s='Rating' mod='productreviews'}</label>
                    <select name="rating" id="review_rating" class="form-control rating-select" required>
                        <option value="5" class="rating-option">★★★★★</option>
                        <option value="4" class="rating-option">★★★★☆</option>
                        <option value="3" class="rating-option">★★★☆☆</option>
                        <option value="2" class="rating-option">★★☆☆☆</option>
                        <option value="1" class="rating-option">★☆☆☆☆</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review_content">{l s='Your Review' mod='productreviews'}</label>
                    <textarea name="content" id="review_content" class="form-control" rows="6" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    {l s='Submit Review' mod='productreviews'}
                </button>
            </form>
        {/if}
    {else}
        <p class="alert alert-warning">
            {l s='Please sign in to write a review' mod='productreviews'}
        </p>
    {/if}

    <div class="reviews-list">
        {if $reviews}
            {foreach from=$reviews item=review}
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">{$review.firstname|escape:'html':'UTF-8'} {$review.lastname|substr:0:1|escape:'html':'UTF-8'}.</span>
                        <span class="review-date">{dateFormat date=$review.date_add full=0}</span>
                        <div class="review-rating">
                            {section name="rating" start=0 loop=5 step=1}
                                {if $smarty.section.rating.index < $review.rating}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                    </div>
                    <h4 class="review-title">{$review.title|escape:'html':'UTF-8'}</h4>
                    <div class="review-content">
                        {$review.content|escape:'html':'UTF-8'|nl2br}
                    </div>
                </div>
            {/foreach}
        {else}
            <p class="alert alert-info">{l s='No reviews yet' mod='productreviews'}</p>
        {/if}
    </div>
</div>
{/if}
