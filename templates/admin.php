<?php
// do_action( 'admin_notices' );
$activate = get_option('mms_its_activate', false);
$key = get_option('mms_its_the_key', '_');

?>
<form method="post" action="options.php">

<div class="wrap tgmrp">
	<h1 class="title">Holy Day Off</h1>

	<?php settings_errors(); ?>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-1">Activate</a></li>
      <li class="tab-2-h"><a href="#tab-2">Settings</a></li>
    <?php
    if(!$activate){
    ?>
  <li class=""><a href="#tab-4" style="font-weight: bold">Buy Pro Version</a></li>
    <?php
    }
    ?>
    <li class=""><a href="#tab-3">Support</a></li>
  </ul>

  <div class="tab-content">
    <div id="tab-1" class="tab-pane active">
        <?php
          settings_fields( 'shabbat_off_options_group' );
          do_settings_sections( 'shabbat_off_plugin_activate_setting' );
          submit_button();
        ?>
    </div>
    <div id="tab-2" class="tab-pane">
        <?php
          settings_fields( 'shabbat_off_options_group' );
          do_settings_sections( 'shabbat_off_plugin_activate_setting2' );
          submit_button();
        ?>
    </div>
    <div id="tab-3" class="tab-pane">
      <h3>For support, please contact:</h3>
      <a href="mailto:support@media-maven.co.il">support@media-maven.co.il</a>
    </div>
     <div id="tab-4" class="tab-pane">
      <h3>Buy Pro Version:</h3>
      <a href="https://topwp.net/product/holy-day-off-wp-plugin/" target="_blank">https://topwp.net/product/holy-day-off-wp-plugin/</a>
    </div>
  </div>
</div>
</form>

<div class="footer">
    by: <a href="https://topwp.net/" target="_blank">TopWP</a>
</div>

<script>
function showOrHide(){
    if(jQuery('.redirect-checkbox input')[0].checked) {
      jQuery('.redirect-option').show();
      jQuery('.alert-option').hide(100);
      jQuery('.mm_woocommerce').hide(100);
    } else {
      jQuery('.redirect-option').hide(100);

      jQuery('.alert-option').show();

      jQuery('.mm_woocommerce').show();
    }
  }
  jQuery('.redirect-checkbox input').change(function() {
    showOrHide();
  })
  showOrHide();
  <?php
    if($activate){
  ?>
      jQuery( document ).ready(function() {
        document.querySelector("ul.nav-tabs li.active").classList.remove("active");
        document.querySelector(".tab-pane.active").classList.remove("active");
        document.querySelector("div#tab-2").classList.add("active");
		    document.querySelector("ul.nav-tabs li.tab-2-h").classList.add("active");
      });
  <?php
    }
    if (! class_exists( 'woocommerce' ) ) {
      ?>
      jQuery('.mm_woocommerce').hide(100);
      <?php
    }
  ?>
</script>
