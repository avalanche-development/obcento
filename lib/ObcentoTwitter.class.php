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
     * @param    string    $consumer_key            from dev.twitter.com
     * @param    string    $consumer_secret         from dev.twitter.com
     * @param    string    $access_token            from dev.twitter.com
     * @param    string    $access_token_secret     from dev.twitter.com
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
     * @param    string    $consumer_key            from dev.twitter.com
     * @param    string    $consumer_secret         from dev.twitter.com
     * @param    string    $access_token            from dev.twitter.com
     * @param    string    $access_token_secret     from dev.twitter.com
     * @return   object    new ObcentoTwitter()
     */
    public static function instance($consumer_key = null, $consumer_secret = null, $access_token = null, $access_token_secret = null)
    {
        return new ObcentoTwitter($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    }

    /**
     * Static method for pulling the default config file w/ params
     *
     * @return    string    config    filepath
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


    public function getSingleDirectMessage($id)
    {
        $parameter_array = array();
        $parameter_array['id'] = $id;
        
        $this->process_request('direct_messages/show', $parameter_array);
        
        return $this;
    }


    public function getBlockedRetweetIds($stringify_ids = true)
    {
        $parameter_array = array();
        $parameter_array['stringify_ids'] = $stringify_ids;
        
        $this->process_request('friendships/no_retweets/ids', $parameter_array);
        
        return $this;
    }


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


    public function getFriendshipsLookupByScreenName($screen_name)
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        
        $this->process_request('friendships/lookup', $parameter_array);
        
        return $this;
    }


    public function getFriendshipsLookupByUserId($user_id)
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        
        $this->process_request('friendships/lookup', $parameter_array);
        
        return $this;
    }


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


    public function getFollowersListById(
        $user_id,
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


    public function getAccountSettings(){

        $this->process_request('account/settings', array());

        return $this;
    }


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


    public function getUsersShowByID(
        $user_id = null,
        $include_entities = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['include_entities'] = $include_entities;

        $this->process_request('users/show', $parameter_array);

        return $this;
    }


    public function getUsersShowByScreenName(
        $screen_name = null,
        $include_entities = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['include_entities'] = $include_entities;

        $this->process_request('users/show', $parameter_array);

        return $this;
    }


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


    public function getUsersContributorsByID(
        $user_id = null,
        $include_entities = null,
        $skip_status = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['include_entities'] = $include_entities;
        $parameter_array['skip_status'] = $skip_status;
        $this->process_request('users/contributors', $parameter_array);
        return $this;
    }


    public function getUsersContributorsScreenName(
        $screen_name = null,
        $include_entities = null,
        $skip_status = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['include_entities'] = $include_entities;
        $parameter_array['skip_status'] = $skip_status;
        $this->process_request('users/contributors', $parameter_array);
        return $this;
    }


    public function getUsersProfileBannerByID(
        $user_id = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $this->process_request('users/profile_banner', $parameter_array);
        return $this;
    }


    public function getUsersProfileBannerScreenName(
        $screen_name = null
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


    public function getUsersSuggestions(
        $lang = null
    )
    {
        $parameter_array = array();
        $parameter_array['lang'] = $lang;
        $this->process_request('users/suggestions', $parameter_array);
        return $this;
    }

    /*public function getUsersSuggestions%3AslugMembers(
        $slug = null
    )
    {
        $parameter_array = array();
        $parameter_array['slug'] = $slug;
        $this->process_request('users/suggestions/%3Aslug/members', $parameter_array);
        return $this;
    }*/


    public function getFavoritesListByID(
        $user_id = null,
        $count = null,
        $since_id = null,
        $max_id = null,
        $include_entities = null
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


    public function getFavoritesListByScreenName(
        $screen_name = null,
        $count = null,
        $since_id = null,
        $max_id = null,
        $include_entities = null
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


    public function getListsListByID(
        $user_id = null,
        $reverse = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['reverse'] = $reverse;
        $this->process_request('lists/list', $parameter_array);
        return $this;
    }


    public function getListsListByScreenName(
        $screen_name = null,
        $reverse = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['reverse'] = $reverse;
        $this->process_request('lists/list', $parameter_array);
        return $this;
    }


    public function getListsStatuses(
        $list_id = null,
        $slug = null,
        $owner_screen_name = null,
        $owner_id = null,
        $since_id = null,
        $max_id = null,
        $count = null,
        $include_entities = null,
        $include_rts = null
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


    public function getListsMembershipsByID(
        $user_id = null,
        $cursor = null,
        $filter_to_owned_lists = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['cursor'] = $cursor;
        $parameter_array['filter_to_owned_lists'] = $filter_to_owned_lists;
        $this->process_request('lists/memberships', $parameter_array);
        return $this;
    }


    public function getListsMembershipsByScreenName(
        $screen_name = null,
        $cursor = null,
        $filter_to_owned_lists = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['cursor'] = $cursor;
        $parameter_array['filter_to_owned_lists'] = $filter_to_owned_lists;
        $this->process_request('lists/memberships', $parameter_array);
        return $this;
    }


    public function getListsSubscribers(
        $list_id = null,
        $slug = null,
        $owner_screen_name = null,
        $owner_id = null,
        $cursor = null,
        $include_entities = null,
        $skip_status = null
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


    public function getListsSubscribersShow(
        $owner_screen_name = null,
        $owner_id = null,
        $list_id = null,
        $slug = null,
        $user_id = null,
        $screen_name = null,
        $include_entities = null,
        $skip_status = null
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


    public function getListsMembersShow(
        $list_id = null,
        $slug = null,
        $user_id = null,
        $screen_name = null,
        $owner_screen_name = null,
        $owner_id = null,
        $include_entities = null,
        $skip_status = null
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


    public function getListsMembers(
        $list_id = null,
        $slug = null,
        $owner_screen_name = null,
        $owner_id = null,
        $cursor = null,
        $include_entities = null,
        $skip_status = null
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


    public function getListsShow(
        $list_id = null,
        $slug = null,
        $owner_screen_name = null,
        $owner_id = null
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


    public function getListsSubscriptionsByID(
        $user_id = null,
        $count = null,
        $cursor = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['count'] = $count;
        $parameter_array['cursor'] = $cursor;
        $this->process_request('lists/subscriptions', $parameter_array);
        return $this;
    }


    public function getListsSubscriptionsByScreenName(
        $screen_name = null,
        $count = null,
        $cursor = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['count'] = $count;
        $parameter_array['cursor'] = $cursor;
        $this->process_request('lists/subscriptions', $parameter_array);
        return $this;
    }


    public function getListsOwnershipsByID(
        $user_id = null,
        $count = null,
        $cursor = null
    )
    {
        $parameter_array = array();
        $parameter_array['user_id'] = $user_id;
        $parameter_array['count'] = $count;
        $parameter_array['cursor'] = $cursor;
        $this->process_request('lists/ownerships', $parameter_array);
        return $this;
    }


    public function getListsOwnershipsByScreenName(
        $screen_name = null,
        $count = null,
        $cursor = null
    )
    {
        $parameter_array = array();
        $parameter_array['screen_name'] = $screen_name;
        $parameter_array['count'] = $count;
        $parameter_array['cursor'] = $cursor;
        $this->process_request('lists/ownerships', $parameter_array);
        return $this;
    }


    public function getSavedSearchesList()
    {
        $parameter_array = array();
        $this->process_request('saved_searches/list', $parameter_array);
        return $this;
    }


    /*public function getSavedSearchesShowByID(
        $id = null
    )
    {
        $parameter_array = array();
        $parameter_array['id'] = $id;
        $this->process_request('saved_searches/show/%3Aid', $parameter_array);
        return $this;
    }*/


    /*public function getGeoIdByPlaceID(
        $place_id = null
    )
    {
        $parameter_array = array();
        $parameter_array['place_id'] = $place_id;
        $this->process_request('geo/id/%3Aplace_id', $parameter_array);
        return $this;
    }*/


    public function getGeoReverseGeocode(
        $lat = null,
        $long = null,
        $accuracy = null,
        $granularity = null,
        $max_results = null,
        $callback = null
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


    public function getGeoSearch(
        $lat = null,
        $long = null,
        $query = null,
        $ip = null,
        $granularity = null,
        $accuracy = null,
        $max_results = null,
        $contained_within = null,
        $attributeStreet_address = null,
        $callback = null
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


    public function getGeoSimilarPlaces(
        $lat = null,
        $long = null,
        $name = null,
        $contained_within = null,
        $attributeStreet_address = null,
        $callback = null
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


    public function getTrendsPlace(
        $id = null,
        $exclude = null
    )
    {
        $parameter_array = array();
        $parameter_array['id'] = $id;
        $parameter_array['exclude'] = $exclude;
        $this->process_request('trends/place', $parameter_array);
        return $this;
    }


    public function getTrendsAvailable()
    {
        $parameter_array = array();
        $this->process_request('trends/available', $parameter_array);
        return $this;
    }


    public function getTrendsClosest(
        $lat = null,
        $long = null
    )
    {
        $parameter_array = array();
        $parameter_array['lat'] = $lat;
        $parameter_array['long'] = $long;
        $this->process_request('trends/closest', $parameter_array);
        return $this;
    }

    
    public function getHelpConfiguration()
    {
        $parameter_array = array();
        $this->process_request('help/configuration', $parameter_array);
        return $this;
    }


    public function getHelpLanguages()
    {
        $parameter_array = array();
        $this->process_request('help/languages', $parameter_array);
        return $this;
    }


    public function getHelpPrivacy()
    {
        $parameter_array = array();
        $this->process_request('help/privacy', $parameter_array);
        return $this;
    }


    public function getHelpTos()
    {
        $parameter_array = array();
        $this->process_request('help/tos', $parameter_array);
        return $this;
    }


    public function getApplicationRateLimitStatus(
        $resources = null
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
     * @param    string    $method                string path that defines the twitter method
     * @param    array     $parameter_array       array of params to pass into twitter
     * @return   boolean                          boolean on whether or not a request was made
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
     * @param    string    $method                 string path that defines the main twitter method
     * @param    int        $id                    tweet id that needs to be validated
     * @return   boolean                           boolean on whether or not a request was made
     */
    private function process_tweet_request($method, $id, $parameter_array)
    {
        // @todo add id validation
        
        return $this->process_request("{$method}/{$id}", $parameter_array);
    }

    /**
     * goes through the params and removes null (default)
     *
     * @param    array    $array    array of params, with key => null that needs to be removed
     * @return   array              clean array with no null values
     */
    private function clean_parameter_array($array)
    {
        return array_filter($array, create_function('$value', 'return $value !== null;'));
    }

    /**
     * goes through the params and validates
     *
     * @param    array    $array    array of clean params that need to be checked for validity
     * @return   bool               simple true/false if the params are valid
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