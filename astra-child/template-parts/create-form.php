<?php
/*Template Name: Create Form*/
get_header(); 
?>

<div class="container py-5">
    <h2 class="mb-4">Form Builder</h2>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="build-form-tab" data-bs-toggle="tab" data-bs-target="#build-form" type="button" role="tab" aria-controls="build-form" aria-selected="true">Build Form</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="create-form-tab" data-bs-toggle="tab" data-bs-target="#create-form" type="button" role="tab" aria-controls="create-form" aria-selected="false">Create Form</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-4" id="formTabsContent">
        <!-- Build Form Tab -->
        <div class="tab-pane fade show active" id="build-form" role="tabpanel" aria-labelledby="build-form-tab">
            <div id="form-builder">
                <!-- Left Side: Available Fields -->
                <div id="field-palette">
                    <h3>Available Fields</h3>
                    <div class="draggable-field" draggable="true" data-type="first-name">Name</div>
                    <div class="draggable-field" draggable="true" data-type="email">Email</div>
                    <div class="draggable-field" draggable="true" data-type="phone">Phone</div>
                    <div class="draggable-field" draggable="true" data-type="address">Address</div>
                    <div class="draggable-field" draggable="true" data-type="submit">Submit</div>
                </div> 

                <!-- Right Side: Form Builder -->
                <div id="form-container" class="droppable-area">
                    <h3>Form Builder</h3>
                    <p>Drag fields here</p>
                </div>

                <!-- Settings Panel -->
                <div id="field-settings-panel" style="display: none;">
                    <h4>Edit Field</h4>
                    <label for="field-label">Field Name:</label>
                    <input type="text" id="field-label" class="form-control" />
                    <button id="save-field-settings" class="btn btn-primary mt-2">Save</button>
                    <button id="cancel-field-settings" class="btn btn-secondary mt-2">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Create Form Tab -->
        <div class="tab-pane fade" id="create-form" role="tabpanel" aria-labelledby="create-form-tab">
            <h3>Create and Publish Form</h3>
            <p>Once you've built your form, you can publish it here.</p>
            <button id="publish-form" class="btn btn-success">Publish Form</button>
        </div>
    </div>
</div>

<?php
get_footer();
?>
