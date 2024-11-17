<?php
require_once '../vendor/autoload.php';

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseUrl = 'https://api.infobip.com'; 
    $apiKey = '3038a262496533bc7a760133bcc22dba-74139d11-e1a8-4bf6-8bcb-452b84cd6057'; // Correct API key

    $message = trim($_POST['message']); 

    if (empty($message) || strlen($message) > 160) {
        die("Error: Message cannot be empty and must be 160 characters or less.");
    }

    $sender = 'ServiceSMS'; 
    $recipients = ['+639932810412', '+639106411147', '+639707084966'];

    try {
        $config = new Configuration($baseUrl, $apiKey);
        $smsApi = new SmsApi($config);

        $destinations = [];
        foreach ($recipients as $recipient) {
            $destinations[] = new SmsDestination($recipient);
        }

        $smsMessage = new SmsTextualMessage([
            'from' => $sender, 
            'text' => $message,
            'destinations' => $destinations,
        ]);

        $advancedRequest = new SmsAdvancedTextualRequest([
            'messages' => [$smsMessage],
        ]);

        $response = $smsApi->sendSmsMessage($advancedRequest);

        echo "SMS sent successfully! Check your delivery report in Infobip.";
    } catch (Exception $e) {
        echo "Error sending SMS: " . $e->getMessage();
    }
}
?>
