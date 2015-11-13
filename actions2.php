<!DOCTYPE html>
<?php
require('helpers.php');
//include 'processKBA.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Time out response                     4002432198795432
//Successful transaction                4200123456719012
//pended transaction                    4628610683834808
//  <ShoppingCart xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></ShoppingCart>
//    'ChargeAccountNumber'            => 4002432198795432
//    PERSONAL ACCOUNT
//    'AccountName'              => BcI/eAaBXWvNozS3xMK5kw==
//    'Password'                 => WNkDUagtVq6w+37jmkcIzXjFGZ27pnp74GzCXcBi3OmH35zrXkNs6/lSaW2n1vx9
//    
//    VESTA ACCOUNT
//    'AccountName'              => c91yKKKHf+rCSzgwdeuD9g==
//    'Password'                 => HTp+CKx137DjQ/ojL+beveq0wWvrwJnsvxjuV/TMz8ue3kGyA5WIAGlkIGVl2J88
//    
//AccountName=>"pZ8H7jvp98+bR7vyskYLsA==“, 
//Password=>"lJKC1an4pEKHPMiko7lCI9au/8m4hasy3R0hqYElqIUZ19IdGYSaL96pKJyHIUci”
//Strip empty tags from array because sandbox runs validation on these even if they are empty
$_POST = array_filter($_POST);

if (isset($_POST["api_method"]) AND $_POST["api_method"] == "PendingTest") {

    // In order to pend a transaction and call ChallengeQuestionBegin, we need a valid webSessionId returned from the getSessionTags API call
    $webSessionID = '';
    $webSessionID = getSessionTags($_POST)['WebSessionID'];
    debug_to_console('Web session ID returned from GetSession Tags <br />', $webSessionID);

    $_POST['WebSessionID'] = $webSessionID;

    //Since this is an indemnified situation we need to switch the Credit Card Number out with a token
    //The call to getToken uses the Credit Card Number from the entry screen.
    //Then we swap out the value to ChargeAccountNumberToken before making the call to ChargeSale
    $newToken = '';
    $newToken = getToken($_POST);
    $_POST['ChargeAccountNumberToken'] = $newToken;

    debug_to_console('Parameters passed to Call ChargeSale with Token for Pended Credit Card:', $_POST);

    $result = callChargeSale($_POST);
    debug_to_console('What is the result of callChargeSale? ', $result);
    if ($result['PaymentStatus'] == 2) {
        // Store this in your database for further use
        debug_to_console('Payment Status = 2 begin challenge', $result);
        challengeQuestionBegin($_POST, $result);
    } else {
        debug_to_console('An ERROR HAPPENED IN CHALLENGE BEGIN:<br />', $result);
    }
} else {
    echo 'Im Sorry, Something has gone wrong';
}

function questionForm($origPostData, $test) {

    debug_to_console('Pass test  this to AnswerQuestion', $test);
    debug_to_console('This is orig post data', $origPostData);
    echo "Question: $test[Prompt]<br>";
    ?>

    <form id="knowledge_form" method="post" action="processKBA.php" > 
        <fieldset>            
            <div id="address1">
                <label class="optional" for="Address1"><?php echo $test["Answer1"]; ?></label> <input type="radio" id="Address1" name="AnswerNumber" value="1"/>
            </div>
            <div id="address2">
                <label class="optional" for="Address2"><?php echo $test["Answer2"]; ?></label> <input type="radio" id="Address2" name="AnswerNumber" value="2"/>
            </div>
            <div id="address3">
                <label class="optional" for="Address3"><?php echo $test["Answer3"]; ?></label> <input type="radio" id="Address3" name="AnswerNumber" value="3"/>
            </div>
            <div id="address4">
                <label class="optional" for="Address4"><?php echo $test["Answer4"]; ?></label> <input type="radio" id="Address4" name="AnswerNumber" value="4"/>
            </div>
            <div id="address5">
                <label class="optional" for="Address5"><?php echo $test["Answer5"]; ?></label> <input type="radio" id="Address5" name="AnswerNumber" value="5"/>
            </div>
            <div id="password" class="hidden" >
                <input type="hidden" id="Password" name="Password" value="<?php echo $origPostData["Password"]; ?>"/>
            </div>
            <div id="accountName" >
                <input type="hidden" id="AccountName" name="AccountName"  value="<?php echo $origPostData["AccountName"]; ?>"/>
            </div>
            <div id="type" class="hidden">
                <input type="hidden" id="Type" name="Type" value="<?php echo $test["Type"]; ?>"/>
            </div>
            <div id="paymentID" class="hidden">
                <input type="hidden" id="PaymentID" name="PaymentID" value="<?php echo $test["PaymentID"]; ?>"/>
            </div>

            <input id="Submit" type="submit" value="submit">
        </fieldset> 
    </form>
    <?PHP
}

function challengeQuestionBegin($origPostData, $responseData) {
    debug_to_console('Parameters passed to Challenge Question begin:', $responseData);
    debug_to_console('origPostdata passed to challengequestionbegin', $origPostData);

    $data = array(
        'PaymentID' => $responseData['PaymentID'],
        'AccountName' => ((isset($origPostData['AccountName'])) ? ($origPostData['AccountName']) : ('')),
        'Password' => ((isset($origPostData['Password'])) ? ($origPostData['Password']) : ('')),
        'DayOfBirth' => ((isset($origPostData['DayOfBirth'])) ? ($origPostData['DayOfBirth']) : ('')),
        'Last4SSN' => ((isset($origPostData['Last4SSN'])) ? ($origPostData['Last4SSN']) : ('')),
        'MonthOfBirth' => ((isset($origPostData['MonthOfBirth'])) ? ($origPostData['MonthOfBirth']) : ('')),
        'YearOfBirth' => ((isset($origPostData['YearOfBirth'])) ? ($origPostData['YearOfBirth']) : (''))
    );
    $data = array_filter($data);
    debug_to_console('These are parameters passed to ChallengeQuestionBegin', $data);
    $query = http_build_query($data);

    $url = 'https://paysafesandbox.ecustomersupport.com/GatewayProxy/Service/ChallengeQuestionBegin';

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $query
        )
    ));

    $result = array();
    parse_str(file_get_contents($url, false, $context), $result);

    $error = null;

    if ($result['ResponseCode'] == 0) {
        debug_to_console("Response from successful call to ChallengeQuestionBegin:", $result);
        if ($result['ActionCode'] == 3) {
            debug_to_console("Match Not found on first attempt, send more info", $result);
            debug_to_console("Match Not found original data", $origPostData);
            //Add Year of Birth before calling again to simulater
            $origPostData['YearOfBirth'] = 1900;
            debug_to_console("ActionCode 3, Match not found. Now pass Year 1900 for success found", $origPostData);
            challengeQuestionBegin($origPostData, $responseData);
        } elseif ($result['ActionCode'] == 2) {
            debug_to_console("Challenge Question begin customer not found on final attempt, quit", $result);
            
        } elseif ($result['ActionCode'] == 0) {
            debug_to_console("Challenge Question begin, customer was found on first attempy", $result);
            questionForm($origPostData, $result);
            return $result;
        }
        return $result;
    } else {
        $error = $result['ResponseText'];

        debug_to_console("Error on ChallengeQuestionBegin:", $error);
        return $error;
    }
}

function getSessionTags($Post_Data) {
    $url = 'https://paysafesandbox.ecustomersupport.com/GatewayProxy/Service/GetSessionTags';

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($Post_Data)
        )
    ));

    $result = array();

    parse_str(file_get_contents($url, true, $context), $result);

    $error = null;
    if ($result['ResponseCode'] == 0) {
        debug_to_console("Successfully called GetSessionTags:", $result);
        $fingerprintEndpoint = 'https://paysafesandbox.ecustomersupport.com/ThreatMetrixUIRedirector';
        $embedHtml = sprintf('<p style="background:url(%1$s/fp/clear.png?org_id=%2$s&session_id=%3$s&m=1);"></p> <img src="%1$s/fp/clear.png?org_id=%2$s&session_id=%3$s&m=2" /> <script type="text/javascript" src="%1$s/fp/check.js?org_id=%2$s&session_id=%3$s"></script> <object data="%1$s/fp/fp.swf?org_id=%2$s&session_id=%3$s" type="application/x-shockwave-flash" width="1" height="1"> <param value="%1$s/fp/fp.swf?org_id=%2$s&session_id=%3$s" name="movie" /> </object>'
                , $fingerprintEndpoint
                , $result['OrgID']
                , $result['WebSessionID']);

        echo $embedHtml;
    } else {
        debug_to_console('OOPS, an error in getSessionTags', $result['ResponseText']);
    }
    return $result;
}

function getToken($_DATA) {

    debug_to_console('Parameters Passed to getToken:', $_DATA);

    $query = http_build_query($_DATA);

    $url = 'https://paysafesandbox.ecustomersupport.com/GatewayProxy/Service/ChargeAccountToTemporaryToken';
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $query
        )
    ));

    $result = array();
    parse_str(file_get_contents($url, false, $context), $result);
    $error = null;
    if ($result['ResponseCode'] == 0) {

        $cardToken = $result['ChargeAccountNumberToken'];

        debug_to_console("SUCCESS getting token:", $cardToken);

        return $cardToken;
    } else {
        $error = $result['ResponseText'];

        debug_to_console("OOPS. getToken Error", $error);
    }
    return $cardToken;
}

function callChargeSale($_data) {
    $query = http_build_query($_data);

    $url = 'https://paysafesandbox.ecustomersupport.com/GatewayProxy/Service/ChargeSale';

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $query
        )
    ));

    $result = array();
    parse_str(file_get_contents($url, false, $context), $result);

    $error = null;

    if ($result['ResponseCode'] == 0) {

        debug_to_console("Successful call to ChargeSale:", $result);

        return $result;
    } else {
        $error = $result['ResponseText'];

        debug_to_console("OOPS, callChargeSaleError", $error);
        return $error;
    }
}

function debug_to_console($message, $data) {
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('PHP: $message " . json_encode($data) . "');</script>");
    } else {
        echo("<script>console.log('PHP: $message" . $data . "');</script>");
    }
}
