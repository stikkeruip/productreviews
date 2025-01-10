$(document).ready(function() {
    $('#product-review-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        var $formWrapper = $form.parent();
        
        // Disable submit button
        $submitButton.prop('disabled', true);

        $.ajax({
            url: $form.data('ajax-url'),
            type: 'POST',
            data: {
                id_product: $form.find('input[name="id_product"]').val(),
                secure_key: $form.find('input[name="secure_key"]').val(),
                rating: $form.find('select[name="rating"]').val(),
                title: $form.find('input[name="title"]').val(),
                content: $form.find('textarea[name="content"]').val(),
                action: 'submitReview'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Replace the entire form with the pending message
                    var pendingMessage = '';
                    if ($('html').attr('lang') === 'el') {
                        pendingMessage = '<div class="alert alert-warning"><p>Η κριτική σας έχει υποβληθεί και βρίσκεται σε αναμονή έγκρισης. Παρακαλώ περιμένετε την έγκριση πριν υποβάλετε νέα κριτική.</p></div>';
                    } else {
                        pendingMessage = '<div class="alert alert-warning"><p>Your review has been submitted and is pending approval. Please wait for approval before submitting a new review.</p></div>';
                    }
                    $formWrapper.html(pendingMessage);
                } else {
                    // Show error message
                    var errorHtml = '<div class="alert alert-danger">' + response.message + '</div>';
                    $form.find('.alert-danger').remove();
                    $form.prepend(errorHtml);
                    $submitButton.prop('disabled', false);
                }
            },
            error: function() {
                // Show error message
                var errorMessage = $('html').attr('lang') === 'el' ? 
                    'Παρουσιάστηκε σφάλμα κατά την υποβολή της κριτικής σας' : 
                    'An error occurred while submitting your review';
                var errorHtml = '<div class="alert alert-danger">' + errorMessage + '</div>';
                $form.find('.alert-danger').remove();
                $form.prepend(errorHtml);
                $submitButton.prop('disabled', false);
            }
        });
        
        return false;
    });
});
