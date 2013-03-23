<?php

/**
 * This is for validating inputs before they're sent off to twitter.
 * For licensing and examples:
 *
 * @see https://github.com/avalanche-development/obcento
 *
 * @author dave_kz (http://www.dave.kz/)
 * @version 1.0 (2013-03-22)
 */
class ObcentoTwitterValidateInput{

	/**
	 * check_count:
	 *
	 * This is a simple method to test a 'count' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_include_user_entities	This value you'd like to test
	 * @return 	boolean 								If it's a valid user_name, TRUE is returned.
	 */
	public static function check_count($input_count)
	{
		return is_int($input_count) && $input_count>=0;
	}

	/**
	 * check_since_id:
	 *
	 * This is a simple method to test a 'since_id' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_include_user_entities	This value you'd like to test
	 * @return 	boolean 								If it's a valid user_name, TRUE is returned.
	 */
	public static function check_since_id($input_since_id)
	{
		return is_int($input_since_id) && $input_since_id>=0;
	}

	/**
	 * check_max_id:
	 *
	 * This is a simple method to test a 'max_id' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_max_id	This value you'd like to test
	 * @return 	boolean 				If it's a valid max_id, TRUE is returned.
	 */
	public static function check_max_id($input_max_id)
	{
		return is_int($input_max_id) && $input_max_id>=0;
	}

	/**
	 * check_trim_user:
	 *
	 * This is a simple method to test a 'trim_user' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_trim_user	This value you'd like to test
	 * @return 	boolean 					If it's a valid trim_user, TRUE is returned.
	 */
	public static function check_trim_user($input_trim_user)
	{
		return is_bool($input_trim_user);
	}

	/**
	 * check_contributor_details:
	 *
	 * This is a simple method to test a 'contributor_details' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_contributor_details	This value you'd like to test
	 * @return 	boolean 							If it's a valid contributor_details, TRUE is returned.
	 */
	public static function check_contributor_details($input_contributor_details)
	{
		return is_bool($input_contributor_details);
	}	

	/**
	 * check_include_entities:
	 *
	 * This is a simple method to test a 'include_entities' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_include_entities	This value you'd like to test
	 * @return 	boolean 						If it's a valid include_entities, TRUE is returned.
	 */
	public static function check_include_entities($input_include_entities)
	{
		return is_bool($input_include_entities);
	}

	/**
	 * check_exclude_replies:
	 *
	 * This is a simple method to test a 'exclude_replies' to make sure it's valid.
	 *
	 * @since: 1.0
	 * @param 	mixed 	$input_exclude_replies	This value you'd like to test
	 * @return 	boolean 						If it's a valid exclude_replies, TRUE is returned.
	 */
	public static function check_exclude_replies($input_exclude_replies)
	{
		return is_bool($input_exclude_replies);
	}

	/**
	 * check_include_rts:
	 *
	 * This is a simple method to test a 'include_rts' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_include_rts	This value you'd like to test
	 * @return 	boolean 					If it's a valid include_rts, TRUE is returned.
	 */
	public static function check_include_rts($input_include_rts)
	{
		return is_bool($input_include_rts);
	}

	/**
	 * check_screen_name:
	 *
	 * This is a simple method to test a 'screen_name' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_screen_name	This value you'd like to test
	 * @return 	boolean 					If it's a valid screen_name, TRUE is returned.
	 */
	public static function check_screen_name($input_screen_name)
	{
		//Stub!
		return TRUE;
	}

	/**
	 * check_user_id:
	 *
	 * This is a simple method to test a 'user_id' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_user_id	This value you'd like to test
	 * @return 	boolean 				If it's a valid user_id, TRUE is returned.
	 */
	public static function check_user_id($input_user_id)
	{
		//Stub!
		return TRUE;
	}

	/**
	 * check_include_user_entities:
	 *
	 * This is a simple method to test a 'include_user_entities' to make sure it's valid.
	 *
	 * @param 	mixed 	$input_include_user_entities	This value you'd like to test
	 * @return 	boolean 								If it's a valid include_user_entities, TRUE is returned.
	 */
	public static function check_include_user_entities($input_include_user_entities)
	{
		//Stub!
		return TRUE;
	}

}
