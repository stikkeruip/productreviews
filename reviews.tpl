{if $lang_iso == 'el'}

<div id="product-reviews" class="product-reviews">
    <h3 class="page-product-heading">Κριτικές</h3>

    {if $is_logged}
        {if $has_pending_review}
            <div class="alert alert-warning">
                <p>Η κριτική σας έχει υποβληθεί και βρίσκεται σε αναμονή έγκρισης. Παρακαλώ περιμένετε...</p>
            </div>
        {elseif $has_approved_review}
            <div class="alert alert-info">
                <p>Έχετε ήδη υποβάλει κριτική για αυτό το προϊόν.</p>
            </div>
        {elseif $can_write_review}
            <!-- Review submission form -->
            <form id="product-review-form" method="post" class="review-form" data-ajax-url="{$ajax_url}">
                <h4>Γράψτε μία κριτική</h4>
                <input type="hidden" name="id_product" value="{$product_id}" />
                <input type="hidden" name="secure_key" value="{$secure_key}" />
                <input type="hidden" name="action" value="submitReview" />

                <!-- Title -->
                <div class="form-group">
                    <label for="review_title">Τίτλος</label>
                    <input type="text" name="title" id="review_title" class="form-control" required />
                </div>

                <!-- 5 new rating fields (Greek) -->
                <div class="form-group">
                    <label for="rating_effectiveness">Αποτελεσματικότητα</label>
                    <select name="rating_effectiveness" id="rating_effectiveness" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_texture">Υφή</label>
                    <select name="rating_texture" id="rating_texture" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_absorption">Απορρόφηση</label>
                    <select name="rating_absorption" id="rating_absorption" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_scent">Άρωμα</label>
                    <select name="rating_scent" id="rating_scent" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_value_for_money">Σχέση ποιότητας-τιμής</label>
                    <select name="rating_value_for_money" id="rating_value_for_money" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>

                <!-- Review content -->
                <div class="form-group">
                    <label for="review_content">Η κριτική σας</label>
                    <textarea name="content" id="review_content" class="form-control" rows="6" required></textarea>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">
                    Υποβολή Κριτικής
                </button>
            </form>
        {/if}
    {else}
        <!-- If not logged in -->
        <p class="alert alert-warning">
            Παρακαλώ συνδεθείτε για να γράψετε μια κριτική
        </p>
    {/if}

    <!-- Reviews listing -->
    <div class="reviews-list">
        {if $reviews}
            {foreach from=$reviews item=review}
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">
                            {$review.firstname|escape:'html':'UTF-8'} {$review.lastname|substr:0:1|escape:'html':'UTF-8'}.
                        </span>
                        <span class="review-date">{dateFormat date=$review.date_add full=0}</span>
                    </div>
                    <h4 class="review-title">{$review.title|escape:'html':'UTF-8'}</h4>

                    <!-- Display the 5 ratings (Greek labels) -->
                    <div class="review-ratings">
                        <div>
                            <strong>Αποτελεσματικότητα:</strong>
                            {section name=eff start=0 loop=5}
                                {if $smarty.section.eff.index < $review.rating_effectiveness}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>Υφή:</strong>
                            {section name=tex start=0 loop=5}
                                {if $smarty.section.tex.index < $review.rating_texture}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>Απορρόφηση:</strong>
                            {section name=abs start=0 loop=5}
                                {if $smarty.section.abs.index < $review.rating_absorption}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>Άρωμα:</strong>
                            {section name=sct start=0 loop=5}
                                {if $smarty.section.sct.index < $review.rating_scent}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>Σχέση ποιότητας-τιμής:</strong>
                            {section name=vfm start=0 loop=5}
                                {if $smarty.section.vfm.index < $review.rating_value_for_money}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                    </div>

                    <!-- Review content -->
                    <div class="review-content">
                        {$review.content|escape:'html':'UTF-8'|nl2br}
                    </div>
                </div>
            {/foreach}
        {else}
            <p class="alert alert-info">
                Δεν υπάρχουν ακόμα κριτικές. Γίνετε ο πρώτος που θα γράψει!
            </p>
        {/if}
    </div>
</div>

{else}

<!-- Default / English -->
<div id="product-reviews" class="product-reviews">
    <h3 class="page-product-heading">{l s='Reviews' mod='productreviews'}</h3>

    {if $is_logged}
        {if $has_pending_review}
            <div class="alert alert-warning">
                <p>{l s='Your review has been submitted and is pending approval. Please wait...' mod='productreviews'}</p>
            </div>
        {elseif $has_approved_review}
            <div class="alert alert-info">
                <p>{l s='You have already submitted a review for this product.' mod='productreviews'}</p>
            </div>
        {elseif $can_write_review}
            <!-- Review submission form (English) -->
            <form id="product-review-form" method="post" class="review-form" data-ajax-url="{$ajax_url}">
                <h4>{l s='Write a review' mod='productreviews'}</h4>
                <input type="hidden" name="id_product" value="{$product_id}" />
                <input type="hidden" name="secure_key" value="{$secure_key}" />
                <input type="hidden" name="action" value="submitReview" />

                <!-- Title -->
                <div class="form-group">
                    <label for="review_title">{l s='Title' mod='productreviews'}</label>
                    <input type="text" name="title" id="review_title" class="form-control" required />
                </div>

                <!-- 5 new rating fields (English) -->
                <div class="form-group">
                    <label for="rating_effectiveness">{l s='Effectiveness' mod='productreviews'}</label>
                    <select name="rating_effectiveness" id="rating_effectiveness" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_texture">{l s='Texture' mod='productreviews'}</label>
                    <select name="rating_texture" id="rating_texture" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_absorption">{l s='Absorption' mod='productreviews'}</label>
                    <select name="rating_absorption" id="rating_absorption" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_scent">{l s='Scent' mod='productreviews'}</label>
                    <select name="rating_scent" id="rating_scent" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating_value_for_money">{l s='Value for Money' mod='productreviews'}</label>
                    <select name="rating_value_for_money" id="rating_value_for_money" class="form-control rating-select" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>

                <!-- Review content -->
                <div class="form-group">
                    <label for="review_content">{l s='Your Review' mod='productreviews'}</label>
                    <textarea name="content" id="review_content" class="form-control" rows="6" required></textarea>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">
                    {l s='Submit Review' mod='productreviews'}
                </button>
            </form>
        {/if}
    {else}
        <!-- If not logged in -->
        <p class="alert alert-warning">
            {l s='Please sign in to write a review' mod='productreviews'}
        </p>
    {/if}

    <!-- Reviews listing -->
    <div class="reviews-list">
        {if $reviews}
            {foreach from=$reviews item=review}
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-author">
                            {$review.firstname|escape:'html':'UTF-8'} {$review.lastname|substr:0:1|escape:'html':'UTF-8'}.
                        </span>
                        <span class="review-date">{dateFormat date=$review.date_add full=0}</span>
                    </div>
                    <h4 class="review-title">{$review.title|escape:'html':'UTF-8'}</h4>

                    <!-- Display the 5 ratings (English labels) -->
                    <div class="review-ratings">
                        <div>
                            <strong>{l s='Effectiveness:' mod='productreviews'}</strong>
                            {section name=eff start=0 loop=5}
                                {if $smarty.section.eff.index < $review.rating_effectiveness}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>{l s='Texture:' mod='productreviews'}</strong>
                            {section name=tex start=0 loop=5}
                                {if $smarty.section.tex.index < $review.rating_texture}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>{l s='Absorption:' mod='productreviews'}</strong>
                            {section name=abs start=0 loop=5}
                                {if $smarty.section.abs.index < $review.rating_absorption}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>{l s='Scent:' mod='productreviews'}</strong>
                            {section name=sct start=0 loop=5}
                                {if $smarty.section.sct.index < $review.rating_scent}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                        <div>
                            <strong>{l s='Value for Money:' mod='productreviews'}</strong>
                            {section name=vfm start=0 loop=5}
                                {if $smarty.section.vfm.index < $review.rating_value_for_money}
                                    <span class="star star_on">★</span>
                                {else}
                                    <span class="star">☆</span>
                                {/if}
                            {/section}
                        </div>
                    </div>

                    <!-- Review content -->
                    <div class="review-content">
                        {$review.content|escape:'html':'UTF-8'|nl2br}
                    </div>
                </div>
            {/foreach}
        {else}
            <p class="alert alert-info">
                {l s='No reviews yet' mod='productreviews'}
            </p>
        {/if}
    </div>
</div>
{/if}
