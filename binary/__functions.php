<?php
/**
 * @category Functions
 * @package  Tacnix
 * @author   Mukunda Pancal <hello@tacnix.com>
 * @license  Apache License 2.0 https://github.com/panchalmukundak/tacnix/blob/main/LICENSE
 * @version  GIT: 1.0.0 https://github.com/panchalmukundak/tacnix/main/
 * @link     https://tacnix.com
 * @var      DEFAULT_TIMEZONE - Default timezone of application is made in India.
 */

 function __customErrorReporting($value) {
     if (__ENV__ === $value) { // DEVELOPMENT
         error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
         ini_set('display_errors', 'On');
         ini_set('log_errors', 'On');
         ini_set('error_log', path . ds . 'error.log');
     } else if (__ENV__ === $value) { // MAINTENANCE
         error_reporting(E_ALL);
         ini_set('display_errors', 'Off');
         ini_set('log_errors', 'On');
         ini_set('error_log', path . ds . 'error.log');
     } else if (__ENV__ === $value) { // LIVE
         error_reporting(E_ALL);
         ini_set('display_errors', 'Off');
         ini_set('log_errors', 'On');
         ini_set('error_log', path . ds . 'error.log');
     }
 }


function __cleanurl() {

  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $url = "https://";
  } else {
      $url = "http://";
      // Append the host(domain name, ip) to the URL.
      $url.= $_SERVER['HTTP_HOST'];

      // Append the requested resource location to the URL
      $url.= $_SERVER['REQUEST_URI'];
      $url.= "?go=home";
      return $url;
  }

}

function __timezone($value) {
  // Check the funtion is exist and set the "default timezone" to the function.
  if (function_exists('date_default_timezone_set')) {
    // Set the defined constant to the prebuilt function.
    date_default_timezone_set($value);
  }
}

function __mysqli() {
  $lang = MYSQLi_LANGUAGE;
  $host = MYSQLi_HOSTNAME;
  $user = MYSQLi_USERNAME;
  $pass = MYSQLi_PASSWORD;
  $dbname = MYSQLi_DATABASE;
  $charset = MYSQLi_CHARSET;
  $port = MYSQLi_PORT;
  $db_prefix = MYSQLi_DB_PREFIX;

  // Create connection
  $mysqli  = new mysqli($host, $user, $pass, $dbname);

  // Check connection
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }
  // echo "Connected successfully";

  /* Set the desired charset after establishing a connection */
  $mysqli->set_charset(MYSQLi_CHARSET);
  // printf("Success... %s\n", $mysqli->host_info);

  /* change default database to "world" */
  $mysqli->select_db(MYSQLi_DATABASE);

  /* get the name of the current default database */
  // $result = $mysqli->query("SELECT DATABASE()");
  // $row = $result->fetch_row();
  // printf("Default database is %s.\n", $row[0]);

}

// function db_close() {$mysqli -> close();}

function db_query($value) {
  // Perform query
  if ($result = $mysqli -> query($value)) {
    echo /*"Returned rows are: " . */ $result -> num_rows;
    // Free result set
    $result -> free_result();
  }
}

// function __posts() {
//
// }

function __contactform() {
  /*
   * CONFIGURE EVERYTHING HERE
   */

  // an email address that will receive the email with the output of the form
  $sendTo = 'contact@tacnix.com';

  // message that will be displayed when everything is OK :)
  $okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

  // If something goes wrong, we will display this message.
  $errorMessage = 'There was an error while submitting the form. Please try again later';

  /*
   * LET'S DO THE SENDING
   */

  // if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
  error_reporting( E_ALL & ~E_NOTICE );

  try {

  	if ( count( $_POST ) == 0 && ! isset($_POST['name'])&& ! isset($_POST['email'])&& ! isset($_POST['message'])) {
  		throw new \Exception( 'Form is empty' );
  	}

  	// an email address that will be in the From field of the email.
  	$from = $_POST['email'] ;
  	// The message send in email
  	$message = $_POST['message'] ;
  	// subject of the email
    $subject = $_POST['name'];

  	$headers = 'From: ' . $from . "\r\n" .
  	           'Reply-To: ' . $sendTo . "\r\n" .
  	           'X-Mailer: PHP/' . phpversion();

  	// Send email
  	mail( $sendTo, $subject, $message, $headers );

  	$responseArray = array('type' => 'success', 'message' => $okMessage);
  } catch ( \Exception $e ) {
  	 $responseArray = array('type' => 'danger', 'message' => $errorMessage);
  }

  // if requested by AJAX request return JSON response
  if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
  	$encoded = json_encode( array( 'status' => true, 'message' => $okMessage ) );
  	header( 'Content-Type: application/json' );
  	echo $encoded;
  } /* else just display the message */ else {
  	$encoded = json_encode( array( 'status' => false, 'message' => $errorMessage ) );
  	header( 'Content-Type: application/json' );
  	echo $encoded;
  }

}
