<?php
if (!defined('_INCODE')) die('Access Deined...');

$data = [
    'pageTitle' => 'Quan ly nguoi dung',
];

layout('header', $data);
?>
<div class="container">
    <hr />
    <h3>Quan ly nguoi dung </h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Ho ten</th>
                <th>Email</th>
                <th>Dien thoai</th>
                <th>Trang thai</th>
                <th width="5%">Sua</th>
                <th width="5%">Xoa</th>
            </tr>
        </thead>
    </table>
</div>
<?php
layout('footer');
