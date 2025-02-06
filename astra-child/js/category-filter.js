jQuery(document).ready(function ($) {    
    $('.category-filter').on('change', function () {       
        // Get selected categories
        let selectedCategories = [];
        $('.category-filter:checked').each(function () {
            selectedCategories.push($(this).val());
        });

        console.log('chack categorly: ' +selectedCategories);
        console.log('chack ajax: ' +ajax_params.ajax_url);

        // Make AJAX request
        $.ajax({
            url: ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_products',
                categories: selectedCategories,
            },
            beforeSend: function () {
                $('#product-list').html('<p>Loading products...</p>');
            },
            success: function (response) {
                $('#product-list').html(response);
            },
        });
    });
});
