<?php
$partner = "khách sạn";
$item = "khách sạn";
?>
<h3>K&iacute;nh gửi qu&yacute; khách : <?= $booking->first_name . " " . $booking->sur_name ?></h3>
<p><strong>Mustgo.vn</strong> xin được gửi lời ch&agrave;o đến qu&yacute; kh&aacute;ch.Ch&uacute;ng t&ocirc;i xin được x&aacute;c
    nhận th&ocirc;ng tin đặt <?= $item ?> của qu&yacute; kh&aacute;ch như
    sau:</p>
<p><strong>T&ecirc;n <?= $item ?>: <?= $booking->hotel->name ?></strong></p>

<p><strong>Hướng dẫn vi&ecirc;n online: <?= isset($booking->user) ? $booking->user->screen_name : '' ?></strong></p>
<p><strong>Số điện thoại HDV: <?= isset($booking->user) ? $booking->user->phone : '' ?></strong></p>
<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <tr>
        <td>
            <p><strong><u></u></strong></p>
        </td>
        <td>
            <p><strong><u>Th&ocirc;ng tin kh&aacute;ch h&agrave;ng</u></strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Mã đặt hàng Vin</em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->reservation_id ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Tên trưởng đoàn</em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->first_name . " " . $booking->sur_name ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Số điện thoại trưởng đoàn</em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->phone ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Lưu ý</em></p>
        </td>
        <td>
            <p>&nbsp;<?= nl2br($booking->note) ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Check in</em></p>
        </td>
        <td>
            <p>&nbsp;<?= date_format($booking->start_date, "d-m-Y") ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Check out</em></p>
        </td>
        <td>
            <p>&nbsp;<?= date_format($booking->end_date, "d-m-Y") ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Tổng giá</em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->user_id == $booking->sale_id ? number_format($booking->price - $booking->sale_discount - $booking->agency_discount) : number_format(($booking->price - $booking->revenue - $booking->sale_discount - $booking->agency_discount)) ?>đ</p>
        </td>
    </tr>
    </tbody>
</table>
<br>
<h2>Chi tiết Booking</h2>
<?php foreach ($booking->vinhmsbooking_rooms as $key => $booking_room): ?>
    <b>Hạng Phòng thứ <?= $key + 1 ?> </b>
    <table style="margin-left: auto; margin-right: auto;" border="1">
        <tbody>
        <tr>
            <td><em>Tên Hạng phòng</em></td>
            <td><?= $booking_room->vinhms_name ?></td>
        </tr>
        <tr>
            <td><em>Tên Gói đặt</em></td>
            <?php
            $arrText = explode('-', $booking_room->vinhms_package_name);
            $packageName = '';
            foreach ($arrText as $kText => $text) {
                $text = trim($text);
                $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                $packageName .= $kText != count($arrText) - 1 ? " - " : '';
            }
            ?>
            <td><?= $packageName ?></td>
        </tr>
        <tr>
            <td><em>Mã gói</em></td>
            <td><?= $booking_room->vinhms_package_code ?></td>
        </tr>
        <tr>
            <td><em>Số người lớn</em></td>
            <td><?= $booking_room->num_adult ?></td>
        </tr>
        <tr>
            <td><em>Số trẻ em</em></td>
            <td><?= $booking_room->num_child ?></td>
        </tr>
        <tr>
            <td><em>Số em bé</em></td>
            <td><?= $booking_room->num_kid ?></td>
        </tr>
        </tbody>
    </table>
    <br>
<?php endforeach; ?>
<?php
$listPeople = json_decode($booking->vin_information, true);
?>
<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <?php foreach ($listPeople as $k => $singleRoom): ?>
        <tr>
            <td><em>Phòng <?= $k + 1 ?></em></td>
            <td>
                <?php foreach ($singleRoom as $person): ?>
                    <p><?= $person['name'] ?> - Ngày sinh: <?= $person['birthday'] ?></p>
                <?php endforeach; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p>Mustgo.vn trân trọng cảm ơn quý khách hàng đã sử dụng dịch vụ, hân hạnh được phục vụ quý khách trong các hành trình
    tiếp theo.</p>
