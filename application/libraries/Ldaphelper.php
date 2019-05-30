<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ldaphelper
 *
 * @author Carlos.Blos
 */
class Ldaphelper {

    //put your code here
    public $server, $port, $base, $login, $password, $CI, $cn, $ldapId, $errormsg, $findusers;

    function __construct() {

        $this->CI = & get_instance();
        $this->ldapId = -1;
        $this->connectedType = -1;

        $this->server = 'ldap://' . $this->CI->cdbhelper->getSystemParameters('AD_IP');
        $this->port = $this->CI->cdbhelper->getSystemParameters('AD_PORT');
        $this->base = $this->CI->cdbhelper->getSystemParameters('AD_BASE_DN');
        $this->domain = $this->CI->cdbhelper->getSystemParameters('AD_DOMAIN');

        $this->serverHK = 'ldap://' . $this->CI->cdbhelper->getSystemParameters('AD_IP_HK');
        $this->portHK = $this->CI->cdbhelper->getSystemParameters('AD_PORT_HK');
        $this->baseHK = $this->CI->cdbhelper->getSystemParameters('AD_BASE_DN_HK');
        $this->domainHK = $this->CI->cdbhelper->getSystemParameters('AD_DOMAIN_HK');


        $this->serverUS = 'ldap://' . $this->CI->cdbhelper->getSystemParameters('AD_IP_US');
        $this->portUS = $this->CI->cdbhelper->getSystemParameters('AD_PORT_US');
        $this->baseUS = $this->CI->cdbhelper->getSystemParameters('AD_BASE_DN_US');
        $this->domainUS = $this->CI->cdbhelper->getSystemParameters('AD_DOMAIN_US');



        $this->cn = 'TTI MilwaukeeLMS (CN.DGMIL-Rel)';
        $this->login = $this->cn;
        $this->password = 'MiL@365.comSF';

        $this->cnHK = 'LDAP6';
        $this->loginHK = $this->cnHK;
        $this->passwordHK = 'RhX4Fxs8';

        $this->findusers = $this->CI->cdbhelper->getSystemParameters('AD_FIND_USERS');
        $this->findusersHK = $this->CI->cdbhelper->getSystemParameters('AD_FIND_USERS_HK');
    }

    public function connect($connectType) {
        if ($this->ldapId != -1) {
            $this->close();
        }
        $ldapconfig = array();

        $this->connectedType = $connectType;

        switch ($connectType) {
            case 1:
                $ldapconfig['host'] = $this->server; //CHANGE THIS TO THE CORRECT LDAP SERVER
                $ldapconfig['port'] = $this->port;
                $ldapconfig['basedn'] = $this->base; //CHANGE THIS TO THE CORRECT BASE DN
                $ldapconfig['usersdn'] = $this->login;
                $password = $this->password;
                break;

            case 2:
                $ldapconfig['host'] = $this->serverHK; //CHANGE THIS TO THE CORRECT LDAP SERVER
                $ldapconfig['port'] = $this->portHK;
                $ldapconfig['basedn'] = $this->baseHK; //CHANGE THIS TO THE CORRECT BASE DN
                $ldapconfig['usersdn'] = $this->loginHK;
                $password = $this->passwordHK;
                break;

            default:
                break;
        }


        $this->errormsg = '';

        $this->ldapId = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
        ldap_set_option($this->ldapId, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapId, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapId, LDAP_OPT_NETWORK_TIMEOUT, 10);
        if (!$bind = ldap_bind($this->ldapId, $ldapconfig['usersdn'], $password)) {
            $this->errormsg = ldap_error($this->ldapId);
            return false;
        }

        return true;
    }

    public function checkLogin($cn, $pass, $connectType) {
        $this->errormsg = '';

        switch ($connectType) {
            case 1:
                $ldapconfig['host'] = $this->server; //CHANGE THIS TO THE CORRECT LDAP SERVER
                $ldapconfig['port'] = $this->port;
                $ldapconfig['basedn'] = $this->base; //CHANGE THIS TO THE CORRECT BASE DN
                $ldapconfig['domain'] = $this->domain; //Domain
                $ldapconfig['usersdn'] = $cn;
                //$password = $this->password;
                break;

            case 2:
                $ldapconfig['host'] = $this->serverHK; //CHANGE THIS TO THE CORRECT LDAP SERVER
                $ldapconfig['port'] = $this->portHK;
                $ldapconfig['basedn'] = $this->baseHK; //CHANGE THIS TO THE CORRECT BASE DN
                $ldapconfig['usersdn'] = $cn;
                $ldapconfig['domain'] = $this->domainHK; //Domain
                //$password = $this->passwordHK;
                break;

            case 3:
                $ldapconfig['host'] = $this->serverUS; //CHANGE THIS TO THE CORRECT LDAP SERVER
                $ldapconfig['port'] = $this->portUS;
                $ldapconfig['basedn'] = $this->baseUS; //CHANGE THIS TO THE CORRECT BASE DN
                $ldapconfig['usersdn'] = $cn;
                $ldapconfig['domain'] = $this->domainUS; //Domain
                //$password = $this->passwordUS;
                break;


            default:
                break;
        }

        //die (print_r($ldapconfig));

        $this->ldapId = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
        ldap_set_option($this->ldapId, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapId, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapId, LDAP_OPT_NETWORK_TIMEOUT, 10);

        //$bind=ldap_bind($ds, $username .'@' .$domain, $password);
        if (!$bind = ldap_bind($this->ldapId, $ldapconfig['usersdn'] . '@' . $ldapconfig['domain'], $pass)) {
            $this->errormsg = ldap_error($this->ldapId);
            $this->close();
            return false;
        }

        $this->close();
        return true;
    }

    public function searchUsers() {
        //die ($this->findusers);
        switch ($this->connectedType) {
            case 1:
                $base = $this->base;
                $find = $this->findusers;

                break;
            case 2:
                $base = $this->baseHK;
                $find = $this->findusersHK;
                break;

            default:
                break;
        }


        $info = array();
        $cookie = '';
        do {
            ldap_control_paged_result($this->ldapId, 1000, true, $cookie);

            if (!$ret = ldap_search($this->ldapId, $base, $find)) {
                $this->errormsg = ldap_error($this->ldapId);
                return false;
            };


            $infoPage = ldap_get_entries($this->ldapId, $ret);

            $info = array_merge($info, $infoPage);

            ldap_control_paged_result_response($this->ldapId, $ret, $cookie);
        } while ($cookie !== null && $cookie != '');

        $add = array();

        foreach ($info as $key => $value) {


            switch ($this->connectedType) {
                case 1:
                    $fullname = str_replace('.', ' ', $value['samaccountname'][0]);
                    $username = $value['samaccountname'][0];
                    $canNoID = false;

                    break;
                case 2:

                    $fullname = str_replace('.', ' ', $value['displayname'][0]);
                    $username = $value['samaccountname'][0];
                    $canNoID = true;

                    break;

                default:
                    break;
            }

            if (!isset($username)) {
                continue;
            }

            if (!isset($value['employeeid'])) {
                if (!$canNoID) {
                    continue;
                }
                $id = 0;
            } else {
                $id = $value['employeeid'][0];
            }

            if (!is_numeric($id)) {
                $id = 0;
            }


            $phonenumber = '';
            if (isset($value['telephonenumber'])) {
                $phonenumber = $value['telephonenumber'][0];
            }

            array_push($add, array(
                'department' => $value['department'][0],
                'cn' => $value['distinguishedname'][0],
                'mail' => $value['userprincipalname'][0],
                'account' => $username,
                'employeeid' => $id,
                'phonenumber' => $phonenumber,
                'idemp' => $id,
                'nr_login_mode' => $this->connectedType,
                'fullname' => $fullname,
                'thumb' => isset($value['thumbnailphoto'][0]) ? $value['thumbnailphoto'][0] : ''
                    )
            );
        }


        return $add;
    }

    public function close() {
        ldap_close($this->ldapId);
        $this->connectedType = -1;
        $this->ldapId = -1;
    }

}
