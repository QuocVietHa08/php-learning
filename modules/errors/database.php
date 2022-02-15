<?php
if (!defined('_INCODE')) die('Access Deined...');
?>

<div class="class" style="width: 600px; padding:20px 30px; text-align: center; margin: 0 auto">
    <h3>Lỗi liên quan đến csdl</h3>
    <hr />
    <p><?php echo $exception->getMessage(); ?></p>
    <p>File: <?php echo $exception->getFile(); ?></p>
    <p>Line: <?php echo $exception->getLine(); ?></p>

    </p>
</div>