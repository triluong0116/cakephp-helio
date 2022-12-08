<table class="table">
    <tr>
        <td>Homestay</td>
        <td><?= $booking->home_stays->name?></td>
    </tr>
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
        <td>Ngày đi</td>
        <td><?= $booking->start_date->format('d-m-Y') ?></td>
    </tr>
    <tr>
        <td>Ngày về</td>
        <td><?= $booking->end_date->format('d-m-Y') ?></td>
    </tr>
    <?php if ($booking->information): ?>
        <tr>
            <td>Danh sách đoàn</td>
            <td><?= $booking->information ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Yêu cầu thêm</td>
        <td><?= $booking->other ?></td>
    </tr>
</table>
