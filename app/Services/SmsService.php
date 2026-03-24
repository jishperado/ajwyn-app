<?php namespace App\Services;

class SmsService
{
     private function sms($message, $number)
    {
      
        // SMS PART START  

        $ver = "1";
        $mode = "1";
        $action = "push_sms";
        $type = "1";
        $route = "2";
     
       
      

        //API URL
        $url = "https://api.xpresssms.in/api/v2/SendSMS";


        //Prepare you post parameters
        $postData = array(
             'ApiKey' => 'DrkQL5cRKSNX9gHqpGL45TyCTsEorEiI8+A/SXa11qA=',
             'ClientId' => '0118340c-71f3-4ce1-9d36-70431a7c8077',
             'SenderId'=>"RUGOTM",
             'Message'=>$message,
             'MobileNumbers'=> '91'.$number,
            'principleEntityId' => '1601476168034563571',
            'templateId' => '1607100000000296822',
           
        );
        $ch = curl_init();

// Set the cURL options


$url .= '?' . http_build_query($postData);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL transfer
$response = curl_exec($ch);
     // dd($response);
// Check for errors
if ($response === false) {
  //  echo 'cURL error: ' . curl_error($ch);
}

// Close the cURL handle
curl_close($ch);

// Use the response
/*$response;

exit;
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
      $res = curl_exec($ch);
       $info = curl_getinfo($ch);
       print_r($res);
       exit;
        //Print error if any
        if (curl_errno($ch)) {
          
        }
       

        curl_close($ch);*/
       
    }
  public  function otp($otp, $number)
    {
        // Send OTP via WhatsApp
         $data = [
            "messaging_product" => "whatsapp",
            "to" => '91'.$number,
            "type" => "template",
            "template" => [
                "name" => "ajwyn_otp",
                "language" => ["code" => "en_US"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => $otp
                            ]

                              ]
                            ],
                             [
                "type" => "button",
                "sub_type" => "url",
                "index" => "0",
                "parameters" => [
                    [
                        "type" => "text",
                        "text" => $otp
                    ]
                ]
            ]
                ]
            ]
        ];

      $res=  $this->sendMessage(getenv('PHONE_NUMBER_ID'), getenv('WTTOKEN'), $data);

      // Also send OTP via SMS
      $this->smsOtp($otp, $number);
    }

    public function smsOtp($otp, $number)
    {
        $apiId = getenv('BULKSMS_API_ID') ?: 'APIHsmXKlJv147340';
        $apiPassword = getenv('BULKSMS_API_PASSWORD') ?: 'bSqpTUhO';
        $sender = getenv('BULKSMS_SENDER') ?: 'AAJWYN';
        $entityId = getenv('BULKSMS_ENTITY_ID') ?: '1705176544413781705';
        $templateId = getenv('BULKSMS_TEMPLATE_ID') ?: '189293';

        $message = "Your OTP to access your AJWYN account is $otp. Please do not share this OTP with anyone. This OTP is valid for 10 minutes.";

        $url = "https://www.bulksmsplans.com/api/verify";

        $params = [
            'api_id'       => $apiId,
            'api_password' => $apiPassword,
            'sms_type'     => 'OTP',
            'sms_encoding' => '1',
            'sender'       => $sender,
            'number'       => $number,
            'message'      => $message,
            'entity_id'    => $entityId,
            'template_id'  => $templateId,
        ];

        $url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        log_message('info', 'BulkSMS OTP Request - Number: ' . $number . ' | Response: ' . ($response ?: 'empty') . ' | Error: ' . ($error ?: 'none'));

        if ($error) {
            log_message('error', 'BulkSMS OTP cURL Error: ' . $error);
            return ['status' => 'error', 'message' => $error];
        }

        $result = json_decode($response, true);

        if (!$result || (isset($result['code']) && $result['code'] != 200)) {
            log_message('error', 'BulkSMS OTP Failed: ' . ($result['message'] ?? $response ?? 'Unknown error'));
        }

        return $result;
    }
    public  function order($number,$order,$name)
    {
       // $message = "Your RUGO app verification code is $otp. Thank you. RUGO";
        //ajwyn_confirmed
         $data = [
            "messaging_product" => "whatsapp",
            "to" => '91'.$number,
            "type" => "template",
            "template" => [
                "name" => "ajwyn_confirmed",
                "language" => ["code" => "en_US"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => $name
                            ],
                             [
                                "type" => "text",
                                "text" => $order
                            ]
            
        ]
                            ]
                            
                ]
            ]
        ];
       
      $res=  $this->sendMessage(getenv('PHONE_NUMBER_ID'), getenv('WTTOKEN'), $data);

      // Also send order confirmation via SMS
      $this->smsOrderConfirm($number, $order, $name);
    }

    public function smsOrderConfirm($number, $order, $name)
    {
        $apiId = getenv('BULKSMS_API_ID') ?: 'APIHsmXKlJv147340';
        $apiPassword = getenv('BULKSMS_API_PASSWORD') ?: 'bSqpTUhO';
        $sender = getenv('BULKSMS_SENDER') ?: 'AAJWYN';
        $entityId = getenv('BULKSMS_ENTITY_ID') ?: '1705176544413781705';
        $templateId = getenv('BULKSMS_ORDER_TEMPLATE_ID') ?: '189295';

        $message = "Order confirmed! Hi $name, Your order from AJWYN has been successfully placed and is being processed. Your order number is $order. https://www.ajwyn.site/";

        $url = "https://www.bulksmsplans.com/api/verify";

        $params = [
            'api_id'       => $apiId,
            'api_password' => $apiPassword,
            'sms_type'     => 'OTP',
            'sms_encoding' => '1',
            'sender'       => $sender,
            'number'       => $number,
            'message'      => $message,
            'entity_id'    => $entityId,
            'template_id'  => $templateId,
        ];

        $url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        log_message('info', 'BulkSMS Order Confirm - Number: ' . $number . ' | Order: ' . $order . ' | Response: ' . ($response ?: 'empty') . ' | Error: ' . ($error ?: 'none'));

        if ($error) {
            log_message('error', 'BulkSMS Order Confirm cURL Error: ' . $error);
            return ['status' => 'error', 'message' => $error];
        }

        $result = json_decode($response, true);

        if (!$result || (isset($result['code']) && $result['code'] != 200)) {
            log_message('error', 'BulkSMS Order Confirm Failed: ' . ($result['message'] ?? $response ?? 'Unknown error'));
        }

        return $result;
    }

     public function sendMessage($phoneNumberId,$apiUrl,$data)  {

       $url = "https://graph.facebook.com/v22.0/$phoneNumberId/messages";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer " . $apiUrl
            ],
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        log_message('info', 'WhatsApp API - To: ' . ($data['to'] ?? 'unknown') . ' | Response: ' . ($response ?: 'empty') . ' | Error: ' . ($error ?: 'none'));

        if ($error) {
            log_message('error', 'WhatsApp API cURL Error: ' . $error);
            return ['status' => 'error', 'message' => $error];
        }

        return json_decode($response, true);
    }
}
