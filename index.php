<?php
/*
* Plugin Name: Sitenburada
* Plugin URI: https://www.sitenburada.com
* Description: Sitenburada Eklentisi
* Version: 1.0.0
* Author: Sitenburada
* Author URI: https://www.sitenburada.com
* Text Domain: devedijital
*/

//Admin Menüleri
function devedijital_admin_menuleri() {

	
add_menu_page( '', 'Sitenburada', 'manage_options', 'devedijital', 'ozel_logo_ayar_sayfasi', plugins_url( 'inc/d/sitenburadalogo.png', __FILE__ ) );
add_submenu_page( 'devedijital', '', 'Blog', 'manage_options', 'edit.php?post_type=blog' );
add_submenu_page( 'devedijital', '', 'Özellikler', 'manage_options', 'edit.php?post_type=ozellik' );
add_submenu_page( 'devedijital', '', 'Neden Biz', 'manage_options', 'edit.php?post_type=nedenbiz' );
add_submenu_page( 'devedijital', '', 'Pazarlar', 'manage_options', 'edit.php?post_type=pazarlar' );
add_submenu_page( 'devedijital', '', 'Güvenlik Emniyet', 'manage_options', 'edit.php?post_type=guvenlikemniyet' );
add_submenu_page( 'devedijital', '', 'Referanslar', 'manage_options', 'edit.php?post_type=referans' );
add_submenu_page( 'devedijital', '', 'Foto Galeri', 'manage_options', 'edit.php?post_type=fotogaleri' );
add_submenu_page( 'devedijital', '', 'Hizmetler', 'manage_options', 'edit.php?post_type=hizmet' );
add_submenu_page( 'devedijital', '', 'SSS', 'manage_options', 'edit.php?post_type=sss' );
add_submenu_page( 'devedijital', '', 'Slider', 'manage_options', 'edit.php?post_type=slider' );
add_submenu_page('devedijital', 'Özel Kodlar', 'Özel Kodlar', 'manage_options', 'edit.php?post_type=ozelkodlar');

}
add_action('admin_menu', 'devedijital_admin_menuleri');
add_action('admin_menu', 'ozel_logo_menusu');

// Ayarları kayıt etmek için hook
add_action('admin_init', 'ozel_logo_ayarlar');

// Admin menüsüne ayar sayfası ekleme fonksiyonu
function ozel_logo_menusu() {
    add_options_page('Özel Logo Ayarları', 'Özel Logo', 'manage_options', 'ozel-logo-ayarlari', 'ozel_logo_ayar_sayfasi');
}

// Ayarları kayıt etme fonksiyonu
function ozel_logo_ayarlar() {
    register_setting('ozel_logo_ayar_grubu', 'ozel_logo');
}
function ozel_logo_ayar_sayfasi() {
   
        ?>
    <div class="wrap">
        <h1>Özel Logo Ayarları</h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php settings_fields('ozel_logo_ayar_grubu'); ?>
            <?php do_settings_sections('ozel_logo_ayar_grubu'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Mevcut Logo</th>
                    <td>
                        <?php 
                        $logo_url = get_option('ozel_logo');
                        $upload_dir = '/home/wordpress.sitenburada.com/public_html/wp-content/plugins/devedijital/inc/d/';
                        $logo_path = $upload_dir . basename($logo_url);
                        
                        if (file_exists($logo_path)) {
                            echo '<img src="' . esc_url(plugin_dir_url(__FILE__) . 'inc/d/' . basename($logo_url)) . '" style="max-width: 200px; display: block;" />';
                        } else {
                            echo 'Logo bulunamadı.';
                        }
                        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Yeni Logo Yükle</th>
                    <td><input type="file" name="ozel_logo_file" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_post_ozel_logo_kaydet', 'ozel_logo_kaydet');

function ozel_logo_kaydet() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!isset($_FILES['ozel_logo_file']) || $_FILES['ozel_logo_file']['error'] !== UPLOAD_ERR_OK) {
        return;
    }

    $target_dir = '/home/wordpress.sitenburada.com/public_html/wp-content/plugins/devedijital/inc/d/';
    $target_url = plugin_dir_url(__FILE__) . 'inc/d/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . basename($_FILES['ozel_logo_file']['name']);
    $target_file_url = $target_url . basename($_FILES['ozel_logo_file']['name']);

    if (move_uploaded_file($_FILES['ozel_logo_file']['tmp_name'], $target_file)) {
        update_option('ozel_logo', $target_file_url);
    }

    wp_redirect(admin_url('options-general.php?page=ozel-logo-ayarlari&settings-updated=true'));
    exit;
}

add_action('admin_notices', 'ozel_logo_admin_notices');

function ozel_logo_admin_notices() {
    if (isset($_GET['settings-updated'])) {
        add_settings_error('ozel_logo_messages', 'ozel_logo_message', 'Ayarlar kaydedildi.', 'updated');
    }

    settings_errors('ozel_logo_messages');
}




// Shortcode Fonksiyonu
function ozel_kod_shortcode() {
    $options = get_option('ozel_kodlar_options');
    if (isset($options['ozel_kod'])) {
        return do_shortcode($options['ozel_kod']);
    }
    return '';
}
add_shortcode('ozel_kod', 'ozel_kod_shortcode');
//ÖZEL KODLAR







//Klasik Editör
add_filter('use_block_editor_for_post', '__return_false', 10);

//Dil Fonksiyonu  
function textdomain_func() {
  load_plugin_textdomain( 'devedijital', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'textdomain_func' );


//Giriş Sayfası
include('shortcode/shortcoder.php');
include('sayfalar/footer.php');

//Sessionlar

function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session');

function admin_uyarilari(){
  if(!empty($_SESSION['admin_uyarilari'])) print $_SESSION['admin_uyarilari'];
  unset ($_SESSION['admin_uyarilari']);
}
add_action( 'admin_notices', 'admin_uyarilari' );


//çerik Tipi
function iceriktipleri() {
      
    
    $labels = array(
        'name'                => __('Ozellik'),
        'singular_name'       => __('Ozellik'),
        'menu_name'           => __('Ozellik'),
        'parent_item_colon'   => __('Ozellik'),
        'all_items'           => __('Tüm Ozellik'),
        'view_item'           => __('Ozellik Gör'),
        'add_new_item'        => __('Yeni Ozellik Ekle'),
        'add_new'             => __('Yeni Ozellik Ekle'),
        'edit_item'           => __('Ozellik Düzenle'),
        'update_item'         => __('Ozellik Güncelle'),
        'search_items'        => __('Ozellik Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('ozellik'),
        'description'         => __('Ozellik'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-admin-customizer",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('ozellik', $args);
    
     $labels = array(
        'name'                => __('Nedenbiz'),
        'singular_name'       => __('Nedenbiz'),
        'menu_name'           => __('Nedenbiz'),
        'parent_item_colon'   => __('Nedenbiz'),
        'all_items'           => __('Tm Nedenbiz'),
        'view_item'           => __('Nedenbiz Gr'),
        'add_new_item'        => __('Yeni Nedenbiz Ekle'),
        'add_new'             => __('Yeni Nedenbiz Ekle'),
        'edit_item'           => __('Nedenbiz Düzenle'),
        'update_item'         => __('Nedenbiz Güncelle'),
        'search_items'        => __('Nedenbiz Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('nedenbiz'),
        'description'         => __('Nedenbiz'),
        'labels'              => $labels,
        'supports'            => array('title', 'excerpt'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-welcome-learn-more",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('nedenbiz', $args);
    
    $labels = array(
        'name'                => __('Güvenlik Emniyet'),
        'singular_name'       => __('Gvenlik Emniyet'),
        'menu_name'           => __('Gvenlik Emniyet'),
        'parent_item_colon'   => __('Güvenlik Emniyet'),
        'all_items'           => __('Tüm Güvenlik Emniyet'),
        'view_item'           => __('Güvenlik Emniyet Gör'),
        'add_new_item'        => __('Yeni Güvenlik Emniyet Ekle'),
        'add_new'             => __('Yeni Gvenlik Emniyet Ekle'),
        'edit_item'           => __('Gvenlik Emniyet Düzenle'),
        'update_item'         => __('Güvenlik Emniyet Güncelle'),
        'search_items'        => __('Güvenlik Emniyet Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('öpte Bulunamad'),
    );

    $args = array(
        'label'               => __('guvenlikemniyets'),
        'description'         => __('Güvenlik Emniyet'),
        'labels'              => $labels,
        'supports'            => array('title', 'excerpt', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-welcome-learn-more",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('guvenlikemniyet', $args);
    
    $labels = array(
        'name'                => __('Pazarlar'),
        'singular_name'       => __('Pazarlar'),
        'menu_name'           => __('Pazarlar'),
        'parent_item_colon'   => __('Pazarlar'),
        'all_items'           => __('Tüm Pazarlar'),
        'view_item'           => __('Pazarlar Gör'),
        'add_new_item'        => __('Yeni Pazarlar Ekle'),
        'add_new'             => __('Yeni Pazarlar Ekle'),
        'edit_item'           => __('Pazarlar Dzenle'),
        'update_item'         => __('Pazarlar Güncelle'),
        'search_items'        => __('Pazarlar Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('pazarlar'),
        'description'         => __('Pazarlar'),
        'labels'              => $labels,
        'supports'            => array('title', 'excerpt', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-welcome-learn-more",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('pazarlar', $args);
    
    
    $labels = array(
        'name'                => __('Referanslar'),
        'singular_name'       => __('Referanslar'),
        'menu_name'           => __('Referanslar'),
        'parent_item_colon'   => __('Referanslar'),
        'all_items'           => __('Tm Referanslar'),
        'view_item'           => __('Referans Gör'),
        'add_new_item'        => __('Yeni Referans Ekle'),
        'add_new'             => __('Yeni Referans Ekle'),
        'edit_item'           => __('Referans Düzenle'),
        'update_item'         => __('Referans Güncelle'),
        'search_items'        => __('Referans Ara'),
        'not_found'           => __('Bulunamad'),
        'not_found_in_trash'  => __('Çpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('referans'),
        'description'         => __('Referans'),
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-admin-users",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('referans', $args);
    
     $labels = array(
        'name'                => __('FotoGaleri'),
        'singular_name'       => __('FotoGaleri'),
        'menu_name'           => __('FotoGaleri'),
        'parent_item_colon'   => __('FotoGaleri'),
        'all_items'           => __('Tm FotoGaleri'),
        'view_item'           => __('FotoGaleri Gör'),
        'add_new_item'        => __('Yeni FotoGaleri Ekle'),
        'add_new'             => __('Yeni FotoGaleri Ekle'),
        'edit_item'           => __('FotoGaleri Düzenle'),
        'update_item'         => __('FotoGaleri Gncelle'),
        'search_items'        => __('FotoGaleri Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('fotogaleri'),
        'description'         => __('FotoGaleri'),
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-admin-users",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('fotogaleri', $args);
    
    $labels = array(
        'name'                => __('Hizmetlerim'),
        'singular_name'       => __('Hizmetlerim'),
        'menu_name'           => __('Hizmetlerim'),
        'parent_item_colon'   => __('Hizmetlerim'),
        'all_items'           => __('Tüm Hizmetlerim'),
        'view_item'           => __('Hizmetlerimi Gör'),
        'add_new_item'        => __('Yeni Hizmet Ekle'),
        'add_new'             => __('Yeni Hizmet Ekle'),
        'edit_item'           => __('Hizmet Düzenle'),
        'update_item'         => __('Hizmet Güncelle'),
        'search_items'        => __('Hizmet Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('pte Bulunamad'),
    );

    $args = array(
        'label'               => __('hizmet'),
        'description'         => __('Hizmet'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-welcome-learn-more",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('hizmet', $args);
    $labels = array(
        'name'                => __('Blog'),
        'singular_name'       => __('Blog'),
        'menu_name'           => __('Blog'),
        'parent_item_colon'   => __('Blog'),
        'all_items'           => __('Tüm Blog'),
        'view_item'           => __('Blog Gr'),
        'add_new_item'        => __('Yeni Blog Ekle'),
        'add_new'             => __('Yeni Blog Ekle'),
        'edit_item'           => __('Blog Düzenle'),
        'update_item'         => __('Blog Gncelle'),
        'search_items'        => __('Blog Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('blog'),
        'description'         => __('Blog'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-welcome-learn-more",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('blog', $args);
    
    
        $labels = array(
        'name'                => __('SSS'),
        'singular_name'       => __('SSS'),
        'menu_name'           => __('SSS'),
        'parent_item_colon'   => __('SSS'),
        'all_items'           => __('Tüm SSS'),
        'view_item'           => __('SSS Gör'),
        'add_new_item'        => __('Yeni SSS Ekle'),
        'add_new'             => __('Yeni SSS Ekle'),
        'edit_item'           => __('SSS Düzenle'),
        'update_item'         => __('SSS Güncelle'),
        'search_items'        => __('SSS Ara'),
        'not_found'           => __('Bulunamadı'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('sss'),
        'description'         => __('SSS'),
        'labels'              => $labels,
        'supports'            => array('title', 'excerpt'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-admin-users",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('sss', $args);
     $labels = array(
        'name'                => __('Slider'),
        'singular_name'       => __('Slider'),
        'menu_name'           => __('Slider'),
        'parent_item_colon'   => __('Slider'),
        'all_items'           => __('Tüm Slider'),
        'view_item'           => __('Slider Gör'),
        'add_new_item'        => __('Yeni Slider Ekle'),
        'add_new'             => __('Yeni Slider Ekle'),
        'edit_item'           => __('Slider Düzenle'),
        'update_item'         => __('Slider Gncelle'),
        'search_items'        => __('Slider Ara'),
        'not_found'           => __('Bulunamad'),
        'not_found_in_trash'  => __('Çöpte Bulunamadı'),
    );

    $args = array(
        'label'               => __('slider'),
        'description'         => __('Slider'),
        'labels'              => $labels,
        'supports'            => array('title'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'           => true,
        'rest_base'          => 'at',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        "menu_icon" => "dashicons-images-alt2",
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );

    register_post_type('slider', $args);
    
	$labels = array(
		'name'                  => __( 'zel Kodlar' ),
		'singular_name'         => __( 'zel Kodlar' ),
		'menu_name'             => __( 'Özel Kodlar' ),
        'parent_item_colon'     => __( 'Özel Kodlar' ),
		'all_items'             => __( 'Tm Özel Kodlar'),
		'view_item'             => __( 'Tüm Özel Kodlar Gör' ),
		'add_new_item'          => __( 'Yeni Özel Kod'),
		'add_new'               => __( 'Yeni zel Kod' ),
		'new_item'              => __( 'Yeni Özel Kod'),
		'search_items'          => __( 'Özel Kod Ara' ),
		'edit_item'             => __( 'Özel Kodu Düzenle' ),
		'update_item'           => __( 'Gncelle' ),
		'not_found'             => __( 'Not found', 'shortcoder' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'shortcoder' ),
	);

	$args =  array(
		'label'                 => __('ozelkodlar' ),
		'description'         => __( 'Özel Kodlar' ),
		'labels'                => $labels,
		'supports'              => array('title'),
		'taxonomies'            => array( 'sc_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'show_in_rest'          => true,
		'rest_base'          => 'ozelkodlar',
		'menu_position'         => 3,
		'menu_icon'             => 'dashicons-email-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);

	register_post_type( 'ozelkodlar', $args );
	
	$labels = array(
		'name'                => __( 'Gönderimler' ),
		'singular_name'       => __( 'Gnderimler' ),
		'menu_name'           => __( 'Gönderimler' ),
		'parent_item_colon'   => __( 'Gnderimler' ),
		'all_items'           => __( 'Tüm Gönderimler' ),
		'view_item'           => __( 'Gnderimleri Gör' ),
		'add_new_item'        => __( 'Yeni Gnderim Ekle' ),
		'add_new'             => __( 'Yeni Gönderim Ekle' ),
		'edit_item'           => __( 'Gönderim Düzenle' ),
		'update_item'         => __( 'Gönderim Güncelle' ),
		'search_items'        => __( 'Gönderim Ara' ),
		'not_found'           => __( 'Bulunamadı' ),
		'not_found_in_trash'  => __( 'Çpte Bulunamadı' ),
	);
	
	
	$args = array(
		'label'               => __( 'gonderim' ),
		'description'         => __( 'Gönderimler' ),
		'labels'              => $labels,
		'supports'            => array( 'title','editor','thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'show_in_rest' 		  => true,
		'rest_base'          => 'gonderim',
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 3,
		"menu_icon" => "dashicons-email-alt",   
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	
	register_post_type( 'gonderim', $args );
	
	
}

add_action( 'init', 'iceriktipleri', 0 );
function ozelkodlar_add_meta_box() {
    add_meta_box(
        'ozelkod_meta_box', // Meta kutu ID'si
        __('Item Alanları', 'devedijital'), // Başlık
        'ozelkodlar_meta_box_callback', // Callback fonksiyon
        'ozelkodlar', // Post type
        'normal', // Bağlam (normal, side, advanced)
        'high' // Öncelik (high, core, default, low)
    );
}
add_action('add_meta_boxes', 'ozelkodlar_add_meta_box');

// Meta Kutu Callback Fonksiyonu
function ozelkodlar_meta_box_callback($post) {
    // Özel alanların mevcut değerlerini alın
    $item_ustu = get_post_meta($post->ID, '_ozelkod_item_ustu', true);
    $item = get_post_meta($post->ID, '_ozelkod_item', true);
    $item_alti = get_post_meta($post->ID, '_ozelkod_item_alti', true);

    // Nonce field for security
    wp_nonce_field('ozelkod_meta_box', 'ozelkod_meta_box_nonce');

    // Meta kutu içeriği
    echo '<label for="ozelkod_item_ustu">' . __('Item Üstü:', 'devedijital') . '</label>';
    echo '<textarea id="ozelkod_item_ustu" name="ozelkod_item_ustu" rows="2" cols="50" style="width:100%;">' . esc_textarea($item_ustu) . '</textarea>';

    echo '<label for="ozelkod_item">' . __('Item:', 'devedijital') . '</label>';
    echo '<textarea id="ozelkod_item" name="ozelkod_item" rows="5" cols="50" style="width:100%;">' . esc_textarea($item) . '</textarea>';

    echo '<label for="ozelkod_item_alti">' . __('Item Altı:', 'devedijital') . '</label>';
    echo '<textarea id="ozelkod_item_alti" name="ozelkod_item_alti" rows="2" cols="50" style="width:100%;">' . esc_textarea($item_alti) . '</textarea>';
}

// Meta Kutusunu Kaydetmek
function ozelkodlar_save_meta_box_data($post_id) {
    // Nonce kontrolü
    if (!isset($_POST['ozelkod_meta_box_nonce']) || !wp_verify_nonce($_POST['ozelkod_meta_box_nonce'], 'ozelkod_meta_box')) {
        return;
    }

    // Otomatik kaydetmeyi yoksay
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Kullanıcı yetkisini kontrol et
    if (isset($_POST['post_type']) && 'ozelkodlar' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Veri mevcut mu kontrol et ve kaydet
    if (isset($_POST['ozelkod_item_ustu'])) {
        $item_ustu = sanitize_text_field($_POST['ozelkod_item_ustu']);
        update_post_meta($post_id, '_ozelkod_item_ustu', $item_ustu);
    }

    if (isset($_POST['ozelkod_item'])) {
        $item = sanitize_text_field($_POST['ozelkod_item']);
        update_post_meta($post_id, '_ozelkod_item', $item);
    }

    if (isset($_POST['ozelkod_item_alti'])) {
        $item_alti = sanitize_text_field($_POST['ozelkod_item_alti']);
        update_post_meta($post_id, '_ozelkod_item_alti', $item_alti);
    }
}
add_action('save_post', 'ozelkodlar_save_meta_box_data');



