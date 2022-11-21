<?php /** @noinspection ALL */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_POST) {
    $recipient = "m_shevchenko@fanshaweonline.ca";
    $subject = 'Email from Mailer';
    $visitor_name         = "";
    $visitor_email        = "";
    $message      = "";
    $fail = array();

    if (isset($_POST['firstname']) && !empty($_POST['firstname'])) {
        //my validation
        if(preg_match ("/^[a-zA-z]*$/", $_POST['firstname'])){
            $visitor_name = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
        }else{
            array_push($fail, "First Name should only contain letters.");
        }
    }else{
        array_push($fail, "firstname field is empty");
    }
    if (isset($_POST['lastname']) && !empty($_POST['lastname'])) {
        //my validation
        if(preg_match ("/^[a-zA-z]*$/", $_POST['lastname'])){
            $visitor_name .= " " . filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
        }else{
            array_push($fail,"Last Name should only contain letters");
        }
    }else{
        array_push($fail, "lastname field is empty");
    }
    // my validation
    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        if (preg_match($pattern,$_POST['email'])){
            $email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['email']);
            $visitor_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        }else{
            array_push($fail,"Write valid email address");
        }

    }else{
        array_push($fail, "email field is empty");
    }

    if (isset($_POST['message']) && !empty($_POST['message'])) {
        $clean = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        $message = htmlspecialchars($clean);
    }else{
        array_push($fail, "message field is empty");
    }

    $headers = "From:" . strval($visitor_email) . "\r\n" .
    "Reply-To: professor@fanshaweonline.ca" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();
    
    if (count($fail)==0) {
        mail($recipient, $subject, $message, $headers);
        $results['message'] = sprintf('Thank you for contacting us, %s. You will get a reply within 24 hours', $visitor_name);
    } else {
        $results['fail'] = $fail;
        header('HTTP/1.1 488 You Did NOT fill out the form correctly');
        die(json_encode(["message" => $fail]));
    }
} else {
    $results['message'] = 'No submission';
}

echo json_encode($results);

?>