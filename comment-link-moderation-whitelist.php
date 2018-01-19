<?php
/**
 * Plugin Name: Comment Link Moderation Whitelist
 * Version:     1.0
 * Plugin URI:  http://coffee2code.com/wp-plugins/comment-link-moderation-whitelist/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: comment-link-moderation-whitelist
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Allows for whitelisted URLs to not count against the comment moderation max number of links limit.
 *
 * Compatible with WordPress 4.8 through 4.9+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/comment-link-moderation-whitelist/
 *
 * @package Comment_Link_Moderation_Whitelist
 * @author  Scott Reilly
 * @version 1.0
 */

/*
 * TODO:
 * - Support (vai UI setting and/or constant) the ability to define an
 *   alternative max links limit that takes into account raw link count (so the
 *   plugin can't be abused to post, say, 100 whitelisted URLs)
 * - Add support for constant to disable admin UI and instead use whitelisted
 *   URLs defined via constnat.
 * - Add filter so list of whitelisted URLs can be programmatically modified.
 */

/*
	Copyright (c) 2018 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_CommentLinkModerationWhitelist' ) ) :

class c2c_CommentLinkModerationWhitelist {

	/**
	 * Name for meta key used to store id of trashing user.
	 *
	 * @access private
	 * @var string
	 */
	private static $setting_name = 'c2c-comment-link-moderation-whitelist';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.0
	 */
	public static function version() {
		return '1.0';
	}

	/**
	 * Hooks actions and filters.
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_action( 'init',       array( __CLASS__, 'do_init' ) );
		add_action( 'admin_init', array( __CLASS__, 'initialize_setting' ), 9 );
	}

	/**
	 * Performs initializations on the 'init' action.
	 *
	 * @since 1.0
	 */
	public static function do_init() {
		// Load textdomain.
		load_plugin_textdomain( 'comment-link-moderation-whitelist' );

		// Register hooks.
		add_filter( 'comment_max_links_url', array( __CLASS__, 'comment_max_links_url' ), 10, 3 );
	}

	/**
	 * Initializes setting.
	 */
	public static function initialize_setting() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		register_setting(
			'discussion',
			self::$setting_name,
			array(
				'type'              => 'string',
				'description'       => __( 'List of URLs that should not count towards the moderation max number of links.', 'comment-link-moderation-whitelist' ),
				'sanitize_callback' => array( __CLASS__, 'sanitize_option' ),
				'show_in_rest'      => false,
				'default'           => '',
			)
		);

		add_settings_field(
			self::$setting_name,
			__( 'Comment Link Moderation Whitelist', 'comment-link-moderation-whitelist' ),
			array( __CLASS__, 'display_option' ),
			'discussion',
			'default',
			array( 'label_for' => esc_attr( self::$setting_name ) )
		);
	}

	/**
	 * Sanitizes the option.
	 *
	 * Basically duplicates core's sanitization of 'moderation_keys' setting.
	 *
	 * @since 1.0
	 *
	 * @param string $value The value to sanitize.
	 * @return string
	 */
	public static function sanitize_option( $value ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			$error = $value->get_error_message();
		} else {
			$value = explode( "\n", $value );
			$value = array_filter( array_map( 'trim', $value ) );
			$value = array_unique( $value );
			$value = implode( "\n", $value );
		}

		if ( ! empty( $error ) ) {
			$value = get_option( self::$setting_name );
			if ( function_exists( 'add_settings_error' ) ) {
				add_settings_error( self::$setting_name, 'invalid_' . self::$setting_name, $error );
			}
		}

		return $value;
	}

	/**
	 * Displays admin setting field.
	 *
	 * @since 1.0
	 *
	 * @param array $args Array of display arguments.
	 */
	public static function display_option( $args ) {
		$value = trim( get_option( self::$setting_name ) );

		echo '<fieldset>';
		echo '<legend class="screen-reader-text"><span>' . __( 'Comment Link Moderation Whitelist', 'comment-link-moderation-whitelist' ) . '</span></legend>';

		printf(
			'<p><label for="%s">%s</label></p>',
			esc_attr( self::$setting_name ),
			__( 'The URLs listed below will not count against the moderation link limit specified just above. One domain per line. Protocol (i.e. "http://") is not necessary. Subdomains are inferred and don\'t need to be listed individually (e.g. <em>wordpress.org</em> also implies <em>developer.wordpress.org</em>).', 'comment-link-moderation-whitelist' )
		);

		printf(
			'<p><textarea name="%s" rows="5" cols="50" id="%s" class="large-text code">%s</textarea></p>',
			esc_attr( self::$setting_name ),
			esc_attr( self::$setting_name ),
			esc_textarea( get_option( self::$setting_name ) )
		);

		// Add inline JS to move Comment Blacklist field to the bottom.
		echo "<script>try { jQuery('#blacklist_keys').closest('tr').appendTo(jQuery('#comment_moderation').closest('tbody')) } catch (err) {}</script>";

		echo "</fieldset>\n";
	}


	/**
	 * Adjusts the max number of links permitted in comments to disregard the
	 * number of whitelisted links.
	 *
	 * @since 1.0
	 *
	 * @param int    $num_links The number of links found.
	 * @param string $url       Comment author's URL. Included in allowed links total.
	 * @param string $comment   Content of the comment.
	 * @return int
	 */
	public static function comment_max_links_url( $num_links, $url, $comment ) {
		// Get comment max links.
		$max_links = get_option( 'comment_max_links' );

		// Bail if the number of links doesn't exceed the maximum.
		if ( $num_links < $max_links ) {
			return $num_links;
		}

		// Get whitelisted URLs.
		$whitelist_urls = trim( get_option( self::$setting_name ) );

		// Bail if no whitelisted URLs are defined.
		if ( ! $whitelist_urls ) {
			return $num_links;
		}

		// URLs are defined one per line.
		$whitelist_urls = explode( "\n", $whitelist_urls );

		// Check if any whitelisted URLs are present in comment.
		foreach ( $whitelist_urls as $url ) {

			// Filter out protocol.
			$url = str_replace( array( 'http://', 'https://' ), '', $url );

			// Remove trailing slash.
			$url = rtrim( $url, '/' );

			// Count the number of occurrences of this particular whitelisted URL.
			$num_whitelist_links = preg_match_all( '%<a [^>]*href=[\'\"]https?://[^/>]*' . preg_quote( $url, '%' ) . '%i', $comment, $out );

			// Increase the limit by the number of whitelisted URLs (so that they don't
			// count against the limit).
			$num_links -= $num_whitelist_links;

			// Stop if there are enough whitelisted links to bring the number of
			// non-whitelisted links below the max.
			if ( $num_links < $max_links ) {
				break;
			}

		}

		return $num_links;
	}

} // end c2c_CommentLinkModerationWhitelist

c2c_CommentLinkModerationWhitelist::init();

endif; // end if !class_exists()
