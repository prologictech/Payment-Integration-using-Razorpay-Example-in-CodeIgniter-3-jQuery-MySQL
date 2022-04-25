<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	$config['mode']   = 'prod';

	/**
	 * Account SID
	 **/
	// $config['account_sid']   = TWILIO_TEST_SID;
	$config['account_sid']   = TWILIO_ACCOUNT_SID;

	/**
	 * Auth Token
	 **/
	// $config['auth_token']    = TWILIO_TEST_TOKEN;
	$config['auth_token']    = TWILIO_AUTH_TOKEN;

	/**
	 * API Version
	 **/
	$config['api_version']   = '2010-04-01';

	/**
	 * Twilio Phone Number
	 **/
	// $config['number']        = TWILIO_TEST_NUMBER;
	$config['number']        = TWILIO_NUMBER;


/* End of file twilio.php */
// vv00-2c7560mzgTdek_nv2e6rVm5xL7JABlZY3sn