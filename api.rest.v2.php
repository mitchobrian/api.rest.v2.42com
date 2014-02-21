<?php

// cfg
$username = '';
$password = '';

// check ssl and check url
$domain = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

// set rest resource
$resource = 'cdr-callshop';

// set get params
$url = '/api/rest/v2/?' . $resource . '&from=' . date('d.m.Y') . '&to=' . date('d.m.Y');

function openRequest($url, $username = '', $password = '') {
   $xDate = new DateTime('@' . time());
   $xDate = $xDate->format('Y-m-d H:i:s.u T');
   $defaults = array(
      CURLOPT_HEADER => 0,
      CURLOPT_URL => $url,
      CURLOPT_FRESH_CONNECT => 1,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_FORBID_REUSE => 1,
      CURLOPT_HTTPHEADER => array(
         'x-date:' . $xDate,
         "x-authorization:" . $username . ":"
         . base64_encode(hash_hmac('sha1', md5($password)
            ."\n"
            . $xDate, md5($password), true))
       ),
   );
   $ch = curl_init();
   curl_setopt_array($ch, $defaults);
   return curl_exec($ch);
}

$result = openRequest($domain.$url, $username, $password);
// http://www.php.net/manual/en/function.json-decode.php
$object = json_decode($result);

if($object->error == true) print 'success';
if($object->error == false) print 'false';

print '<br />';
print $object->codes[0]->code;

print '<br />';
print $object->codes[0]->description;
