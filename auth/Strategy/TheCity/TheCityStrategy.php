<?php
include('./../db.php');

/**
 * The City strategy for Opauth
 * based on https://github.com/uzyn/opauth-facebook
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @copyright    Copyright © 2013 City Dev Force
 * @link         http://opauth.org
 * @package      Opauth.TheCityStrategy
 * @license      MIT License
 */

 class User {
    function __construct ($dataArray){
        $this->theCityId = $dataArray['uid'];
        $this->firstName = $dataArray['info']['first_name'];
        $this->lastName = $dataArray['info']['last_name'];
        $this->email = $dataArray['info']['email'];
        $this->image = $dataArray['info']['image'];
    }
};


class TheCityStrategy extends OpauthStrategy{
  // Define oauth urls
  public $authorize_url = 'https://authentication.onthecity.org/oauth/authorize';
  public $token_url = 'https://authentication.onthecity.org/oauth/token';
  public $authorization_url = 'https://authentication.onthecity.org/authorization';

  /**
   * Compulsory config keys, listed as unassociative arrays
   * eg. array('app_id', 'app_secret');
   */
  public $expects = array('client_id', 'client_secret');
  
  /**
   * Optional config keys with respective default values, listed as associative arrays
   * eg. array('scope' => 'email');
   */
  public $defaults = array(
    'redirect_uri' => '{complete_url_to_strategy}int_callback'
  );

  /**
   * Auth request
   */
  public function request(){
    $params = array(
      'client_id' => $this->strategy['client_id'],
      'redirect_uri' => $this->strategy['redirect_uri'],
      'response_type' => 'code',
    );

    if (!empty($this->strategy['scope'])) $params['scope'] = $this->strategy['scope'];
    if (!empty($this->strategy['state'])) $params['state'] = $this->strategy['state'];
    if (!empty($this->strategy['response_type'])) $params['response_type'] = $this->strategy['response_type'];
    if (!empty($this->strategy['display'])) $params['display'] = $this->strategy['display'];
    if (!empty($this->strategy['auth_type'])) $params['auth_type'] = $this->strategy['auth_type'];

    $this->clientGet($this->authorize_url, $params);
  }
  
  /**
   * Internal callback, after Facebook's OAuth
   */
  public function int_callback(){
    if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
      $url = 'https://graph.facebook.com/oauth/access_token';
      $params = array(
        'client_id' =>$this->strategy['client_id'],
        'client_secret' => $this->strategy['client_secret'],
        'redirect_uri'=> $this->strategy['redirect_uri'],
        'code' => trim($_GET['code']),
        'grant_type' => 'authorization_code',
      );

      $response = $this->serverPost($this->token_url, $params, null, $headers);
      $results = json_decode($response);
      
      if (!empty($results) && !empty($results->access_token)){
        $me = $this->me($results->access_token);
        
        $this->auth = array(
          'uid' => $me->id,
          'info' => array(),
          'credentials' => array(
            'token' => $results->access_token,
            'expires' => date('c', time() + $results->expires_in)
          ),
          'raw' => $me,
        );

        if (!empty($me->email)) $this->auth['info']['email'] = $me->email;
        if (!empty($me->first_name)) $this->auth['info']['first_name'] = $me->first_name;
        if (!empty($me->last_name)) $this->auth['info']['last_name'] = $me->last_name;
        if (!empty($me->profile_picture)) $this->auth['info']['image'] = $me->profile_picture;
        
        $this->callback();
      }
      else{
        $error = array(
          'provider' => 'TheCity',
          'code' => 'access_token_error',
          'message' => 'Failed when attempting to obtain access token',
          'raw' => $headers
        );

        $this->errorCallback($error);
      }
    }
    else{
      $error = array(
        'provider' => 'TheCity',
        'code' => $_GET['error'],
        'message' => $_GET['error_description'],
        'raw' => $_GET
      );
      
      $this->errorCallback($error);
    }
  }


  public function callback(){
      $timestamp = date('c');

		// To standardize the way of accessing data, objects are translated to arrays
		//$this->auth = $this->recursiveGetObjectVars($this->auth);
		//
		//$this->auth['provider'] = $this->strategy['strategy_name'];
		//
		//$params = array(
		//	'auth' => $this->auth, 
		//	'timestamp' => $timestamp,
		//	'signature' => $this->sign($timestamp)
		//);
		//

        $user = new User($this->auth);

        $brugerData = $this->getBrugerFromTheCityId($user->theCityId);

        if($brugerData == NULL){
            $brugerData = $this->createUser($user);
        }else {
            $brugerData = $this->updateUser($user, $brugerData);
        }
        

        session_start();
        
        $_SESSION['logget_ind'] = 1;
        $_SESSION['brugerid'] = $brugerData["BrugerId"];
        $_SESSION['brugernavn'] = $brugerData['Brugernavn'];
        $_SESSION['password'] = $brugerData['Kode'];
        $_SESSION['email'] = $brugerData["Email"];
        $_SESSION['admin'] = $brugerData["Admin"];

        header("Location: /main.php");
        die();
  }

  /**
   * Queries The City API for user info
   *
   * @param string $access_token 
   * @return array Parsed JSON results
   */
  private function me($access_token){
    $headers = '';
    $me = $this->serverGet(
      $this->authorization_url,
      array('access_token' => $access_token, 'format' => 'json'),
      NULL,
      $headers
    );
    if (!empty($me)){
      $result = json_decode($me);
      if(!empty($result->users)) {
        return $result->users[0];
      } else {
        return $result->user;
      }
    }
    else{
      $error = array(
        'provider' => 'TheCity',
        'code' => 'me_error',
        'message' => 'Failed when attempting to query for user information',
        'raw' => array(
          'response' => $userInfo,
          'headers' => $headers
        )
      );

      $this->errorCallback($error);
    }
  }

    private function createUser($user){
        $sql = sprintf("insert into Bruger (Brugernavn, Fornavn, Efternavn, Email, ImageUrl, OnTheCityId) Values ('%s','%s','%s','%s','%s',%d)",
            $user->email,
            $user->firstName,
            $user->lastName,
            $user->email,
            $user->image,
            $user->theCityId);

        openDB();
        doSQLQuery($sql);
        closeDB();

        return $this->getBrugerFromTheCityId($user->theCityId);
    }

    private function updateUser($user, $brugerData){
        if($user->firstName != $brugerData['Fornavn'] ||
            $user->lastName != $brugerData['Efternavn'] ||
            $user->email != $brugerData['Email'] ||
            $user->image != $brugerData['ImageUrl']
        ){
            $sql = 'update Bruger set Fornavn = \''.$user->firstName.'\''
                    .',Efternavn = \''.$user->lastName.'\''
                    .',Email = \''.$user->email.'\''
                    .',ImageUrl=\''.$user->image.'\''
                    .' Where BrugerId = '.$brugerData['BrugerId'];

            openDB();
            doSQLQuery($sql);
            closeDB();
            
            return $this->getBrugerFromTheCityId($user->theCityId);
        }
        return $brugerData;
    }

    private function getBrugerFromTheCityId($cityId){
        $sql = 'select * from Bruger where OnTheCityId = ' . $cityId;
        openDB();
        $result = doSQLQuery($sql);
        $brugerData = db_fetch_array($result);
        closeDB();
        return $brugerData;
    }
}