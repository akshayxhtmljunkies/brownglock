<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
class InstaWCD{
    function userID(){
        $username = strtolower($this->username); // sanitization
        $token = $this->access_token;
        
    if(!empty($username) && !empty($token)) {

        $url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$token;
        
        $get = wp_remote_get($url);
        $response = wp_remote_retrieve_body( $get );
        $json = json_decode($response);

        foreach($json->data as $user){
            if($user->username == $username){
                return $user->id;
            }
        }
            return '00000000'; // return this if nothing is found
         }
    }

    function get_remote_data_from_instagram_in_json($url){
        $content = wp_remote_get( $url );
        if(isset($content->errors)){
            echo $content->errors['http_request_failed']['0'];
            die();
        }else{
            $response = wp_remote_retrieve_body( $content );
            $json = json_decode( $response, true );
            return $json;
        }
    }

    function userMedia(){
        $url = 'https://api.instagram.com/v1/users/'.$this->userID().'/media/recent/?access_token='.$this->access_token;
        $json = self:: get_remote_data_from_instagram_in_json( $url );
        return $json;
        // $content = wp_remote_get($url);
        // $response = wp_remote_retrieve_body( $content );
        //   return $json = json_decode($response, true);
    }
}
$insta = new InstaWCD();
        $insta->username = $username;
        $insta->access_token = $access_token;
?>