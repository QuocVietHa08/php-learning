<?php
if (!defined('_INCODE')) die('Access Deined...');
// login functionality
$data = [
    'pageTitle' => 'Đăng lại mật khẩu '
];
layout('header-login', $data);
if (isLogin()) {
    redirect('?module=users');
}

if (isPost()) {
    $body = getBody();

    if (!empty($body['email'])) {
        $email = $body['email'];
        $queryUser = firstRaw("select id from users where email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];

            $forgotToken = sha1(uniqid() . time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                echo $forgotToken;
                redirect('?module=auth&action=reset&token=' . $forgotToken);
            } else {
                setFalshData('msg', 'Loi he thong! Ban khong the su dung chuc nang nay');
                setFalshData('msg_type', 'danger');
            }
        } else {
            setFalshData('msg', 'Dia chi email khong ton tai');
            setFalshData('msg_type', 'danger');
        }
    } else {
        setFalshData('msg', 'Vui long nhap dia chi email');
        setFalshData('msg_type', 'danger');
    }

    redirect('?module=auth&action=forgot');
}




$msg = getFalshData('msg');
$msg_type = getFalshData('msg_type');

?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đăng lai mat khau </h3>
        <?php getMsg($msg, $msg_type) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Địa chỉ email" />
            </div>


            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr />
            <p><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p><a href="?module=auth&action=register">Đăng kí tài khoản</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
