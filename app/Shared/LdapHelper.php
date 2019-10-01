<?php

namespace App\Shared;

class LdapHelper {

  // validate login
  public static function DoLogin($username, $password){

    $errorcode = 200;
    $errm = 'success';
    $retdata = [];

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
            $costcenter = $ldapdata['0']['ppcostcenter']['0'];
            $stid = $ldapdata['0']['cn']['0'];

            if($ldapdata['0']['employeetype']['0'] == 'Vendors'){
              $unit = $ldapdata['0']['ppdivision']['0'];
              $subunit = 'Vendors';
              $dept = 'Vendors';
            } else {
              $unit = $ldapdata['0']['pporgunitdesc']['0'];
              $subunit = $ldapdata['0']['ppsuborgunitdesc']['0'];
              $dept = $ldapdata['0']['pporgunit']['0'];
            }


            $retdata = [
              'STAFF_NO' => $stid,
              'NAME' => $ldapdata['0']['fullname']['0'],
              'UNIT' => $unit,
              'SUBUNIT' => $subunit,
              'DEPARTMENT' => $dept,
              'COST_CENTER' => $costcenter,
              'SAP_NUMBER' => $ldapdata['0']['employeenumber']['0'],
              'JOB_GRADE' => $ldapdata['0']['ppjobgrade']['0'],
              'NIRC' => $ldapdata['0']['ppnewic']['0'],
              'EMAIL' => $ldapdata['0']['mail']['0'],
              //'MOBILE_NO' => $ldapdata['0']['mobile']['0'],
              'SUPERIOR' => $ldapdata['0']['ppreporttoname']['0'],
              'PERSNO' => $ldapdata['0']['employeenumber']['0']
            ];
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
      'data' => $retdata
    ];

  }

  public static function FetchUser($username, $searchtype = 'cn'){

    // do the ldap things
    $errm = 'success';
    $errorcode = 200;
    $adminuser = env('TMLDAP_ADMINUSER', 'adminuser');
    $udn= "cn=$adminuser, ou=serviceAccount, o=Telekom";
    $password = env('TMLDAP_ADMINPASS', 'adminpassword');
    $hostnameSSL = env('TMLDAP_HOSTNAME', 'ldaps://idssldap.tm.com.my:636');
    $retdata = [];
    //	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
    putenv('LDAPTLS_REQCERT=never');

    $con =  ldap_connect($hostnameSSL);
    if (is_resource($con)){
      if (ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3)){
        ldap_set_option($con, LDAP_OPT_REFERRALS, 0);

        // try to bind / authenticate
        try{
        if (ldap_bind($con,$udn, $password)){

          // perform the search
          $ldres = ldap_search($con, 'ou=users,o=data', "$searchtype=$username");
          $ldapdata = ldap_get_entries($con, $ldres);
          // dd($ldapdata);
          // return $ldapdata;

          if($ldapdata['count'] == 0){
            $errorcode = 404;
            $errm = 'user not found';
          } else {
            $costcenter = $ldapdata['0']['ppcostcenter']['0'];
            $stid = $ldapdata['0']['cn']['0'];

            if($ldapdata['0']['employeetype']['0'] == 'Vendors'){
              $unit = $ldapdata['0']['ppdivision']['0'];
              $subunit = 'Vendors';
              $dept = 'Vendors';
            } else {
              $unit = $ldapdata['0']['pporgunitdesc']['0'];
              $subunit = $ldapdata['0']['ppsuborgunitdesc']['0'];
              $dept = $ldapdata['0']['pporgunit']['0'];
            }


            $retdata = [
              'STAFF_NO' => $stid,
              'NAME' => $ldapdata['0']['fullname']['0'],
              'UNIT' => $unit,
              'SUBUNIT' => $subunit,
              'DEPARTMENT' => $dept,
              'COST_CENTER' => $costcenter,
              'SAP_NUMBER' => $ldapdata['0']['employeenumber']['0'],
              'JOB_GRADE' => $ldapdata['0']['ppjobgrade']['0'],
              'NIRC' => $ldapdata['0']['ppnewic']['0'],
              'EMAIL' => $ldapdata['0']['mail']['0'],
              'MOBILE_NO' => $ldapdata['0']['mobile']['0'],
              'SUPERIOR' => $ldapdata['0']['ppreporttoname']['0'],
              'PERSNO' => $ldapdata['0']['employeenumber']['0']
            ];

            //$retdata = $ldapdata;
          }

        } else {
          $errorcode = 403;
          $errm = 'Invalid admin credentials.';
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
      'data' => $retdata
    ];
  }

}
