<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wa.hauchat.com/
 * @since             1.0.0
 * @package           Clone_And_Translate_Post
 *
 * @wordpress-plugin
 * Plugin Name:       clone-and-translate-post
 * Plugin URI:        https://wa.hauchat.com/wp-plugins/clone-and-translate-post
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            YongHuaGao
 * Author URI:        https://wa.hauchat.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clone-and-translate-post
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CLONE_AND_TRANSLATE_POST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clone-and-translate-post-activator.php
 */
function activate_clone_and_translate_post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clone-and-translate-post-activator.php';
	Clone_And_Translate_Post_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clone-and-translate-post-deactivator.php
 */
function deactivate_clone_and_translate_post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clone-and-translate-post-deactivator.php';
	Clone_And_Translate_Post_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clone_and_translate_post' );
register_deactivation_hook( __FILE__, 'deactivate_clone_and_translate_post' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clone-and-translate-post.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clone_and_translate_post() {

	$plugin = new Clone_And_Translate_Post();
	$plugin->run();

}
run_clone_and_translate_post();

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'catp_plugin_settings_link' );
function catp_plugin_settings_link( $links )
{
   // echo "<script> alert('123');</script>" ;
    $url = 'https://www.google.com';
    $_link = '<a href="'.$url.'" target="_blank">' . __( 'Setting', 'domain' ) . '</a>';
    $links[] = $_link;
    return $links;
}


add_action('admin_menu', 'catp_plugin_setup_menu');
function catp_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'CATP', 'manage_options', 'test-plugin', 'test_init' );
}
function test_init(){
        echo "<h1>888</h1>";
}


function Clone_Translate($actions, $page_object)
{
   $actions['google_link'] = '<a href="http://google.com/search?q=' . $page_object->post_title . '" class="google_link">' . __('Clone&Translate') . '</a>';
    echo "<script> alert('search google');</script>" ;
   return $actions;
}
add_filter('post_row_actions', 'Clone_Translate', 10, 2);



function catp_startSession() {
    if(!session_id()) {
        session_start();
        echo  "session started" . "<br>" ;
    }
}
add_action('init', 'catp_startSession', 1);


$catp_post_id= 0 ; 
$catp_post_title= "" ; 
$catp_post_content= "" ; 

function handel_session() {
    $queried_object = get_queried_object();

  if ( $queried_object ) {
    global $post , $catp_post_id,$catp_post_title,$catp_post_content ; 
      $post_id = $queried_object->ID;
    //  echo $post_id . "<br>";
    //  echo $post->ID . "<br>";
      $_SESSION['catp_post_id'] =$post->ID  ;
    //  echo $post->post_title . "<br>";
       $_SESSION['catp_post_title'] = $post->post_title ;
     // echo $post->post_content. "<br>";
     //  $_SESSION['catp_post_content'] = $post->post_content ;

      $_SESSION['catp_post_content'] = wpautop( $post->post_content );

     //  $_SESSION['catp_post_content'] = apply_filters('the_content', $post->post_content);
      // echo  wp_remote_get("http://www.google.com");
  }

}
add_action( 'template_redirect', 'handel_session' );


//$old_content= the_content() ;
//$old_content = get_the_content();
$old_content ="" ; 
function to_footer($content) 
{
$old_content = $content;
    $trans_btn  ="<div style='width:100%;text-align:center;'>" ;
    $trans_btn =  $trans_btn . "<button id='btn_translate'>Translate This Post</button>"; 
     $trans_btn =  $trans_btn . "</div>" ;
    return $content .   $trans_btn; 
}


include_once(ABSPATH . 'wp-includes/pluggable.php');  //  this make is_super_admin()  work 
//if (is_admin()) {
//if (current_user_can('administrator')) {
if ( is_super_admin()) {
add_action('the_content', 'to_footer');
}

?>


<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Processing ... </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" align=center >
        
        <img id ="img_processing" src="<?php echo plugin_dir_url( __FILE__ ) . "processing.gif" ; ?>" />
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="btn_goto_new" class="btn btn-primary" data-dismiss="modal" style="display:none;">done ! go to check new post !</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<script type="text/javascript" src= <?php echo plugin_dir_url( __FILE__ ) . "public/js/jquery-1.10.2.min.js" ; ?> ></script> 

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


<!--<script type="text/javascript" src="../wp-content/plugins/clone-and-translate-post/public/js/jquery-1.10.2.min.js"></script>-->



<script>
 // alert("WOOOOOOOO");

 // alert( <?php echo plugin_dir_path( __FILE__ )  ; ?> ) ; 
  jQuery(document).ready(function($) {
  
 
    var old_content = $(".entry-content").html();
     
/*
     var trans_btn  ="<div style='width:100%;text-align:center;'>" ;
      trans_btn =  trans_btn + "<button id='btn_translate'>Translate This Post</button>"; 
      trans_btn =  trans_btn + "</div>" ;
      $(".entry-content").after(trans_btn) ;
*/
    $(document).on("click","#btn_goto_new",function() {
      var new_post_id=5 ;
      location.href="<?php echo get_site_url() ; ?>" + "?p=" + new_post_id; 
    });

    $(document).on("click","#btn_translate",function() {
       $("#myModal").modal("show") ;
      // alert("ready to trans");
       //var plugin_ur=  "../wp-content/plugin/"  ;
     //  var plugin_url="" +  <?php echo plugin_dir_path( __FILE__ )  ; ?>   ;
     //  alert("plugin_url=" + plugin_url) ; 
      
     //  alert(window.location.href) ; 

     //the problem here is : this plugin run form wp root folder like index.php , so the  relative path is a problem 
       var post_url = "<?php echo plugin_dir_url( __FILE__ ) . "translate.php" ; ?>";  
      
      // alert("post_url=" + post_url) ; 
      
      $.ajax({ 
         url: post_url,
          type: "POST",
          data: {
            content:"123" 
          }
        }).done(function(msg) {
         //  alert("msg=" + msg) ;
           //  include wp-blog-header.php  in translate.php will cause  return whole html content(include js code) 
           // so the msg here can't be used 

           $("#btn_goto_new").show() ; 
           var done_img = "<?php echo plugin_dir_url( __FILE__ ) . "done.png" ; ?>" ; 
           $("#img_processing").attr("src",done_img);

        });
       
  }) ; 
});
 
</script>


<?php


/*
add_filter( 'plugin_row_meta','my_plugin_row_meta', 10, 2 );
function my_plugin_row_meta( $actions, $plugin_file ) {

 $action_links = array(
                        
   'donate' => array(
      'label' => __('Donate', 'my_domain'),
      'url'   => 'http://www.my-plugins-site.com/donate'
    ));

  return plugin_action_links( $actions, $plugin_file, $action_links, 'after');
}
*/


