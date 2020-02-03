<?php
/**
 * Plugin Name: MusicFM Now Playing
 * Plugin URI: https://github.com/aaronthorp/musicfm-now-playing
 * Description: This plugin provides Now Playing information for your WordPress Site
 * Version: 1.0.0
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

register_activation_hook( __FILE__, 'mfm_create_db' );

function mfm_create_db() {
    // Create DB Here
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
        jQuery("div.mfm_now_playing_artist").text( data.artist );
        jQuery("div.mfm_now_playing_title").text( data.title );
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
