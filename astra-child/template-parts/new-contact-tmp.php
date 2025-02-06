<?php
/*
* Template Name: New Contact
*/
get_header();
?>

<style>
.new-contact-page {
    margin: 40px auto !important;
    padding: 60px 50px !important;
}
.new-contact-page form {
    max-width: 600px;
    margin: 0 auto;
}
.captcha img {
    margin-top: 10px;
    display: block;
}
</style>

<div id="primary" class="new-contact-page" <?php astra_primary_class(); ?>>

    <?php
    // Include Really Simple CAPTCHA
    require_once WP_PLUGIN_DIR . '/really-simple-captcha/really-simple-captcha.php';
   
    $captcha = new ReallySimpleCaptcha();
    
    // echo '<pre>';
    // print_r($captcha);

    // Generate CAPTCHA
    $captcha_prefix = wp_generate_uuid4(); // Unique prefix    
    $captcha_word = $captcha->generate_random_word();    
    $captcha_image = $captcha->generate_image($captcha_prefix, $captcha_word);    
    //echo plugins_url('really-simple-captcha/tmp/' . $captcha_image);   
    //die('check captch');

    ?>

    <form id="custom-contact-form" method="POST" action="">
        <p>
            <label for="contact-name">Name</label>
            <input type="text" id="contact-name" name="contact_name" required>
        </p>
        <p>
            <label for="contact-email">Email</label>
            <input type="email" id="contact-email" name="contact_email" required>
        </p>
        <p>
            <label for="contact-message">Message</label>
            <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
        </p>

        <!-- CAPTCHA -->
        <div class="captcha">
            <p>Enter the text below:</p>            
            <img src="<?php echo $captcha_image_url; ?>" alt="CAPTCHA Image">
            <input type="text" name="captcha_input" placeholder="Enter CAPTCHA" required>
            <input type="hidden" name="captcha_prefix" value="<?php echo $captcha_prefix; ?>">
        </div>
        
        <p>
            <button type="submit" id="contact-submit">Send</button>
        </p>
        <div id="contact-form-message"></div>
    </form>

</div><!-- #primary -->

<!-- <script>
jQuery(document).ready(function ($) {
    $('#custom-contact-form').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '<?php //echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: formData + '&action=custom_contact_form',
            success: function (response) {
                if (response.success) {
                    $('#contact-form-message').html('<p style="color: green;">' + response.data.message + '</p>');
                    $('#custom-contact-form')[0].reset();
                } else {
                    $('#contact-form-message').html('<p style="color: red;">' + response.data.message + '</p>');
                }
            },
            error: function (xhr, status, error) {
                $('#contact-form-message').html('<p style="color: red;">An error occurred: ' + error + '</p>');
            }
        });
    });
});
</script> -->

<?php get_footer(); ?>
