<?php
$partner = '';
switch ($booking->type) {
    case HOTEL:
    case VOUCHER:
        $partner = 'khách sạn';
        break;
    case HOMESTAY:
    case LANDTOUR:
        $partner = 'đối tác';
}
?>
<h3>K&iacute;nh gửi qu&yacute; kh&aacute;ch h&agrave;ng: <?= $booking->full_name ?> </h3>
<p><br/><strong> Mustgo.vn </strong> trân trọng cảm ơn quý khách đã lựa chọn
    <?php
    if ($booking->vouchers) {
        echo $booking->vouchers->name;
    }
    if ($booking->home_stays) {
        echo $booking->home_stays->name;
    }
    if ($booking->hotels) {
        echo $booking->hotels->name;
    }
    if ($booking->land_tours) {
        echo $booking->land_tours->name;
    }
    ?> Cho chuyến đi lần này<br/> <strong><em>Mustgo.vn</em></strong> tr&acirc;n
    trọng gửi đến qu&yacute; kh&aacute;ch h&agrave;ng xác nhận đặt phòng và yêu cầu thanh toán cho giao dịch booking số:
    <strong><?= $booking->code ?></strong> như sau:
    <br/>
<p>- <strong>XÁC NHẬN ĐẶT PHÒNG:</strong> Quý khách vui lòng truy cập <a href="<?= \Cake\Routing\Router::url('/', true) . '/files/attachments/' . $booking->code . '_book_agency.pdf' ?>" download="">"File đính kèm"</a></p>
<p>- <strong>THÔNG TIN THANH TOÁN:</strong> Quý khách vui lòng xem thông tin thanh toán và tải hình ảnh thanh toán vào link sau <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $booking->code], true) ?>">"Link thanh toán"</a></p>
<br>
<p>Trân trọng cảm ơn</p>
<br>
<p style="color: orange">Quý khách sạn vui lòng xuất hóa đơn đỏ cho công ty theo thông tin phí dưới - hình thức thanh toán tiền mặt</p>
<br>
<p>Xin chân thành cảm ơn!</p>
<?= $signature ?>
