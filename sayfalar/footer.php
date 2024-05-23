<?php 
 function myplugin_add_admin_page() {
    add_options_page('My Plugin Settings', 'My Plugin', 'manage_options', 'myplugin-settings', 'myplugin_render_admin_page');
}
add_action('admin_menu', 'myplugin_add_admin_page');

// Ayar sayfasındaki içeriği oluşturun
function myplugin_render_admin_page() {
    ?>
    <div class="wrap">
        <h2>My Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('myplugin_settings_group'); ?>
            <?php do_settings_sections('myplugin-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Ayarları ve alanları kaydedin
function myplugin_initialize_settings() {
    register_setting('myplugin_settings_group', 'myplugin_footer_content');
    add_settings_section('myplugin_footer_section', 'Footer Settings', 'myplugin_footer_section_callback', 'myplugin-settings');
    add_settings_field('myplugin_footer_content_field', 'Footer Content', 'myplugin_footer_content_field_callback', 'myplugin-settings', 'myplugin_footer_section');
}
add_action('admin_init', 'myplugin_initialize_settings');

// Footer bölümünü ayarlar sayfasına ekleyin
function myplugin_footer_section_callback() {
    echo '<p>Customize your footer content here.</p>';
}

// Footer içeriği için bir metin alanı ekleyin
function myplugin_footer_content_field_callback() {
    $footer_content = get_option('myplugin_footer_content');
    echo '<textarea name="myplugin_footer_content" rows="5" cols="50">' . esc_textarea($footer_content) . '</textarea>';
}

// Footer içeriğini tema dosyasında gösterin
function myplugin_display_footer_content() {
    $footer_content = get_option('myplugin_footer_content');
    echo $footer_content; // Bu içeriği footer.php dosyasında kullanabilirsiniz.
}
    
    
    
    

?>




    