<?php function epostaci(){ ?>
	
<div class="wrap">
<h2>E-Postacı Eklenti Ayarları</h2>
<?php settings_errors(); ?> 
<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'eposta_genel_ayarlar_tab'; ?>  
<h2 class="nav-tab-wrapper">  
<a href="?page=epostaci&tab=eposta_genel_ayarlar_tab" class="nav-tab <?php echo $active_tab == 'eposta_genel_ayarlar_tab' ? 'nav-tab-active' : ''; ?>">E-Posta Gönderim Ayarları</a>  
<a href="?page=epostaci&tab=eposta_sayfalar_tab" class="nav-tab <?php echo $active_tab == 'eposta_sayfalar_tab' ? 'nav-tab-active' : ''; ?>">Sayfalar</a>  
</h2>  
<form action='options.php' method='post'>
<?php 
	if( $active_tab == 'eposta_genel_ayarlar_tab' ) {  
		settings_fields( 'epostaci_ayarlar' );
		do_settings_sections( 'epostaci_genel_ayarlar' ); 

	} else if( $active_tab == 'eposta_sayfalar_tab' ) {
		settings_fields( 'epostaci_sayfalar' );
		do_settings_sections( 'epostaci_sayfalar' ); 
	}
?> 
<?php submit_button(); ?>  
</form>
</div>

<?php	
}

function epostaci_ayarlari_goster() {

//Ayarlar Tabı
	
register_setting( 'epostaci_ayarlar', 'epostaci_ayarlar_secenekler' );
add_settings_section( 'epostaci_genel_ayarlar', '', '', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_eposta_gonderen_baslik', 'E-Posta Başlık', 'epostaci_eposta_gonderen_baslik_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_eposta_gonderen_email', 'E-Posta', 'epostaci_eposta_gonderen_email_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_eposta_gonderen_email_sifre', 'E-Posta Şifre', 'epostaci_eposta_gonderen_email_sifre_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_eposta_gonderen_email_sunucu', 'E-Posta Sunucu', 'epostaci_eposta_gonderen_email_sunucu_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_eposta_gonderen_email_baglanti_tipi', 'E-Posta Bağlantı Tipi', 'epostaci_eposta_gonderen_email_baglanti_tipi_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );	
	
add_settings_field( 'epostaci_eposta_gonderen_email_port', 'E-Posta Port', 'epostaci_eposta_gonderen_email_port_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );	
	
add_settings_field( 'epostaci_calisacak_icerik_tipleri', 'Gönderim Yapılacak İçerik Tipleri', 'epostaci_calisacak_icerik_tipleri_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );	
	
add_settings_field( 'epostaci_cron_calisma_dongusu', 'Cron Çalışma Döngüsü', 'epostaci_cron_calisma_dongusu_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );	
	
add_settings_field( 'epostaci_cron_gonderilecek_email_sayisi', 'Her döngüde Gönderilecek E-Mail Sayısı', 'epostaci_cron_gonderilecek_email_sayisi_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
add_settings_field( 'epostaci_abonelik_bilesen_shortcode_bilgileri', 'Abonelik Shortcode Bilgileri', 'epostaci_abonelik_bilesen_shortcode_bilgileri_render', 'epostaci_genel_ayarlar', 'epostaci_genel_ayarlar' );
	
	
//Sayfalar Tabı	

register_setting( 'epostaci_sayfalar', 'epostaci_sayfalar_secenekler' );	
add_settings_section( 'epostaci_sayfalar', '', '', 'epostaci_sayfalar' );	
	
add_settings_field( 'epostaci_abonelik_onay_sayfasi', 'Abonelik Onay Sayfası Seçimi', 'epostaci_abonelik_onay_sayfasi_render', 'epostaci_sayfalar', 'epostaci_sayfalar' );	
	
add_settings_field( 'epostaci_abonelik_cikis_sayfasi', 'Abonelik Çıkış Sayfası Seçimi', 'epostaci_abonelik_cikis_sayfasi_render', 'epostaci_sayfalar', 'epostaci_sayfalar' );
	
}

add_action("admin_init", "epostaci_ayarlari_goster");


//Genel Ayarlar Tab Render

function epostaci_eposta_gonderen_baslik_render() {
	
	$eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
	//echo "<pre>"; print_r($eposta_ayarlar); echo "</pre>";
	
	if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
		
		echo '<div class="notice notice-error"><p>E-Postacı - Cron İşlemleri Sunucunuzda veya wp-config.php dosyanızda kapatılmıştır, bu şartlarda planlı gönderim yapamazsınız, düzeltmek için wp-config.php dosyanızdaki DISABLE_WP_CRON değerini false olarak değiştirin veya sunucunuzla konu ile ilgili iletişime geçin.</p></div>';
		
	}
	
	
	if(empty($eposta_ayarlar['gonderen_baslik']) || empty($eposta_ayarlar['gonderen_email'])){
		echo '<div class="notice notice-error is-dismissible"><p>E-Postacı - Ayarlar bölümünüz boş</p></div>';
	}
	
?>
<input id='epostaci_eposta_gonderen_baslik' name='epostaci_ayarlar_secenekler[gonderen_baslik]' required type='text' value='<?php if(!empty($eposta_ayarlar['gonderen_baslik'])) echo $eposta_ayarlar['gonderen_baslik'];?>' />
<?php
	
}

function epostaci_eposta_gonderen_email_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' ); 
	
?>
	<input id='epostaci_eposta_gonderen_email' name='epostaci_ayarlar_secenekler[gonderen_email]' required type="email" value='<?php if(!empty($eposta_ayarlar['gonderen_email'])) echo $eposta_ayarlar['gonderen_email'];?>' />
<?php }

function epostaci_eposta_gonderen_email_sifre_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
?>
	<input id='epostaci_eposta_gonderen_email_sifre' name='epostaci_ayarlar_secenekler[gonderen_email_sifre]' required type="password" value='<?php if(!empty($eposta_ayarlar['gonderen_email_sifre'])) echo $eposta_ayarlar['gonderen_email_sifre'];?>' />
<?php }

function epostaci_eposta_gonderen_email_sunucu_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
?>
	<input id='epostaci_eposta_gonderen_email_sunucu' name='epostaci_ayarlar_secenekler[gonderen_email_sunucu]' required type="text" value='<?php if(!empty($eposta_ayarlar['gonderen_email_sunucu'])) echo $eposta_ayarlar['gonderen_email_sunucu'];?>' />
<?php }

function epostaci_eposta_gonderen_email_baglanti_tipi_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
?>
	<select name="epostaci_ayarlar_secenekler[gonderen_email_baglanti_tipi]">
	<option value="tls" <?php if(isset($eposta_ayarlar['gonderen_email_baglanti_tipi']) && $eposta_ayarlar['gonderen_email_baglanti_tipi'] == "tls") echo "selected";?> >TLS</option>	
	<option value="ssl" <?php if(isset($eposta_ayarlar['gonderen_email_baglanti_tipi']) && $eposta_ayarlar['gonderen_email_baglanti_tipi'] == "ssl") echo "selected";?>>SSL</option>
	</select>
<?php }

function epostaci_eposta_gonderen_email_port_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' ); 
?>
	<input id='epostaci_eposta_gonderen_email_port' name='epostaci_ayarlar_secenekler[gonderen_email_port]' required type="number" value='<?php if(!empty($eposta_ayarlar['gonderen_email_port'])) echo $eposta_ayarlar['gonderen_email_port'];?>' />
<?php }


function epostaci_calisacak_icerik_tipleri_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' ); 
	$args = array(
	   'public'   => true,
	);
	$icerik_tipleri = get_post_types( $args, 'objects');
	//echo "<pre>"; print_r($icerik_tipleri); echo "</pre>";
?>
	<?php foreach ( $icerik_tipleri  as $icerik_tipi ) { ?>
	
	<label><input type="checkbox" value="<?php echo $icerik_tipi->name;?>"
	<?php if(isset($eposta_ayarlar['calisacak_icerik_tipleri'])){
			if( in_array($icerik_tipi->name, $eposta_ayarlar['calisacak_icerik_tipleri'] ) ) {echo "checked"; };
		} else {
			if($icerik_tipi->name == "post"){echo "checked";}
		} ?>
	name="epostaci_ayarlar_secenekler[calisacak_icerik_tipleri][]"> <?php echo $icerik_tipi->label;?></label><br>
    <?php } ?>
	

<?php }

function epostaci_cron_calisma_dongusu_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
	//echo $eposta_ayarlar['cron_caslisma_dongusu'];

?>

	<select name="epostaci_ayarlar_secenekler[cron_caslisma_dongusu]">
		
	<option value="1 Dakikalık" <?php if ( isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "1 Dakikalık" ) echo 'selected="selected"'; ?>>1 Dakikalık</option>	
		
	<option value="5 Dakikalık" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "5 Dakikalık") echo "selected";?>>5 Dakikalık</option>
		
	<option value="15 Dakikalık" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "15 Dakikalık") echo "selected";?>>15 Dakikalık</option>
		
	<option value="30 Dakikalık" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "30 Dakikalık") echo "selected";?>>30 Dakikalık</option>
		
	<option value="1 Saatlik" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "1 Saatlik") echo "selected";?>>1 Saatlik</option>
		
	<option value="3 Saatlik" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "3 Saatlik") echo "selected";?>>3 Saatlik</option>	
		
	<option value="6 Saatlik" <?php if ( isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "6 Saatlik" ) echo 'selected="selected"'; ?> >6 Saatlik</option>
		
	<option value="12 Saatlik" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "12 Saatlik") echo "selected";?>>12 Saatlik</option>
		
	<option value="Günlük" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "Günlük") echo "selected";?>>Günlük</option>
		
	<option value="3 Günlük" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "3 Günlük") echo "selected";?>>3 Günlük</option>
		
	<option value="Haftalık" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "Haftalık") echo "selected";?>>Haftalık</option>
		
	<option value="Aylık" <?php if(isset($eposta_ayarlar['cron_caslisma_dongusu']) && $eposta_ayarlar['cron_caslisma_dongusu'] == "Aylık") echo "selected";?>>Aylık</option>	
		
	</select>
<?php }


function epostaci_cron_gonderilecek_email_sayisi_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' ); ?>
	<input id='epostaci_cron_email_sayisi' name='epostaci_ayarlar_secenekler[epostaci_cron_email_sayisi]' required type="number" value='<?php echo $eposta_ayarlar['epostaci_cron_email_sayisi'];?>' />

	<p>Sunucunuz tarafından spam email gönderimi muamelesi görmemek için saatte 150 e-postayı aşmamanız önerilir.</p>
<?php }


function epostaci_abonelik_bilesen_shortcode_bilgileri_render() {
    $eposta_ayarlar = get_option( 'epostaci_ayarlar_secenekler' );
?>
	<p>Abonelik formu bileşenini çağırmak için kullanacağınız kod <strong>[abonelik_formu]</strong> </p>
	<p>Abonelik onay sayfasını çağırmak için kullanacağınız kod <strong>[abonelik_onay_sayfasi]</strong> </p>
	<p>Abonelik çıkış sayfasını çağırmak için kullanacağınız kod <strong>[abonelik_cikis_sayfasi]</strong> </p>
<?php }

//Sayfalar Tab Render

function epostaci_abonelik_onay_sayfasi_render() {
	$eposta_ayarlar = get_option( 'epostaci_sayfalar_secenekler' ); 
	$sayfalar = get_pages();
?>
<select name="epostaci_sayfalar_secenekler[abonelik_onay_sayfasi]">
	<?php foreach( $sayfalar as $sayfa ) { ?>
        <option value='<?php echo $sayfa->ID; ?>' <?php if(isset($eposta_ayarlar['abonelik_onay_sayfasi']) && $eposta_ayarlar['abonelik_onay_sayfasi'] == $sayfa->ID) echo "selected";?> ><?php echo $sayfa->post_title; ?></option>
    <?php }; ?>
</select>
<?php	
}

function epostaci_abonelik_cikis_sayfasi_render() {
	$eposta_ayarlar = get_option( 'epostaci_sayfalar_secenekler' ); 
	$sayfalar = get_pages();
?>
<select name="epostaci_sayfalar_secenekler[abonelik_cikis_sayfasi]">
	<?php foreach( $sayfalar as $sayfa ) { ?>
        <option value='<?php echo $sayfa->ID; ?>' <?php if(isset($eposta_ayarlar['abonelik_cikis_sayfasi']) && $eposta_ayarlar['abonelik_cikis_sayfasi'] == $sayfa->ID) echo "selected";?> ><?php echo $sayfa->post_title; ?></option>
    <?php }; ?>
</select>
<?php	
}







