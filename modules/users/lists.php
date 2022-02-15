<?php
if (!defined('_INCODE')) die('Access Deined...');

$data = [
    'pageTitle' => 'Quan ly nguoi dung',
];

layout('header', $data);
// xu ly loc du lieu
$filter = '';
$statusSql = 0;
if(isGet()) {
    $body = getBody();
    // loc theo tu khoa
    if(!empty($body['status'])) {
        $status = $body['status'];
        $statusSql = $status == 2 ? 0 : $status;
        if(!empty($body['keyword'])) {
            $keyword = $body['keyword'];
            if(!empty($filter) &&  strpos($filter, 'where') >= 0) {
                $operator = 'and';
            }else {
                $operator = 'where';
            }
            $filter .= "$operator fullname like %$filter%";
        }
    }
    $filter .=  'where status ='.$statusSql;
    // loc theo keyword
    if(!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if(!empty($filter) &&  strpos($filter, 'where') >= 0) {
            $operator = 'and';
        }else {
            $operator = 'where';
        }

        $filter .= "$operator fullname like %$filter%";
    }
}

$allUser = getRows("select id from users $filter");
$perPage = 3;
//2 tinh so trang
$maxPage = ceil($allUser / $perPage);
//3 Xu ly so trang 
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
// tinh toan offset trong limit dua vao bien $page
$offset = ($page - 1) * $perPage;
$listAllUser = getRaw("select * from users $filter order by updateAt limit  $offset, $perPage");
?>
<div class="container">
    <hr />
    <h3>Quan ly nguoi dung </h3>
    <p><a href="#" class="btn btn-success ">Them nguoi dung</a></p>
    <form action="" method="get">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="0">Chọn trạng thái</option>
                        <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected':false; ?>>Kích hoạt</option>
                        <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected':false; ?>>Chưa kích hoạt</option>
                    </select>
                </div>
            </div>

            <div class="col">
                <input type="search" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm...">
            </div>
            <div class="col">
                <button class='btn btn-primary' type="submit">Tìm kiếm </button>
            </div>
        </div>
        <input type="hidden" name="module" value="users">
    </form>
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
        <tbody>
            <?php
            if (!empty($listAllUser)) {
                $count = 0;
                foreach ($listAllUser as $item) :
                    $count++;
            ?>
                    <tr>
                        <td><?php echo $count ?></td>
                        <td><?php echo $item['fullname'] ?></td>
                        <td><?php echo $item['email'] ?></td>
                        <td><?php echo $item['phone'] ?></td>
                        <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success">Kich hoat</button>' :
                                '<button class="btn btn-warning">Chua kich hoat</button>' ?></td>
                        <td>
                            <a href="#" class="btn btn-warning">
                                Sua
                            </a>
                        </td>
                        <td>
                            <a href="#" onclick="return confirm()" class="btn btn-danger">
                                Xoa
                            </a>
                        </td>
                    </tr>
            <?php endforeach;
            } else {
            } ?>

        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if ($page > 1) {
                $perPage = $page - 1;
                echo '<li class="page-item"><a class="page-link" 
                  href="' . _WEB_HOST_ROOT . '?module=users&page=' . $perPage . '">Trước</a></li>';
            }
            ?>
            <?php
            $begin = $page - 2;
            if ($begin < 1) {
                $begin = 1;
            }
            $end = $page + 2;
            if ($end > $maxPage) {
                $end = $maxPage;
            }
            for ($index = $begin; $index <= $end; $index++) { ?>
                <li class="page-item  <?php echo ($index == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo  _WEB_HOST_ROOT . '?module=users&page=' . $index ?>">
                        <?php echo $index ?>
                    </a>
                </li>
            <?php } ?>

            <?php
            if ($page < $maxPage) {
                $perviewPage = $page - 1;
                echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT . '?module=users&page=' . $perviewPage . '">Sau</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>
<?php
layout('footer');
