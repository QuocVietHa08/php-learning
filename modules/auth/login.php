<?php
if (!defined('_INCODE')) die('Access Deined...');
// login functionality
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', $data);
// if (isLogin()) {
//     redirect('?module=users');
// }



if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && !empty(trim($body['password']))) {
        $email = $body['email'];
        $password = $body['password'];

        $userQuery = firstRaw("select id, password from users where email = '$email'");

        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            print_r($userQuery);
            if (password_verify($password, $passwordHash)) {
                $tokenLogin = sha1(uniqid() . time());

                $dataToken = [
                    'userId' => $userId,
                    'token' => $tokenLogin,
                    'createAt' => date('Y-m-d H:i:s')
                ];

                $insertTokenStatus = insert('login_token', $dataToken);
                if ($insertTokenStatus) {
                    setSession('loginToken', $tokenLogin);
                    redirect('?module=users');
                } else {
                    setFalshData('msg', 'Loi he thong ban khong the dang nhap luc nay');
                    setFalshData('msg_type', 'danger');
                    // redirect('?module=auth&action=login');
                }
            } else {
                setFalshData('msg', 'Mat khau khong chinh xac');
                setFalshData('msg_type', 'danger');
                // redirect('?module=auth&action=login');
            }
        } else {
            setFalshData('msg', 'Email khong ton tai trong he thong');
            setFalshData('msg_type', 'danger');
            // redirect('?module=auth&action=login');
        }
    } else {
        setFalshData('msg', 'Vui long nhap mat khau va email');
        setFalshData('msg_type', 'danger');
        // redirect('?module=auth&action=login');
    }
    redirect('?module=auth&action=login');
}
$msg = getFalshData('msg');
$msg_type = getFalshData('msg_type');

?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống </h3>
        <?php getMsg($msg, $msg_type) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Địa chỉ email" />
            </div>

            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" />
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr />
            <p><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p><a href="?module=auth&action=register">Đăng kí tài khoản</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
