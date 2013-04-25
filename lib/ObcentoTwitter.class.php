<?php

require_once dirname(__FILE__) . '/ObcentoTwitterRequest.class.php';
require_once dirname(__FILE__) . '/ObcentoTwitterValidateInput.class.php';


/**
 * This is the client class for the Obcento Twitter
 * For licensing and examples:
 *
 * @see https://github.com/avalanche-development/obcento
 *
 * @author dave_kz (http://www.dave.kz/)
 * @version 1.0 (2013-03-18)
 */
class ObcentoTwitter 
{

	/**
	 * $result Holds the results of the twitter request
	 */
	private $result;

	/**
	 * $obcentoRequest Holds the request class after it is instantiated with the configs
	 */
	private $obcentoRequest;

	/**
	 * The constructor for this class
	 *
	 * @param	string	$consumer_key			from dev.twitter.com
	 * @param	string	$consumer_secret		from dev.twitter.com
	 * @param	string	$access_token			from dev.twitter.com
	 * @param	string	$access_token_secret	from dev.twitter.com
	 */
	public function __construct($consumer_key = null, $consumer_secret = null, $access_token = null, $access_token_secret = null)
	{
		// if any of the keys are not defined, try to load the config file
		if($consumer_key === null || $consumer_secret === null || $access_token === null || $access_token_secret === null)
			require_once self::get_config_filepath();
		
		if($consumer_key === null || $consumer_secret === null || $access_token === null || $access_token_secret === null)
			trigger_error('ObcentoTwitter OAuth credentials were not set in the constructor!');
		else
			$this->obcentoRequest = new ObcentoTwitterRequest($consumer_key, $consumer_secret, $access_token, $access_token_secret);
	}


	/**
	 * For you peeps out there who like to have cool-looking code
	 *
	 * @param	string	$consumer_key			from dev.twitter.com
	 * @param	string	$consumer_secret		from dev.twitter.com
	 * @param	string	$access_token			from dev.twitter.com
	 * @param	string	$access_token_secret	from dev.twitter.com
	 * @return	object	new ObcentoTwitter()
	 */
	public static function instance($consumer_key = null, $consumer_secret = null, $access_token = null, $access_token_secret = null)
	{
		return new ObcentoTwitter($consumer_key, $consumer_secret, $access_token, $access_token_secret);
	}

	/**
	 * Static method for pulling the default config file w/ params
	 *
	 * @return	string	config filepath
	 */
	private static function get_config_filepath()
	{
		$path = dirname(__FILE__);
		$path = explode(DIRECTORY_SEPARATOR, $path);
		array_pop($path);
		$path = implode(DIRECTORY_SEPARATOR, $path);
		$path .= DIRECTORY_SEPARATOR;
		
		return "{$path}config.php";
	}

	/**
	 * getMentionsTimeline:
	 * 
	 * Returns the 20 most recent mentions (tweets containing a users's @screen_name) for the authenticating user. The timeline returned is the
	 * equivalent of the one seen when you view your mentions on twitter.com. This method can only return up to 800 tweets.
	 * For an deeper look at these parameters, check out:
	 * @see https://dev.twitter.com/docs/api/1.1/get/statuses/mentions_timeline
	 *
	 * @param int $count	Specifies the number of tweets to try and retrieve, up to a maximum of 200. The value of count is best thought of as a 
	 *						limit to the number of tweets to return because suspended or deleted content is removed after the count has been applied. 
	 *						We include retweets in the count, even if include_rts is not supplied. It is recommended you always send include_rts=1 
	 *						when using this API method. (Optional)
	 *
	 * @param int $since_id	Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number
	 *						of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the 
	 *						since_id will be forced to the oldest ID available. (Optional)
	 *
	 * @param int $max_id	Returns results with an ID less than (that is, older than) or equal to the specified ID. (Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *							numerical ID. Omit this parameter to receive the complete user object. (Optional)
	 *
	 * @param boolean $contributor_details	This parameter enhances the contributors element of the status response to include the screen_name of 
	 * 										the contributor. By default only the user_id of the contributor is included. (Optional)
	 *
	 * @param boolean $include_entities	The entities node will be disincluded when set to false. (Optional)
	 *
	 * @return object $this object for further manipulations
	 */
	public function getMentionsTimeline(
		$count = NULL,
		$since_id = NULL,
		$max_id = NULL,
		$trim_user = NULL,
		$contributor_details = NULL,
		$include_entities = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['count'] = $count;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['contributor_details'] = $contributor_details;
		$parameter_array['include_entities'] = $include_entities;
		
		$this->process_request('statuses/mentions_timeline', $parameter_array);
		
		return $this;
	}

	/**
	 * getUserTimeline:
	 *
	 * Returns a collection of the most recent Tweets posted by the user indicated by the screen_name. User timelines
	 * belonging to protected users may only be requested when the authenticated user either "owns" the timeline or is an approved follower 
	 * of the owner. The timeline returned is the equivalent of the one seen when you view a user's profile on twitter.com. This method can 
	 * only return up to 3,200 of a user's most recent Tweets. Native retweets of other statuses by the user is included in this total, 
	 * regardless of whether include_rts is set to false when requesting this resource.
 	 * For an deeper look at these parameters, check out:
	 * @see https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
	 *
	 * @param string $screen_name	The screen name of the user for whom to return results for.(Optional)
	 *
	 * @param int $since_id	Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number
	 *						of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the 
	 *						since_id will be forced to the oldest ID available.(Optional)
	 *
	 * @param int $count	Specifies the number of tweets to try and retrieve, up to a maximum of 200. The value of count is best thought of as a 
	 *						limit to the number of tweets to return because suspended or deleted content is removed after the count has been applied. 
	 *						We include retweets in the count, even if include_rts is not supplied. It is recommended you always send include_rts=1 
	 *						when using this API method. (Optional)
	 *
	 * @param int $max_id	Returns results with an ID less than (that is, older than) or equal to the specified ID.(Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *								numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @param boolean $exclude_replies	This parameter will prevent replies from appearing in the returned timeline. Using exclude_replies with 
	 *									the count parameter will mean you will receive up-to count tweets � this is because the count parameter
	 *									retrieves that many tweets before filtering out retweets and replies. This parameter is only supported 
	 *									for JSON and XML responses.
	 *
	 * @param boolean $contributor_details	This parameter enhances the contributors element of the status response to include the screen_name
	 *										of the contributor. By default only the user_id of the contributor is included.
	 *
	 * @param boolean $include_rts	When set to false, the timeline will strip any native retweets (though they will still count toward both 
	 *								the maximal length of the timeline and the slice selected by the count parameter). Note: If you're using 
	 *								the trim_user parameter in conjunction with include_rts, the retweets will still contain a full user object.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getUserTimeline(
		$screen_name = NULL,
		$since_id = NULL,
		$count = NULL,
		$max_id = NULL,
		$trim_user = NULL,
		$exclude_replies = NULL,
		$contributor_details = NULL,
		$include_rts = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['count'] = $count;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['exclude_replies'] = $exclude_replies;
		$parameter_array['contributor_details'] = $contributor_details;
		$parameter_array['include_rts'] = $include_rts;
		
		$this->process_request('statuses/user_timeline', $parameter_array);
		
		return $this;
	}

	/**
	 * getUserTimelineByUserId:
	 *
	 * Returns a collection of the most recent Tweets posted by the user indicated by the user_id. User timelines
	 * belonging to protected users may only be requested when the authenticated user either "owns" the timeline or is an approved follower 
	 * of the owner. The timeline returned is the equivalent of the one seen when you view a user's profile on twitter.com. This method can 
	 * only return up to 3,200 of a user's most recent Tweets. Native retweets of other statuses by the user is included in this total, 
	 * regardless of whether include_rts is set to false when requesting this resource.
	 * For an deeper look at these parameters, check out:
	 * @see https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
	 *
	 * @param int $user_id	The ID of the user for whom to return results for.(Optional)
	 *
	 * @param int $since_id	Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number
	 *						of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the 
	 *						since_id will be forced to the oldest ID available.(Optional)
	 *
	 * @param int $count	Specifies the number of tweets to try and retrieve, up to a maximum of 200. The value of count is best thought of as a 
	 *						limit to the number of tweets to return because suspended or deleted content is removed after the count has been applied. 
	 *						We include retweets in the count, even if include_rts is not supplied. It is recommended you always send include_rts=1 
	 *						when using this API method. (Optional)
	 *
	 * @param int $max_id	Returns results with an ID less than (that is, older than) or equal to the specified ID.(Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *							numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @param boolean $exclude_replies	This parameter will prevent replies from appearing in the returned timeline. Using exclude_replies with 
	 *									the count parameter will mean you will receive up-to count tweets � this is because the count parameter
	 *									retrieves that many tweets before filtering out retweets and replies. This parameter is only supported 
	 *									for JSON and XML responses.
	 *
	 * @param boolean $contributor_details	This parameter enhances the contributors element of the status response to include the screen_name
	 *										of the contributor. By default only the user_id of the contributor is included.
	 *
	 * @param boolean $include_rts	When set to false, the timeline will strip any native retweets (though they will still count toward both 
	 *								the maximal length of the timeline and the slice selected by the count parameter). Note: If you're using 
	 *								the trim_user parameter in conjunction with include_rts, the retweets will still contain a full user object.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getUserTimelineByUserId(
		$user_id,
		$since_id = NULL,
		$count = NULL,
		$max_id = NULL,
		$trim_user = NULL,
		$exclude_replies = NULL,
		$contributor_details = NULL,
		$include_rts = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['count'] = $count;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['exclude_replies'] = $exclude_replies;
		$parameter_array['contributor_details'] = $contributor_details;
		$parameter_array['include_rts'] = $include_rts;
		
		$this->process_request('statuses/user_timelin', $parameter_array);
		
		return $this;
	}

	/**
	 * getHomeTimeline:
	 * 
	 * Returns a collection of the most recent Tweets and retweets posted by the authenticating user and the users they follow. The home 
	 * timeline is central to how most users interact with the Twitter service. Up to 800 Tweets are obtainable on the home timeline. It 
	 * is more volatile for users that follow many users or follow users who tweet frequently.
	 * For an deeper look at these parameters, check out:
	 * @see https://dev.twitter.com/docs/api/1.1/get/statuses/home_timeline
	 *
	 * @param int !count	Specifies the number of tweets to try and retrieve, up to a maximum of 200. The value of count is best thought of as a 
	 *						limit to the number of tweets to return because suspended or deleted content is removed after the count has been applied. 
	 *						We include retweets in the count, even if include_rts is not supplied. It is recommended you always send include_rts=1 
	 *						when using this API method. (Optional)
	 *
	 * @param int $since_id	Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number
	 *						of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the 
	 *						since_id will be forced to the oldest ID available.(Optional)
	 *
	 * @param int $max_id	Returns results with an ID less than (that is, older than) or equal to the specified ID.(Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *								numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @param boolean $exclude_replies	This parameter will prevent replies from appearing in the returned timeline. Using exclude_replies with 
	 *									the count parameter will mean you will receive up-to count tweets � this is because the count parameter
	 *									retrieves that many tweets before filtering out retweets and replies. This parameter is only supported 
	 *									for JSON and XML responses.
	 *
	 * @param boolean $contributor_details	This parameter enhances the contributors element of the status response to include the screen_name
	 *										of the contributor. By default only the user_id of the contributor is included.
	 *
	 * @param boolean $include_entities	The entities node will be disincluded when set to false.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getHomeTimeline(
		$count = NULL,
		$since_id = NULL,
		$max_id = NULL,
		$trim_user = NULL,
		$exclude_replies = NULL,
		$contributor_details = NULL,
		$include_entities = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['count'] = $count;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['exclude_replies'] = $exclude_replies;
		$parameter_array['contributor_details'] = $contributor_details;
		$parameter_array['include_entities'] = $include_entities;
		
		$this->process_request('statuses/home_timeline', $parameter_array);
		
		return $this;
	}

	/**
	 * getRetweetsOfMe:
	 *
	 * Returns the most recent tweets authored by the authenticating user that have been retweeted by others. This timeline is a subset 
	 * of the user's GET statuses/user_timeline.
	 * For an deeper look at these parameters, check out:
	 * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweets_of_me
	 *
	 * @param int $count	Specifies the number of tweets to try and retrieve, up to a maximum of 200. The value of count is best thought of as a 
	 *						limit to the number of tweets to return because suspended or deleted content is removed after the count has been applied. 
	 *						We include retweets in the count, even if include_rts is not supplied. It is recommended you always send include_rts=1 
	 *						when using this API method. (Optional)
	 *
	 * @param int $since_id	Returns results with an ID greater than (that is, more recent than) the specified ID. There are limits to the number
	 *						of Tweets which can be accessed through the API. If the limit of Tweets has occured since the since_id, the 
	 *						since_id will be forced to the oldest ID available.(Optional)
	 *
	 * @param int $max_id	Returns results with an ID less than (that is, older than) or equal to the specified ID.(Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *								numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @param boolean $include_entities	The entities node will be disincluded when set to false.
	 *
	 * @param boolean $include_user_entities	The user entities node will be disincluded when set to false.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getRetweetsOfMe(
		$count = NULL,
		$since_id = NULL,
		$max_id = NULL,
		$trim_user = NULL,
		$include_entities = NULL,
		$include_user_entities = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['count'] = $count;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['include_user_entities'] = $include_user_entities;
		
		$this->process_request('statuses/retweets_of_me', $parameter_array);
		
		return $this;
	}

	/**
	 * getRetweetsByTweet
	 *
	 * Returns up to 100 of the first retweets of a given tweet.
	 *
	 *
	 * @param int $id	The numerical ID of the desired status. (Required)
	 *
	 * @param int $count	Specifies the number of records to retrieve. Must be less than or equal to 100. (Optional)
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *								numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @return object $this object for further manipulations
	 */
	public function getRetweetsByTweet(
		$id,
		$count = NULL,
		$trim_user = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['count'] = $count;
		$parameter_array['trim_user'] = $trim_user;
		
		$this->process_tweet_request('statuses/retweets', $id, $parameter_array);
		
		return $this;
	}

	/**
	 * getSingleTweetById
	 *
	 * Returns a single Tweet, specified by the id parameter. The Tweet's author will also be embedded within the tweet.
	 *
	 * @param int $id	The numerical ID of the desired Tweet.
	 *
	 * @param boolean $trim_user	When set to true, each tweet returned in a timeline will include a user object including only the status authors
	 *								numerical ID. Omit this parameter to receive the complete user object.(Optional)
	 *
	 * @param boolean $include_my_retweet	When set to either true, t or 1, any Tweets returned that have been retweeted by the 
	 *										authenticating user will include an additional current_user_retweet node, containing 
	 *										the ID of the source status for the retweet.
	 *
	 * @param boolean $include_user_entities	The user entities node will be disincluded when set to false.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getSingleTweetById(
		$id,
		$trim_user = NULL,
		$include_my_retweet = NULL,
		$include_entities = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['trim_user'] = $trim_user;
		$parameter_array['include_my_retweet'] = $include_my_retweet;
		$parameter_array['include_entities'] = $include_entities;
		
		$this->process_tweet_request('statuses/show', $id, $parameter_array);
		
		return $this;
	}

	/**
	 * getOEmbedById:
	 *
	 * Returns information allowing the creation of an embedded representation of a Tweet on third party sites. See the oEmbed
	 * specification for information about the response format.
	 *
	 * While this endpoint allows a bit of customization for the final appearance of the embedded Tweet, be aware that the 
	 * appearance of the rendered Tweet may change over time to be consistent with Twitter's Display Requirements. Do not 
	 * rely on any class or id parameters to stay constant in the returned markup.
	 *
	 * @param int $id	The Tweet/status ID to return embed code for.
	 *
	 * @param int $maxwidth	The maximum width in pixels that the embed should be rendered at. This value is constrained 
	 *						to be between 250 and 550 pixels.
	 *
	 * @param boolean $hide_media	Specifies whether the embedded Tweet should automatically expand images which were 
	 *								uploaded via POST statuses/update_with_media. When set to either true, t or 1 images
	 *								will not be expanded. Defaults to false.
	 *
	 * @param boolean $hide_thread	Specifies whether the embedded Tweet should automatically show the original message 
	 *								in the case that the embedded Tweet is a reply. When set to either true, t or 1 the 
	 *								original Tweet will not be shown. Defaults to false.
	 *
	 * @param boolean $omit_script	Specifies whether the embedded Tweet HTML should include a <script> element pointing to
	 *								widgets.js. In cases where a page already includes widgets.js, setting this value to true 
	 *								will prevent a redundant script element from being included. When set to either true the
	 *								<script> element will not be included in the embed HTML, meaning that pages must include 
	 *								a reference to widgets.js manually. Defaults to false.
	 *
	 * @param string $align		Specifies whether the embedded Tweet should be left aligned, right aligned, or centered 
	 *							in the page. Valid values are left, right, center, and none. Defaults to none, meaning 
	 *							no alignment styles are specified for the Tweet.
	 *
	 * @param string $related	A value for the TWT related parameter, as described in Web Intents. This value will be 
	 *							forwarded to all Web Intents calls.
	 *							Example Values: twitterapi,twittermedia,twitter
	 *
	 * @param string $lang	Language code for the rendered embed. This will affect the text and localization of the 
	 *						rendered HTML.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getOEmbedById(
		$id,
		$maxwidth = NULL,
		$hide_media = NULL,
		$hide_thread = NULL,
		$omit_script = NULL,
		$align = NULL,
		$related = NULL,
		$lang = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['id'] = $id;
		$parameter_array['maxwidth'] = $maxwidth;
		$parameter_array['hide_media'] = $hide_media;
		$parameter_array['hide_thread'] = $hide_thread;
		$parameter_array['omit_script'] = $omit_script;
		$parameter_array['align'] = $align;
		$parameter_array['related'] = $related;
		$parameter_array['lang'] = $lang;
		
		$this->process_request('statuses/oembed', $parameter_array);
		
		return $this;
	}

	/**
	 * getOEmbedByURL:
	 *
	 * Returns information allowing the creation of an embedded representation of a Tweet on third party sites. See the oEmbed
	 * specification for information about the response format.
	 *
	 * While this endpoint allows a bit of customization for the final appearance of the embedded Tweet, be aware that the 
	 * appearance of the rendered Tweet may change over time to be consistent with Twitter's Display Requirements. Do not 
	 * rely on any class or id parameters to stay constant in the returned markup.
	 *
	 * @param int $url	The URL of the Tweet/status to be embedded.
	 *					Example Values:
	 *						To embed the Tweet at https://twitter.com/#!/twitter/status/99530515043983360, use:
	 *							https%3A%2F%2Ftwitter.com%2F%23!%2Ftwitter%2Fstatus%2F99530515043983360
	 *
	 *						To embed the Tweet at https://twitter.com/twitter/status/99530515043983360, use:
	 *							https%3A%2F%2Ftwitter.com%2Ftwitter%2Fstatus%2F99530515043983360
	 *
	 * @param int $maxwidth	The maximum width in pixels that the embed should be rendered at. This value is constrained 
	 *						to be between 250 and 550 pixels.
	 *
	 * @param boolean $hide_media	Specifies whether the embedded Tweet should automatically expand images which were 
	 *								uploaded via POST statuses/update_with_media. When set to either true, t or 1 images
	 *								will not be expanded. Defaults to false.
	 *
	 * @param boolean $hide_thread	Specifies whether the embedded Tweet should automatically show the original message 
	 *								in the case that the embedded Tweet is a reply. When set to either true, t or 1 the 
	 *								original Tweet will not be shown. Defaults to false.
	 *
	 * @param boolean $omit_script	Specifies whether the embedded Tweet HTML should include a <script> element pointing to
	 *								widgets.js. In cases where a page already includes widgets.js, setting this value to true 
	 *								will prevent a redundant script element from being included. When set to either true the
	 *								<script> element will not be included in the embed HTML, meaning that pages must include 
	 *								a reference to widgets.js manually. Defaults to false.
	 *
	 * @param string $align		Specifies whether the embedded Tweet should be left aligned, right aligned, or centered 
	 *							in the page. Valid values are left, right, center, and none. Defaults to none, meaning 
	 *							no alignment styles are specified for the Tweet.
	 *
	 * @param string $related	A value for the TWT related parameter, as described in Web Intents. This value will be 
	 *							forwarded to all Web Intents calls.
	 *							Example Values: twitterapi,twittermedia,twitter
	 *
	 * @param string $lang	Language code for the rendered embed. This will affect the text and localization of the 
	 *						rendered HTML.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getOEmbedByURL(
		$url,
		$maxwidth = NULL,
		$hide_media = NULL,
		$hide_thread = NULL,
		$omit_script = NULL,
		$align = NULL,
		$related = NULL,
		$lang = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['url'] = $url;
		$parameter_array['maxwidth'] = $maxwidth;
		$parameter_array['hide_media'] = $hide_media;
		$parameter_array['hide_thread'] = $hide_thread;
		$parameter_array['omit_script'] = $omit_script;
		$parameter_array['align'] = $align;
		$parameter_array['related'] = $related;
		$parameter_array['lang'] = $lang;
		
		$this->process_request('statuses/oembed', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getSearchTweets(
		$q,
		$geocode = NULL,
		$lang = NULL,
		$locale = NULL,
		$result_type = NULL,
		$count = NULL,
		$until = NULL,
		$since_id = NULL,
		$max_id = NULL,
		$include_entities = NULL,
		$callback = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['q'] = $q;
		$parameter_array['geocode'] = $geocode;
		$parameter_array['lang'] = $lang;
		$parameter_array['locale'] = $locale;
		$parameter_array['result_type'] = $result_type;
		$parameter_array['count'] = $count;
		$parameter_array['until'] = $until;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['callback'] = $callback;
		
		$this->process_request('search/tweets', $parameter_array);
		
		return $this;
	}

	//-------------------- Skipping the streaming methods for now

	/**
	 */
	public function getDirectMessages(
		$since_id = NULL,
		$max_id = NULL,
		$count = NULL,
		$include_entities = NULL,
		$skip_status = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['count'] = $count;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		
		$this->process_request('direct_messages', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getDirectMessagesSent(
		$since_id = NULL,
		$max_id = NULL,
		$count = NULL,
		$page = NULL,
		$include_entities = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['count'] = $count;
		$parameter_array['page'] = $page;
		$parameter_array['include_entities'] = $include_entities;
		
		$this->process_request('direct_messages/sent', $parameter_array);
		
		return $this;
	}

	/**
	 * getSingleDirectMessage:
	 *
	 * Returns a single direct message, specified by an id parameter. Like the /1.1/direct_messages.format request,
	 * this method will include the user objects of the sender and recipient.
	 *
	 * Important: This method requires an access token with RWD (read, write & direct message) permissions. Consult
	 * The Application Permission Model for more information.
	 *
	 * @param int $id	The ID of the direct message. Example Values: 587424932
	 *
	 * @return object $this object for further manipulations
	 */
	public function getSingleDirectMessage($id)
	{
		$parameter_array = array();
		$parameter_array['id'] = $id;
		
		$this->process_request('direct_messages/show', $parameter_array);
		
		return $this;
	}

	/**
	 *
	 *
	 * Returns a collection of user_ids that the currently authenticated user does not want to receive retweets from.
	 *
	 * @param boolean $stringify_ids	Many programming environments will not consume our ids due to their size. 
	 *									Provide this option to have ids returned as strings instead. Read more about 
	 *									Twitter IDs, JSON and Snowflake. This parameter is especially important to 
	 *									use in Javascript environments.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getBlockedRetweetIds($stringify_ids = true)
	{
		$parameter_array = array();
		$parameter_array['stringify_ids'] = $stringify_ids;
		
		$this->process_request('friendships/no_retweets/ids', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getFriendsIdsByUserId(
		$user_id,
		$cursor = NULL,
		$stringify_ids = NULL,
		$count = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['stringify_ids'] = $stringify_ids;
		$parameter_array['count'] = $count;
		
		$this->process_request('friendshipts/friends/ids', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getFriendsIdsByScreenName(
		$screen_name,
		$cursor = NULL,
		$stringify_ids = NULL,
		$count = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['stringify_ids'] = $stringify_ids;
		$parameter_array['count'] = $count;
		
		$this->process_request('followers/ids', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getFollowersIdsByUserId(
		$user_id,
		$cursor = NULL,
		$stringify_ids = NULL,
		$count = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['stringify_ids'] = $stringify_ids;
		$parameter_array['count'] = $count;
		
		$this->process_request('followers/ids', $parameter_array);
		
		return $this;
	}

	/**
	 */
	public function getFollowersIdsByScreenName(
		$screen_name,
		$cursor = NULL,
		$stringify_ids = NULL,
		$count = NULL
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['stringify_ids'] = $stringify_ids;
		$parameter_array['count'] = $count;
		
		$this->process_request('friendships/friends/ids', $parameter_array);
		
		return $this;
	}

	/**
	 * getFriendshipsLookupByScreenName:
	 *
	 * Returns the relationships of the authenticating user to the comma-separated list of up to 100 screen_names or 
	 * user_ids provided. Values for connections can be: following, following_requested, followed_by, none.
	 *
	 * @param string $screen_name	A comma separated list of screen names, up to 100 are allowed in a single request.
	 *
	 * @return object $this object for further manipulations
	 */
	// @todo screen separated? we may want to look into this
	public function getFriendshipsLookupByScreenName($screen_name)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		
		$this->process_request('friendships/lookup', $parameter_array);
		
		return $this;
	}

	/**
	 * getFriendshipsLookupByUserId:
	 *
	 * Returns the relationships of the authenticating user to the comma-separated list of up to 100 screen_names or 
	 * user_ids provided. Values for connections can be: following, following_requested, followed_by, none.
	 *
	 * @param string $user_id	A comma separated list of user IDs, up to 100 are allowed in a single request.
	 *
	 * @return object $this object for further manipulations
	 */
	public function getFriendshipsLookupByUserId($user_id)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		
		$this->process_request('friendships/lookup', $parameter_array);
		
		return $this;
	}


	/**
	 *
	 */
	public function getFriendshipsIncoming(
		$cursor = NULL,
		$stringify_ids = NULL
	)
	{
		$params = array();

		$params['cursor'] = $cursor;
		$params['stringify_ids'] = $stringify_ids;

		$params = $this->validateInputArray($params);

		$this->process_request('friendships/incoming', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFriendshipsOutgoing(
		$cursor = NULL,
		$stringify_ids = NULL
	)
	{
		$params = array();

		$params['cursor'] = $cursor;
		$params['stringify_ids'] = $stringify_ids;

		$params = $this->validateInputArray($params);

		$this->process_request('friendships/outgoing', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFriendshipsDetailsById(
		$source_id = NULL,
		$target_id = NULL
	)
	{
		$params = array();

		$params['source_id'] = $source_id;
		$params['target_id'] = $target_id;

		$params = $this->validateInputArray($params);

		$this->process_request('friendships/show', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFriendshipsDetailsByScreenName(
		$source_screen_name = NULL,
		$target_screen_name = NULL
	)
	{
		$params = array();

		$params['source_screen_name'] = $source_screen_name;
		$params['target_screen_name'] = $target_screen_name;

		$params = $this->validateInputArray($params);

		$this->process_request('friendships/show', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFriendsListById(
		$user_id = NULL,
		$cursor = NULL,
		$skip_status = NULL,
		$include_user_entities = NULL
	)
	{
		$params = array();

		$params['user_id'] = $user_id;
		$params['cursor'] = $cursor;
		$params['skip_status'] = $skip_status;
		$params['include_user_entities'] = $include_user_entities;

		$params = $this->validateInputArray($params);

		$this->process_request('friends/list', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFriendsListByScreenName(
		$screen_name = NULL,
		$cursor = NULL,
		$skip_status = NULL,
		$include_user_entities = NULL
	)
	{
		$params = array();

		$params['screen_name'] = $screen_name;
		$params['cursor'] = $cursor;
		$params['skip_status'] = $skip_status;
		$params['include_user_entities'] = $include_user_entities;

		$params = $this->validateInputArray($params);

		$this->process_request('friends/list', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFollowersListById(
		$user_id       ,
		$cursor = NULL,
		$skip_status = NULL,
		$include_user_entities = NULL
	)
	{
		$params = array();

		$params['user_id'] = $user_id;
		$params['cursor'] = $cursor;
		$params['skip_status'] = $skip_status;
		$params['include_user_entities'] = $include_user_entities;

		$params = $this->validateInputArray($params);

		$this->process_request('followers/list', $params);

		return $this;
	}


	/**
	 *
	 */
	public function getFollowersListByScreenName(
		$screen_name,
		$cursor = NULL,
		$skip_status = NULL,
		$include_user_entities = NULL
	)
	{
		$params = array();

		$params['screen_name'] = $screen_name;
		$params['cursor'] = $cursor;
		$params['skip_status'] = $skip_status;
		$params['include_user_entities'] = $include_user_entities;

		$params = $this->validateInputArray($params);

		$this->process_request('followers/list', $params);

		return $this;
	}


	// User functions

	/**
	 * getAccountSettings:
	 *
	 * Returns settings (including current trend, geo and sleep time information) for the authenticating user.
	 */
	public function getAccountSettings(){

		$this->process_request('account/settings', array());

		return $this;
	}


	/**
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getVerifyCredentials(
		$include_entities = NULL,
		$skip_status = NULL
	)
	{
		$params = array();

		$params['include_entities'] = $include_entities;
		$params['skip_status'] = $skip_status;

		$params = $this->validateInputArray($params);

		$this->process_request('account/verify_credentials', $params);

		return $this;
	}


	/**
	 * @param null $include_entities
	 * @param null $skip_status
	 * @param null $cursor
	 * @return $this
	 */
	public function getBlocksList(
		$include_entities = NULL,
		$skip_status = NULL,
		$cursor = NULL
	)
	{
		$params = array();

		$params['include_entities'] = $include_entities;
		$params['skip_status'] = $skip_status;
		$params['cursor'] = $cursor;

		$params = $this->validateInputArray($params);

		$this->process_request('blocks/list', $params);

		return $this;
	}


	/**
	 * @param null $stringify_ids
	 * @param null $cursor
	 * @return $this
	 */
	public function getBlocksIds(
		$stringify_ids = NULL,
		$cursor = NULL
	)
	{
		$params = array();

		$params['stringify_ids'] = $stringify_ids;
		$params['cursor'] = $cursor;

		$params = $this->validateInputArray($params);

		$this->process_request('blocks/ids', $params);

		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $include_entities
	 * @return $this
	 */
	public function getUsersLookupByScreenName(
		$screen_name = null,
		$include_entities = null
	)
	{
		$parameter_array = array();

		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['include_entities'] = $include_entities;

		$parameter_array = $this->validateInputArray($parameter_array);

		$this->results = $this->process_request('users/lookup', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $include_entities
	 * @return $this
	 */
	public function getUsersLookupByUserID(
		$user_id = null,
		$include_entities = null
	)
	{
		$parameter_array = array();

		$parameter_array['user_id'] = $user_id;
		$parameter_array['include_entities'] = $include_entities;

		$parameter_array = $this->validateInputArray($parameter_array);

		$this->results = $this->process_request('users/lookup', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $include_entities
	 * @return $this
	 */
	public function getUsersShowByID(
		$user_id  = null,
		$include_entities  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['include_entities'] = $include_entities;

		$this->process_request('users/show', $parameter_array);

		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $include_entities
	 * @return $this
	 */
	public function getUsersShowByScreenName(
		$screen_name  = null,
		$include_entities  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['include_entities'] = $include_entities;

		$this->process_request('users/show', $parameter_array);

		return $this;
	}


	/**
	 * @param null $q
	 * @param null $page
	 * @param null $count
	 * @param null $include_entities
	 * @return $this
	 */
	public function getUsersSearch(
		$q = null,
		$page = null,
		$count = null,
		$include_entities = null
	)
	{
		$parameter_array = array();
		$parameter_array['q'] = $q;
		$parameter_array['page'] = $page;
		$parameter_array['count'] = $count;
		$parameter_array['include_entities'] = $include_entities;

		$this->process_request('users/search', $parameter_array);

		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getUsersContributeesByID(
		$user_id = null,
		$include_entities = null,
		$skip_status = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;

		$this->process_request('users/contributees', $parameter_array);

		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getUsersContributeesByScreenName(
		$screen_name = null,
		$include_entities = null,
		$skip_status = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;

		$this->process_request('users/contributees', $parameter_array);

		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getUsersContributorsByID(
		$user_id  = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('users/contributors', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getUsersContributorsScreenName(
		$screen_name  = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('users/contributors', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @return $this
	 */
	public function getUsersProfileBannerByID(
		$user_id  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$this->process_request('users/profile_banner', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @return $this
	 */
	public function getUsersProfileBannerScreenName(
		$screen_name  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$this->process_request('users/profile_banner', $parameter_array);
		return $this;
	}

	/*public function getUsersSuggestionsBySlug(
		$slug = null,
		$lang = null
	)
	{
		$parameter_array = array();
		$parameter_array['slug'] = $slug;
		$parameter_array['lang'] = $lang;
		$this->process_request('users/suggestions/%3Aslug', $parameter_array);
		return $this;
	}*/


	/**
	 * @param null $lang
	 * @return $this
	 */
	public function getUsersSuggestions(
		$lang  = null
	)
	{
		$parameter_array = array();
		$parameter_array['lang'] = $lang;
		$this->process_request('users/suggestions', $parameter_array);
		return $this;
	}

	/*public function getUsersSuggestions%3AslugMembers(
		$slug  = null
	)
	{
		$parameter_array = array();
		$parameter_array['slug'] = $slug;
		$this->process_request('users/suggestions/%3Aslug/members', $parameter_array);
		return $this;
	}*/


	/**
	 * @param null $user_id
	 * @param null $count
	 * @param null $since_id
	 * @param null $max_id
	 * @param null $include_entities
	 * @return $this
	 */
	public function getFavoritesListByID(
		$user_id  = null,
		$count  = null,
		$since_id  = null,
		$max_id  = null,
		$include_entities  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['count'] = $count;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['include_entities'] = $include_entities;
		$this->process_request('favorites/list', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $count
	 * @param null $since_id
	 * @param null $max_id
	 * @param null $include_entities
	 * @return $this
	 */
	public function getFavoritesListByScreenName(
		$screen_name  = null,
		$count  = null,
		$since_id  = null,
		$max_id  = null,
		$include_entities  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['count'] = $count;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['include_entities'] = $include_entities;
		$this->process_request('favorites/list', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $reverse
	 * @return $this
	 */
	public function getListsListByID(
		$user_id  = null,
		$reverse  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['reverse'] = $reverse;
		$this->process_request('lists/list', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $reverse
	 * @return $this
	 */
	public function getListsListByScreenName(
		$screen_name  = null,
		$reverse  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['reverse'] = $reverse;
		$this->process_request('lists/list', $parameter_array);
		return $this;
	}


	/**
	 * @param null $list_id
	 * @param null $slug
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @param null $since_id
	 * @param null $max_id
	 * @param null $count
	 * @param null $include_entities
	 * @param null $include_rts
	 * @return $this
	 */
	public function getListsStatuses(
		$list_id  = null,
		$slug  = null,
		$owner_screen_name  = null,
		$owner_id  = null,
		$since_id  = null,
		$max_id  = null,
		$count  = null,
		$include_entities  = null,
		$include_rts  = null
	)
	{
		$parameter_array = array();
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$parameter_array['since_id'] = $since_id;
		$parameter_array['max_id'] = $max_id;
		$parameter_array['count'] = $count;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['include_rts'] = $include_rts;
		$this->process_request('lists/statuses', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $cursor
	 * @param null $filter_to_owned_lists
	 * @return $this
	 */
	public function getListsMembershipsByID(
		$user_id  = null,
		$cursor  = null,
		$filter_to_owned_lists  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['filter_to_owned_lists'] = $filter_to_owned_lists;
		$this->process_request('lists/memberships', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $cursor
	 * @param null $filter_to_owned_lists
	 * @return $this
	 */
	public function getListsMembershipsByScreenName(
		$screen_name  = null,
		$cursor  = null,
		$filter_to_owned_lists  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['filter_to_owned_lists'] = $filter_to_owned_lists;
		$this->process_request('lists/memberships', $parameter_array);
		return $this;
	}


	/**
	 * @param null $list_id
	 * @param null $slug
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @param null $cursor
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getListsSubscribers(
		$list_id  = null,
		$slug  = null,
		$owner_screen_name  = null,
		$owner_id  = null,
		$cursor  = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('lists/subscribers', $parameter_array);
		return $this;
	}


	/**
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @param null $list_id
	 * @param null $slug
	 * @param null $user_id
	 * @param null $screen_name
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getListsSubscribersShow(
		$owner_screen_name  = null,
		$owner_id  = null,
		$list_id  = null,
		$slug  = null,
		$user_id  = null,
		$screen_name  = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['user_id'] = $user_id;
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('lists/subscribers/show', $parameter_array);
		return $this;
	}


	/**
	 * @param null $list_id
	 * @param null $slug
	 * @param null $user_id
	 * @param null $screen_name
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getListsMembersShow(
		$list_id  = null,
		$slug  = null,
		$user_id  = null,
		$screen_name  = null,
		$owner_screen_name  = null,
		$owner_id  = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['user_id'] = $user_id;
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('lists/members/show', $parameter_array);
		return $this;
	}


	/**
	 * @param null $list_id
	 * @param null $slug
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @param null $cursor
	 * @param null $include_entities
	 * @param null $skip_status
	 * @return $this
	 */
	public function getListsMembers(
		$list_id  = null,
		$slug  = null,
		$owner_screen_name  = null,
		$owner_id  = null,
		$cursor = null,
		$include_entities  = null,
		$skip_status  = null
	)
	{
		$parameter_array = array();
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$parameter_array['cursor'] = $cursor;
		$parameter_array['include_entities'] = $include_entities;
		$parameter_array['skip_status'] = $skip_status;
		$this->process_request('lists/members', $parameter_array);
		return $this;
	}


	/**
	 * @param null $list_id
	 * @param null $slug
	 * @param null $owner_screen_name
	 * @param null $owner_id
	 * @return $this
	 */
	public function getListsShow(
		$list_id  = null,
		$slug  = null,
		$owner_screen_name  = null,
		$owner_id  = null
	)
	{
		$parameter_array = array();
		$parameter_array['list_id'] = $list_id;
		$parameter_array['slug'] = $slug;
		$parameter_array['owner_screen_name'] = $owner_screen_name;
		$parameter_array['owner_id'] = $owner_id;
		$this->process_request('lists/show', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $count
	 * @param null $cursor
	 * @return $this
	 */
	public function getListsSubscriptionsByID(
		$user_id  = null,
		$count  = null,
		$cursor  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['count'] = $count;
		$parameter_array['cursor'] = $cursor;
		$this->process_request('lists/subscriptions', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $count
	 * @param null $cursor
	 * @return $this
	 */
	public function getListsSubscriptionsByScreenName(
		$screen_name  = null,
		$count  = null,
		$cursor  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['count'] = $count;
		$parameter_array['cursor'] = $cursor;
		$this->process_request('lists/subscriptions', $parameter_array);
		return $this;
	}


	/**
	 * @param null $user_id
	 * @param null $count
	 * @param null $cursor
	 * @return $this
	 */
	public function getListsOwnershipsByID(
		$user_id  = null,
		$count  = null,
		$cursor  = null
	)
	{
		$parameter_array = array();
		$parameter_array['user_id'] = $user_id;
		$parameter_array['count'] = $count;
		$parameter_array['cursor'] = $cursor;
		$this->process_request('lists/ownerships', $parameter_array);
		return $this;
	}


	/**
	 * @param null $screen_name
	 * @param null $count
	 * @param null $cursor
	 * @return $this
	 */
	public function getListsOwnershipsByScreenName(
		$screen_name  = null,
		$count  = null,
		$cursor  = null
	)
	{
		$parameter_array = array();
		$parameter_array['screen_name'] = $screen_name;
		$parameter_array['count'] = $count;
		$parameter_array['cursor'] = $cursor;
		$this->process_request('lists/ownerships', $parameter_array);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function getSavedSearchesList()
	{
		$parameter_array = array();
		$this->process_request('saved_searches/list', $parameter_array);
		return $this;
	}


	/*public function getSavedSearchesShowByID(
		$id  = null
	)
	{
		$parameter_array = array();
		$parameter_array['id'] = $id;
		$this->process_request('saved_searches/show/%3Aid', $parameter_array);
		return $this;
	}*/


	/*public function getGeoIdByPlaceID(
		$place_id  = null
	)
	{
		$parameter_array = array();
		$parameter_array['place_id'] = $place_id;
		$this->process_request('geo/id/%3Aplace_id', $parameter_array);
		return $this;
	}*/


	/**
	 * @param null $lat
	 * @param null $long
	 * @param null $accuracy
	 * @param null $granularity
	 * @param null $max_results
	 * @param null $callback
	 * @return $this
	 */
	public function getGeoReverseGeocode(
		$lat  = null,
		$long  = null,
		$accuracy  = null,
		$granularity  = null,
		$max_results  = null,
		$callback  = null
	)
	{
		$parameter_array = array();
		$parameter_array['lat'] = $lat;
		$parameter_array['long'] = $long;
		$parameter_array['accuracy'] = $accuracy;
		$parameter_array['granularity'] = $granularity;
		$parameter_array['max_results'] = $max_results;
		$parameter_array['callback'] = $callback;
		$this->process_request('geo/reverse_geocode', $parameter_array);
		return $this;
	}


	/**
	 * @param null $lat
	 * @param null $long
	 * @param null $query
	 * @param null $ip
	 * @param null $granularity
	 * @param null $accuracy
	 * @param null $max_results
	 * @param null $contained_within
	 * @param null $attributeStreet_address
	 * @param null $callback
	 * @return $this
	 */
	public function getGeoSearch(
		$lat  = null,
		$long  = null,
		$query  = null,
		$ip  = null,
		$granularity  = null,
		$accuracy  = null,
		$max_results  = null,
		$contained_within  = null,
		$attributeStreet_address  = null,
		$callback  = null
	)
	{
		$parameter_array = array();
		$parameter_array['lat'] = $lat;
		$parameter_array['long'] = $long;
		$parameter_array['query'] = $query;
		$parameter_array['ip'] = $ip;
		$parameter_array['granularity'] = $granularity;
		$parameter_array['accuracy'] = $accuracy;
		$parameter_array['max_results'] = $max_results;
		$parameter_array['contained_within'] = $contained_within;
		$parameter_array['attribute:street_address'] = $attributeStreet_address;
		$parameter_array['callback'] = $callback;
		$this->process_request('geo/search', $parameter_array);
		return $this;
	}


	/**
	 * @param null $lat
	 * @param null $long
	 * @param null $name
	 * @param null $contained_within
	 * @param null $attributeStreet_address
	 * @param null $callback
	 * @return $this
	 */
	public function getGeoSimilarPlaces(
		$lat  = null,
		$long  = null,
		$name  = null,
		$contained_within  = null,
		$attributeStreet_address  = null,
		$callback  = null
	)
	{
		$parameter_array = array();
		$parameter_array['lat'] = $lat;
		$parameter_array['long'] = $long;
		$parameter_array['name'] = $name;
		$parameter_array['contained_within'] = $contained_within;
		$parameter_array['attribute:street_address'] = $attributeStreet_address;
		$parameter_array['callback'] = $callback;
		$this->process_request('geo/similar_places', $parameter_array);
		return $this;
	}


	/**
	 * @param null $id
	 * @param null $exclude
	 * @return $this
	 */
	public function getTrendsPlace(
		$id  = null,
		$exclude  = null
	)
	{
		$parameter_array = array();
		$parameter_array['id'] = $id;
		$parameter_array['exclude'] = $exclude;
		$this->process_request('trends/place', $parameter_array);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function getTrendsAvailable()
	{
		$parameter_array = array();
		$this->process_request('trends/available', $parameter_array);
		return $this;
	}


	/**
	 * @param null $lat
	 * @param null $long
	 * @return $this
	 */
	public function getTrendsClosest(
		$lat  = null,
		$long  = null
	)
	{
		$parameter_array = array();
		$parameter_array['lat'] = $lat;
		$parameter_array['long'] = $long;
		$this->process_request('trends/closest', $parameter_array);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function getHelpConfiguration()
	{
		$parameter_array = array();
		$this->process_request('help/configuration', $parameter_array);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function getHelpLanguages()
	{
		$parameter_array = array();
		$this->process_request('help/languages', $parameter_array);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function getHelpPrivacy()
	{
		$parameter_array = array();
		$this->process_request('help/privacy', $parameter_array);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function getHelpTos()
	{
		$parameter_array = array();
		$this->process_request('help/tos', $parameter_array);
		return $this;
	}


	/**
	 * @param null $resources
	 * @return $this
	 */
	public function getApplicationRateLimitStatus(
		$resources  = null
	)
	{
		$parameter_array = array();
		$parameter_array['resources'] = $resources;
		$this->process_request('application/rate_limit_status', $parameter_array);
		return $this;
	}


	/**
	 * @return string JSON String of the data from Twitter
	 */
	public function fetchJSON()
	{
		return $this->result;
	}

	/**
	 * @return string JSON String of the data from Twitter
	 */
	public function __toString()
	{
		return $this->fetchJSON();
	}

	/**
	 * @return array array of the data from Twitter
	 */
	public function fetchArray()
	{
		return json_decode($this->result);
	}

	/**
	 * simple abstracted function to process the parameters and validate
	 *
	 * @param	string	$method				string path that defines the twitter method
	 * @param	array	$parameter_array	array of params to pass into twitter
	 * @return	boolean						boolean on whether or not a request was made
	 */
	private function process_request($method, $parameter_array)
	{
		$parameter_array = $this->clean_parameter_array($parameter_array);
		
		if($this->check_parameter_array($parameter_array) === false)
			return false;
		
		$this->result = $this->obcentoRequest->execute($method, $parameter_array);
		
		return true;
	}

	/**
	 * process_tweet_request
	 *
	 * special case abstract function with the tweet id is part of the request endpoint
	 *
	 * @param	string	$method				string path that defines the main twitter method
	 * @param	int		$id					tweet id that needs to be validated
	 * @return	boolean						boolean on whether or not a request was made
	 */
	private function process_tweet_request($method, $id, $parameter_array)
	{
		// @todo add id validation
		
		return $this->process_request("{$method}/{$id}", $parameter_array);
	}

	/**
	 * goes through the params and removes null (default)
	 *
	 * @param	array	$array	array of params, with key => null that needs to be removed
	 * @return	array			clean array with no null values
	 */
	private function clean_parameter_array($array)
	{
		return array_filter($array, create_function('$value', 'return $value !== null;'));
	}

	/**
	 * goes through the params and validates
	 *
	 * @param	array	$array	array of clean params that need to be checked for validity
	 * @return	bool			simple true/false if the params are valid
	 */
	private function check_parameter_array($array)
	{
		return true; // yes, this is a temp
	}

	private function validateInputArray($array)
	{
		$obcentoValidateInput = new ObcentoTwitterValidateInput();
		
		//if($contributor_details !== NULL && $this->obcentoValidateInput->check_contributor_details($contributor_details))
		//if($count !== NULL && $this->obcentoValidateInput->check_count($count))
		//if($exclude_replies !== NULL && $this->obcentoValidateInput->check_exclude_replies($exclude_replies))
		//if($include_entities !== NULL && $this->obcentoValidateInput->check_include_entities($include_entities))
		//if($include_rts !== NULL && $this->obcentoValidateInput->check_include_rts($include_rts))
		//if($include_user_entities !== NULL && $this->obcentoValidateInput->check_include_user_entities($include_user_entities))
		//if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
		//if($screen_name !== NULL && $this->obcentoValidateInput->check_screen_name($screen_name))
		//if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
		//if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
		//if($user_id !== NULL && $this->obcentoValidateInput->check_user_id($user_id))
		//if($count !== NULL && $this->obcentoValidateInput->check_count($count))
		
		$cleanArray = array();
		foreach($array as $key => $value)
		{
			if($value != NULL)
				$cleanArray[$key] = $value;
		}
		return $cleanArray;
	}

}