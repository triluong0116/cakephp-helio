<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel[]|\Cake\Collection\CollectionInterface $hotels
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách booking Landtour</h2>
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
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Địa phương</th>
                                <th scope="col">Ngày đi</th>
                                <th scope="col">Giá Net</th>
                                <th scope="col">Doanh thu</th>
                                <th scope="col">Mustgo thu hộ</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bookings as $key => $booking): ?>
                                <?php $booking->days_attended = date_diff($booking->start_date, $booking->end_date) ?>
                                <tr>
                                    <td><?= ++$key ?></td>
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
                                        if ($booking->creator_type == 0) {
                                            echo "Booking thuộc hệ thống";
                                        } else {
                                            echo "Booking do Sale tạo";
                                        }
                                        ?>
                                    </td>
                                    <td><?= $booking->code ?></td>
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
                                                    echo $booking->agency_pay == 1 ? '<h5 class="label label-warning">ĐL đã TT, chờ KT TT</h5>' : '<h5 class="label label label-default">Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán</h5>';
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
                                        <?= date_format($booking->start_date, 'd-m-Y') ?>
                                    </td>
                                    <td><?= number_format($booking->price) ?></td>
                                    <td>
                                        <?= $booking->sale_id == $booking->user_id ? $booking->sale_revenue + $booking->revenue : $booking->sale_revenue ?>
                                    </td>
                                    <td><?= number_format($booking->payment_method == MUSTGO_DEPOSIT ? $booking->mustgo_deposit : 0) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                        <?php if ($booking->booking_type == SYSTEM_BOOKING && $booking->sale_id != $booking->user_id): ?>
                                            <?php if ($booking->status == 3 && $booking->payment_method == 0): ?>

                                            <?php elseif (($booking->status == 3 && $booking->payment_method == 1) || ($booking->status == 4)): ?>

                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if ($booking->status >= 3): ?>

                                            <?php endif; ?>
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
