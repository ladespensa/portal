<?php
require_once "config.php";

$tittle = 'Polar App';
$bosy = 'Quedate en casa';
$id = 'eFJWpGb9SY648__JCoym-r:APA91bGiF8tLo_Tu9rzHy3vtn8rMaPnR_1XBi77xtiabkxuDqLm_SXhlksIYH2Bm8V8ospjyUMCJQc3HKrtz7Y12qXIuFeLOH4wSTx3GaPq5QCePOBz39HqO43bGxikPbnsoDJnsY2Pb';

sendFCM($tittle,$body,$id);


function sendFCM($tittle,$body,$id) {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array (
            'to' => $id,
            'notification' => array (
                    "body" => $body,
                    "title" => $tittle,
                    "icon" => "myicon"
            )
    );
    $fields = json_encode ( $fields );
    $headers = array (
            'Authorization: key='.KEY_FIREBASE,
            'Content-Type: application/json'
    );
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    }

?>