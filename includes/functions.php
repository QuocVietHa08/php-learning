d<?php
    if (!defined('_INCODE')) die('Access Deined...');;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function layout($layoutName = 'header', $data = [])
    {
        if (file_exists(_WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php')) {
            require_once _WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php';
        }
    }

    function sendMail($to, $subject, $content)
    {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'quocvietha08@gmail.com';                     //SMTP username
            $mail->Password   = 'secret';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('quocvietha08@gmail.com', 'Edward Ha');
            $mail->addAddress($to);     //Add a recipient
            // $mail->addReplyTo($to);
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }
        return false;
    }

    function isGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        }
        return false;
    }

    function getBody()
    {
        $bodyArray = [];
        if (isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArray[$key] = filter_input(
                            INPUT_GET,
                            $key,
                            FILTER_SANITIZE_SPECIAL_CHARS,
                            FILTER_REQUIRE_ARRAY
                        );
                    } else {
                        $bodyArray[$key] = filter_input(
                            INPUT_GET,
                            $key,
                            FILTER_SANITIZE_SPECIAL_CHARS,
                        );
                    }
                }
            }
        }
        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArray[$key] = filter_input(
                            INPUT_POST,
                            $key,
                            FILTER_SANITIZE_SPECIAL_CHARS,
                            FILTER_REQUIRE_ARRAY
                        );
                    } else {
                        $bodyArray[$key] = filter_input(
                            INPUT_POST,
                            $key,
                            FILTER_SANITIZE_SPECIAL_CHARS,
                        );
                    }
                }
            }
        }

        return $bodyArray;
    }


    function isEmail($email)
    {
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $checkEmail;
    }

    function isNumberInt($number, $range = [])
    {
        if (!empty($range)) {
            $options = ['options' => $range];
            $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
        } else {
            $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
        }

        return $checkNumber;
    }

    function isNumberFloat($number, $range = [])
    {
        if (!empty($range)) {
            $options = ['options' => $range];
            $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
        } else {
            $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
        }

        return $checkNumber;
    }


    function isPhone($phone)
    {
        $checkFirstZero = false;
        if ($phone[0] == '0') {
            $checkFirstZero = true;
            $phone = substr($phone, 1);
        }
        return $phone;
        $checkNumberLast = false;
        if (isNumberInt($phone) && strlen($phone) > 5) {
            $checkNumberLast = true;
        }

        if ($checkFirstZero && $checkNumberLast) {
            return true;
        }
        return false;
    }

    function getMsg($msg, $type = 'success')
    {

        if (!empty($msg)) {
            echo '<div class="alert alert-' . $type . '">' . $msg . '</div>';
        }
    }


    function redirect($path = 'index.php')
    {
        header("Location: $path");
        exit;
    }

    function form_error($fieldName, $errors, $beforeHtml = '', $afterHtml = '')
    {
        return (!empty($errors[$fieldName])) ?
            $beforeHtml . reset($errors[$fieldName]) . $afterHtml  : null;
    }

    function old($fieldName, $oldData, $default = null)
    {
        return (!empty($oldData[$fieldName]) ? $oldData[$fieldName] : $default);
    }

    function isLogin()
    {
        $checkLogin = false;
        if (getSession('loginToken')) {
            $tokenLogin = getSession('loginToken');
            $query = firstRaw("select userId from login_token where token='$tokenLogin'");
            if (!empty($query)) {
                $checkLogin = true;
            } else {
                removeSession('loginToken');
            }
        }

        return $checkLogin;
    }
