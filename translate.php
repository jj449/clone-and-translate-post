<?php 
if(!session_id()) {
        session_start();
}

$api_key ="";

if (isset($_POST['api_key'])) {
	$api_key = $_POST['api_key']; 
} 

if (isset($_GET['api_key'])) {
	$api_key = $_GET['api_key']; 
} 

if ($api_key =="") {
	exit;
}


$source = "zh-TW"; 
$target =  "en" ; 


if (isset($_POST['old_lan'])) {
	$source = $_POST['old_lan']; 
}
if (isset($_POST['new_lan'])) {
	$target = $_POST['new_lan']; 
}

require_once( dirname( __FILE__ ) . '../../../../wp-blog-header.php' );
global $wpdb;


//quota handle move to  clone-and_translate-post.php 
/*   
$api_key_url ="https://wa.hauchat.com/livecam/get_translate_api_key.php" ; 
$api_key= get_option('google-translate-api-key');
if ($api_key=="" || $api_key==false) {
	$response =  wp_remote_get($api_key_url);
	if ( is_array( $response ) ) {
			$api_key = $response['body'];
	}
}

if ($api_key=="out of quota") {
	echo "out of quota" ;
	exit; 
}
*/ 


$catp_post_id =  $_SESSION['catp_post_id'] ; 
//echo $catp_post_id  ."<br>";
$catp_title = $_SESSION['catp_post_title'] ; 
//echo $catp_title  ."<br>";
//$catp_title = str_replace("&nbsp;","",$catp_title);

$url ="https://www.googleapis.com/language/translate/v2?key=" . $api_key . "&source=" .$source ."&target=" .$target  . "&q=" ;
	$url = $url .$catp_title ; 
	$response =  wp_remote_get($url);
	if ( is_array( $response ) ) {
		$body = $response['body'];
		$translated_txt = json_decode($body, true);
		$translated_txt = $translated_txt['data']['translations'][0]['translatedText'];
		$catp_title =  $translated_txt; 
	}

$baboo= $wpdb->get_var("SELECT post_content FROM wp_posts WHERE ID = " . $catp_post_id );
//echo $baboo;

$catp_content = $baboo ; 
//$catp_content = str_replace("&nbsp;","",$catp_content);

$catp_content_length = mb_strlen($catp_content)  ; 
//echo   "catp_content_length=" . $catp_content_length . "<br>" ;
$sub_length = 1000 ; 
$sub_start =0 ;


$new_content="";


while ($sub_start < $catp_content_length) {
	$this_content = mb_substr($catp_content,$sub_start,$sub_length);
	if ($sub_start==1000) {
		//echo $this_content;
	}
	//$this_content  = html_entity_decode($this_content ) ; 
	//echo $this_content;
	$this_content  = rawurlencode($this_content ) ; 
	
	
	$url ="https://www.googleapis.com/language/translate/v2?format=html&key=" . $api_key . "&source=" .$source ."&target=" .$target  . "&q=" ;
	$url = $url .$this_content ; 
	$response =  wp_remote_get($url);
	if ( is_array( $response ) ) {
		$body = $response['body'];
		$translated_txt = json_decode($body, true);
		$translated_txt = $translated_txt['data']['translations'][0]['translatedText'];
		if ($sub_start==1000) {
			//echo $translated_txt;
		}

		//$translated_txt = htmlentities($translated_txt) ;
		$new_content = $new_content .  $translated_txt; 
	}

	$sub_start = $sub_start + $sub_length ;
	//echo "run ="  . $sub_start;
	sleep(1);
}

//echo $new_content ; 


 $my_post = array(
        'post_title'    => $catp_title,
        'post_content'  => $new_content,
        'post_status'   => 'publish',
        'post_author'   => 1
      //  'post_category' => 'uncategorized'   // category and tag must be array 
    );

    // Insert the post into the database
    wp_insert_post( $my_post );



//echo "123";
?>
