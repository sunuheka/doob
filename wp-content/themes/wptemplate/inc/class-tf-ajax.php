<?php

namespace TF;

use WP_REST_Server;
use WP_REST_Request;
use WP_Error;

class TF_Ajax {

	const REST_NAMESPACE = 'theme/v1';
	const REST_ROUTE = 'ajax';

	private static $handlers = [];

	public static function add_handler( $action, $handler_function ) {
		self::$handlers[ $action ] = $handler_function;
	}

	public static function register_rest_route() {
		register_rest_route(
			self::REST_NAMESPACE,
			self::REST_ROUTE,
			[
				'methods' => [ WP_REST_Server::READABLE, WP_REST_Server::CREATABLE ],
				'callback' => [ __NAMESPACE__ . '\TF_Ajax', 'maybe_handle' ],
				'args' => [
					'action' => [
						'type' => 'string',
						'required' => true,
						'validate_callback' => function( $value ) {
							return isset( self::$handlers[ $value ] );
						},
					],
				],
			]
		);
	}

	public static function maybe_handle( WP_REST_Request $request ) {
		$action = $request->get_param( 'action' );

		if ( empty( $action ) || ! isset( self::$handlers[ $action ] ) ) {
			return new WP_Error( 'rest_no_route', __( 'No route was found matching the URL and request method' ), array( 'status' => 400 ) );
		}

		add_filter( 'wp_doing_ajax', '__return_true' );

		// Fix to set the Polylang current language.
		if ( function_exists( 'PLL' ) && class_exists( 'PLL_Choose_Lang_Url' ) ) {
			$choose_lang = new \PLL_Choose_Lang_Url( PLL() );
			$lang = $choose_lang->get_preferred_language();

			if ( pll_current_language() != $lang ) {
				PLL()->curlang = $lang;
				$GLOBALS['text_direction'] = PLL()->curlang->is_rtl ? 'rtl' : 'ltr';
			}
		}

		do_action( 'tf/ajax/pre', $action, $request );
		do_action( 'tf/ajax/pre/action=' . $action, $action );

		$result = call_user_func( self::$handlers[ $action ], $request->get_params(), $request );

		do_action( 'tf/ajax/post', $action, $request );
		do_action( 'tf/ajax/post/action=' . $action, $action, $request );

		$result = apply_filters( 'tf/ajax/result', $result, $action, $request );
		$result = apply_filters( 'tf/ajax/result/action=' . $action, $result, $action, $request );

		return $result;
	}

	public static function get_url() {
		return rest_url( self::REST_NAMESPACE . '/' . self::REST_ROUTE );
	}

	public static function get_path() {
		return '/' . rest_get_url_prefix() . '/' . self::REST_NAMESPACE . '/' . self::REST_ROUTE;
	}
}

add_action( 'rest_api_init', [ __NAMESPACE__ . '\TF_Ajax', 'register_rest_route' ] );
