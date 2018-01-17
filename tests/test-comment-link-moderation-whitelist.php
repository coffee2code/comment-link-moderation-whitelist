<?php

defined( 'ABSPATH' ) or die();

class Comment_Link_Moderation_Whitelist_Test extends WP_UnitTestCase {

	protected static $setting_name = 'c2c-comment-link-moderation-whitelist';

	public function setUp() {
		parent::setUp();

		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$this->server = $wp_rest_server = new \WP_REST_Server;
		do_action( 'rest_api_init' );
	}

	public function tearDown() {
		parent::tearDown();
		$this->unset_current_user();
	}


	//
	// HELPER FUNCTIONS
	//


	private function create_user( $set_as_current = true ) {
		$user_id = $this->factory->user->create();
		if ( $set_as_current ) {
			wp_set_current_user( $user_id );
		}
		return $user_id;
	}

	// helper function, unsets current user globally. Taken from post.php test.
	private function unset_current_user() {
		global $current_user, $user_ID;

		$current_user = $user_ID = null;
	}

	private function check_comment( $comment_max_links = 2, $link_urls = array() ) {
		update_option( 'comment_whitelist', 0 );
		update_option( 'comment_max_links', $comment_max_links );

		$author       = 'BobtheBuilder';
		$author_email = 'bob@example.com';
		$author_url   = 'http://example.com';
		$author_ip    = '192.168.0.1';
		$user_agent   = '';
		$comment_type = '';

		if ( ! $link_urls ) {
			$comment  = 'This is a comment with <a href="http://example.com">multiple</a> <a href="http://bob.example.com">links</a>.';
		} else {
			$comment  = 'This is a comment with';
			foreach ( (array) $link_urls as $i => $link ) {
				$comment .= ' <a href="http://' . $link . '">link #' . $i . '</a>';
			}
			$comment .= '.';
		}

		return check_comment( $author, $author_email, $author_url, $comment, $author_ip, $user_agent, $comment_type );
	}


	//
	// TESTS
	//


	public function test_plugin_version() {
		$this->assertEquals( '1.0', c2c_CommentLinkModerationWhitelist::version() );
	}

	public function test_class_is_available() {
		$this->assertTrue( class_exists( 'c2c_CommentLinkModerationWhitelist' ) );
	}

	// Duplicates core test of this behavior.
	public function test_should_return_false_when_link_count_exceeds_comment_max_length_setting() {
		$this->assertFalse( $this->check_comment() );
	}

	// Duplicates core test of this behavior.
	public function test_should_return_true_when_link_count_does_not_exceed_comment_max_length_setting() {
		$this->assertTrue( $this->check_comment( 3 ) );
	}

	public function test_link_count_not_exceeded_if_domain_is_whitelisted() {
		update_option( self::$setting_name, 'example.com' );

		$this->assertTrue( $this->check_comment() );
	}

	public function test_link_count_not_exceeded_if_whitelisted_domain_includes_path() {
		update_option( self::$setting_name, 'example.com/docs' );

		$this->assertTrue( $this->check_comment( 2, array( 'example.com', 'example.com/docs/doc-a', 'example.com/docs/doc-b' ) ) );
	}

}
