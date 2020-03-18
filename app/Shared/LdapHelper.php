<?php

namespace App\Shared;

use App\User;

class LdapHelper {

  // validate login
  public static function DoLogin($username, $password){
    $kont = new \App\Http\Controllers\Controller;

    set_error_handler(array($kont, 'errorHandler'));

    $errorcode = 200;
    $errm = 'success';
    $persno = '';

    $udn = "cn=$username,ou=users,o=data";
    $hostnameSSL = env('TMLDAP_HOSTNAME', 'ldaps://idssldap.tm.com.my:636');
    //	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
    putenv('LDAPTLS_REQCERT=never');

    $con =  ldap_connect($hostnameSSL);
    if (is_resource($con)){
      if (ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3)){
        ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

        // try to mind / authenticate
        try{
        if (ldap_bind($con,$udn, $password)){
          $errm = 'success';
          // perform the search
          $ldres = ldap_search($con, 'ou=users,o=data', "cn=$username");
          $ldapdata = ldap_get_entries($con, $ldres);
          // dd($ldapdata);
          // return $ldapdata;

          if($ldapdata['count'] == 0){
            $errorcode = 404;
            $errm = 'user not found';
          } else {
            $persno_str = $ldapdata['0']['employeenumber']['0'];
            $persno = substr($persno_str, -7);
          }

        } else {
          $errorcode = 401;
          $errm = 'Invalid credentials.';
        }} catch(Exception $e) {
          $errorcode = 500;
          $errm = $e->getMessage();
        }

      } else {
        $errorcode = 500;
        $errm = "TLS not supported. Unable to set LDAP protocol version to 3";
      }

      // clean up after done
      ldap_close($con);

    } else {
      $errorcode = 500;
      $errm = "Unable to connect to $hostnameSSL";
    }

    return [
      'code' => $errorcode,
      'msg' => $errm,
      'data' => $persno
    ];

  }
}
