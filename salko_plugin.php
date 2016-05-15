<?php
/*
Plugin Name: salko_plugin
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: twitter feed.
Version: Номер версии плагина, например: 1.0
Author: Salko
Author URI: http://страница_автора_плагина
*/
class SalkoWidget extends WP_Widget
{
	
	public static $settings;
	
    public function __construct() {
        parent::__construct("text_widget", "Salko Widget",
            array("description" => "A simple widget to show how WP Plugins work"));
		
		add_filter('connect1', array( __Class__, 'connect1' ) );
		add_filter( 'salko-links', array( __Class__, 'filter_add_links' ) );
		add_action( 'admin_menu', array( __Class__, 'register_my_custom_menu_page' ));
		add_action( 'wp_enqueue_scripts', array( __Class__, 'my_scripts_method' ));
		add_action( 'admin_init', array( __Class__, 'register_settings' ));
		add_action('wp_ajax_my_sajax', array( __Class__, 'my_sajax'));
		add_action('wp_ajax_nopriv_my_sajax', array( __Class__, 'my_sajax'));
		self::$settings = get_option('salko-widget');
    }
	
	public static function filter_add_links( $content ){

		//Find url and place a tag
		$urladd = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	    preg_match_all( $urladd, $content, $matches );
	    $usedpatternsurl = array();

	    foreach( $matches[0] as $pattern ){
			if( !array_key_exists( $pattern, $usedpatternsurl ) ){
				$usedpatternsurl[ $pattern ] = true;
				$content = str_replace( $pattern, '<a href="' . $pattern . '" rel="nofollow" target="_blank">' . $pattern . '</a>', $content );   
			}
		}

		//Find usernames and add links
		$useradd = "/\@[a-z0-9_]+/i";
	    preg_match_all( $useradd, $content, $matches );
		$usedPatterns = array();
		
	    foreach( $matches[ 0 ] as $pattern ){
			if( !array_key_exists( $pattern, $usedPatterns ) ){
				$usedPatterns[ $pattern ]=true;
				$content = str_replace( $pattern, '<a href="http://www.twitter.com/' . $pattern . '" rel="nofollow" target="_blank">' . $pattern . '</a>', $content );   
			}
		}

		return $content;
	}
	
	static function my_sajax() {
		$result = apply_filters('connect1','https://api.twitter.com/1.1/statuses/home_timeline.json','?count='.self::$settings[ 'salko-twitter_count' ]);
		echo $result;
		wp_die();
	}
	
	static function register_settings(){
	    register_setting( 'salko-widget', 'salko-widget');
	}
	
	static function my_scripts_method() {
		wp_enqueue_script('jquery');
		wp_register_script('scr1','/wp-content/plugins/salko_plugin/scr1.js',array(),false,false);
		wp_enqueue_script( 'scr1' );
		wp_enqueue_style('salko-style','/wp-content/plugins/salko_plugin/salko-style.css');
	}
	
	static function my_css_method() {
		wp_enqueue_style('salko-style','/wp-content/plugins/salko_plugin/salko-style.css');
	}
	
	static function my_plugin_page() {
		require_once( dirname( __FILE__ ) . '/plugin-page-view/plugin-page.php' ); 
	}
	static function register_my_custom_menu_page(){
		$menu = add_menu_page('salkomenu', 'salko-plugin', 'manage_options', 'salko-widget', array( __Class__, 'my_plugin_page'), 'dashicons-twitter', 6 );
	}
	
	static function connect1($url=NULL, $field = NULL, $method = "GET") {
		require_once( dirname( __FILE__ ) . '/twitter-api-php/TwitterAPIExchange.php' );
		$settings_twitter = array(
				'oauth_access_token' 		=> self::$settings[ 'salko-twitter_a_t' ],
				'oauth_access_token_secret' => self::$settings[ 'salko-twitter_a_t_s' ],
				'consumer_key' 				=> self::$settings[ 'salko-twitter_c_k' ],
				'consumer_secret' 			=> self::$settings[ 'salko-twitter_c_s' ],
		);
			
		if( $url !== NULL ) {
			$twitter = new Salko_TwitterAPIExchange( $settings_twitter );
			$response = $twitter->setGetfield($field)->buildOauth( $url, $method )->performRequest();
			$items = $response ? json_decode( $response ) : array();
			if(!isset($items->errors)) {
				$str = '';
				for ($i=0; $i<self::$settings[ 'salko-twitter_count' ]; $i++) {
					$urltext = apply_filters('salko-links', $items[$i]->text);
					$str .= "<li class='style_one'><figure><img src='".$items[$i]->user->profile_image_url."' alt='".$items[$i]->user->name."' /></figure><p class='text-typing'>".$urltext."</p><time title='".date('g:i A - M j Y',strtotime($items[$i]->created_at))."'>".date('M j',strtotime($items[$i]->created_at))."</time></li>";
				}
			}
			else {
				$str = 'Too match updates!';
			}
		}
		else {
			$str = 'Incorrect twitter information';
		}
		return $str;
	}

	public function form($instance) {
		$title = "";
		$text = "";
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$text = $instance["text"];
		}
 
		$tableId = $this->get_field_id("title");
		$tableName = $this->get_field_name("title");
		echo '<label for="' . $tableId . '">Title</label><br>';
		echo '<input id="' . $tableId . '" type="text" name="' .
		$tableName . '" value="' . $title . '"><br>';
	}
	
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"]);
		return $values;
	}
	
	public function widget($args, $instance) {
		echo "<section id='salko-twitter-feed-widget' class='salko-twitter-feed-widget-in'>";
		$title = $instance["title"];
		echo "<h2>$title</h2>";
		echo "<button id='twit_btn' count_twit='2'>UPDATE</button>";
		echo "<div id='stw'>";
		$result = apply_filters('connect1','https://api.twitter.com/1.1/statuses/home_timeline.json','?count='.self::$settings[ 'salko-twitter_count' ]);
		echo $result;
		echo "</div>";
		echo "</section>";
	}
	
}



add_action("widgets_init", function () {
    register_widget("SalkoWidget");
});
?>