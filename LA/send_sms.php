<?php
require_once '../vendor/autoload.php'; 

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseUrl = 'https://api.infobip.com'; 
    $apiKey = 'App 3038a262496533bc7a760133bcc22dba-74139d11-e1a8-4bf6-8bcb-452b84cd6057';

    $floodId = $_POST['flood_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $waterLevel = $_POST['water_level'];
    $message = $_POST['message'];

    $config = new Configuration($baseUrl, $apiKey);
    $smsApi = new SmsApi($config);

    $recipients = ['+639106411147']; 

    try {
        $destinations = array_map(function ($recipient) {
            return new SmsDestination($recipient);
        }, $recipients);

        $smsMessage = new SmsTextualMessage([
            'from' => 'FloodPing',
            'text' => $message,
            'destinations' => $destinations,
        ]);

        $advancedRequest = new SmsAdvancedTextualRequest([
            'messages' => [$smsMessage],
        ]);

        $response = $smsApi->sendSmsMessage($advancedRequest);

        echo "SMS sent successfully!";
    } catch (Exception $e) {
        echo "Error sending SMS: " . $e->getMessage();
    }
}
?>
