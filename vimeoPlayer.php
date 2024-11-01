<?php
/*___________________________________________________________________________________________________________________________________________________
 _ jquery.mb.components                                                                                                                             _
 _                                                                                                                                                  _
 _ file: vimeoPlayer.php                                                                                                                            _
 _ last modified: 8/28/19 7:37 PM                                                                                                                   _
 _                                                                                                                                                  _
 _ Open Lab s.r.l., Florence - Italy                                                                                                                _
 _                                                                                                                                                  _
 _ email: matteo@open-lab.com                                                                                                                       _
 _ site: http://pupunzi.com                                                                                                                         _
 _       http://open-lab.com                                                                                                                        _
 _ blog: http://pupunzi.open-lab.com                                                                                                                _
 _ Q&A:  http://jquery.pupunzi.com                                                                                                                  _
 _                                                                                                                                                  _
 _ Licences: MIT, GPL                                                                                                                               _
 _    http://www.opensource.org/licenses/mit-license.php                                                                                            _
 _    http://www.gnu.org/licenses/gpl.html                                                                                                          _
 _                                                                                                                                                  _
 _ Copyright (c) 2001-2019. Matteo Bicocchi (Pupunzi);                                                                                              _
 ___________________________________________________________________________________________________________________________________________________*/

/*
Plugin Name: mb.vimeoPlayer for background videos
Plugin URI: http://pupunzi.com/#mb.components/mb.mbVPlayer/mbVPlayer.html
Description: Play a Vimeo video as background of your page. Go to <strong>mb.ideas > mb.vimeoPlayerPlus</strong> to activate the background video option for your pages.
Author: Pupunzi (Matteo Bicocchi)
Version: 1.2.3
Author URI: http://pupunzi.com
Text Domain: mbvplayer
*/


define("MBVPLAYER_VERSION", "1.2.3");

function mbVPlayer_get_price($plugin_prefix)
{
	$url = 'https://pupunzi.com/wpPlus/controller.php';

	$data = array(
	  'CMD' => 'GET-PRICE',
	  'plugin_prefix' => $plugin_prefix,
	);

	$args = array(
	  'body' => $data,
	  'timeout' => '5',
	  'redirection' => '5',
	  'httpversion' => '1.0',
	  'blocking' => true,
	  'headers' => array(),
	  'cookies' => array()
	);

	$response = wp_remote_post($url, $args)['body'];

	$res_array = json_decode($response, true);
	return $res_array;
}

// Set unique string for this site
function mbVPlayer_get_domain()
{
	$lic_domain = $_SERVER['HTTP_HOST'];
	if (!isset($lic_domain) || empty($lic_domain))
		$lic_domain = $_SERVER['SERVER_NAME'];
	if (!isset($lic_domain) || empty($lic_domain))
		$lic_domain = get_bloginfo('name');

	return $lic_domain;
}

$mbVPlayer_lic_domain = mbVPlayer_get_domain();

$this_plugin = plugin_basename(__FILE__);
$mbVPlayer_plus_link = "https://pupunzi.com/wpPlus/go-plus.php?locale=" . get_locale() . "&plugin_prefix=VIPL&plugin_version=" . MBVPLAYER_VERSION . "&lic_domain=" . $mbVPlayer_lic_domain . "&lic_theme=" . get_template() . "&php=" . phpversion();

if (version_compare(phpversion(), '5.6.0', '>')) {
	require('inc/mb_notice/notice.php');
	//$ytp_notice->reset_notice();
	$ytp_message = '<b>mb.vimeo_player Plus</b>: <br>Go to Plus to activate all the features!' . ' <a target="_blank" href="' . $mbVPlayer_plus_link . '">' . __('Get your <b>Plus</b> now!', 'mbvplayer') . '</a>';

	$ytp_notice = new mbideas_notice('mbVPlayer', plugin_basename(__FILE__));
	$ytp_notice->add_notice($ytp_message, 'success');
}

register_activation_hook(__FILE__, 'mbVPlayer_install');
function mbVPlayer_install()
{
// add and update our default options upon activation
	add_option('mbVPlayer_version', MBVPLAYER_VERSION);
	add_option('mbVPlayer_is_active', 'true');

	add_option('mbVPlayer_video_url', '');
	add_option('mbVPlayer_video_page', 'static');
}

$mbVPlayer_version = get_option('mbVPlayer_version');
$mbVPlayer_is_active = get_option('mbVPlayer_is_active');
$mbVPlayer_video_url = get_option('mbVPlayer_video_url');
$mbVPlayer_video_page = get_option('mbVPlayer_video_page');

if ($mbVPlayer_version != MBVPLAYER_VERSION) {
	update_option('mbVPlayer_price', mbVPlayer_get_price("VIPL"));
	update_option('mbVPlayer_version', MBVPLAYER_VERSION);
}

$mbVPlayer_price = get_option('mbVPlayer_price');

$mbVPlayer_show_controls = "false";
$mbVPlayer_show_videourl = "false";
$mbVPlayer_ratio = "16/9";
$mbVPlayer_audio_volume = 50;
$mbVPlayer_mute = true;
$mbVPlayer_start_at = 0;
$mbVPlayer_stop_at = 0;
$mbVPlayer_loop = "true";
$mbVPlayer_opacity = 10;
$mbVPlayer_quality = "default";
$mbVPlayer_add_raster = "true";
$mbVPlayer_realfullscreen = "false";
$mbVPlayer_stop_on_blur = "true";
$mbVPlayer_track_ga = "false";

//set up defaults if these fields are empty
$mbVPlayer_version = MBVPLAYER_VERSION;

if (empty($mbVPlayer_is_active)) {
	$mbVPlayer_is_active = false;
}
if (empty($mbVPlayer_show_videourl)) {
	$mbVPlayer_show_videourl = "false";
}
if (empty($mbVPlayer_video_page)) {
	$mbVPlayer_video_page = "static";
}

if (empty($mbVPlayer_price)) {
	update_option('mbVPlayer_price', mbVPlayer_get_price("VIPL"));
	$mbVPlayer_price = get_option('mbVPlayer_price');
}

//action link http://www.wpmods.com/adding-plugin-action-links
add_filter('plugin_action_links', 'mbVPlayer_action_links', 10, 2);
function mbVPlayer_action_links($links, $file)
{
	global $mbVPlayer_plus_link;
	// check to make sure we are on the correct plugin
	if ($file == plugin_basename(__FILE__)) {
		// the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
		$settings_link = '<a style="color: #008000" href="' . $mbVPlayer_plus_link . '" target="_blank">Go PLUS</a> | ';
		$settings_link .= '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wp-vimeoplayer%2FvimeoPlayer.php">Settings</a>';
		// add the link to the list
		array_unshift($links, $settings_link);
	}
	return $links;
}

add_action('wp_enqueue_scripts', 'mbVPlayer_init');
function mbVPlayer_init()
{
	global $mbVPlayer_version;

	if (!is_admin()) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('mb.vimeo_player', plugins_url('/js/jquery.mb.vimeo_player.js', __FILE__), array('jquery'), $mbVPlayer_version, true, 1000);
		wp_enqueue_style('mb.vimeo_player_css', plugins_url('/css/jquery.mb.vimeo_player.min.css', __FILE__), array(), $mbVPlayer_version, 'screen');
	}
}

add_action('plugins_loaded', 'mbVPlayer_localize');
function mbVPlayer_localize()
{
	load_plugin_textdomain('mbvplayer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// scripts to load in the footer
add_action('wp_footer', 'mbVPlayer_player_foot', 20);
function mbVPlayer_player_foot()
{
	global $mbVPlayer_video_url, $mbVPlayer_show_controls, $mbVPlayer_ratio, $mbVPlayer_show_videourl, $mbVPlayer_start_at, $mbVPlayer_stop_at, $mbVPlayer_mute, $mbVPlayer_loop, $mbVPlayer_opacity, $mbVPlayer_quality, $mbVPlayer_add_raster, $mbVPlayer_track_ga, $mbVPlayer_realfullscreen, $mbVPlayer_stop_on_blur, $mbVPlayer_video_page, $mbVPlayer_is_active, $mbVPlayer_audio_volume;

	$canShowMovie = is_front_page() && !is_home(); // A static page set as home;
	if ($mbVPlayer_video_page == "blogindex")
		$canShowMovie = is_home(); // the blog index page;
	else if ($mbVPlayer_video_page == "both")
		$canShowMovie = is_front_page() || is_home(); // A static page set as home;
	else if ($mbVPlayer_video_page == "all")
		$canShowMovie = true; // on all pages;

	if ($canShowMovie && $mbVPlayer_is_active) { // && !isMbMobile()

		if (empty($mbVPlayer_video_url))
			return false;

		if ($mbVPlayer_opacity > 1)
			$mbVPlayer_opacity = $mbVPlayer_opacity / 10;

		$vids = explode(',', $mbVPlayer_video_url);
		$n = rand(0, count($vids) - 1);
		$mbVPlayer_video_url_revised = $vids[$n];

		$mbVPlayer_start_at = $mbVPlayer_start_at > 0 ? $mbVPlayer_start_at : 1;
		$mbVPlayer_player_homevideo =
		  '<div id=\"vimeo_bgnd\" data-property=\"{ videoURL:\'' . $mbVPlayer_video_url_revised . '\',opacity:' . $mbVPlayer_opacity . ',autoPlay:true,containment:\'body\',startAt:' . $mbVPlayer_start_at . ',stopAt:' . $mbVPlayer_stop_at . ',mute:' . $mbVPlayer_mute . ',vol:' . $mbVPlayer_audio_volume . ',optimizeDisplay:true,showControls:' . $mbVPlayer_show_controls . ',printUrl:' . $mbVPlayer_show_videourl . ',loop:' . $mbVPlayer_loop . ',addRaster:' . $mbVPlayer_add_raster . ',quality:\'' . $mbVPlayer_quality . '\',ratio:\'' . $mbVPlayer_ratio . '\',realfullscreen:\'' . $mbVPlayer_realfullscreen . '\',gaTrack:\'' . $mbVPlayer_track_ga . '\',stopMovieOnBlur:\'' . $mbVPlayer_stop_on_blur . '\'}\"></div>';
		echo '
	<!-- mbVPlayer Home -->
	<script type="text/javascript">

	jQuery(function(){
	    var vimeo_video = "' . $mbVPlayer_player_homevideo . '";
	    jQuery("body").prepend(vimeo_video);
	    jQuery("#vimeo_bgnd").vimeo_player();
    });

	</script>
	<!-- end mbVPlayer Home -->
        ';
	}
}

;

add_shortcode('mbVPlayer', '__return_false');

add_action('admin_init', 'mbVPlayer_register_settings');
function mbVPlayer_register_settings()
{
	//register mbVPlayer settings
	register_setting('mbVPlayer-activate-group', 'mbVPlayer_is_active');

	register_setting('mbVPlayer-settings-group', 'mbVPlayer_version');
	register_setting('mbVPlayer-settings-group', 'mbVPlayer_video_url');
	register_setting('mbVPlayer-settings-group', 'mbVPlayer_video_page');
}

/**
 * Add root menu
 */
require("inc/mb-admin-menu.php");
add_action('admin_menu', 'mbVPlayer_add_option_page');
function mbVPlayer_add_option_page()
{
	add_submenu_page('mb-ideas-menu', 'VimeoPlayer', 'VimeoPlayer', 'manage_options', __FILE__, 'mbVPlayer_options_page');
}

function mbVPlayer_options_page()
{ // Output the options page
	global $mbVPlayer_plus_link, $mbVPlayer_price;
	?>

  <div class="wrap">
    <a href="http://pupunzi.com"><img style=" width: 350px" src="<?php echo plugins_url('images/logo.png', __FILE__); ?>" alt="Made by Pupunzi"/></a>

    <h2 class="title"><?php _e('mb.vimeoPlayer', 'mbvplayer'); ?></h2>

    <img style=" width: 120px; position: absolute; right: 0; top: 0; z-index: 100" src="<?php echo plugins_url('images/VIPL.svg', __FILE__); ?>" alt="mb.mbVPlayer icon"/>

    <h3><?php _e('From here you can set a background video for your pages.', 'mbvplayer'); ?></h3>

    <form id="optionsForm" method="post" action="options.php">
		<?php settings_fields('mbVPlayer-activate-group'); ?>
		<?php do_settings_sections('mbVPlayer-activate-group'); ?>

      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php _e('activate the background video', 'mbvplayer'); ?></th>
          <td>
            <div class="onoffswitch">
              <input class="onoffswitch-checkbox" type="checkbox" id="mbVPlayer_is_active"
                     name="mbVPlayer_is_active" value="true" <?php if (get_option('mbVPlayer_is_active')) {
				  echo ' checked="checked"';
			  } ?>/> <label class="onoffswitch-label" for="mbVPlayer_is_active"></label>
            </div>
          </td>
        </tr>
      </table>
    </form>

    <form id="optionsForm" method="post" action="options.php">
		<?php settings_fields('mbVPlayer-settings-group'); ?>
		<?php do_settings_sections('mbVPlayer-settings-group'); ?>

      <table class="form-table">
        <tr valign="top">
          <th scope="row"> <?php _e('The Vimeo video url is:', 'mbvplayer'); ?></th>
          <td>

			  <?php
			  $vimeo_video_url = get_option('mbVPlayer_video_url');

			  $vids = explode(',', $vimeo_video_url);
			  $n = count($vids);
			  $n = $n > 2 ? 2 : $n;
			  $w = (480 / $n) - ($n > 1 ? (3 * $n) : 0);
			  $h = 315 / $n;
			  foreach ($vids as $vurl) {
				  $urlParts = explode("/", parse_url($vurl, PHP_URL_PATH));
				  $videoId = (int)$urlParts[count($urlParts) - 1];
				  if ($videoId) {
					  ?>
                  <iframe src="https://player.vimeo.com/video/<?php echo $videoId ?>" width="<?php echo $w ?>"
                          height="<?php echo $h ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen
                          allowfullscreen></iframe><?php
				  }
			  } ?>

            <textarea name="mbVPlayer_video_url" style="width:100%"
                      value="<?php echo esc_attr(get_option('mbVPlayer_video_url')); ?>"><?php echo esc_attr(get_option('mbVPlayer_video_url')); ?></textarea>
            <p><?php _e('Copy and paste here the URL of the Youtube video you want as your homepage background. If you add more then one URL comma separated it will be chosen one randomly each time you reach the page', 'mbvplayer'); ?></p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php _e('The page where to show the background video is:', 'mbvplayer'); ?></th>
          <td>
            <input type="radio" name="mbVPlayer_video_page" value="static" <?php if (get_option('mbVPlayer_video_page') == "static" || get_option('mbVPlayer_video_page') == "") {
				echo ' checked';
			} ?> /> <?php _e('Static Homepage', 'mbvplayer'); ?> <br>
            <input type="radio" name="mbVPlayer_video_page" value="blogindex" <?php if (get_option('mbVPlayer_video_page') == "blogindex") {
				echo ' checked';
			} ?>/> <?php _e('Blog index Homepage', 'mbvplayer'); ?> <br>
            <input type="radio" name="mbVPlayer_video_page" value="both" <?php if (get_option('mbVPlayer_video_page') == "both") {
				echo ' checked';
			} ?>/> <?php _e('Both', 'mbvplayer'); ?> <br>
            <input type="radio" name="mbVPlayer_video_page" value="all" <?php if (get_option('mbVPlayer_video_page') == "all") {
				echo ' checked';
			} ?>/> <?php _e('All', 'mbvplayer'); ?> <br>

            <p><?php _e('Choose on which page you want the background video to be shown', 'mbvplayer'); ?></p>
          </td>
        </tr>
      </table>

      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
      </p>
    </form>
    <a href="<?php echo $mbVPlayer_plus_link ?>" target="_blank"> <img
          src="<?php echo plugins_url('/images/pro-opt.png', __FILE__); ?>"></a>
  </div>

  <!-- ---------------------------—---------------------------—---------------------------—---------------------------
Right column
---------------------------—---------------------------—---------------------------—---------------------------— -->
  <div class="rightCol">

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	PLUS box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div id="getLic" class="box box-success">
      <h3><?php _e('Get your <strong>PLUS</strong> plug-in!', 'mbvplayer'); ?></h3>
		<?php _e('The <strong>mb.vimeoPlayer PLUS</strong> plug-in enable the advanced settings panel, add a short-code editor on the post/page editor and remove the water-mark from the video player.', 'mbvplayer'); ?>
      <br>
      <br>
      <a target="_blank" href="<?php echo $mbVPlayer_plus_link ?>" class="getKey">
        <span><?php printf(__('<strong>Go PLUS</strong> for <b>%s EUR</b> Only', 'mbvplayer'), $mbVPlayer_price["COM"]) ?></span>
      </a>
    </div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	ADVs box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div id="ADVs" class="box"></div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	Info box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div class="box">
      <h3><?php _e('Thanks for installing <b>mb.vimeoPlayer</b>!', 'mbvplayer'); ?></h3>

      <p>
		  <?php printf(__('You\'re using mb.vimeoPlayer v. <b>%s</b>', 'mbvplayer'), MBVPLAYER_VERSION); ?>
        <br><?php _e('by', 'mbvplayer'); ?> <a href="http://pupunzi.com">mb.ideas (Pupunzi)</a>
      </p>
      <hr>
      <p><?php _e('Don’t forget to follow me on twitter', 'mbvplayer'); ?>: <a
            href="https://twitter.com/pupunzi">@pupunzi</a><br>
		  <?php _e('Visit my site', 'mbvplayer'); ?>: <a href="http://pupunzi.com">http://pupunzi.com</a><br>
		  <?php _e('Visit my blog', 'mbvplayer'); ?>: <a
            href="http://pupunzi.open-lab.com">http://pupunzi.open-lab.com</a><br>
        Paypal: <a
            href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted"
            target="_blank"><?php _e('donate', 'mbvplayer'); ?></a>
      <hr>
      <!-- Begin MailChimp Signup Form -->
      <form action="http://pupunzi.us6.list-manage2.com/subscribe/post?u=4346dc9633&amp;id=91a005172f"
            method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
            target="_blank" novalidate>
        <label for="mce-EMAIL"><?php _e('Subscribe to my mailing list <br>to stay in touch', 'mbvplayer'); ?>
          :</label>
        <br>
        <br>
        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL"
               placeholder="<?php _e('your email address', 'mbvplayer'); ?>" required>
        <input type="submit" value="<?php _e('Subscribe', 'mbvplayer'); ?>" name="subscribe"
               id="mc-embedded-subscribe" class="button">
      </form>
      <!--End mc_embed_signup-->
      <hr>

      <!--SHARE-->

      <div id="share" style="margin-top: 10px; min-height: 80px">
        <a href="https://twitter.com/share" class="twitter-share-button"
           data-url="http://wordpress.org/extend/plugins/mbvplayer/"
           data-text="I'm using the mb.mbVPlayer WP plugin for background videos" data-via="pupunzi"
           data-hashtags="HTML5,wordpress,plugin">Tweet</a>
        <script>!function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (!d.getElementById(id)) {
					js = d.createElement(s);
					js.id = id;
					js.src = '//platform.twitter.com/widgets.js';
					fjs.parentNode.insertBefore(js, fjs)
				}
			}(document, 'script', 'twitter-wjs')</script>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s);
				js.id = id;
				js.src = '//connect.facebook.net/it_IT/all.js#xfbml=1';
				fjs.parentNode.insertBefore(js, fjs)
			}(document, 'script', 'facebook-jssdk'))</script>
        <div style="margin-top: 10px" class="fb-like"
             data-href="http://wordpress.org/extend/plugins/mbvplayer/" data-send="false"
             data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>
      </div>
    </div>

  </div>
  <script>
	  jQuery(function () {

		  var activate = jQuery('#mbVPlayer_is_active');
		  activate.on('change', function () {
			  var val = this.checked ? 1 : 0;
			  jQuery.ajax({
				  type    : 'post',
				  dataType: 'json',
				  url     : ajaxurl,
				  data    : { action: 'mbVPlayer_activate', activate: val },
				  success : function (resp) {
				  }
			  })
		  });

		  // Add ADVs
		  jQuery.ajax({
			  type    : 'post',
			  dataType: 'html',
			  url     : 'https://pupunzi.com/wpPlus/advs.php',
			  data    : { plugin: 'YTPL' },
			  success : function (resp) {
				  jQuery('#ADVs').html(resp)
			  }
		  })

	  })
  </script>
	<?php
}


add_action('admin_enqueue_scripts', 'mbVPlayer_load_admin_script');
function mbVPlayer_load_admin_script($hook)
{
	//var_dump($hook);
	//string(40) "mb-ideas_page_wp-vimeoPlayer/vimeoPlayer"
	if ($hook == 'mb-ideas_page_wp-vimeoplayer/vimeoPlayer' && $hook != 'toplevel_page_mb-ideas-menu') {
		wp_enqueue_style('mbVPlayer_admin_css', plugins_url('/inc/mb_admin.css', __FILE__), null, MBVPLAYER_VERSION);
	}
}

add_filter('admin_body_class', 'mbVPlayer_add_body_classes');
function mbVPlayer_add_body_classes($classes)
{

	$screen = (get_current_screen()->id == "mb-ideas_page_vimeoPlayer/vimeoPlayer") ? 1 : 0;
	$classes = '';
	if ($screen)
		$classes = 'mb-free';
	return $classes;
}

/**
 * activate option saved via ajax
 */
add_action('wp_ajax_mbVPlayer_activate', 'mbVPlayer_activate');
function mbVPlayer_activate()
{
	$activate = $_POST["activate"] == 1 ? true : false;
	update_option('mbVPlayer_is_active', $activate);
}

/**
 * Water-mark
 */
add_action('wp_head', 'mbVPlayer_custom_js');
function mbVPlayer_custom_js()
{
	if (!wp_script_is('jquery', 'done')) {
		wp_enqueue_script('jquery');
	}

	$script = 'jQuery(function(){
      var class_name = null;
     setInterval (function(){
          var vpl_videos = jQuery(".vimeo_player_overlay");
          vpl_videos.each(function(){
          var vpl_video = jQuery(this);
          jQuery("[class*=vpl_wm_]", vpl_video).remove();
          class_name = "vpl_wm_" + Math.floor(Math.random()*100000);
          var $wm = jQuery("<img/>").attr("src","data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAG9CAYAAAB56wSaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAADTtJREFUeNrsXaFzIzsPdzsBAe/NLFy4MHBhYGBhYWHhwcIH+yc8WFh4MDAwMHBh4MLAzLwPBH7rjtzzqV7bsmU3vUozmU6Tze4vsixLsiQrJUSjG8rF//33v+X0p/3777/GwHXd9Getr51ezfQ6Ty/9nf303XMJYA/Tn9X0OsBDLo5rNtOfjec22+l7Q+hZtwRQHYBSwI0f03stumYVAKXpHu7FAwzA2LSEIbLpLvJe9yzAQLZW6O2dPZTTNT3IE6bT9MJD3gB3sznWov8vDjlxPeh1uu5l+vuvg7stBzAsE2PENUcze4GzQ+D6ZBnDw2MPdQsy9xuwiB/DDgxT73jvSBk6LmBNQL5ODh3X+biOaZEydJpD0/Cd4H3XbBzRUDcu8BzAXPLh01kH9P8m8p60oZyZVbM/wl4PQV/1vmtyZWzn0EWY9A/YIsXs0vJ7NuEHrr16huACCvWMvrNFmv8Ysk7I1gXSWys0C4+B6x8s8JerMfz0sGJrhJ1juQDNZJg4d8hVFxyAGlAbK1i+9hx6LAdQB4A6yvcWBYfLWLNNyj0WBYZrDTK0zLnXgnG41jPGIqYzLFlDsVkJ5nTscGmlevDpuyyOwXD1wKHo4ZoAvRYZShiufsYodA1XkzMalEX8MQLUCA7tv7lyyzUrB5CfE9cMXzCBio5J1ATWg6mtAQ4xJg03sDGwrPSWL3CoHYZqLc1OoomTz8WAobVwPeMhzdErZZiz7THQ/n2k9XCC2TsUB5aogC+g747VLFjCkqVVzP5TTGsY5vVM3MILrKgFC7I0EIe5ns0Ps3GcAO6tYb4+inHlantJDTKNWOyx5xxNDkO4QfGL8sI/Ab9Ds2+XYwZxDmWLtH+Wl3SrrpQEmAATYNdGyXpsUqiPDj1m0910jR1rbaoAizClW5ExASYkVIFyg8Nm53YFess2DkcICQwplmxOUGVDcMNGqql9kwjqMUGzXwBc1E7xbSVQxgeISjhKUbBrD6gTDJl5zW2W3sc8aJEgV5h8uWTal7xDk0InHPWhIaVwzBUQ0TGu3dxWMjz8xcG9NedQYtkYYwQZwuw7bBLBCBQBFh2Zhh9wodhrt8RZhXUThU6lgGEuUFMUxsAPFUNR3DesPJ9lKAUYs4yNzM8+KyGhr+C+QWUDJ42+rWjKrOQGtvfNdFGw31Lzv4rmFyqpYHPJygcKKtdqwADUE3LZvElItdSFK2F3LQrWQ67YxeFahN9kSUUJv9DV6DErp99soB6LhtOth77Ht7C8zMRejQsYFe9PifNv1Mc0vzM88Ajh8kfPLfTsfOGs5NKg7sHExpzQw3UPOWGhcPlSRdT6Uque+8ADH1TcpvwqFBymGIox+0Y2qBM4HBflzk9sfc4IBViH/tcPNnmsK/Ux2LuzJsUIw9yi+7EAw6z/aQQYMtOf7A8dmv2oCHtQqWvlGVUFnpENn110lwws8N7ps4CJX0mlNpB05Pq8StLRUvmTjkKfi4x9H2BCQuIlRViwz8zP9lbZiB779taFy1cciJbrWAOY6cigQ0sHjn4WlFkZW6x+4QCYEiKIrafMApgc7SG0i0gCyFGR2qn4SsDo0m3OilTTASRYfR/Tl4BNj2kuTC/dCmd7TXrMdDRa5zohbMBS1Egx4Sf2TNGCrq2IY7FZaW1UxTThOMMMHFJ+PEXz36m4Ji5ZgFJkbF0DEPusBGA9TIYYGnw/ghMYVU2MYsFK7EJIqGaIwLN29spdMGWKWw4pzYZybP61cm8RzpnUu6I2v9VNcoWcXVeb1V79CrObHoqnUsB+WA/bh7wfsGzvgbPR4Kj22J1lZWxjLQlUAabl7SU0rNQdXgPqrYYNWpCsfHKo9ykBxE/gmPGm2JYkO8XK2O2aCw+uHFnYP9cc1nvlS1SftGapS0J9rQ+WE2JkrbcfBD5ma02CNbh4g/q1fdhzcMzOsTg6bry1ZQa4Yzu19rXHGPuN2td6dBmGrgQiAGreb4CLyuLY1dQlNdaKUNQeO6MZ65LL1nF9wwlsxLKGhvXOGipbb5nhsjf3o4BR+1prvdWBTO0todaAn6C/3UX5KwtXbM4IEuS1NfN2jtnbOcJOB2vIWzQ7s2Vsb36x0fbwQNyFGXPqpzW8dxbYE+daac6A+G0xdiSxXSCIckYrQa8i07RSgipmMb5ASOAQ+E4D1kVHWfw5CthHsMdwa/IOuGgHYqItklol/2cANday+XFLaJf+G1IiQNxR68a3fgoxuH7ZSxI3oI1RN2zAkJPboZlnhH2c+a5ph98ozt7pIOAPyp1raLLPtZmtXbod+u5GEcrUqBybA/XBcZmAaFPn4ND8vMBg4aZ0BtlocJazq4oAU+6+6HY2Jw6qmExiF10U4574B+PPliNCv5URlie2JPBmxj6zjUnfTDNdaF5jwlKpeuw8E3s4eUzzLaXQIBnYjAl+nob0g+Xry+H5NL8yBVRth/fPACb5YyJjpUn6XQhdjR7j9jsVqtTJsWCf0RpIVbjSCRyTdAIXYAJMgF272YMV6qNDj9kkncBFxq4amFBJC3RzrTKmY6pPoQPKP0v438LpWrlSToitOSu1cv2hd21DWy+fpS60g/FUQv6oXQE3AZd/y7UdSN0Tj9nlGFVEuLyIwwv73fcBiyHEOe/Ob+7WMyWzE1O5iCKkNuhjR0/cwr/I5JjWZXe5thcbMCsdpleF6KaAXEVlsHDqsZiZONurv+RQPpbWXWzCz63tOYBlyxEk7V44gWXJkZUabXpHZQNLlqOZ6sITy1BSj922Fn3yEd8cwu8bLtLRo0WBBQ5rdU0gXrMnc7iijvjOKWahnvu8V4Qi9kXicMUeoWxPnn0RGQObP/bQ6SHX8uDsBG7KrQfIv6gGbI6iT7yu6VcWIw6OvXWbBBkcFFOHEAqwfUBXmWTdDbQvrG5aU7S7rS6eizu8ieshScH+eUtSpM3lo53PAmazLmCI7BPX18qdcGmofkqzdeJ6Yy30yyrCzzDM3qBKtbwLNJs1wPEqgDkSQi6+mVkzbd6VEHL6cou4ABNgAuzLmtYM+0VdEWCK/yAnkTERfkyvzM+WlGZW4nLf9OvEERrIAubxxk3p2CF3tySlTvwhUotvc0JTVHVBKUeMPjs8CxgEg1cJP6Q4x/oZXXRQ8wcYNqmpNjlljLoJwk8HVx+R192phM18Csc+HHDiCA2cIH7h+17ZtdKzC4cbbDQ1gWXpKDF7PtvswWpgztRuIq9lO7wVP3yTee1eyeGtIvxi8wt9sjMS6NU5woJ+rN2rc6PiN7bIiUocvaFiidTB4aYSKNu9e4kZ2hQvqfVwZITXHFd87UzSFCwUGLi8JOfxCVYDeLyA6xPO1qFsPVIFxIxT60x204IOm1gv6mPm05plKFHvzXdnJMbTBmHfOmy0joNj+CZnSuIk9PccA/dMAtYEPKEYOlLcutuMaa8S9BieoVdhj5H2xFOBpTixXYCDScDwTXq7aShhsY82FKndTfEqEEuuU4zze3VayR6/DQ1UdC19nLJaYWKfkqclJgzdD8cvtzuBvwWIrS6m6xl5fA3FaKnWRa8yooTGNsNt5rJnJSxBOUlrQwyoZAsW6kbuSnCKw7Ru1a8+naEZvafG/bl2RjoQ8tbSe+eY2SdU3a/EwTdqUreVPG7PUJZDdrBpvXdMht8Op0B+ZIMW8pEr2hOzHnYoOjSm3kwCdwJMgAmwSMrpQtMF3LMWNRSt1oXmMULhiowJMGqVDSeNSkjoK9r8HMLPtVnPDWyvZLNeNH88sZy6njorn1SlY+FJwCw3f1MDYE6no40qeG597mY9BaAp+DwXB2YBjD0W/k2xxkSKWIvyIgHWPbPe8oQajhstmDgVW6L9dohYDeGPVR9nRay6T00IMXW5UefVFz36quZ59VQZ+yfiGrbj4TljsO/7TJF5/DxN3SOI2qdn7/NVxVCU2IXELoQkdiGxC1Gw30DzvzI/+w/Pxyjd+TtnKHXnbz29B+7ubLkK9tkRLBlK5e5wHEh3BIDHzxrKOXrLWodjRwcuLnJHFNm4SI0odkRvKJmLOcd2m0BKES5yZNwV4SJ3KzkKF72RRe5Wcm+BYI4OuuzWBYQ75wpf6gdVEvrElmvtm9Bcz4Q6y/RRTODOCeTvyB7qLMmdXBl7KsWdosKvIntw1gJ2tuyyC6faWWRwZyjRlz8FWDHuFF0rI2MfJsygOf3T9wNvK4HqUOyjU4FSxlqeeBf5XnVgY+R7dYHB7D0gUAcl9B3oppB6cG22GktjLALMeqg93d8DLRHV9/raLbfD62pK9T7TdNoCHK0cqgPfsRWwW5yYMxK7SFBKcRWwWzdbEh94Uol9ySjWBdUde3doZ4pGW+UpMKYAa+fkZKas8WBpfn1YxRE5wE2RJckWXpcgO0waUrSHs//YiGQri0ot4pdrBfZpzsgyUMzi+rxKMYtZAVI//7pDKcBKCv++goMiJHR9zogkhIiCFbNn3jCUYhYXOClmkWIWl4deHBgRoBSzSDFLCIwUs8SSFLNwA5NiFgEmsQsO+r8AAwCeEG6R7UZv4AAAAABJRU5ErkJggg==");
          vpl_wm = jQuery("<div/>").addClass(class_name).html($wm);
          $wm.attr("style", "filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;height:100%!important; width:auto!important;display:block!important;visibility:visible!important;top:0!important;right:0!important;opacity:1!important;position:absolute!important;margin:auto!important;z-index:10000!important;")
          vpl_wm.attr("style", "filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;display:block!important;position:absolute!important;top:0!important;left:0!important;bottom:0!important;right:0!important;margin:auto!important;z-index:10000!important;max-height:450px!important;");
          vpl_video.prepend(vpl_wm);
          })
        }, 5000);
  });';
	echo "<script>" . $script . "</script>";
}

/**
 * Deactivate plugin if PLUS version exists.
 */
add_action('plugins_loaded', 'mbVPlayer_free_deactivate');
function mbVPlayer_free_deactivate()
{
	global $vplppro;
	if ($vplppro) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		deactivate_plugins(plugin_basename(__FILE__));

		$dir = plugin_dir_path(__FILE__);

		deleteDir($dir);

	}
}

if (!function_exists("deleteDir")) {

	function deleteDir($dirPath)
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
}
