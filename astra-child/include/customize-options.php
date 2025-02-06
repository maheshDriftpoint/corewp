<?php 

function astra_child_customize_register($wp_customize) {

    // Add a new section in the Customizer for Astra Child Theme options
    $wp_customize->add_section('astra_child_theme_section', array(
        'title'       => __('Astra Child Theme Options', 'astra-child'),
        'description' => __('Customize settings for the Astra Child theme.', 'astra-child'),
        'priority'    => 160, // Position in Customizer
    ));

    // Add a setting for the custom footer text
    $wp_customize->add_setting('astra_child_footer_text', array(
        'default'           => 'Your custom footer text here',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add a control for the custom footer text
    $wp_customize->add_control('astra_child_footer_text_control', array(
        'label'    => __('Footer Text', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'astra_child_footer_text',
        'type'     => 'text',
    ));

    // Add a setting for the custom logo
    $wp_customize->add_setting('astra_child_custom_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw', // Sanitize URL input
    ));

    // Add a control for the custom logo
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize, 'astra_child_logo_control', array(
            'label'    => __('Custom Logo', 'astra-child'),
            'section'  => 'astra_child_theme_section',
            'settings' => 'astra_child_custom_logo',
        )
    ));

    $wp_customize->add_setting('header_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'header_background_color_control',
        array(
            'label'    => __('Header Background Color', 'astra-child'),
            'section'  => 'astra_child_theme_section',
            'settings' => 'header_background_color',
        )
    ));

    $wp_customize->add_setting('body_font_size', array(
        'default'           => '16px',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('body_font_size_control', array(
        'label'    => __('Body Font Size', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'body_font_size',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('layout_type', array(
        'default'           => 'full-width',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('layout_type_control', array(
        'label'    => __('Layout Type', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'layout_type',
        'type'     => 'radio',
        'choices'  => array(
            'full-width' => __('Full Width', 'astra-child'),
            'boxed'      => __('Boxed', 'astra-child'),
        ),
    ));
    
    $wp_customize->add_setting('sticky_header', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('sticky_header_control', array(
        'label'    => __('Enable Sticky Header', 'astra-child'),
        'section'  => 'header_image',
        'settings' => 'sticky_header',
        'type'     => 'checkbox',
    ));
    
    $wp_customize->add_setting('footer_social_links', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('footer_social_links_control', array(
        'label'    => __('Social Media Links (Comma Separated)', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'footer_social_links',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('blog_excerpt_length', array(
        'default'           => 30,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('blog_excerpt_length_control', array(
        'label'    => __('Excerpt Length', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'blog_excerpt_length',
        'type'     => 'number',
    ));
    
    $wp_customize->add_setting('facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('facebook_url_control', array(
        'label'    => __('Facebook URL', 'astra-child'),
        'section'  => 'astra_child_theme_section',
        'settings' => 'facebook_url',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('button_background_color', array(
        'default'           => '#0073e6',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'button_background_color_control',
        array(
            'label'    => __('Button Background Color', 'astra-child'),
            'section'  => 'astra_child_theme_section',
            'settings' => 'button_background_color',
        )
    ));
    
}

add_action('customize_register', 'astra_child_customize_register');


