<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Pending Test</title>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="styles/mystyle.css">
    </head>
    <body>
        <p>Select the API from the dropdown you wish to test.</p><br>

        <form id="form1" method="post" action="actions2.php" >       
            <select id="select" name="api_method" onChange="handleSelection(value)" >
                <option value="empty">Choose API Call</option>
                <option value="PendingTest">Pending Test</option>
            </select>

            <p><br>Enter the required parameters to make your api call.</p>
            <fieldset>
                <div id="container1" class="hidden">
                    <div id="accountName">
                        <label class="requiredred" for="AccountName">Account Name:</label> <input type="text" id="AccountName" name="AccountName" />
                    </div>
                    <div id="cardHolderAddressLine1">
                        <label class="requiredred" for="CardHolderAddressLine1">Address Line 1:</label> <input type="text" id="CardHolderAddressLine1" name="CardHolderAddressLine1" />
                    </div>
                    <div id="cardHolderAddressLine2">
                        <label class="optional" for="CardHolderAddressLine2">Address Line 2:</label> <input type="text" id="CardHolderAddressLine2" name="CardHolderAddressLine2" />
                    </div>
                    <div id="cardHolderCity">
                        <label class="requiredred" for="CardHolderCity">City:</label> <input type="text" id="CardHolderCity" name="CardHolderCity" />
                    </div>
                    <div id="cardHolderCountryCode">
                        <label class="requiredred" for="CardHolderCountryCode">Country Code:</label> <input type="text" id="CardHolderCountryCode" name="CardHolderCountryCode" />
                    </div>
                    <div id="cardHolderFirstName">
                        <label class="requiredred" for="CardHolderFirstName">First Name:</label> <input type="text" id="CardHolderFirstName" name="CardHolderFirstName" />
                    </div>
                    <div id="cardHolderLastName">
                        <label class="requiredred" for="CardHolderLastName">Last Name:</label> <input type="text" id="CardHolderLastName" name="CardHolderLastName" />
                    </div> 
                    <div id="cardHolderPostalCode">
                        <label class="requiredred" for="CardHolderPostalCode">Postal Code:</label> <input type="text" id="CardHolderPostalCode" name="CardHolderPostalCode" />
                    </div> 
                    <div id="cardHolderRegion">
                        <label class="requiredred" for="CardHolderRegion">Region:</label> <input type="text" id="CardHolderRegion" name="CardHolderRegion" />
                    </div>
                    <div id="chargeAccountNumberToken">
                        <label class="requiredred" for="ChargeAccountNumberToken">Charge Account Token:</label> <input type="text" id="ChargeAccountNumberToken" name="ChargeAccountNumberToken" />
                    </div>
                    <div id="chargeAccountNumber">
                        <label class="requiredred" for="ChargeAccountNumber">Credit Card Number:</label> <input type="text" id="ChargeAccountNumber" name="ChargeAccountNumber" />
                    </div>
                    <div id="chargeAmount">
                        <label class="requiredred" for="ChargeAmount">Charge Amount:</label> <input type="text" id="ChargeAmount" name="ChargeAmount" />
                    </div>
                    <div id="chargeCVN">
                        <label class="optional" for="ChargeCVN">Charge CVN:</label> <input type="number" id="ChargeCVN" name="ChargeCVN" />
                    </div>
                    <div id="chargeExpirationMMYY">
                        <label class="optional" for="ChargeExpirationMMYY">Card Expiration MMYY</label> <input type="text" id="ChargeExpirationMMYY" name="ChargeExpirationMMYY" />
                    </div>
                    <div id="chargeSource">
                        <label class="requiredred" for="ChargeSource">Charge Source (PPD, TEL, WEB)</label> <input type="text" id="ChargeSource" name="ChargeSource" />
                    </div>
                    <div id="createdByUser">
                        <label class="optional" for="CreatedByUser">Created by User:</label> <input type="text" id="CreatedByUser" name="CreatedByUser" />
                    </div>
                    <div id="dayOfBirth">
                        <label class="optional" for="DayOfBirth">Day of Birth:</label> <input type="text" id="DayOfBirth" name="DayOfBirth" />
                    </div>
                    <div id="isTempToken">
                        <label class="requiredred" for="IsTempToken">Is Temp Token</label> <input type="checkbox" id="IsTempToken" name="IsTempToken" value="true" />
                    </div>
                    <div id="monthOfBirth">
                        <label class="optional" for="MonthOfBirth">Month of Birth:</label> <input type="text" id="MonthOfBirth" name="MonthOfBirth" />
                    </div>
                    <div id="password">
                        <label class="requiredred" for="Password">Password:</label> <input type="text" id="Password" name="Password" />
                    </div>
                    <div id="paymentDescriptor">
                        <label class="optional" for="PaymentDescriptor">Payment Descriptor:</label> <input type="text" id="PaymentDescriptor" name="PaymentDescriptor" />
                    </div>
                    <div id="paymentID">
                        <label class="requiredred" for="PaymentID">Payment ID:</label> <input type="text" id="PaymentID" name="PaymentID" />
                    </div>
                    <div id="refundAmount">
                        <label class="requiredred" for="RefundAmount">Refund Amount:</label> <input type="text" id="RefundAmount" name="RefundAmount" />
                    </div>
                    <div id="riskInformation">
                        <label class="optional" for="RiskInformation">Risk Information Blob</label> <input type="text" id="RiskInformation" name="RiskInformation" />
                    </div>
                    <div id="storeCard">
                        <label class="optional" for="StoreCard">Store Card:</label> <input type="checkbox" id="StoreCard" name="StoreCard" value="true"/>
                    </div>
                    <div id="transactionID">
                        <label class="requiredred" for="TransactionID">Transaction ID:</label> <input type="text" id="TransactionID" name="TransactionID" />
                    </div>
                    <div id="webSessionID">
                        <label class="optional" for="WebSessionID">Web Session ID:</label> <input type="text" id="WebSessionID" name="WebSessionID" />
                    </div>
                    <div id="yearOfBirth">
                        <label class="optional" for="YearOfBirth">Year of Birth:</label> <input type="text" id="YearOfBirth" name="YearOfBirth" />
                    </div>
                </div>

                <input id="Submit" type="submit" value="submit">
            </fieldset>
        </form>

        <script type="text/javascript">

            function handleSelection(choice) {
                console.log('What was selected: ' + choice);
                if (choice === 'PendingTest')
                {
                    console.log('Inside Pending');
                    $(".hidden > *").css('display', 'none');
                    $("#accountName").show();
                    $("#cardHolderAddressLine1").show();
                    $("#cardHolderAddressLine2").show();
                    $("#cardHolderCity").show();
                    $("#cardHolderCountryCode").show();
                    $("#cardHolderFirstName").show();
                    $("#cardHolderLastName").show();
                    $("#cardHolderPostalCode").show();
                    $("#cardHolderRegion").show();
                    $("#chargeAccountNumber").show();
                    $("#chargeAmount").show();
                    $("#chargeCVN").show();
                    $("#chargeExpirationMMYY").show();
                    $("#chargeSale").show();
                    $("#chargeSource").show();
                    $("#dayOfBirth").show();
                    $("#isTempToken").show();
                    $("#monthOfBirth").show();
                    $("#password").show();
                    $("#paymentDescriptor").show();
                    $("#riskInformation").show();
                    $("#storeCard").show();
                    $("#transactionID").show();
                    $("#webSessionID").show();
                    $("#yearOfBirth").show();

                    document.getElementById('container1').style.display = "block";
                }
            }
        </script>
        <?php
        // put your code here
        ?>
    </body>
</html>
