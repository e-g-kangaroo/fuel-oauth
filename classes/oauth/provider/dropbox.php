<?php
/**
 * OAuth Twitter Provider
 *
 * Documents for implementing Twitter OAuth can be found at
 * <http://dev.twitter.com/pages/auth>.
 *
 * [!!] This class does not implement the Twitter API. It is only an
 * implementation of standard OAuth with Twitter as the service provider.
 *
 * @package    Kohana/OAuth
 * @category   Provider
 * @author     Kohana Team
 * @copyright  (c) 2010 Kohana Team
 * @license    http://kohanaframework.org/license
 * @since      3.0.7
 */

namespace OAuth;

class OAuth_Provider_Dropbox extends OAuth_Provider {

	public $name = 'dropbox';
	
	public $uid_key = 'uid';

	public function url_request_token()
	{
		return 'https://api.dropbox.com/0/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'http://www.dropbox.com/0/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.dropbox.com/0/oauth/access_token';
	}
	
	public function get_user_info(OAuth_Consumer $consumer, OAuth_Token $token)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', 'https://api.dropbox.com/0/account/info', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->token,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$user = json_decode($request->execute());
		
		// Create a response from the request
		return array(
			'name' => $user->display_name,
			'location' => $user->country,
			'credentials' => array(
				'uid' => $token->uid,
				'provider' => $this->name,
				'token' => $token->token,
				'secret' => $token->secret,
			),
		);
	}

} // End OAuth_Provider_Dropbox