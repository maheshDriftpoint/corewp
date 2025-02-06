jQuery(document).ready(function ($) {
    const $draggableFields = $('.draggable-field');
    const $formContainer = $('#form-container');
    const $settingsPanel = $('#field-settings-panel');
    let $selectedField = null;

    // // Enable sorting in the form container
    // $formContainer.sortable({
    //     placeholder: "ui-state-highlight",
    //     stop: function (event, ui) {
    //         updateFieldOrder();
    //     }
    // });

    // // Function to update field order (serial)
    // function updateFieldOrder() {
    //     $formContainer.find('.form-field').each(function (index) {
    //         $(this).attr('data-order', index + 1);
    //     });

    //     // Log the updated order (for testing)
    //     const fieldOrder = [];
    //     $formContainer.find('.form-field').each(function () {
    //         fieldOrder.push($(this).data('type'));
    //     });
    //     console.log('Updated Field Order:', fieldOrder);
    // }

    // Add dragstart event to all draggable fields
    $draggableFields.on('dragstart', function (event) {
        event.originalEvent.dataTransfer.setData('field-type', $(this).data('type'));
    });

    // Allow dropping on the form container
    $formContainer.on('dragover', function (event) {
        event.preventDefault();
    });

    // Handle the drop event
    $formContainer.on('drop', function (event) {
        event.preventDefault();
        const fieldType = event.originalEvent.dataTransfer.getData('field-type');

        let fieldHtml = '';
        switch (fieldType) {
            case 'first-name':
                fieldHtml = '<div class="form-field" data-type="first-name"><label>Name:</label><input type="text" name="first_name" /><button class="remove-field btn btn-danger mt-2" style="display: none;">Remove</button></div>';
                break;
            case 'email':
                fieldHtml = '<div class="form-field" data-type="email"><label>Email:</label><input type="email" name="email" /><button class="remove-field btn btn-danger mt-2" style="display: none;">Remove</button></div>';
                break;
            case 'phone':
                fieldHtml = '<div class="form-field" data-type="phone"><label>Phone:</label><input type="tel" name="phone" /><button class="remove-field btn btn-danger mt-2" style="display: none;">Remove</button></div>';
                break;
            case 'address':
                fieldHtml = '<div class="form-field" data-type="address"><label>Address:</label><textarea name="address"></textarea><button class="remove-field btn btn-danger mt-2" style="display: none;">Remove</button></div>';
                break;
            case 'submit':
                fieldHtml = '<div class="form-field" data-type="submit" class="form-submit"><input type="submit" class="btn btn-success" value="Submit" /><button class="remove-field btn btn-danger mt-2" style="display: none;">Remove</button></div>';
                break;
        }

        // Append the field to the form container 
        if (fieldHtml) {
            $formContainer.append(fieldHtml);
        }
    });


    // Show settings panel and remove button when a field is clicked
    $formContainer.on('click', '.form-field', function () {
        // Deselect any previously selected field
        if ($selectedField) {
            $selectedField.removeClass('selected');
            $selectedField.find('.remove-field').hide();            
        }

        const fieldType = $(this).data('type');
        if (fieldType === 'submit') {
            // Do not open settings panel for submit button      
            //$selectedField.find('.remove-field').hide();    
            $('#field-settings-panel').hide();    
            //return;
        }

        $selectedField = $(this); // Set the selected field
        $selectedField.addClass('selected');
        $selectedField.find('.remove-field').show(); // Show the remove button for the selected field

        const currentLabel = $selectedField.find('label').text().trim();
        $('#field-label').val(currentLabel); // Pre-fill the field label in the settings panel
        $settingsPanel.show(); // Show the settings panel

        
    });

    // Save the new field name
    $('#save-field-settings').on('click', function () {
        const newLabel = $('#field-label').val();
        if (newLabel) {
            $selectedField.find('label').text(newLabel); // Update the label of the selected field
        }
        $settingsPanel.hide(); // Hide the settings panel after saving
    });

    // Cancel the settings panel
    $('#cancel-field-settings').on('click', function () {
        $settingsPanel.hide(); // Hide the settings panel without saving
    });

    // Event delegation for dynamically added "Remove" buttons
    $formContainer.on('click', '.remove-field', function () {
        $(this).closest('.form-field').remove(); // Remove the parent form field
        $settingsPanel.hide(); // Hide the settings panel if the field is removed
        $selectedField = null; // Reset selected field        
    });

    // Deselect the field and hide settings when clicking outside the form fields
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.form-field, #field-settings-panel').length) {
            if ($selectedField) {
                $selectedField.removeClass('selected');
                $selectedField.find('.remove-field').hide();
            }
            $settingsPanel.hide();
            $selectedField = null;
        }
    });

    $('#submit-form').on('click', function () {
        // Collect form data
        const formData = [];
        $('#form-container .form-field').each(function () {
            const label = $(this).find('label').text().trim();
            const fieldType = $(this).data('type');
            formData.push({ label, fieldType });
        });

        // Log or handle the form data
        console.log('Form Data:', formData);

        // Example: Display a message
        alert('Form submitted! Check the console for form data.');
    });
    
});


jQuery(document).ready(function ($) {
    $('#publish-form').on('click', function () {

        const formId = 'default'; // You can make this dynamic
        const fields = [];

        $('#form-container .form-field').each(function () {
            fields.push($(this).data('type')); // Collect field types
        });

        console.log('FORM Data ' +fields);

        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            method: 'POST',
            data: {
                action: 'save_form_configuration',
                form_id: formId,
                fields: fields,
            },
            success: function (response) {
                if (response.success) {
                    alert('Form saved successfully!');
                } else {
                    alert('Failed to save the form.');
                }
            },
            error: function () {
                alert('An error occurred.');
            },
        });
    });
});