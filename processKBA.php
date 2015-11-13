<?php
include 'actions2.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//    print_r($_POST);

if (isset($_POST['AnswerNumber'])) {
    $url = 'https://paysafesandbox.ecustomersupport.com/GatewayProxy/Service/ChallengeQuestionAnswer';

    $payload = array(
        'AnswerNumber' => $_POST["AnswerNumber"],
        'AccountName' => $_POST["AccountName"],
        'PaymentID' => $_POST["PaymentID"],
        'Type' => $_POST["Type"],
        'Password' => $_POST["Password"],
    );

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($payload)
        )
    ));

    $result = array();

    parse_str(file_get_contents($url, true, $context), $result);

    debug_to_console1('What was the result?', $result);

    if ($result['ResponseCode'] == 0) {
        //Successful Call. Now we need to check ActionCode to see if we need to continue or PaymentStatus to end trancaction
        debug_to_console1('Successfully called ChallengeQuestionAnswer<br />', $result);
        if (isset($result['ActionCode']) AND $result['ActionCode'] == 0) {
            //Need to show new question
            questionForm($_POST, $result);
        }elseif(isset($result["PaymentID"]) AND $result["PaymentStatus"] == 10) {
            echo 'SUCCESS';
}elseif(isset($result["PaymentID"]) AND $result["PaymentStatus"] != 10) {
            echo 'FAILED';
}
    } else {
        debug_to_console1('Error in ChallengeQuestionAnswer<br />', $result);
    }
    return $result;
} else {
    echo ('Something went wrong processing answers');
}

function debug_to_console1($message, $data) {
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('PHP: $message " . json_encode($data) . "');</script>");
    } else {
        echo("<script>console.log('PHP: $message" . $data . "');</script>");
    }
}
