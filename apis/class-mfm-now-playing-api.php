<?php
/**
* Register Posts API
*
* @package REST API ENDPOINT
*/

class Mfm_Now_Playing_Api {
	/**
	* Rae_Register_Posts_Api constructor.
	*/
	function __construct() {
		add_action( 'rest_api_init', array( $this, 'mfm_rest_posts_endpoints' ) );
	}
	
	/**
	* Register posts endpoints.
	*/
	function mfm_rest_posts_endpoints() {
		/**
		* Handle Create Post request.
		*
		* This endpoint takes 'title', 'content' and 'user_id' in the body of the request.
		* Returns the user object on success
		* Also handles error by returning the relevant error if the fields are empty.
		*/
		register_rest_route( 'mfm', '/nowplaying', array(
			'methods' => 'GET',
			'callback' => array( $this, 'mfm_get_playing_endpoint_handler' ),
		));

		register_rest_route( 'mfm', '/nowplaying', array(
			'methods' => 'POST',
			'callback' => array( $this, 'mfm_set_playing_endpoint_handler' ),
		));
	}
	
	/**
	* Creat Get call back.
	*
	* @param WP_REST_Request $request
	*/
	function mfm_get_playing_endpoint_handler( WP_REST_Request $request ) {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'mfm_now_playing';
		
		$response = $wpdb->get_results("SELECT * FROM $table_name WHERE id = (select max(id) from $table_name)");
		
		if (count($response)) {
			$ret = $response[0];
		} else {
			$ret = new stdClass();
		}

		return new WP_REST_Response( $ret );
	}
	
	/**
	* Creat Get call back.
	*
	* @param WP_REST_Request $request
	*/
	function mfm_set_playing_endpoint_handler( WP_REST_Request $request ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'mfm_now_playing';
		
		$parameters = $request->get_params();

		$code = sanitize_text_field( $parameters['code'] );

		$artist = sanitize_text_field( $parameters['artist'] );
		$title = sanitize_text_field( $parameters['title'] );
		$type = sanitize_text_field( $parameters['type'] );
		$stamp = sanitize_text_field( $parameters['stamp'] );
		
		$store_arr["artist"]=$artist;
		$store_arr["title"]=$title;
		$store_arr["type"]=$type;
		$store_arr["stamp"]=$stamp;
		
		if ($code == "!cisuMehTtuobAllAstIMFcisuM") {
			$wpdb->insert( $table_name, $store_arr );
		}

		return new WP_REST_Response( $store_arr );
	}
}

new Mfm_Now_Playing_Api();
