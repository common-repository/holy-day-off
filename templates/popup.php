<?php
add_action( 'template_redirect', 'wpse_inspect_page_id' );
function wpse_inspect_page_id() {
    $page_id = get_queried_object_id();
    // var_dump(get_option( 'mms_modal_page'));
    // var_dump($page_id);
    if($page_id != get_option( 'mms_modal_page')) {
      wp_redirect( get_permalink( get_option( 'mms_modal_page') ) );
      exit;
    }
}
?>
