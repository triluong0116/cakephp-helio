<?php if ($booking->booking_rooms): ?>
    <?php foreach ($booking->booking_rooms as $booking_room): ?>
        <fieldset class="booking-room-item">
            <legend>Hạng phòng</legend>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>Hạng phòng: <?= $booking_room->room->name ?></td>
                        <td>Số phòng: <?= $booking_room->num_room ?></td>
                    </tr>
                    <tr>
                        <td>Ngày đi: <?= $booking_room->start_date->format('d-m-Y') ?></td>
                        <td>Ngày về: <?= $booking_room->end_date->format('d-m-Y') ?></td>
                    </tr>
                    <tr>
                        <td>Số người lớn: <?= $booking_room->num_adult ?></td>
                        <td>Số trẻ em: <?= $booking_room->num_children ?></td>
                    </tr>
                    <?php $child_ages = json_decode($booking_room->child_ages, true); ?>
                    <?php if ($child_ages): ?>
                        <tr>
                            <td>Tuổi của các bé</td>
                            <td><?= implode(', ', $child_ages) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </fieldset>
    <?php endforeach; ?>
<?php endif ?>
    <table class="table">
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
        <?php if ($booking->information): ?>
            <tr>
                <td>Danh sách đoàn và ngày sinh trẻ em</td>
                <td><?= $booking->information ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>Yêu cầu thêm</td>
            <td><?= $booking->other ?></td>
        </tr>
    </table>
<?php if ($booking->booking_surcharges): ?>
    <h3>Phụ thu</h3>
    <div class="p15">
        <table class="table">
            <?php foreach ($booking->booking_surcharges as $surcharge): ?>
                <tr>
                    <td><?= \App\View\Helper\SystemHelper::getSurchargeName($surcharge->surcharge_type) ?></td>
                    <td><?= number_format($surcharge->price) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>
