<?php

require('CFPropertyList/CFPropertyList.php'); 
require_once('vendor/autoload.php');

$debug=false;
if($debug==true)
{ 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

error_reporting(0);
set_time_limit(0);


if(!isset($_POST['activation-info']) || empty($_POST['activation-info'])) { exit('Method not implemented'); }
if(isset($_POST['activation-info'])) {
    
	$activation = $_POST['activation-info'];
	$ticketID = $_POST['activation-info'];
	$encodedrequest = new DOMDocument;
	$encodedrequest->loadXML($activation);
	$activationDecoded= base64_decode($encodedrequest->getElementsByTagName('data')->item(0)->nodeValue);

	$decodedrequest = new DOMDocument;
	$decodedrequest->loadXML($activationDecoded);
	$nodes = $decodedrequest->getElementsByTagName('dict')->item(0)->getElementsByTagName('*');
	    
	for ($i = 0; $i < $nodes->length - 1; $i=$i+2){
	  ${$nodes->item($i)->nodeValue} = preg_match('/(true|false)/', $nodes->item($i + 1)->nodeName) ? $nodes->item($i + 1)->nodeName : $nodes->item($i + 1)->nodeValue;
	    switch ($nodes->item($i)->nodeValue){
        case "ActivationState": $activationState = $nodes->item($i + 1)->nodeValue; break;
        case "ActivationRandomness": $activationRandomness = $nodes->item($i + 1)->nodeValue; break;
        case "DeviceCertRequest": $deviceCertRequest = $nodes->item($i + 1)->nodeValue; break;
        case "DeviceClass": $deviceClass = $nodes->item($i + 1)->nodeValue; break;
        case "BasebandChipID": $BasebandChipID = $nodes->item($i + 1)->nodeValue; break;
        case "InternationalMobileEquipmentIdentity": $imei = $nodes->item($i + 1)->nodeValue; break;
        case "MobileEquipmentIdentifier": $meid = $nodes->item($i + 1)->nodeValue; break;
        case "ProductType": $productType = $nodes->item($i + 1)->nodeValue; break;
        case "ProductVersion": $productVersion = $nodes->item($i + 1)->nodeValue; break;
        case "OSType": $OSType = $nodes->item($i + 1)->nodeValue; break;
        case "WifiAddress": $wifi = $nodes->item($i + 1)->nodeValue; break;
        case "UniqueChipID": $ecid = $nodes->item($i + 1)->nodeValue; break;
        case "ChipID": $chipID = $nodes->item($i + 1)->nodeValue; break;
        case "BluetoothAddress": $BluetoothAddress = $nodes->item($i + 1)->nodeValue; break;
        case "UniqueDeviceID": $uniqueDeviceID = $nodes->item($i + 1)->nodeValue; break;
        case "SerialNumber": $serialNumber = $nodes->item($i + 1)->nodeValue; break;
        case "BasebandMasterKeyHash": $BasebandMasterKeyHash = $nodes->item($i + 1)->nodeValue; break;
        case "BasebandSerialNumber": $BasebandSerialNumber = $nodes->item($i + 1)->nodeValue; break;
        case "RegulatoryModelNumber": $RegulatoryModelNumber = $nodes->item($i + 1)->nodeValue; break;
        case "ModelNumber": $ModelNumber = $nodes->item($i + 1)->nodeValue; break;
        case "BuildVersion": $BuildVersion = $nodes->item($i + 1)->nodeValue; break;
        case "mac_fg": $mac_fg = $nodes->item($i + 1)->nodeValue; break;
        case "SIMStatus": $SIMStatus = $nodes->item($i + 1)->nodeValue; break;
        case "UIKCertification": $UIKCertification = $nodes->item($i + 1)->nodeValue; break;
	    }
	} 
	$RKCertification= $encodedrequest->getElementsByTagName('data')->item(3)->nodeValue;
	$RKSignature= $encodedrequest->getElementsByTagName('data')->item(4)->nodeValue;

	$IMSIUnlocked = '311580112345678';
	$ICCIDUnlocked = '89014103278673435379';
	$iOSBuildUnlocked = '19B74';
	$iOSVersionUnlocked = '15.1';

    $FPDC=FPDC_ALL($activationRandomness,$deviceCertRequest,$uniqueDeviceID,$BuildVersion,$DeviceClass,$DeviceVariant,$ModelNumber,$OSType,$productType,$ProductVersion,$RegulatoryModelNumber,$UniqueChipID);


    $FairPlayKeyData = $FPDC[0];
    $DeviceCertificate = $FPDC[1];
    
$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCzYmXsSN3d7UTU8f77wm9C0IIJAwCmAeixBwkmWxJl239RFe9P
RbOPzk0WHTiEARBXToxx4V7eZxR12kiaTG/wRWVm6Jy1okz0U8HsmGKQsJS+EvKg
rFx3FgdzclqXulBOZzBSHvAwTo+ypNPR+vhmeYeRL6HvTuZBjZQYKeDyzwIDAQAB
AoGBAKL7vzFND1CpWIXGDe9+vIpPWiaH9NngGCRoCRcxXejv4qCwtksnQDtjrMRv
7j55nPhGZPK/WuvlakCeAKM42eZF/q2gRBeAZJNQkSHBW9d/OEt7bla92Fj+8IjP
A3cQ+eyo/KyNtF6OL9KE6ghMskKsGBkdMZkDJHMxVu+sK35pAkEA3QBbOwB4tPdK
4w+RwufoTmmSDxTGO5uvpsBRnFQ4K0s3WfPjhumDQRBeic+HxTDY72O1/iDpTbL9
pTW4f5qeswJBAM/K108a370DybA87FYVvMDOGBJsudIzLLhNj4eP4pO2+Dai955Y
qXTF1ntlOX7lD73QYFyrfrvMqWj43i3laXUCQFUymvkPAHm7T+pjCS1bW+pGtqEL
wDQgm8GsKIocyZ6fG5KY/CD5irkdh2SXVd8GKst25CU5KNfkZfY31I2U3RMCQQC4
DqGHNXPH1ooZrO1fF2QZmLSj5WD3u1K6ciFX3/DADUtyAgq6XSjFAdUJelFigH3g
Eaq5i0L4EMJi9EbBertdAkAdMef5SNkge26nq7nylq0/mVA0sEPTA/bSAMrZDVgV
4UBLXq12y1pQArJ/8rzkdL4x6fak50qzupAa/Jer8kie
-----END RSA PRIVATE KEY-----';


    $MEIDTicket = "LS0tLS1CRUdJTiBDRVJUSUZJQ0FURS0tLS0tCk1JSURWRENDQXZxZ0F3SUJBZ0lHQVhBTHpBT3RNQW9HQ0NxR1NNNDlCQU1DTUVVeEV6QVJCZ05WQkFnTUNrTmgKYkdsbWIzSnVhV0V4RXpBUkJnTlZCQW9NQ2tGd2NHeGxJRWx1WXk0eEdUQVhCZ05WQkFNTUVFWkVVa1JETFZWRApVbFF0VTFWQ1EwRXdIaGNOTWpBd01qQXpNVFUxTkRRMFdoY05NakF3TWpFd01UWXdORFEwV2pCdU1STXdFUVlEClZRUUlEQXBEWVd4cFptOXlibWxoTVJNd0VRWURWUVFLREFwQmNIQnNaU0JKYm1NdU1SNHdIQVlEVlFRTERCVjEKWTNKMElFeGxZV1lnUTJWeWRHbG1hV05oZEdVeElqQWdCZ05WQkFNTUdUQXdNREE0TURBd0xUQXdNREF3TlRjMwpPRUpDUWpZNVJqa3dXVEFUQmdjcWhrak9QUUlCQmdncWhrak9QUU1CQndOQ0FBUXdWN1doYVczbkk4R0d0b0JMCnQxcUFWYytheFlzRHIyVlBGeE5CTXk3M3hCMjlUT3BlTnU3bEdUMWpLQVN4TFN1dGJDMkRZc2NsMUg5ME5KY1cKNnFNUW80SUJxekNDQWFjd0RBWURWUjBUQVFIL0JBSXdBREFPQmdOVkhROEJBZjhFQkFNQ0JQQXdnZ0VYQmdrcQpoa2lHOTJOa0NnRUVnZ0VJTVlJQkJQK0VtcUdTVUEwd0N4WUVRMGhKVUFJREFJQUEvNFNxalpKRUVEQU9GZ1JGClEwbEVBZ1lGZDR1N2Fmbi9ocE8xd21NYk1Ca1dCR0p0WVdNRUVUazRPakV3T21VNE9qQTRPbVUwT2pkaS80YkwKdGNwcEdUQVhGZ1JwYldWcEJBOHpOVFV6T1Rrd09EUTFPREl5TWpmL2h1dVYwbVFZTUJZV0JHMWxhV1FFRGpNMQpOVE01T1RBNE5EVTRNakl5LzRlYnlkeHRGakFVRmdSemNtNXRCQXhHUmsxVU9UaEdTMGRTV1VmL2g2dVIwbVF5Ck1EQVdCSFZrYVdRRUtEUmxOVFZrWXpBell6Y3pNamRrTmpRd01EazNZVE13TkdObFlXRmtZV1kxWXpsbU1Ea3gKWTJiL2g3dTF3bU1iTUJrV0JIZHRZV01FRVRrNE9qRXdPbVU0T2pBNE9tVTBPamRoTURJR0NpcUdTSWIzWTJRRwpBUThFSkRFaS80VHFoWnhRQ2pBSUZnUk5RVTVRTVFEL2hQcUpsRkFLTUFnV0JFOUNTbEF4QURBU0Jna3Foa2lHCjkyTmtDZ0lFQlRBREFnRUFNQ1FHQ1NxR1NJYjNZMlFJQndRWE1CVy9pbmdHQkFReE1pNDB2NHA3QndRRk1UWkgKTnpjd0NnWUlLb1pJemowRUF3SURTQUF3UlFJZ05sdXJQZS9RbEtsYTRmd05DYnU3RjMvaEhkZmFxVGM0TWlOVQp3NTlqWW4wQ0lRQ1c3TWtoTFEvM090eTdIbkxNMmU2eEFDRm1wV2tBSWVmK3h6SGdBUnVuaWc9PQotLS0tLUVORCBDRVJUSUZJQ0FURS0tLS0tdWtNdEg5UmRTUXZIekJ4N0ZpQkdyNy9LY21seFgvWHdvV2VXbldiNklSTT0KLS0tLS1CRUdJTiBDRVJUSUZJQ0FURS0tLS0tCk1JSUNGekNDQVp5Z0F3SUJBZ0lJT2NVcVE4SUMvaHN3Q2dZSUtvWkl6ajBFQXdJd1FERVVNQklHQTFVRUF3d0wKVTBWUUlGSnZiM1FnUTBFeEV6QVJCZ05WQkFvTUNrRndjR3hsSUVsdVl5NHhFekFSQmdOVkJBZ01Da05oYkdsbQpiM0p1YVdFd0hoY05NVFl3TkRJMU1qTTBOVFEzV2hjTk1qa3dOakkwTWpFME16STBXakJGTVJNd0VRWURWUVFJCkRBcERZV3hwWm05eWJtbGhNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVJrd0Z3WURWUVFEREJCR1JGSkUKUXkxVlExSlVMVk5WUWtOQk1Ga3dFd1lIS29aSXpqMENBUVlJS29aSXpqMERBUWNEUWdBRWFEYzJPL01ydVl2UApWUGFVYktSN1JSem42NkIxNC84S29VTXNFRGI3bkhrR0VNWDZlQyswZ1N0R0hlNEhZTXJMeVdjYXAxdERGWW1FCkR5a0dRM3VNMmFON01Ia3dIUVlEVlIwT0JCWUVGTFNxT2tPdEcrVit6Z29NT0JxMTBobkxsVFd6TUE4R0ExVWQKRXdFQi93UUZNQU1CQWY4d0h3WURWUjBqQkJnd0ZvQVVXTy9XdnNXQ3NGVE5HS2FFcmFMMmUzczZmODh3RGdZRApWUjBQQVFIL0JBUURBZ0VHTUJZR0NTcUdTSWIzWTJRR0xBRUIvd1FHRmdSMVkzSjBNQW9HQ0NxR1NNNDlCQU1DCkEya0FNR1lDTVFEZjV6TmlpS04vSnFtczF3KzNDRFlrRVNPUGllSk1wRWtMZTlhMFVqV1hFQkRMMFZFc3EvQ2QKRTNhS1hrYzZSMTBDTVFEUzRNaVdpeW1ZK1J4a3Z5L2hpY0REUXFJL0JMK04zTEhxekpaVXV3MlN4MGFmRFg3Qgo2THlLaytzTHE0dXJrTVk9Ci0tLS0tRU5EIENFUlRJRklDQVRFLS0tLS0=";


    if(empty($imei)==false && empty($meid)==true){
      $baseband_ticket=baseband_nomeid($BasebandMasterKeyHash, $BasebandChipID, $BasebandSerialNumber, $BuildVersion, $productType, $productVersion, $RegulatoryModelNumber,$activationRandomness);
        
        $AccountToken=base64_encode('{
        "InternationalMobileEquipmentIdentity" = "'.$imei.'";
        "PhoneNumberNotificationURL" = "https://albert.apple.com/deviceservices/phoneHome";
        "SerialNumber" = "'.$serialNumber.'";
        "InternationalMobileSubscriberIdentity" = "'.$IMSIUnlocked.'";
        "ProductType" = "'.$productType.'";
        "UniqueDeviceID" = "'.$uniqueDeviceID.'";
        "WildcardTicket" = "'.$baseband_ticket.'";
        "ActivationRandomness" = "'.$activationRandomness.'";
        "ActivityURL" = "https://albert.apple.com/deviceservices/activity";
        "IntegratedCircuitCardIdentity" = "'.$ICCIDUnlocked.'";
        }');
    } else if(empty($imei)==false && empty($meid)==false){

      $AccountToken=base64_encode('{
          "InternationalMobileEquipmentIdentity" = "'.$imei.'";
          "ActivationTicket" = "'.$MEIDTicket.'";
          "PhoneNumberNotificationURL" = "https://albert.apple.com/deviceservices/phoneHome";
          "SerialNumber" = "'.$serialNumber.'";
          "InternationalMobileSubscriberIdentity" = "'.$IMSIUnlocked.'";
          "MobileEquipmentIdentifier" = "'.$meid.'";
          "ProductType" = "'.$productType.'";
          "UniqueDeviceID" = "'.$uniqueDeviceID.'";
          "ActivationRandomness" = "'.$activationRandomness.'";
          "ActivityURL" = "https://albert.apple.com/deviceservices/activity";
          "IntegratedCircuitCardIdentity" = "'.$ICCIDUnlocked.'";
        }');
    }else{
      $AccountToken=base64_encode('{
        "CertificateURL" = "https://albert.apple.com/deviceservices/certifyMe";
        "SerialNumber" = "'.$serialNumber.'";
        "InternationalMobileSubscriberIdentity" = "";
        "ProductType" = "'.$productType.'";
        "UniqueDeviceID" = "'.$uniqueDeviceID.'";
        "ActivationRandomness" = "'.$activationRandomness.'";
        "ActivityURL" = "https://albert.apple.com/deviceservices/activity";
        "IntegratedCircuitCardIdentity" = "";
        }');
    }

    $AccountTokenCertificate = 'LS0tLS1CRUdJTiBDRVJUSUZJQ0FURS0tLS0tCk1JSURaekNDQWsrZ0F3SUJBZ0lCQWpBTkJna3Foa2lHOXcwQkFRVUZBREI1TVFzd0NRWURWUVFHRXdKVlV6RVQKTUJFR0ExVUVDaE1LUVhCd2JHVWdTVzVqTGpFbU1DUUdBMVVFQ3hNZFFYQndiR1VnUTJWeWRHbG1hV05oZEdsdgpiaUJCZFhSb2IzSnBkSGt4TFRBckJnTlZCQU1USkVGd2NHeGxJR2xRYUc5dVpTQkRaWEowYVdacFkyRjBhVzl1CklFRjFkR2h2Y21sMGVUQWVGdzB3TnpBME1UWXlNalUxTURKYUZ3MHhOREEwTVRZeU1qVTFNREphTUZzeEN6QUoKQmdOVkJBWVRBbFZUTVJNd0VRWURWUVFLRXdwQmNIQnNaU0JKYm1NdU1SVXdFd1lEVlFRTEV3eEJjSEJzWlNCcApVR2h2Ym1VeElEQWVCZ05WQkFNVEYwRndjR3hsSUdsUWFHOXVaU0JCWTNScGRtRjBhVzl1TUlHZk1BMEdDU3FHClNJYjNEUUVCQVFVQUE0R05BRENCaVFLQmdRREZBWHpSSW1Bcm1vaUhmYlMyb1BjcUFmYkV2MGQxams3R2JuWDcKKzRZVWx5SWZwcnpCVmRsbXoySkhZdjErMDRJekp0TDdjTDk3VUk3ZmswaTBPTVkwYWw4YStKUFFhNFVnNjExVApicUV0K25qQW1Ba2dlM0hYV0RCZEFYRDlNaGtDN1QvOW83N3pPUTFvbGk0Y1VkemxuWVdmem1XMFBkdU94dXZlCkFlWVk0d0lEQVFBQm80R2JNSUdZTUE0R0ExVWREd0VCL3dRRUF3SUhnREFNQmdOVkhSTUJBZjhFQWpBQU1CMEcKQTFVZERnUVdCQlNob05MK3Q3UnovcHNVYXEvTlBYTlBIKy9XbERBZkJnTlZIU01FR0RBV2dCVG5OQ291SXQ0NQpZR3UwbE01M2cyRXZNYUI4TlRBNEJnTlZIUjhFTVRBdk1DMmdLNkFwaGlkb2RIUndPaTh2ZDNkM0xtRndjR3hsCkxtTnZiUzloY0hCc1pXTmhMMmx3YUc5dVpTNWpjbXd3RFFZSktvWklodmNOQVFFRkJRQURnZ0VCQUY5cW1yVU4KZEErRlJPWUdQN3BXY1lUQUsrcEx5T2Y5ek9hRTdhZVZJODg1VjhZL0JLSGhsd0FvK3pFa2lPVTNGYkVQQ1M5Vgp0UzE4WkJjd0QvK2Q1WlFUTUZrbmhjVUp3ZFBxcWpubTlMcVRmSC94NHB3OE9OSFJEenhIZHA5NmdPVjNBNCs4CmFia29BU2ZjWXF2SVJ5cFhuYnVyM2JSUmhUekFzNFZJTFM2alR5Rll5bVplU2V3dEJ1Ym1taWdvMWtDUWlaR2MKNzZjNWZlREF5SGIyYnpFcXR2eDNXcHJsanRTNDZRVDVDUjZZZWxpblpuaW8zMmpBelJZVHh0UzZyM0pzdlpEaQpKMDcrRUhjbWZHZHB4d2dPKzdidFcxcEZhcjBaakY5L2pZS0tuT1lOeXZDcndzemhhZmJTWXd6QUc1RUpvWEZCCjRkK3BpV0hVRGNQeHRjYz0KLS0tLS1FTkQgQ0VSVElGSUNBVEUtLS0tLQo=';

    // Sign Account Token
    openssl_sign(base64_decode($AccountToken), $signature, $private_key, 'sha1WithRSAEncryption'); //sha1WithRSAEncryption
    $AccountTokenSignature = base64_encode($signature);

    $response =
    '<plist version="1.0">
      <dict>
        <key>'.($deviceClass == "iPhone" ? 'iphone' : 'device').'-activation</key>
        <dict>
          <key>activation-record</key>
          <dict>
            <key>unbrick</key>
            <true/>
            <key>AccountTokenCertificate</key>
            <data>'.$AccountTokenCertificate.'</data>
            <key>DeviceCertificate</key>
            <data>'.$DeviceCertificate.'</data>
            <key>FairPlayKeyData</key><data>'.$FairPlayKeyData.'</data>
            <key>AccountToken</key><data>'.$AccountToken.'</data>
            <key>AccountTokenSignature</key><data>'.$AccountTokenSignature.'</data>
            <key>LDActivationVersion</key>
            <integer>2</integer>
            <key>RegulatoryInfo</key>
            <data>eyJtYW51ZmFjdHVyaW5nRGF0ZSI6bnVsbCwiZWxhYmVsIjp7ImJpcyI6bnVsbCwibWlpdCI6eyJuYWwiOm51bGwsImxhYmVsSWQiOm51bGx9fSwiY291bnRyeU9mT3JpZ2luIjpudWxsfQ==</data>
          </dict>
          <key>show-settings</key>
          <true/>
        </dict>
      </dict>
    </plist>';

    header("ARS: ".base64_encode(hex2bin(hash("sha1", $activation))));
    header('Content-type: application/xml');
    header('Content-Length: '.strlen($response));
    echo $response;

}


function albertrequest($query){
  $url = 'https://albert.apple.com/deviceservices/deviceActivation';
  $data_info=urlencode($query);
$post_data ="activation-info=".$data_info;
  
  //echo $bacio;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL , $url );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
  curl_setopt($ch, CURLOPT_TIMEOUT , 60);
  curl_setopt($ch, CURLOPT_VERBOSE, 0);
   curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: albert.apple.com", "Content-Type: application/x-www-form-urlencoded", "Connection: keep-alive", "Accept: */*", "Accept-Language: en-US", "Content-Length: ".strlen($post_data)));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_USERAGENT , "iOS Device Activator (MobileActivation-353.200.48)" );
  curl_setopt($ch, CURLOPT_POST , 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS , $post_data );

  $xml_response = curl_exec($ch);

  if (curl_errno($ch)) {
      $error_message = curl_error($ch);
      $error_no = curl_errno($ch);

      echo "error_message: " . $error_message . "<br>";
      echo "error_no: " . $error_no . "<br>";
  }

  curl_close($ch);

  return $xml_response;
}


// Generate Activation Record chunks based on iPhone 5C template activation request
function FPDC_ALL($activationRandomness,$deviceCertRequest,$uniqueDeviceID,$BuildVersion,$DeviceClass,$DeviceVariant,$ModelNumber,$OSType,$productType,$ProductVersion,$RegulatoryModelNumber,$UniqueChipID){

  $ActivationInfoXML = '<?xml version="1.0" encoding="UTF-8"?>
  <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
  <plist version="1.0">
  <dict>
      <key>ActivationRequestInfo</key>
      <dict>
          <key>ActivationRandomness</key>
          <string>'.$activationRandomness.'</string>
          <key>ActivationState</key>
          <string>Unactivated</string>
          <key>FMiPAccountExists</key>
          <false/>
      </dict>
      <key>BasebandRequestInfo</key>
      <dict>
          <key>ActivationRequiresActivationTicket</key>
          <true/>
          <key>BasebandActivationTicketVersion</key>
          <string>V2</string>
          <key>BasebandChipID</key>
          <integer>7282913</integer>
          <key>BasebandMasterKeyHash</key>
          <string>AEA5CCE143668D0EFB4CE1F2C94C966A6496C6AA</string>
          <key>BasebandSerialNumber</key>
          <data>
          MJh5Bg==
          </data>
          <key>InternationalMobileEquipmentIdentity</key>
          <string>****REDACTED*****</string>
          <key>SIMStatus</key>
          <string>kCTSIMSupportSIMStatusNotInserted</string>
          <key>SupportsPostponement</key>
          <true/>
          <key>kCTPostponementInfoPRIVersion</key>
          <string>0.0.0</string>
          <key>kCTPostponementInfoPRLName</key>
          <integer>0</integer>
          <key>kCTPostponementInfoServiceProvisioningState</key>
          <false/>
      </dict>
      <key>DeviceCertRequest</key>
      <data>
      '.$deviceCertRequest.'
      </data>
      <key>DeviceID</key>
      <dict>
          <key>SerialNumber</key>
          <string>FCGNN5D2****</string>
          <key>UniqueDeviceID</key>
          <string>'.$uniqueDeviceID.'</string>
      </dict>
      <key>DeviceInfo</key>
      <dict>
          <key>BuildVersion</key>
          <string>'.$BuildVersion.'</string>
          <key>DeviceClass</key>
          <string>'.$DeviceClass.'</string>
          <key>DeviceVariant</key>
          <string>'.$DeviceVariant.'</string>
          <key>ModelNumber</key>
          <string>'.$ModelNumber.'</string>
          <key>OSType</key>
          <string>'.$OSType.'</string>
          <key>ProductType</key>
          <string>'.$productType.'</string>
          <key>ProductVersion</key>
          <string>'.$ProductVersion.'</string>
          <key>RegionCode</key>
          <string>IP</string>
          <key>RegionInfo</key>
          <string>IP/A</string>
          <key>RegulatoryModelNumber</key>
          <string>'.$RegulatoryModelNumber.'</string>
          <key>UniqueChipID</key>
          <integer>'.$UniqueChipID.'</integer>
      </dict>
      <key>RegulatoryImages</key>
      <dict>
          <key>DeviceVariant</key>
          <string>'.$DeviceVariant.'</string>
      </dict>
      <key>UIKCertification</key>
      <dict>
          <key>BluetoothAddress</key>
          <string>68:ae:20:9d:ce:81</string>
          <key>BoardId</key>
          <integer>14</integer>
          <key>ChipID</key>
          <integer>35152</integer>
          <key>EthernetMacAddress</key>
          <string>68:ae:20:9d:ce:82</string>
          <key>UIKCertification</key>
          <data>
          TlVMTA==
          </data>
          <key>WifiAddress</key>
          <string>68:ae:20:9d:ce:80</string>
      </dict>
  </dict>
  </plist>
';

  $ActivationInfoXML64 = base64_encode($ActivationInfoXML);

  $private = provide_privk();
  
  $FairPlayCertChain = provide_fairplaycertchain();
  
  openssl_sign($ActivationInfoXML, $signature, $private, 'sha1WithRSAEncryption'); //sha1WithRSAEncryption
  $ActivationInfoXMLSignature = base64_encode($signature);

  
  $data = '<?xml version="1.0" encoding="UTF-8"?>
  <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
  <plist version="1.0">
  <dict>
      <key>ActivationInfoComplete</key>
      <true/>
      <key>ActivationInfoXML</key>
      <data>'.$ActivationInfoXML64.'</data>
      <key>FairPlayCertChain</key>
      <data>'.$FairPlayCertChain.'</data>
      <key>FairPlaySignature</key>
      <data>'.$ActivationInfoXMLSignature.'</data>
      <key>RKCertification</key>
      <data>
      TlVMTA==
      </data>
      <key>RKSignature</key>
      <data>
      TlVMTA==
      </data>
      <key>serverKP</key>
      <data>
      TlVMTA==
      </data>
      <key>signActRequest</key>
      <data>
      TlVMTA==
      </data>
</dict>
</plist>
';

  $responseXML=albertrequest($data);
        
  $encodedrequest = new DOMDocument;
  $encodedrequest->loadXML($responseXML);


$FairPlayKeyData=$encodedrequest->getElementsByTagName('data')->item(2)->nodeValue;
$DeviceCertificate=$encodedrequest->getElementsByTagName('data')->item(1)->nodeValue;

  return array($FairPlayKeyData, $DeviceCertificate);
}
                                  
                                  
function provide_privk() {
                                  
$private = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDFYApbhRlNahCjy7KYJr6ueYIZHUiZwYXqHiwVlx7SH6wO8VGd
y20FAXIRhqVg79bX6JDwJioCyy+CnnZrfPJ6EOeQnamLTIAsyZoK6/AfNLVlbL3U
iEduvo/oYX42NLakS5dd1xjBHEcVTVwcAJG48xSziREotHNpV2N6s7ZdMwIDAQAB
AoGALBnrBosFXc5SvH6Rv7x8g7ijsJ/h7nUWynqGaCaVnOa1x7r0/RiMmf86MR9Q
SYodi62r4PTuEyS6T2m/1QGcnfOaGRG1O1Lp4hS0h2iRzpzlfOw7Y4d5UwJkkGzU
kkOvKHWkygcrRP05bpNpNOmZnkIWwd94YTMm+2NKDt0FNJUCQQD70kEBv0z4+gwJ
HSqinCsM/dkFSJUEOMxcQz2tinj43ONp7Y/ItwmdmCsySaqz8Qx0kO3qHceSRKZV
AOwa04olAkEAyKZ/UACRT8C+5xhgwJrd7of02PJdBPbk0jXSIIZuKo/lxba2ZRIO
ejoIQaiyMkyI3uTDfirP+EiSzmcZhsyudwJAfkgM43RshDrYiEWBGPSZvjUafMTO
PcTB7s6lgn57dclHndpNDYmEn+wsVVaN7RjHdzkqpgnzB823X89Ll5RXpQJATYIj
g9/0Qf6Ov+5m0YABYvcZ2gQlcpl84sbvmKjVPZPAipN9+aTz+rsYHWTxEQUHijKM
Ydxf4eUG8Lxa2/uNGQJBAI+iWlpfSEMe/GmZugUhtEhodPDBuOzgCVHgivnfji/a
0EFPkqfehr4eM5X3YED2hxIz1XXfZcQXj8PsYYO00O0=
-----END RSA PRIVATE KEY-----';
return $private;
                                  
 }

function provide_fairplaycertchain() {

$FairPlayCertChain = "MIIC8zCCAlygAwIBAgIKBE5S8+SIir3++TANBgkqhkiG9w0BAQUFADBaMQswCQYDVQQGEwJVUzETMBEGA1UEChMKQXBwbGUgSW5jLjEVMBMGA1UECxMMQXBwbGUgaVBob25lMR8wHQYDVQQDExZBcHBsZSBpUGhvbmUgRGV2aWNlIENBMB4XDTIxMTAwNTIxNTczM1oXDTI0MTAwNTIxNTczM1owgYMxLTArBgNVBAMWJEJDQjgwQjJELThCRTUtNENGRi04NDlFLUMzREUxQ0MzQTkzNDELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRIwEAYDVQQHEwlDdXBlcnRpbm8xEzARBgNVBAoTCkFwcGxlIEluYy4xDzANBgNVBAsTBmlQaG9uZTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAxWAKW4UZTWoQo8uymCa+rnmCGR1ImcGF6h4sFZce0h+sDvFRncttBQFyEYalYO/W1+iQ8CYqAssvgp52a3zyehDnkJ2pi0yALMmaCuvwHzS1ZWy91IhHbr6P6GF+NjS2pEuXXdcYwRxHFU1cHACRuPMUs4kRKLRzaVdjerO2XTMCAwEAAaOBlTCBkjAfBgNVHSMEGDAWgBSy/iEjRIaVannVgSaOcxDYp0yOdDAdBgNVHQ4EFgQUs0JDow7EDVVF/jkOUk3jRJHKPKkwDAYDVR0TAQH/BAIwADAOBgNVHQ8BAf8EBAMCBaAwIAYDVR0lAQH/BBYwFAYIKwYBBQUHAwEGCCsGAQUFBwMCMBAGCiqGSIb3Y2QGCgIEAgUAMA0GCSqGSIb3DQEBBQUAA4GBAKyCogu/zz2wrlwjFc8R/Fk6snHMxZlXcmDyxJWdjdkdAuaA1my0s8s+xvwPyHI83UjYSxtGSxZ/hx11gF1i1HP58WkGTUaU6l6h42yIp14VmPAZFhhp417AvU/HDR13Ru6mwBhP8MP55OhDZirX6MvYfmJ5rs97RPmxq2aiAk/XMIIDaTCCAlGgAwIBAgIBATANBgkqhkiG9w0BAQUFADB5MQswCQYDVQQGEwJVUzETMBEGA1UEChMKQXBwbGUgSW5jLjEmMCQGA1UECxMdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxLTArBgNVBAMTJEFwcGxlIGlQaG9uZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTAeFw0wNzA0MTYyMjU0NDZaFw0xNDA0MTYyMjU0NDZaMFoxCzAJBgNVBAYTAlVTMRMwEQYDVQQKEwpBcHBsZSBJbmMuMRUwEwYDVQQLEwxBcHBsZSBpUGhvbmUxHzAdBgNVBAMTFkFwcGxlIGlQaG9uZSBEZXZpY2UgQ0EwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAPGUSsnquloYYK3Lok1NTlQZaRdZB2bLl+hmmkdfRq5nerVKc1SxywT2vTa4DFU4ioSDMVJl+TPhl3ecK0wmsCU/6TKqewh0lOzBSzgdZ04IUpRai1mjXNeT9KD+VYW7TEaXXm6yd0UvZ1y8Cxi/WblshvcqdXbSGXH0KWO5JQuvAgMBAAGjgZ4wgZswDgYDVR0PAQH/BAQDAgGGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFLL+ISNEhpVqedWBJo5zENinTI50MB8GA1UdIwQYMBaAFOc0Ki4i3jlga7SUzneDYS8xoHw1MDgGA1UdHwQxMC8wLaAroCmGJ2h0dHA6Ly93d3cuYXBwbGUuY29tL2FwcGxlY2EvaXBob25lLmNybDANBgkqhkiG9w0BAQUFAAOCAQEAd13PZ3pMViukVHe9WUg8Hum+0I/0kHKvjhwVd/IMwGlXyU7DhUYWdja2X/zqj7W24Aq57dEKm3fqqxK5XCFVGY5HI0cRsdENyTP7lxSiiTRYj2mlPedheCn+k6T5y0U4Xr40FXwWb2nWqCF1AgIudhgvVbxlvqcxUm8Zz7yDeJ0JFovXQhyO5fLUHRLCQFssAbf8B4i8rYYsBUhYTspVJcxVpIIltkYpdIRSIARA49HNvKK4hzjzMS/OhKQpVKw+OCEZxptCVeN2pjbdt9uzi175oVo/u6B2ArKAW17u6XEHIdDMOe7cb33peVI6TD15W4MIpyQPbp8orlXe+tA8JDCCA/MwggLboAMCAQICARcwDQYJKoZIhvcNAQEFBQAwYjELMAkGA1UEBhMCVVMxEzARBgNVBAoTCkFwcGxlIEluYy4xJjAkBgNVBAsTHUFwcGxlIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MRYwFAYDVQQDEw1BcHBsZSBSb290IENBMB4XDTA3MDQxMjE3NDMyOFoXDTIyMDQxMjE3NDMyOFoweTELMAkGA1UEBhMCVVMxEzARBgNVBAoTCkFwcGxlIEluYy4xJjAkBgNVBAsTHUFwcGxlIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MS0wKwYDVQQDEyRBcHBsZSBpUGhvbmUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCjHr7wR8C0nhBbRqS4IbhPhiFwKEVgXBzDyApkY4j7/Gnu+FT86Vu3Bk4EL8NrM69ETOpLgAm0h/ZbtP1k3bNy4BOz/RfZvOeo7cKMYcIq+ezOpV7WaetkC40Ij7igUEYJ3Bnk5bCUbbv3mZjE6JtBTtTxZeMbUnrc6APZbh3aEFWGpClYSQzqR9cVNDP2wKBESnC+LLUqMDeMLhXr0eRslzhVVrE1K1jqRKMmhe7IZkrkz4nwPWOtKd6tulqz3KWjmqcJToAWNWWkhQ1jez5jitp9SkbsozkYNLnGKGUYvBNgnH9XrBTJie2htodoUraETrjIg+z5nhmrs8ELhsefAgMBAAGjgZwwgZkwDgYDVR0PAQH/BAQDAgGGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFOc0Ki4i3jlga7SUzneDYS8xoHw1MB8GA1UdIwQYMBaAFCvQaUeUdgn+9GuNLkCm90dNfwheMDYGA1UdHwQvMC0wK6ApoCeGJWh0dHA6Ly93d3cuYXBwbGUuY29tL2FwcGxlY2Evcm9vdC5jcmwwDQYJKoZIhvcNAQEFBQADggEBAB3R1XvddE7XF/yCLQyZm15CcvJp3NVrXg0Ma0s+exQl3rOU6KD6D4CJ8hc9AAKikZG+dFfcr5qfoQp9ML4AKswhWev9SaxudRnomnoD0Yb25/awDktJ+qO3QbrX0eNWoX2Dq5eu+FFKJsGFQhMmjQNUZhBeYIQFEjEra1TAoMhBvFQe51StEwDSSse7wYqvgQiO8EYKvyemvtzPOTqAcBkjMqNrZl2eTahHSbJ7RbVRM6d0ZwlOtmxvSPcsuTMFRGtFvnRLb7KGkbQ+JSglnrPCUYb8T+WvO6q7RCwBSeJ0szT6RO8UwhHyLRkaUYnTCEpBbFhW3ps64QVX5WLP0g8wggS7MIIDo6ADAgECAgECMA0GCSqGSIb3DQEBBQUAMGIxCzAJBgNVBAYTAlVTMRMwEQYDVQQKEwpBcHBsZSBJbmMuMSYwJAYDVQQLEx1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTEWMBQGA1UEAxMNQXBwbGUgUm9vdCBDQTAeFw0wNjA0MjUyMTQwMzZaFw0zNTAyMDkyMTQwMzZaMGIxCzAJBgNVBAYTAlVTMRMwEQYDVQQKEwpBcHBsZSBJbmMuMSYwJAYDVQQLEx1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTEWMBQGA1UEAxMNQXBwbGUgUm9vdCBDQTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAOSRqQkfkdseR1DrBe1eeYQt6zaiV0xV7IsZid75S2z1B6siMALoGD74UAnTf0GomPnRymacJGsR0KO75Bsqwx+VnnoMpEeLW9QWNzPLxA9NzhRp0ckZcvVdDtV/X5vyJQO6VY9NXQ3xZDUjFUsVWR2zlPf2nJ7PULrBWFBnjwi0IPfLrCwgb3C2PwEwjLdDzw+dPfMrSSgayP7OtbkO2V4c1ss9tTqt9A8OAJILsSEWLnTVPA3bYharo3GSR1NVwa8vQbP4++NwzeajTEV+H0xrUJZBicR0YgsQg0GHM4qBsTBY7FoEMoxos48d3mVz/2deZbxJ2HafMxRloXeUyS0CAwEAAaOCAXowggF2MA4GA1UdDwEB/wQEAwIBBjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBQr0GlHlHYJ/vRrjS5ApvdHTX8IXjAfBgNVHSMEGDAWgBQr0GlHlHYJ/vRrjS5ApvdHTX8IXjCCAREGA1UdIASCAQgwggEEMIIBAAYJKoZIhvdjZAUBMIHyMCoGCCsGAQUFBwIBFh5odHRwczovL3d3dy5hcHBsZS5jb20vYXBwbGVjYS8wgcMGCCsGAQUFBwICMIG2GoGzUmVsaWFuY2Ugb24gdGhpcyBjZXJ0aWZpY2F0ZSBieSBhbnkgcGFydHkgYXNzdW1lcyBhY2NlcHRhbmNlIG9mIHRoZSB0aGVuIGFwcGxpY2FibGUgc3RhbmRhcmQgdGVybXMgYW5kIGNvbmRpdGlvbnMgb2YgdXNlLCBjZXJ0aWZpY2F0ZSBwb2xpY3kgYW5kIGNlcnRpZmljYXRpb24gcHJhY3RpY2Ugc3RhdGVtZW50cy4wDQYJKoZIhvcNAQEFBQADggEBAFw2mUwteLftjJvc83eb8nbSdzBPwR+Fg4UbmT1HN/Kpm0COLNSxkBLYvvRzm+7SZA/LeU802KI++Xj/a8gH7H05g4tTINM4xLG/mk8Ka/8r/FmnBQl8F0BWER5007eLIztHo9VvJOLr0bdw3w9F4SfK8W147ee1Fxeo3H4iNcol1dkP1mvUoiQjEfehrI9zgWDGG1sJL5Ky+ERI8GA4nhX1PSZnIIozavcNgs/e66Mv+VNqW2TAYzN39zoHLFbr2g8hDtq6cxlPtdk2f8GHVdmnmbkyQvvY1XGefqFStxu9k0IkEirHDx22TZxeY8hLgBdQqorV2uT80AkHN7B1dSE=";

return $FairPlayCertChain;
}


   // Generate GSM wildcard ticket based on iPhone 5S template activation request
                                  
  function baseband_nomeid($BasebandMasterKeyHash, $BasebandChipID, $BasebandSerialNumber, $BuildVersion, $productType, $productVersion, $RegulatoryModelNumber,$activationRandomness){
      $ActivationInfoXML = '<?xml version="1.0" encoding="UTF-8"?>
      <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
      <plist version="1.0">
      <dict>
          <key>ActivationRequestInfo</key>
          <dict>
              <key>ActivationRandomness</key>
              <string>'.$activationRandomness.'</string>
              <key>ActivationState</key>
              <string>Unactivated</string>
              <key>FMiPAccountExists</key>
              <false/>
          </dict>
          <key>BasebandRequestInfo</key>
          <dict>
              <key>ActivationRequiresActivationTicket</key>
              <true/>
              <key>BasebandActivationTicketVersion</key>
              <string>V2</string>
              <key>BasebandChipID</key>
              <integer>'.$BasebandChipID.'</integer>
              <key>BasebandMasterKeyHash</key>
              <string>'.$BasebandMasterKeyHash.'</string>
              <key>BasebandSerialNumber</key>
              <data>
              '.$BasebandSerialNumber.'
              </data>
              <key>InternationalMobileEquipmentIdentity</key>
              <string>35882605574****</string>
              <key>SupportsPostponement</key>
              <true/>
              <key>kCTPostponementInfoPRIVersion</key>
              <string>0.1.167</string>
              <key>kCTPostponementInfoPRLName</key>
              <integer>0</integer>
              <key>kCTPostponementInfoServiceProvisioningState</key>
              <false/>
          </dict>
          <key>DeviceCertRequest</key>
          <data>
          TlVMTA==
          </data>
          <key>DeviceID</key>
          <dict>
              <key>SerialNumber</key>
              <string>F19M1NVZ****</string>
              <key>UniqueDeviceID</key>
              <string>59f52bd8bd9a03937035edffda7a83b748130ba9</string>
          </dict>
          <key>DeviceInfo</key>
          <dict>
              <key>BuildVersion</key>
              <string>'.$BuildVersion.'</string>
              <key>DeviceClass</key>
              <string>iPhone</string>
              <key>DeviceVariant</key>
              <string>A</string>
              <key>ModelNumber</key>
              <string>ME434</string>
              <key>OSType</key>
              <string>iPhone OS</string>
              <key>ProductType</key>
              <string>'.$productType.'</string>
              <key>ProductVersion</key>
              <string>'.$productVersion.'</string>
              <key>RegionCode</key>
              <string>DN</string>
              <key>RegionInfo</key>
              <string>DN/A</string>
              <key>RegulatoryModelNumber</key>
              <string>'.$RegulatoryModelNumber.'</string>
              <key>UniqueChipID</key>
              <integer>8616919305680</integer>
          </dict>
          <key>RegulatoryImages</key>
          <dict>
              <key>DeviceVariant</key>
              <string>A</string>
          </dict>
          <key>UIKCertification</key>
          <dict>
              <key>BluetoothAddress</key>
              <string>48:74:6e:b6:7b:40</string>
              <key>BoardId</key>
              <integer>2</integer>
              <key>ChipID</key>
              <integer>35168</integer>
              <key>EthernetMacAddress</key>
              <string>48:74:6e:b6:7b:41</string>
              <key>UIKCertification</key>
              <data>
              TlVMTA==
              </data>
              <key>WifiAddress</key>
              <string>48:74:6e:b6:7b:3f</string>
          </dict>
      </dict>
      </plist>

';

      $ActivationInfoXML64 = base64_encode($ActivationInfoXML);

      $private = provide_privk();
      
      $FairPlayCertChain = provide_fairplaycertchain();

      openssl_sign($ActivationInfoXML, $signature, $private, 'sha1WithRSAEncryption'); //sha1WithRSAEncryption
      $FairPlaySignature = base64_encode($signature);

      $data = '<?xml version="1.0" encoding="UTF-8"?>
      <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
      <plist version="1.0">
      <dict>
          <key>ActivationInfoComplete</key>
          <true/>
          <key>ActivationInfoXML</key>
          <data>'.$ActivationInfoXML64.'</data>
          <key>FairPlayCertChain</key>
          <data>'.$FairPlayCertChain.'</data>
          <key>FairPlaySignature</key>
          <data>'.$FairPlaySignature.'</data>
          <key>RKCertification</key>
          <data>
          TlVMTA==
          </data>
          <key>RKSignature</key>
          <data>
          TlVMTA==
          </data>
          <key>serverKP</key>
          <data>
          TlVMTA==
          </data>
          <key>signActRequest</key>
          <data>
          TlVMTA==
          </data>
      </dict>
      </plist>';
      $responseXML=albertrequest($data);
                    
      $WilcardTicket="";
      $encodedrequest = new DOMDocument;
      $encodedrequest->loadXML($responseXML);

      $accountToken=$encodedrequest->getElementsByTagName('data')->item(3)->nodeValue;

      $accountToken=base64_decode($accountToken);
      //echo $accountToken;
      if(strpos($accountToken, "WildcardTicket")!==false)
      {
          $accountToken=explode('"WildcardTicket" = ', $accountToken);
          $accountToken=explode(";", $accountToken[1]);
          $WilcardTicket=str_replace('"', '', $accountToken[0]);
      }
      else if(strpos($accountToken, "ActivationTicket")!==false)
      {
          $accountToken=explode('"ActivationTicket" = ', $accountToken);
          $accountToken=explode(";", $accountToken[1]);
          $WilcardTicket=str_replace('"', '', $accountToken[0]);
      }

      return $WilcardTicket;
  } //baseband function
