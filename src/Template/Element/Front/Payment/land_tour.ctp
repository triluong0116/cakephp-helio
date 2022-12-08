<table class="table">
    <tr>
        <td>Tour</td>
        <td><?= $booking->land_tours->name ?></td>
    </tr>
    <tr>
        <td>Ngày đi</td>
        <td><?= $booking->start_date->format('d-m-Y') ?></td>
    </tr>
    <?php if ($booking->booking_landtour): ?>
        <tr>
            <td>Số người lớn</td>
            <td><?= $booking->booking_landtour->num_adult ?></td>
        </tr>
        <tr>
            <td>Số trẻ em</td>
            <td><?= $booking->booking_landtour->num_children ?></td>
        </tr>
        <tr>
            <td>Số em bé</td>
            <td><?= $booking->booking_landtour->num_kid ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Họ và tên Trưởng đoàn</td>
        <td><?= $booking->full_name ?></td>
    </tr>
    <tr>
        <td>Số điện thoại</td>
        <td><?= $booking->phone ?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?= $booking->email ?></td>
    </tr>
    <tr>
        <td>Yêu cầu thêm</td>
        <td><?= $booking->other ?></td>
    </tr>
</table>
<?php if (count($booking->booking_landtour_accessories) > 0): ?>
    <h3>Các gói</h3>
    <div class="p15">
        <table class="table">
            <?php foreach ($booking->booking_landtour_accessories as $k => $accessory): ?>
                <tr>
                    <td style="width: 455.483px"><?= $accessory->land_tour_accessory->name ?></td>
                    <td><?= $booking->booking_landtour->num_adult + $booking->booking_landtour->num_children + $booking->booking_landtour->num_kid ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>
<h3>Phụ thu</h3>
<div class="p15">
    <table class="table">
        <tr>
            <td style="width: 455.483px">Phụ thu đưa đón</td>
            <td><?= number_format($booking->booking_landtour->drive_surchage) ?></td>
        </tr>
    </table>
</div>
