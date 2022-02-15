<?php
if (!defined('_INCODE')) die('Access Deined...');
layout('header-login');
echo '<div class="container text-center"><br />';
$token = getBody()['token'];
$msg = getFalshData('msg');
$msg_type = getFalshData('msg_type');
if (!empty($token)) {
    $tokenQuery = firstRaw("select id, fullname,email from users where forgotToken = '$token' ");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];

        if (isPost()) {
            $body = getBody();
            $errors = [];
            $password = $body['password'];
            $confirmPassword = $body['confirm_password'];


            if (empty(trim($body['password']))) {
                $errors['password']['required'] = 'Mat khau bat buoc phai nhap';
            } else {
                if (strlen(trim($body['password'])) < 4) {
                    $errors['password']['min'] = 'Mat khau khong duoc nho hon 4 ky tu';
                }
            }
            if (empty(trim($body['confirm_password']))) {
                $errors['confirm_password']['required'] = 'Mat khau bat buoc phai nhap';
            } else {
                if ($body['confirm_password'] != $body['password']) {
                    $errors['confirm_password']['unequal'] = 'Khong dung voi mat khau';
                }
            }
            redirect('?module=auth&action=reset&token=' . $token);
        }
?>
        <div class="row text-left">
            <div class="col-6" style="margin: 20px auto">
                <h3 class="text-center text-uppercase"> Dat lai mat khau </h3>
                <?php getMsg($msg, $msg_type) ?>
                <form action="" method="post">

                    <div class="form-group">
                        <label for="">Mật khẩu moi</label>
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu moi" />
                        <?php echo (!empty($errors['password'])) ?
                            '<span class="text-danger">' . reset($errors['password']) . '</span>' : null; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Nhap lai mật khẩu</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhap lai mật khẩu" />
                        <?php echo (!empty($errors['password'])) ?
                            '<span class="text-danger">' . reset($errors['password']) . '</span>' : null; ?>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $token ?>" />

                    <button type="submit" class="btn btn-primary btn-block">Xac Nhan</button>
                    <hr />
                    <p><a href="?module=auth&action=login">Dang nhap</a></p>
                    <p><a href="?module=auth&action=register">Đăng kí tài khoản</a></p>
                </form>
            </div>
        </div>
<?php
    }
} else {
    getMsg('Lien ket khong ton tai hoac da het han', 'danger');
}

echo '<div />';
layout('header-footer');
