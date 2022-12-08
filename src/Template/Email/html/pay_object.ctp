<p><strong>&nbsp;</strong></p>
<?php
$partner = '';
switch ($booking->type) {
    case HOTEL:
    case VOUCHER:
        $partner = 'khách sạn';
        break;
    case HOMESTAY:
        $partner = 'homestay';
    case LANDTOUR:
        $partner = 'đối tác';
}
?>
<h3>K&iacute;nh gửi qu&yacute; <?= $partner ?>:&nbsp;
    <?php
    if ($booking->hotels) {
        echo $booking->hotels->name;
    }
    if ($booking->vouchers) {
        echo $booking->vouchers->hotel->name;
    }
    if ($booking->home_stays) {
        echo $booking->home_stays->name;
    }
    if ($booking->land_tours) {
        echo $booking->land_tours->organizer;
    }
    ?>
</h3>
<?php
if ($booking->type != LANDTOUR) {
    $objName = "phòng";
} else {
    $objName = "landtour";
}
?>
<p><strong>Mustgo.vn</strong> cảm ơn quý <?= $partner ?> vì sự hợp tác trong thời gian qua. </p>
<p><strong>Mustgo.vn</strong> gửi thông tin Thanh toán <?= $objName ?> cho booking mã "<?= $booking->hotel_code ?>" của khách hàng <?= $booking->full_name ?> ngày checkin: <?= date_format($booking->start_date, 'd-m-Y') ?> ngày checkout: <?= date_format($booking->end_date, 'd-m-Y') ?></p>
<p>Vui lòng phản hồi giúp Mustgo nếu đã nhận được khoản thanh toán của booking này:</p>
<?php $paymentPhotos = json_decode($booking->payment->payment_photo) ?>
<?php foreach ($paymentPhotos as $k => $photo): ?>
    <img src="<?= $this->Url->build('/' . $photo, true) ?>" alt="">
<?php endforeach; ?>
<p>Ghi chú thanh toán: <?= $booking->note_for_hotel_payment ?></p>
<?= $signature ?>
