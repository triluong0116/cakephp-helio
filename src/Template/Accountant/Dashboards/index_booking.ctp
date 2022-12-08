<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2 class="col-sm-12">Danh sách booking phòng khách sạn</h2>
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
                                <th scope="col">Tên loại hình</th>
                                <th scope="col">Gói đặt</th>
                                <th scope="col">Loại Booking</th>
                                <th scope="col">Mã Booking</th>
                                <th scope="col">Mã xác nhận ks</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Địa phương</th>
                                <th scope="col">Số phòng</th>
                                <th scope="col">Số đêm</th>
                                <th scope="col">In</th>
                                <th scope="col">Out</th>
                                <th scope="col">Giá gốc</th>
                                <th scope="col">Giá bán Đại lý</th>
                                <th scope="col">Doanh thu</th>
                                <th scope="col">ĐL TT</th>
                                <th scope="col">ĐL Công nợ</th>
                                <th scope="col">TT KS</th>
                                <th scope="col">Công nợ KS</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bookings as $key => $booking): ?>
                                <?php  $booking->days_attended = date_diff($booking->start_date, $booking->end_date)  ?>
                                <tr
                                    <td><?= $this->Number->format($key + 1) ?></td>
                                    <td><?= $this->Number->format($key + 1) ?></td>
                                    <td><?= date_format($booking->created, "d/m/Y H:i:s") ?></td>
                                    <td><?= $booking->user ? $booking->user->screen_name : 'Booking chưa có sale chọn' ?></td>
                                    <td><?php
                                        if ($booking->type == HOTEL) {
                                            echo $booking->hotels->name;
                                        }
                                        if ($booking->type == VOUCHER) {
                                            echo $booking->vouchers->hotel->name;
                                        }
                                        if ($booking->type == HOMESTAY) {
                                            echo $booking->home_stays->name;
                                        }
                                        if ($booking->type == LANDTOUR) {
                                            echo $booking->land_tours->name;
                                        }
                                        ?></td>
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
                                        <?php
                                        if ($booking->creator_type == 1) {
                                            echo "Booking do Sale đặt";
                                        } else {
                                            echo "Booking thuộc hệ thống";
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
                                                    if ($booking->mail_type == 0) {
                                                        if ($booking->payment && $booking->payment->images) {
                                                            echo '<h5 class="label label-default">ĐL đã TT, chờ KT xác nhận tiền nổi</h5>';
                                                        } else {
                                                            echo '<h5 class="label label-default">Đã gửi mail xác nhận và đề nghị TT, Chờ Đl thanh toán</h5>';
                                                        }
                                                    } else {
                                                        if ($booking->mail_type == 1) {
                                                            echo '<h5 class="label label-warning">ĐL đã thanh toán, chưa thanh toán KS</h5>';
                                                        } elseif ($booking->mail_type == 2) {
                                                            echo '<h5 class="label label-warning">Công nợ ĐL, chưa thanh toán KS</h5>';
                                                        }
                                                    }
                                                    break;
                                                case 3:
                                                    echo '<h5 class="label label-danger">Đã gửi mail thanh toán KS</h5>';
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
                                    <?php
                                    $total_price = 0;
                                    $totalSurchargePrice = 0;
                                    foreach ($booking->booking_surcharges as $surcharge) {
                                        $totalSurchargePrice += $surcharge->price;
                                    }
                                    $total_price = $booking->price +
                                        ($booking->adult_fee ? $booking->adult_fee : 0)
                                        + ($booking->children_fee ? $booking->children_fee : 0)
                                        + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                                        + ($booking->other_fee ? $booking->other_fee : 0)
                                        + $totalSurchargePrice;
                                    ?>
                                    <td><?= number_format($total_price - $booking->sale_revenue - $booking->revenue) ?></td>
                                    <td><?php
                                        if ($booking->sale_id != $booking->user_id) {
                                            echo number_format($total_price - $booking->revenue);
                                        } else {
                                            echo number_format($total_price);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($booking->sale_id != $booking->user_id) {
                                            echo number_format($booking->sale_revenue);
                                        } else {
                                            echo number_format($booking->sale_revenue + $booking->revenue);
                                        }
                                        ?></td>
                                    <td>
                                        <?php if ($booking->mail_type == 1): ?>
                                            Đã thanh toán
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($booking->mail_type == 2): ?>
                                            Công nợ
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($booking->pay_hotel_type == 1): ?>
                                            Đã thanh toán
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($booking->pay_hotel_type == 2): ?>
                                            Công nợ
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                        <?php if ($booking->status == 3 || $booking->status == 4): ?>
                                            <a type="button" class="btn btn-xs btn-success"
                                               href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBooking', $booking->id]) ?>">Ủy nhiệm chi thanh toán</a>
                                        <?php endif; ?>
                                        <?php if ($booking->status <= 2): ?>
                                            <?php
                                            echo $this->Form->postLink(__('Hủy'), ['controller' => 'Dashboards', 'action' => 'cancelBooking', $booking->id], ['confirm' => __('Bạn có chắc muốn hủy đơn hàng # {0}?', $booking->code), 'class' => 'btn btn-xs btn-danger']);
                                            ?>
                                        <?php endif; ?>
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
