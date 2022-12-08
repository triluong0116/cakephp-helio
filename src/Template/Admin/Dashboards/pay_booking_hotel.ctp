<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách booking phòng khách sạn</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_content table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ngày tạo Booking</th>
                                <th scope="col">Đại lý</th>
                                <th scope="col">Tên khách sạn</th>
                                <th scope="col">Loại Booking</th>
                                <th scope="col">Mã Booking</th>
                                <th scope="col">Mã xác nhận ks</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Số phòng</th>
                                <th scope="col">Số đêm</th>
                                <th scope="col">In</th>
                                <th scope="col">Out</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bookings as $key => $booking): ?>
                                <?php $booking->days_attended = date_diff($booking->start_date, $booking->end_date) ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= date_format($booking->created, "d/m/Y h:m:s") ?></td>
                                    <td><?= $booking->user ? $booking->user->screen_name : 'Booking chưa có sale chọn' ?></td>
                                    <td><?php
                                        if ($booking->type == HOTEL) {
                                            echo $booking->hotels->name;
                                        }
                                        ?></td>
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
                                    <td><?= $booking->hotel_code ?></td>
                                    <td><?= $booking->full_name ?></td>
                                    <td>
                                        <?php
                                        if ($booking->booking_type == SYSTEM_BOOKING) {
                                            switch ($booking->status) {
                                                case 0:
                                                    echo '<h5 class="label label-primary">Đại lý mới đặt</h5>';
                                                    break;
                                                case 1:
                                                    echo '<h5 class="label label-default">Chờ KS mail XN, gửi mail XN và ĐNTT</h5>';
                                                    break;
                                                case 2:
                                                    if($booking->payment && $booking->payment->images) {
                                                        echo '<h5 class="label label-default">ĐL đã TT, chờ KT xác nhận tiền nổi</h5>';
                                                    } else {
                                                        echo '<h5 class="label label-default">Đã gửi mail xác nhận và đề nghị TT, Chờ Đl thanh toán</h5>';
                                                    }
                                                    break;
                                                case 3:
                                                    $status = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? '<h5 class="label label-danger">Hoàn thành</h5>' : '<h5 class="label label-danger">Hoàn thành</h5>';
                                                    echo $status;
                                                    break;
                                                case 4:
                                                    echo '<h5 class="label label-danger">Hoàn thành</h5>';
                                                    break;
                                                case 5:
                                                    echo '<h5 class="label label-danger">Đã hủy</h5>';
                                                    break;
                                            }
                                        } elseif ($booking->booking_type == ANOTHER_BOOKING) {
                                            echo 'Hoàn thành';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $num_room = 0;
                                        foreach ($booking->booking_rooms as $booking_room) {
                                            $num_room += $booking_room->num_room;
                                        }
                                        echo $num_room;
                                        ?>
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
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
