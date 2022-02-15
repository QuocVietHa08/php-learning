<?php
if (!defined('_INCODE')) die('Access Deined...');
// login functionality
$data = [
    'pageTitle' => 'Đăng ký hệ thống'
];
layout('header-login', $data);

if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Ho ten bat buoc phai nhap';
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['min'] = 'Ho ten phai > 5 ky tu';
        }
    }

    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'so dien thoai bat buoc phai nhap';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isPhone'] = 'So dien thoai khong hop le';
        }
    }

    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bat buoc phai nhap';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'email khong hop le';
        } else {
            $email = trim($body['email']);
            $sql = "select id from users where email = '.$email'";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'Dia chi email da ton tai';
            }
        }
    }

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

    if (empty($errors)) {
        // setFalshData('msg', 'Validate thanh cong');
        // setFalshData('msg_type', 'success');
        // redirect('?module=auth&action=register');
        $activeToken = sha1(uniqid() . time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' =>  password_hash($body['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'createAt' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFalshData('msg', 'Dang ki tai khoan thanh cong');
            setFalshData('msg_type', 'success');
            redirect('?module=auth&action=login');
        }
    } else {
        setFalshData('msg', ' Vui long kiem tra lai du lieu nhap vao');
        setFalshData('msg_type', 'danger');
        setFalshData('errors', $errors);
        setFalshData('old', $body);
        redirect('?module=auth&action=register');
    }
}

$msg = getFalshData('msg');
$msg_type = getFalshData('msg_type');
$errors = getFalshData('errors');
$old = getFalshData('old')
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đăng ký tài khoản </h3>

        <?php
        getMsg($msg, $msg_type)
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Họ tên</label>
                <input type="text" name="fullname" value="<?php echo old('fullname', $old) ?>" class="form-control" placeholder="Nhập họ tên" />
                <?php echo form_error('fullname', $errors, '<span class="text-danger"', '</span') ?>
            </div>

            <div class="form-group">
                <label for="">Điện thoại</label>
                <input type="text" name="phone" value="<?php echo old('phone', $old) ?>" class="form-control" placeholder="Nhập sđt" />
                <?php echo form_error('phone', $errors, '<span class="text-danger"', '</span') ?>
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" value="<?php echo old('email', $old) ?>" class="form-control" placeholder="Nhập địa chỉ email" />
                <?php echo form_error('email', $errors, '<span class="text-danger"', '</span') ?>
            </div>

            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" />
                <?php echo (!empty($errors['password'])) ?
                    '<span class="text-danger">' . reset($errors['password']) . '</span>' : null; ?>

            </div>

            <div class="form-group">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" />
                <?php echo (!empty($errors['confirm_password'])) ?
                    '<span class="text-danger">' . reset($errors['confirm_password']) . '</span>' : null; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng kí</button>
            <hr />
            <p><a href="?module=auth&action=login">Đăng nhập</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
