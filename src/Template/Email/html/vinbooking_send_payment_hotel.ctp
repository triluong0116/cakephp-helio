<p><strong>&nbsp;</strong></p>
<h3>K&iacute;nh gửi qu&yacute; Khách sạn:&nbsp;<?= $booking->hotel->name ?></h3>
<p><strong>Mustgo.vn</strong> cảm ơn quý khách sạn vì sự hợp tác trong thời gian qua. </p>
<p><strong>Mustgo.vn</strong> gửi thông tin Thanh toán phòng cho booking mã "<?= $booking->reservation_id ?>" của khách hàng <?= $booking->first_name . " " . $booking->sur_name ?> ngày checkin: <?= date_format($booking->start_date, 'd-m-Y') ?> ngày checkout: <?= date_format($booking->end_date, 'd-m-Y') ?></p>
<p>Vui lòng phản hồi giúp Mustgo nếu đã nhận được khoản thanh toán của booking này:</p>
<?php $paymentPhotos = json_decode($booking->vinpayment->payment_photo) ?>
<?php foreach ($paymentPhotos as $k => $photo): ?>
    <img src="<?= $this->Url->build('/' . $photo, true) ?>" alt="">
<?php endforeach; ?>
<p>Ghi chú thanh toán: <?= $booking->note_for_hotel_payment ?></p>
<?= $signature ?>
