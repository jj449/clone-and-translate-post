<?php 

$api_key= "AIzaSyB7gXCJhkTQWlzO21MVKd0umdSND4BiEAY" ;
$source = "zh-TW"; 
$target =  "en" ; 



   if(!session_id()) {
        session_start();
    }
 
require( dirname( __FILE__ ) . '../../../../wp-blog-header.php' );


//echo  $_SESSION['catp_post_id']."<br>" .  $_SESSION['catp_post_title'] . "<br>" . $_SESSION['catp_post_content'] ; 

//$url = "http://www.google.com/" ;



$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyB7gXCJhkTQWlzO21MVKd0umdSND4BiEAY&source=zh-TW&target=en&q=" ; 


$catp_post_id =  $_SESSION['catp_post_id'] ; 
$catp_title = $_SESSION['catp_post_title'] ; 
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




global $wpdb;
$baboo= $wpdb->get_var("SELECT post_content FROM wp_posts WHERE ID = " . $catp_post_id );
//echo $baboo;

$catp_content = $baboo ; 
//$catp_content = str_replace("&nbsp;","",$catp_content);

/*
$catp_content = "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人&nbsp;" ;


$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
$catp_content = $catp_content. "中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人中國人" ;
*/

//echo $catp_content; 
//echo mb_strlen($catp_content)  ; 






$catp_content_length = mb_strlen($catp_content)  ; 
echo   "catp_content_length=" . $catp_content_length . "<br>" ;
$sub_length = 1000 ; 
$sub_start =0 ;


$new_content="";


while ($sub_start < $catp_content_length) {
	$this_content = mb_substr($catp_content,$sub_start,$sub_length);
	if ($sub_start==1000) {
		//echo $this_content;
	}
	//$this_content  = html_entity_decode($this_content ) ; 
	echo $this_content;
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
	echo "run ="  . $sub_start;
	sleep(1);
}

echo $new_content ; 
/*
function so46492768_insert_post() {
    $postContent = 'test post';
    $title = 'test title';

    // Create post object
    $my_post = array(
        'post_title'    => $catp_title,
        'post_content'  => $new_content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_category' => 'uncategorized'
    );

    // Insert the post into the database
    wp_insert_post( $my_post );

    echo 'At least the code executed :/';
}
add_action('init','so46492768_insert_post');
*/


 $my_post = array(
        'post_title'    => $catp_title,
        'post_content'  => $new_content,
        'post_status'   => 'publish',
        'post_author'   => 1
      //  'post_category' => 'uncategorized'   // category and tag must be array 
    );

    // Insert the post into the database
    wp_insert_post( $my_post );

//echo $new_content ; 


//$url = html_entity_decode ($url) ;

//echo file_get_contents($url) ;

//$response = wp_remote_get($url);




//echo "123";
?>
