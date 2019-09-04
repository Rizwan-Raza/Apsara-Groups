<?php
header('Access-Control-Allow-Origin: *');
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['email'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);

        $sql = "SELECT `_uid` FROM `users` WHERE `email`='$email'";
        require 'db.inc.php';

        $conn = DB::getConnection();
        $result = $conn->query($sql);
        if ($result != false) {
            if ($result->num_rows == 1) {
                $_uid = $name = "";
                extract($result->fetch_assoc(), EXTR_IF_EXISTS);
                $url = $_SERVER['SERVER_NAME'] . "/reset";
                $hash = md5("*WAMP*" . $_uid . "*WAMP*");
                $url .= "?hash=$hash&uid=$_uid#reset";
                $subject = "Account Recovery for Apsara Groups Web Platform.";
                $body = '<!DOCTYPE html>
                <html>
                
                <head>
                    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
                </head>
                
                <body style="font-family: Arial, Helvetica, sans-serif;margin:0px;background-color: #ffffff;">
                    <table
                        style="background-color: #eeeeee;padding: 8px 16px;width: 100%;box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);">
                        <tr>
                            <td><img src="https://www.apsarafoods.in/images/apsara.jpg" height="50px" alt="Apsara Foods" /></td>
                            <td style="line-height: 50px;vertical-align: top; margin:0px; font-size: 32px; font-weight: 500;">Password
                                Reset for ' . $name . '</td>
                        </tr>
                    </table>
                    <table style="padding: 16px;width: 100%;font-weight: 500;" cellspacing="10">
                        <tr>
                            <td><a style="text-transform: initial;letter-spacing: .25px;font-weight: 500;border-radius: 4px;border: none;display: inline-block;height: 36px;line-height: 36px;padding: 0 16px;vertical-align: middle;-webkit-tap-highlight-color: transparent;-webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);background-color: #880e4f;color: #ffffff;font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif;text-decoration:none;font-size: 14px;"
                                    href="' . $url . '">Password
                                    Reset Link</a>
                            </td>
                        </tr>
                    </table>
                    <p style="padding:16px;">Click the above button, if it isn&apos;t clickable, click or copy this url <a href="' . $url . '">' . $url . '</a> and paste it to to
                        your browser address bar.</p>
                    <table style="background-color: #880e4f;padding: 8px 16px;width: 100%;color: #ffffff;">
                        <tr>
                            <td style="line-height: 50px;vertical-align: top; margin:0px; font-size: 24px; font-weight: 500;"><a
                                    href="https://www.apsarafoods.in/" style="color: #ffffff;text-decoration:none">Apsara Groups</a>
                            </td>
                            <td><a href="https://www.apsarafoods.in/about" style="color: #ffffff;text-decoration:none">About</a></td>
                            <td><a href="https://www.apsarafoods.in/contact" style="color: #ffffff;text-decoration:none">Contact</a>
                            </td>
                        </tr>
                    </table>
                </body>
                
                </html>';
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // $headers .= "Cc: info@apsarafoods.in" . "\r\n";
                $headers .= 'From: Support | ApsaraFoods <support@apsarafoods.in>' . "\r\n";

                if (mail($to, $subject, $body, $headers)) {
                    $data = array("message" => "Recovery mail sent successfully.", "status" => "success");
                } else {
                    $data = array("message" => "Email seems to be wrong, Try again.", "status" => "success");
                }
            } else {
                $data = array("message" => "User not registered, try to sign up", "status" => "user_error");
            }
        } else {
            $data = array("message" => "Something went wrong! Try again", "status" => "server_error");
        }
    } else {
        $data = array("message" => "Parameters missing", "status" => "server_error");
    }
}
echo json_encode($data);
return;
