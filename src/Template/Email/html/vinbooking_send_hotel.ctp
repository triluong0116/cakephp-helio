<?php
$partner = "khách sạn";
$item = "khách sạn";
?>
<h3>Kính gửi quý khách sạn : <?= $booking->hotel->name ?></h3>
<p><strong>Mustgo.vn</strong> xin được gửi lời chào đến khách sạn. <strong>Mustgo</strong> gửi thông tin cập nhật booking mã <?= $booking->reservation_id ?> như sau:</p>
<p><strong>T&ecirc;n <?= $item ?>: <?= $booking->hotel->name ?></strong></p>

<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <tr>
        <td>
            <p><em>Mã Booking</em></p>
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
    </tbody>
</table>
<br>
<h2>Chi tiết Booking</h2>
<?php
foreach ($booking->vinhmsbooking_rooms as $room) {
    if (!isset($listRoom[$room->room_index])) {
        $listRoom[$room->room_index]['vinhms_name'] = $room['vinhms_name'];
        $listRoom[$room->room_index]['num_adult'] = $room['num_adult'];
        $listRoom[$room->room_index]['num_kid'] = $room['num_kid'];
        $listRoom[$room->room_index]['num_child'] = $room['num_child'];
        $listRoom[$room->room_index]['packages'][] = $room;
    } else {
        $listRoom[$room->room_index]['packages'][] = $room;
    }
}
$booking->vinhmsbooking_rooms = $listRoom;
?>
<?php foreach ($booking->vinhmsbooking_rooms as $key => $booking_room): ?>
    <b>Hạng Phòng thứ <?= $key + 1 ?>: <?= $booking_room['vinhms_name'] ?> </b>
    <?php  foreach ($booking_room['packages'] as $package): ?>
        <table style="margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
                <td><em>Tên Gói đặt</em></td>
                <?php
                $arrText = explode('-', $package->vinhms_package_name);
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
                <td><?= $package->vinhms_package_code ?></td>
            </tr>
            <tr>
                <td><em>Check In</em></td>
                <td><?= date('d-m-Y', strtotime($package->checkin)) ?></td>
            </tr>
            <tr>
                <td><em>Check Out</em></td>
                <td><?= date('d-m-Y', strtotime($package->checkout)) ?></td>
            </tr>
            <tr>
                <td><em>Số người lớn</em></td>
                <td><?= $booking_room['num_adult'] ?></td>
            </tr>
            <tr>
                <td><em>Số trẻ em</em></td>
                <td><?= $booking_room['num_child'] ?></td>
            </tr>
            <tr>
                <td><em>Số em bé</em></td>
                <td><?= $booking_room['num_kid'] ?></td>
            </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
    <br>
<?php endforeach; ?>
<h2>Danh sách đoàn</h2>
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
<?= $signature ?>
