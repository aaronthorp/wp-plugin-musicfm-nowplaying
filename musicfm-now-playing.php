<?php
/**
 * Plugin Name: MusicFM Now Playing
 * Plugin URI: https://github.com/aaronthorp/wp-plugin-musicfm-nowplaying
 * Description: This plugin provides Now Playing information for your WordPress Site
 * Version: 1.0.2
 * Author: Aaron Thorp
 * Author URI: https://aaronthorp.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: musicfm-now-playing
 * Domain Path: /languages
 *
 * @package WordPress Contributors
 */

// Define Constants.
define( 'MFM_URI', plugins_url( 'musicfm-now-playing' ) );
define( 'MFM_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'MFM_PLUGIN_PATH', __FILE__ );

add_action('admin_menu', 'mfm_plugin_setup_menu');

register_activation_hook( __FILE__, 'mfm_create_db' );

function mfm_create_db() {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'mfm_now_playing';
    
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
        
        $sql = "CREATE TABLE $table_name ( 
            ID mediumint(9) NOT NULL AUTO_INCREMENT, 
            `time` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL, 
            `artist` text NOT NULL, 
            `title` text NOT NULL, 
            `type` text NOT NULL,
            `stamp` text NOT NULL,
            PRIMARY KEY (ID) 
            ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        dbDelta( $sql );
    }
}

add_action('wp_footer', 'mfm_now_playing_updater');

function mfm_now_playing_updater(){
?>
<script>
    function mfmUpdatePlayer() {
        jQuery.ajax({
        url: <?php echo wp_json_encode( esc_url_raw( rest_url( 'mfm/nowplaying' ) ) ); ?>
    }).done(function( data ) {
        jQuery(".mfm_now_playing_artist").text( data.artist );
        jQuery(".mfm_now_playing_title").text( data.title );
        jQuery(".mfm_now_playing_type").text( data.type );
    });
    }
    mfmUpdatePlayer();
    setInterval(mfmUpdatePlayer, 10000);
</script>
<?php
};

// File Includes
//include_once 'apis/class-mfm-set-playing-api.php';
include_once 'apis/class-mfm-now-playing-api.php';

// Admin Page and Button
function mfm_plugin_setup_menu(){
        add_menu_page( 'MusicFM Now Playing', 'MusicFM', 'manage_options', 'test-plugin', 'mfm_admin_init' );
}
 
function mfm_admin_init(){
    global $wpdb;

    mfm_admin_handle_post();
?>
    <div class="wrap">
    <h1>MusicFM Now Playing v1.0.2</h1>
    
    <h2>Current Song</h2>
    <span class="mfm_now_playing_artist">Loading...</span> - <span class="mfm_now_playing_title"></span>
    
    <h2>Recent Songs</h2>
    <p>Showing the 20 most recent songs.</p>
        <table class="table">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Type</th>
                <th>Artist</th>
                <th>Title</th>
            </tr>
        </thead>
        <tbody>
<?
$table_name = $wpdb->prefix . 'mfm_now_playing';
		
$response = $wpdb->get_results("SELECT * FROM $table_name ORDER BY time DESC LIMIT 20;");

foreach($response as $song) {
    ?>
    <tr>
    <td><? echo $song->time; ?></td>
    <td><? echo $song->type; ?></td>
    <td><? echo $song->artist; ?></td>
    <td><? echo $song->title; ?></td>
    </tr>

    <?
}
?>
</tbody>
        </table>
    </div>
<?
mfm_now_playing_updater();
}
 
function mfm_admin_handle_post() {

}

?>