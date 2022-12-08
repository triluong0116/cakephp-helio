<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Ngày tạo Booking</th>
        <th scope="col">Đại lý</th>
        <th scope="col">Tên loại hình</th>
        <th scope="col">Gói đặt</th>
        <th scope="col">Loại Booking</th>
        <th scope="col">Mã Booking</th>
        <th scope="col">Khách hàng</th>
        <th scope="col">Địa phương</th>
        <th scope="col">Số phòng</th>
        <th scope="col">Số đêm</th>
        <th scope="col">In</th>
        <th scope="col">Out</th>
        <th scope="col">Giá gốc</th>
        <th scope="col">Giá bán Đại lý</th>
        <th scope="col">Doanh thu</th>
        <th scope="col">Đại lý Cọc</th>
        <th scope="col">Đại lý TT</th>
        <th scope="col">TT KS</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bookings as $key => $booking): ?>
        <?php $booking->days_attended = date_diff($booking->start_date, $booking->end_date) ?>
        <tr>
            <td><?= ++$key ?></td>
            <td><?= date_format($booking->created, "d/m/Y h:m:s") ?></td>
            <td><?= $booking->user->screen_name ?></td>
            <td>
                <?php
                if ($booking->type == HOTEL) {
                    echo "Khách sạn";
                }
                if ($booking->type == VOUCHER) {
                    echo "Voucher";
                }
                if ($booking->type == LANDTOUR) {
                    echo "Lantour";
                }
                if ($booking->type == HOMESTAY) {
                    echo "Homestay";
                }
                ?>
            </td>
            <td>
                <?php if ($booking['item_id']) : ?>
                    <?= $booking->has('home_stays') ? $booking->home_stays->name : '' ?>
                    <?= $booking->has('vouchers') ? $booking->vouchers->name : '' ?>
                    <?= $booking->has('hotels') ? $booking->hotels->name : '' ?>
                    <?= $booking->has('land_tours') ? $booking->land_tours->name : '' ?>
                <?php else: ?>
                    <?= $booking->object_name ?>
                <?php endif; ?>
            </td>
            <td>
                <?php
                if ($booking->creator_type == 0) {
                    echo "Booking thuộc hệ thống";
                } else {
                    echo "Booking do Sale tạo";
                }
                ?>
            </td>
            <td><?= $booking->code ?></td>
            <td><?= $booking->full_name ?></td>
            <td><?php
                if ($booking->type == HOTEL) {
                    echo $booking->hotels->location->name;
                }
                if ($booking->type == VOUCHER) {
                    echo $booking->vouchers->hotel->location->name;
                }
                if ($booking->type == LANDTOUR) {
                    echo $booking->land_tours->destination->name;
                }
                if ($booking->type == HOMESTAY) {
                    echo $booking->home_stays->location->name;
                }
                ?></td>
            <td>
                <?= $booking->amount ?>
            </td>
            <td>
                <?= date_diff($booking->start_date, $booking->end_date)->days ?>
            </td>
            <td>
                <?= date_format($booking->start_date, 'd-m-Y') ?>
            </td>
            <td>
                <?= date_format($booking->end_date, 'd-m-Y') ?>
            </td>
            <td>
                <?= number_format($booking->price - $booking->sale_revenue - $booking->revenue) ?>
            </td>
            <td>
                <?= $booking->sale_id == $booking->user_id ? number_format($booking->price) : number_format($booking->price - $booking->revenue) ?>
            </td>
            <td>
                <?= $booking->sale_id == $booking->user_id ? number_format($booking->revenue + $booking->sale_revenue) : number_format($booking->sale_revenue) ?>
            </td>
            <td>
                Cọc <?= $booking->customer_deposit / 1000000 ?>tr
            </td>
            <td>
                <?= $booking->agency_pay == 1 ? 'Rồi' : 'Chưa' ?>
            </td>
            <td>
                <?= $booking->pay_hotel == 1 ? 'Rồi' : 'Chưa' ?>
            </td>
            <td class="actions">
                <a type="button" class="btn btn-xs btn-primary"
                   href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'bookingView', $booking->id]) ?>">Xem</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
