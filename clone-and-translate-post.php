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
if(!session_id()) {
        session_start();
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


/*
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'catp_plugin_settings_link' );
function catp_plugin_settings_link( $links )
{
   // echo "<script> alert('123');</script>" ;
    $url = 'https://www.google.com';
    $_link = '<a href="'.$url.'" target="_blank">' . __( 'Setting', 'domain' ) . '</a>';
    $links[] = $_link;
    return $links;
}
*/




function Clone_Translate($actions, $page_object)
{
   $actions['google_link'] = '<a href="http://google.com/search?q=' . $page_object->post_title . '" class="google_link">' . __('Clone&Translate') . '</a>';
    echo "<script> alert('search google');</script>" ;
   return $actions;
}
add_filter('post_row_actions', 'Clone_Translate', 10, 2);


$catp_post_id= 0 ; 
$catp_post_title= "" ; 
$catp_post_content= "" ; 

function handel_session() {
    $queried_object = get_queried_object();

  if ( $queried_object ) {
   // echo "queried_object";
    global $post , $catp_post_id,$catp_post_title,$catp_post_content ; 
      $post_id = $queried_object->ID;
    //  echo $post_id . "<br>";
    //  echo $post->ID . "<br>";
      $_SESSION['catp_post_id'] =$post->ID  ;
    //  echo "<span id='sp_post_id' style='display:none;'>" .  $post->ID    . "</span>";
    //  echo $post->post_title . "<br>";
       $_SESSION['catp_post_title'] = $post->post_title ;
     // echo $post->post_content. "<br>";
     //  $_SESSION['catp_post_content'] = $post->post_content ;

      $_SESSION['catp_post_content'] = wpautop( $post->post_content );
     //  $_SESSION['catp_post_content'] = apply_filters('the_content', $post->post_content);
      
  }

}
add_action( 'template_redirect', 'handel_session' );
//add_action( 'wp_head', 'handel_session' );


//$old_content= the_content() ;
//$old_content = get_the_content();
$old_content ="" ; 

//include_once(ABSPATH . 'wp-includes/pluggable.php');  //  this make is_super_admin()  work 
//if (is_admin()) {
//if (current_user_can('administrator')) {

$lan_option_str="";


add_action( 'plugins_loaded', 'catp_admin123_action' );
function catp_admin123_action() {
  global $lan_option_str ; 
if ( is_super_admin()) {
    global $wpdb; 

    $query = "SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 0,1";

    $result = $wpdb->get_results($query);
    $row = $result[0];
    $latest_id = $row->ID;
    echo "<span id='sp_latest_id' style='display:none !important;'>" . $latest_id   ."</span>" ; 

    $api_key= get_option('google-translate-api-key');
    if ($api_key=="" || $api_key==false) {
          $api_key = "there is no api key";
    }
    echo "<span id='sp_api_key' style='display:none;'>" .   $api_key  ."</span>" ; 
    //change json source from url to file  
    //  $google_trans_lan_list_json =  file_get_contents(ABSPATH . 'wp-content/plugins/clone-and-translate-post/google_lan_code_list.json') ; 
       $google_trans_lan_list_json =  file_get_contents( plugin_dir_url( __FILE__ ) . 'google_lan_code_list.json') ;

      /*  
       $google_trans_lan_list_url ="https://wa.hauchat.com/livecam/get_g_trans_lan_list.php" ; 
      $response =  wp_remote_get($google_trans_lan_list_url);
      $response =  file_get_contents($google_trans_lan_list_url);
      if ( is_array( $response ) ) {
          $google_trans_lan_list_json = $response['body'];
      } else {
         //echo "not array" . "<br>" ; 
      }
      */

      $arr = json_decode($google_trans_lan_list_json,true);
     //echo "arr length=" .  count($arr) . "<br>";
      
      foreach($arr as $item) { //foreach element in $arr
          $lan_option_str =  $lan_option_str . "<option value='" . $item['google_lan_code']   . "'>" .   $item['lan_name']   . "</option>"   ; 
        //  $lan_option_str =  $lan_option_str . "<option value='" . $item->google_lan_code   . "'>" .   $item->lan_name   . "</option>"   ; 
      }
     // echo "<span id='sp_lan_option_str' style='display:none;'>" .  $lan_option_str   ."</span>" ; 
      //echo  $lan_option_str ; 
      //echo "lan_list_length=" . strlen( $lan_option_str)    ;

    function to_footer($content) {
       global  $lan_option_str;
      if( is_single() ) {
        $old_content = $content;
          $trans_btn  ="<div style='width:100%;text-align:center;background-color:yellow;padding:3px;'>" ;
           $trans_btn  = $trans_btn ."<span style='font-size:50%;'>From<span>";
            $trans_btn  = $trans_btn . "<select id='select_old_lan' style='font-size:50%;width:20%;'>"; 
              $trans_btn  = $trans_btn .  $lan_option_str   ; 
             $trans_btn  = $trans_btn . "</select>"; 
             $trans_btn  = $trans_btn ."<span style='font-size:50%;'>To<span>";
             $trans_btn  = $trans_btn . "<select id='select_new_lan' style='font-size:50%;width:20%;'>"; 
              $trans_btn  = $trans_btn .  $lan_option_str   ; 
             $trans_btn  = $trans_btn . "</select>"; 
          $trans_btn =  $trans_btn . "<button id='btn_translate' style='font-size:80%;margin-left:10px;border-radius:20%;'>Translate This Post</button>"; 

          return $content .   $trans_btn; 
        } else {
           return $content;
        }
    }
    add_action('the_content', 'to_footer');

    function output_html_modal() {
      echo '<div class="modal" id="myModal"> ' ; 
      echo '    <div class="modal-dialog">';
      echo '      <div class="modal-content">';
      echo '        <!-- Modal Header -->';
      echo '        <div class="modal-header">';
      echo '          <h4 class="modal-title">Processing ... </h4>';
      echo '          <button type="button" class="close" data-dismiss="modal">&times;</button>';
      echo '        </div>';
      echo '        <!-- Modal body -->';
      echo '        <div class="modal-body" align=center >';
      echo '          <img id ="img_processing" src="' ;
      echo plugin_dir_url( __FILE__ ) . "processing.gif" ;
      echo '"/>';
      echo '        </div>';
      echo '        <!-- Modal footer -->';
      echo '        <div class="modal-footer">';
      echo '          <button type="button" id="btn_goto_new" class="btn btn-primary" data-dismiss="modal" style="display:none;">done ! go to check new post !</button>';
      echo '          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>';
      echo '        </div>';
      echo '      </div>';
      echo '    </div>';
      echo '</div>';
    }
    add_action( 'wp_head', 'output_html_modal' );
  }
}  
?>
<?php
function catp_register_settings() {
   add_option( 'google-translate-api-key', 'This is my option value.');
   register_setting( 'catp_options_group', 'google-translate-api-key', 'myplugin_callback' );
}
add_action( 'admin_init', 'catp_register_settings' );


function myplugin_register_options_page() {
  add_options_page('catp options', 'catp options', 'manage_options', 'myplugin', 'catp_option_page');
  //add_menu_page('catp options', 'catp options', 'manage_options', 'myplugin', 'myplugin_options_page');
}
add_action('admin_menu', 'myplugin_register_options_page');

function catp_option_page()
{
?>
  <div>
 
  <h2>CATP- Clone-And-Translate-Post</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'catp_options_group' ); ?>
  <h3>If you get message "out of testing quota"  when translate post , you have out of quota for testing , you have to setup your own google translate api key to continue to translate post</h3>
<p> your google translate api key will be saved in your own wp database only , won't be transfer to remote or anywhere </p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="google-translate-api-key">google api key</label></th>
  <td><input type="text" id="google-translate-api-key" name="google-translate-api-key" value="<?php echo get_option('google-translate-api-key'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}

function catp_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'CATP', 'manage_options', 'test-plugin', 'test_init' );
}
add_action('admin_menu', 'catp_plugin_setup_menu');

function test_init(){
        echo "<h1>888</h1>";
}


?>
<script type="text/javascript" src= <?php echo plugin_dir_url( __FILE__ ) . "public/js/jquery-1.10.2.min.js" ;?>></script> 
<script type="text/javascript" src= <?php echo plugin_dir_url( __FILE__ ) . "public/js/jquery.blockUI.js" ;?>></script> 

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
  jQuery(document).ready(function($) {
    var old_content = $(".entry-content").html();
     
/*
     var trans_btn  ="<div style='width:100%;text-align:center;'>" ;
      trans_btn =  trans_btn + "<button id='btn_translate'>Translate This Post</button>"; 
      trans_btn =  trans_btn + "</div>" ;
      $(".entry-content").after(trans_btn) ;
*/
var new_post_id=0 ; 
    
    $(document).on("click","#btn_goto_new",function() {
      location.href="<?php echo get_site_url() ; ?>" + "?p=" + new_post_id; 
    });

    $(document).on("click","#btn_translate",function() {
      if ($("#sp_api_key").text() == "there is no api key") {
        alert("You have to setup your own google api key to translate") ;
        //$("dv_msg").html("You have to setup your own google api key to translate") ; 
        return ; 
      }
        

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
            old_lan:$("#select_old_lan").val(),
             new_lan:$("#select_new_lan").val(),
             api_key:$("#sp_api_key").text()
          }
        }).done(function(msg) {
         //  alert("msg=" + msg) ;
           //  include wp-blog-header.php  in translate.php will cause  return whole html content(include js code) 
           // so the msg here can't be used 

          
         //  alert("old id=" + $("#sp_post_id").text()) ;
           new_post_id=  Number($("#sp_latest_id").text() ) + 1 ;   
           //alert("new id=" + new_post_id) ; 
           // alert("new id=" + new_post_id) ;
           $("#btn_goto_new").show() ; 
           var done_img = "<?php echo plugin_dir_url( __FILE__ ) . "done.png" ; ?>" ; 
           $("#img_processing").attr("src",done_img);

        });
  }) ; 
});
</script>