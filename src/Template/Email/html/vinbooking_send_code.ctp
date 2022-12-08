<?php
$partner = 'Khách sạn';
?>
<h3>K&iacute;nh gửi qu&yacute; kh&aacute;ch h&agrave;ng: <?= $booking->first_name . " " . $booking->surname ?> </h3>
<p><br/><strong> Mustgo.vn </strong> trân trọng cảm ơn quý khách đã lựa chọn
    <?php
    echo $booking->hotel->name;
    ?> Cho chuyến đi lần này<br/> <strong><em>Mustgo.vn</em></strong> tr&acirc;n
    trọng gửi đến qu&yacute; kh&aacute;ch h&agrave;ng xác nhận đặt phòng cho booking số:
    <strong><?= $booking->code ?></strong> như sau:
    <br/>
<p>- <strong>XÁC NHẬN ĐẶT PHÒNG:</strong> Quý khách vui lòng truy cập <a href="<?= \Cake\Routing\Router::url('/', true) . 'files/attachments/' . $booking->code . '_vinbooking_send_code.pdf' ?>" download="">"File đính kèm"</a></p>
<br>
<p>Trân trọng cảm ơn</p>
<br>
<?= $signature ?>
