<?php
/**
 * Plugin Name: Easy Cookie Law
 * Description: Minimal code to make sure your website repect the coockie law
 * Version: 0.1.2
 * Author: Antonio Sanchez
 * Author URI: http://antsanchez.com
 * Text Domain: easy-cookie-law
 * Domain Path: easy-cookie-law
 * License: GPL2 v2.0

    Copyright 2014  Antonio Sanchez (email : antonio@antsanchez.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
*
* Load Plugin Textdomain
*
*/
load_plugin_textdomain('easy-cookie-law', false, basename( dirname( __FILE__ ) ) . '/languages' );

/**
*
* Admin Styles
*
*/
add_action( 'admin_init', 'ecl_style' );
function ecl_style(){
    wp_register_style( 'easy-cookie-law', plugins_url('/easy-cookie-law/css/ecl-style.css') );
}

function ecl_enqueue_style(){
    wp_enqueue_style('easy-cookie-law');
}

/**
*
* Menu Page functions
*
*/

// Add menu page
add_action('admin_menu', 'ecl_menu');
function ecl_menu(){
    $page = add_options_page( 'Easy Cookie Law', 'Easy Cookie Law', 'manage_options', 'ecl_menu', 'ecl_options');
    add_action( 'admin_print_styles-' . $page, 'ecl_enqueue_style' );
}

// Global variables and default values
$empty_options = array("ecl_text" => "Cookies help us deliver our services. By using our services, you agree to our use of cookies.",
                                "ecl_link" => "http://www.aboutcookies.org/",
                                "ecl_link_text" => "More Info",
                                "ecl_close" => "Close",
                                "ecl_position" => "bottom",
                                "ecl_custom" => "0",
                                "ecl_noticecolor" => "#ffffff",
                                "ecl_textcolor" => "#000000",
                                "ecl_linkscolor" => "#b30000");

// Display form, collect and save data
function ecl_options(){

    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'easy-cookie-law' ) );
	}

    global $empty_options;

    if(isset($_POST['enviar'])){

        $opciones_saved = $empty_options;

        if(isset($_POST['ecltext'])){
            $opciones_saved['ecl_text'] = sanitize_text_field($_POST['ecltext']);
        }
        if(isset($_POST['ecllink'])){
            $opciones_saved['ecl_link'] = esc_url($_POST['ecllink']);
        }
        if(isset($_POST['ecllinktext'])){
            $opciones_saved['ecl_link_text'] = sanitize_text_field($_POST['ecllinktext']);
        }
        if(isset($_POST['eclclose'])){
            $opciones_saved['ecl_close'] = sanitize_text_field($_POST['eclclose']);
        }
        if(isset($_POST['eclposition'])){
            $opciones_saved['ecl_position'] = esc_attr($_POST['eclposition']);
        }
        if(isset($_POST['eclcustom'])){
            $opciones_saved['ecl_custom'] = esc_attr($_POST['eclcustom']);
        }
        if(isset($_POST['eclnoticecolor'])){
            $opciones_saved['ecl_noticecolor'] = esc_attr($_POST['eclnoticecolor']);
        }
        if(isset($_POST['ecltextcolor'])){
            $opciones_saved['ecl_textcolor'] = esc_attr($_POST['ecltextcolor']);
        }
        if(isset($_POST['ecllinkscolor'])){
            $opciones_saved['ecl_linkscolor'] = esc_attr($_POST['ecllinkscolor']);
        }

        echo "<div class='wrap'>";
        foreach($opciones_saved as $valor){
            echo "Saved: $valor <br>";
        }
        echo "</div>";

        update_option("ecl_options", $opciones_saved);

    }else{

        $opciones_saved = get_option("ecl_options", $empty_options);

?>
    <div class="wrap">
    <h1><?php echo __('Easy Cookie Law Menu Options', 'easy-cookie-law'); ?></h1>
        <form method="post">
    
            <!-- Text -->
            <div class="caja">
            <div class="form-box">
                <label for="ecltext"><?php echo __('Message', 'easy-cookie-law'); ?></label>
                <textarea rows="5" name="ecltext" id="ecltext"><?php echo sanitize_text_field($opciones_saved["ecl_text"]); ?></textarea>
                <em><?php echo __("People will see this notice only the first time that they enter your site", "easy-cookie-law"); ?></em><br>
            </div>
            </div>

            <!-- Link -->
            <div class="caja">
            <div class="form-box">
                <label for="ecllink"><?php echo __('More Info URL', 'easy-cookie-law'); ?></label>
                <input type="url" name="ecllink" id="ecllink" value="<?php echo esc_url($opciones_saved["ecl_link"]); ?>" />
            </div>
            <div class="form-box">
                <label for="ecllinktext"><?php echo __('More Info text (text showed in the link)', 'easy-cookie-law'); ?></label>
                <input type="text" name="ecllinktext" id="ecllinktext" value="<?php echo sanitize_text_field($opciones_saved["ecl_link_text"]); ?>" />
            </div>
            </div>

            <!-- Close Text -->
            <div class="caja">
            <div class="form-box">
                <label for="eclclose"><?php echo __('Text for the link to close the message', 'easy-cookie-law'); ?></label>
                <input type="text" name="eclclose" id="eclclose" value="<?php echo sanitize_text_field($opciones_saved["ecl_close"]); ?>" />
            </div>
            </div>

            <!-- Position -->
            <div class="caja">
            <div class="form-box">
                <label for="eclposition"><?php echo __('Position of the notice', 'easy-cookie-law'); ?></label>
                <?php
                if($opciones_saved['ecl_position'] == "top"){
                    $top = 1;
                }else{
                    $top = 2;
                }
                ?>
                <input type="radio" name="eclposition" value="top" <?php if($top == "1"): ?>checked<?php endif; ?>><?php echo __('Top', 'easy-cookie-law'); ?></input>
                &nbsp; <input type="radio" name="eclposition" value="bottom" <?php if($top == "2"): ?>checked<?php endif; ?>><?php echo __('Bottom', 'easy-cookie-law'); ?></input>
            </div>
            <div class="form-box">
                <label for="eclnoticecolor"><?php echo __('Background color of the notice', 'easy-cookie-law'); ?></label>
                <input type="color" name="eclnoticecolor" id="eclnoticecolor" value="<?php echo esc_attr($opciones_saved["ecl_noticecolor"]); ?>" />
            </div>
            <div class="form-box">
                <label for="ecltextcolor"><?php echo __('Text color of the notice', 'easy-cookie-law'); ?></label>
                <input type="color" name="ecltextcolor" id="ecltextcolor" value="<?php echo esc_attr($opciones_saved["ecl_textcolor"]); ?>" />
            </div>
            <div class="form-box">
                <label for="ecllinkscolor"><?php echo __('Links color of the notice', 'easy-cookie-law'); ?></label>
                <input type="color" name="ecllinkscolor" id="ecllinkscolor" value="<?php echo esc_attr($opciones_saved["ecl_linkscolor"]); ?>" />
            </div>
            </div>

            <div class="caja">
            <div class="form-box-check">
                <label for="eclcustom"><?php echo __('Let me use my own CSS', 'easy-cookie-law'); ?></label>
                <input type="checkbox" name="eclcustom" id="eclcustom" value='1' <?php if($opciones_saved['ecl_custom'] == 1){ echo "checked";} ?> />
                <?php echo __('Check this if you want to use your custom CSS written in any other stylesheet.<br>All your stlyes should be included within the id #ecl_notice, since it is the only css id that this plugin uses.', 'easy-cookie-law'); ?>
            </div>
    
            <!-- Submit button -->
            <div class="caja">
            <div class="form-box">
                <input type="submit" id="enviar" name="enviar" />
            </div>
            </div>

        </form>
    </div>
<?php
    }
}

/**
*
* Function to check if the visitor is a Bot
*
*/
function ecl_crawler_detect($USER_AGENT){
	$bots_list = array(
		'Google'=> 'Googlebot',
		'Bing' => 'bingbot',
		'Rambler'=>'Rambler',
		'Yahoo'=> 'Slurp',
		'AbachoBOT'=> 'AbachoBOT',
		'accoona'=> 'Accoona',
		'AcoiRobot'=> 'AcoiRobot',
		'ASPSeek'=> 'ASPSeek',
		'CrocCrawler'=> 'CrocCrawler',
		'Dumbot'=> 'Dumbot',
		'FAST-WebCrawler'=> 'FAST-WebCrawler',
		'GeonaBot'=> 'GeonaBot',
		'Gigabot'=> 'Gigabot',
		'Lycos spider'=> 'Lycos',
		'MSRBOT'=> 'MSRBOT',
		'Altavista robot'=> 'Scooter',
		'AltaVista robot'=> 'Altavista',
		'ID-Search Bot'=> 'IDBot',
		'eStyle Bot'=> 'eStyle',
		'Scrubby robot'=> 'Scrubby',
	);
	
    $regexp= '/'.  implode("|", $bots_list).'/';
	if ( preg_match($regexp, $USER_AGENT)){
		return true;
		// It is a bot
    }else{
		return false;
		// It is not
	}
}
 
/**
*
* Creates cookie
*
*/
function ecl_cookie_test(){

	if(!ecl_crawler_detect($_SERVER['HTTP_USER_AGENT'])){
		$name = "easy-cookie-law";
		session_start();
		global $ecl_user;
		if(isset($_COOKIE[$name])){
			$ecl_user = $_COOKIE[$name];
			if($ecl_user == 1){ 
				setcookie($name, 3, time() + (86400 * 30), "/"); 
				$ecl_user = 3;
			}
		}else{
			setcookie($name, 1, time() + (86400 * 30));
			$ecl_user = 1;
		}
	}
}
add_action('get_header', 'ecl_cookie_test', 1);

/**
*
* Print CSS and JavaScript needed
*
*/
function ecl_print_styles(){

    global $ecl_user;
    if($ecl_user == 1){

    global $empty_options;
    $opciones_saved = get_option("ecl_options", $empty_options);
    $eclcustom = esc_attr($opciones_saved['ecl_custom']);

    if($eclcustom == 0){
        $eclback = esc_attr($opciones_saved['ecl_noticecolor']);
        $ecltext = esc_attr($opciones_saved['ecl_textcolor']);
        $ecllink = esc_attr($opciones_saved['ecl_linkscolor']);
        
        if($opciones_saved['ecl_position'] == "top"){
            echo "<style type='text/css'>#ecl-notice{position: fixed; z-index: 1000000; top: 0; left: 0; width: 100%; font-size: 14px; padding: 0.5em; background-color: $eclback; color: $ecltext;}#ecl-notice a{color:$ecllink;}</style>";
        }else{
            echo "<style type='text/css'>#ecl-notice{position: fixed; z-index: 1000000; bottom: 0; left: 0; width: 100%; font-size: 14px; padding: 0.5em; background-color: $eclback; color: $ecltext;}#ecl-notice a{color:$ecllink;}</style>";
        }
    }

    ?>
    <script type="text/javascript">function ecl_close_div(){document.getElementById('ecl-notice').style.display = "none";}</script>
    <?php

    }
}
add_action('wp_head', 'ecl_print_styles', 100);

/**
*
* Show notice, only the first time the user enter the site
*
*/
function ecl_notice(){

    global $ecl_user;
    if($ecl_user == 1){

    echo "<div id='ecl-notice'>";

        global $empty_options;
        $opciones_saved = get_option("ecl_options", $empty_options);
        
        $ecl_text = $opciones_saved['ecl_text'];
        $ecl_more_text = $opciones_saved['ecl_link_text'];
        $ecl_more_link = $opciones_saved['ecl_link'];
        $ecl_ok = $opciones_saved['ecl_close'];

        echo sanitize_text_field($ecl_text);
        echo " <a href=" . esc_url($ecl_more_link) . ">" . sanitize_text_field($ecl_more_text) . "</a>";
        echo " | <a href='#' onclick='ecl_close_div();' >" . $ecl_ok . "</a>";

    echo "</div>";

    }
}
add_action("wp_footer", 'ecl_notice');

?>