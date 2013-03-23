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
	 * $results Holds the results of the twitter request
	 */
	private $results;

	/**
	 * $obcentoRequest Holds the request class after it is instantiated with the configs
	 */
	private $obcentoRequest;
	
	private $obcentoValidateInput;

	/**
	 * The constructor for this class
	 *
	 * @param	string	$consumer_key			from dev.twitter.com
	 * @param	string	$consumer_secret		from dev.twitter.com
	 * @param	string	$access_token			from dev.twitter.com
	 * @param	string	$access_token_secret	from dev.twitter.com
	 */
	public function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret)
	{
		$this->obcentoRequest = new ObcentoTwitterRequest($consumer_key, $consumer_secret, $access_token, $access_token_secret);
		$this->obcentoValidateInput new ObcentoValidateInput();
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
	public static function instance($consumer_key, $consumer_secret, $access_token, $access_token_secret)
	{
		return new ObcentoTwitter($consumer_key, $consumer_secret, $access_token, $access_token_secret);
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
		$params = array();
		
		if($count !== NULL && $this->obcentoValidateInput->check_count($count))
			$params['count'] = $count;
		
		if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
			$params['since_id'] = $since_id;
		
		if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
			$params['max_id'] = $max_id;
		
		if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
			$params['trim_user'] = $trim_user;
		
		if($contributor_details !== NULL && $this->obcentoValidateInput->check_contributor_details($contributor_details))
			$params['contributor_details'] = $contributor_details;
		
		if($include_entities !== NULL && $this->obcentoValidateInput->check_include_entities($include_entities))
			$params['include_entities'] = $include_entities;
		
		$this->results = $this->obcentoRequest->
			execute('statuses/mentions_timeline', $params);
		
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
		$params = array();
		
		if($screen_name !== NULL && $this->obcentoValidateInput->check_screen_name($screen_name))
			$params['screen_name'] = $screen_name;
		
		if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
			$params['since_id'] = $since_id;
		
		if($count !== NULL && $this->obcentoValidateInput->check_count($count))
			$params['count'] = $count;
		
		if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
			$params['max_id'] = $max_id;
		
		if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
			$params['trim_user'] = $trim_user;
		
		if($exclude_replies !== NULL && $this->obcentoValidateInput->check_exclude_replies($exclude_replies))
			$params['exclude_replies'] = $exclude_replies;
		
		if($contributor_details !== NULL && $this->obcentoValidateInput->check_contributor_details($contributor_details))
			$params['contributor_details'] = $contributor_details;
		
		if($include_rts !== NULL && $this->obcentoValidateInput->check_include_rts($include_rts))
			$params['include_rts'] = $include_rts;
		
		$this->results = $this->obcentoRequest->
			execute('statuses/user_timeline', $params);
		
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
		$params = array();
		
		if($user_id !== NULL && $this->obcentoValidateInput->check_user_id($user_id))
			$params['user_id'] = $user_id;
		
		if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
			$params['since_id'] = $since_id;
		
		if($count !== NULL && $this->obcentoValidateInput->check_count($count))
			$params['count'] = $count;
		
		if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
			$params['max_id'] = $max_id;
		
		if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
			$params['trim_user'] = $trim_user;
		
		if($exclude_replies !== NULL && $this->obcentoValidateInput->check_exclude_replies($exclude_replies))
			$params['exclude_replies'] = $exclude_replies;
		
		if($contributor_details !== NULL && $this->obcentoValidateInput->check_contributor_details($contributor_details))
			$params['contributor_details'] = $contributor_details;
		
		if($include_rts !== NULL && $this->obcentoValidateInput->check_include_rts($include_rts))
			$params['include_rts'] = $include_rts;
		
		$this->results = $this->obcentoRequest->
			execute('statuses/user_timeline', $params);
		
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
		$params = array();
		
		if($count !== NULL && $this->obcentoValidateInput->check_count($count))
			$params['count'] = $count;
		
		if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
			$params['since_id'] = $since_id;
		
		if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
			$params['max_id'] = $max_id;
		
		if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
			$params['trim_user'] = $trim_user;
		
		if($exclude_replies !== NULL && $this->obcentoValidateInput->check_exclude_replies($exclude_replies))
			$params['exclude_replies'] = $exclude_replies;
		
		if($contributor_details !== NULL && $this->obcentoValidateInput->check_contributor_details($contributor_details))
			$params['contributor_details'] = $contributor_details;
		
		if($include_entities !== NULL && $this->obcentoValidateInput->check_include_entities($include_entities))
			$params['include_entities'] = $include_entities;
		
		$this->results = $this->obcentoRequest->
			execute('statuses/home_timeline', $params);
		
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
		$params = array();
		
		if($count !== NULL && $this->obcentoValidateInput->check_count($count))
			$params['count'] = $count;
		
		if($since_id !== NULL && $this->obcentoValidateInput->check_since_id($since_id))
			$params['since_id'] = $since_id;
		
		if($max_id !== NULL && $this->obcentoValidateInput->check_max_id($max_id))
			$params['max_id'] = $max_id;
		
		if($trim_user !== NULL && $this->obcentoValidateInput->check_trim_user($trim_user))
			$params['trim_user'] = $trim_user;
		
		if($include_entities !== NULL && $this->obcentoValidateInput->check_include_entities($include_entities))
			$params['include_entities'] = $include_entities;
		
		if($include_user_entities !== NULL && $this->obcentoValidateInput->check_include_user_entities($include_user_entities))
			$params['include_user_entities'] = $include_user_entities;
		
		$this->results = $this->obcentoRequest->
			execute('statuses/retweets_of_me', $params);
		
		return $this;
	}

	/**
	 * @return string JSON String of the data from Twitter
	 */
	public function fetchJSON()
	{
		return $this->results;
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
		return json_decode($this->results);
	}

}