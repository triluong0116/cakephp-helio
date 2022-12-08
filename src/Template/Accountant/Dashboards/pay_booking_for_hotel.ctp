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
                                        if ($booking->type == VOUCHER) {
                                            echo $booking->vouchers->hotel->name;
                                        }
                                        if ($booking->type == HOMESTAY) {
                                            echo $booking->home_stays->name;
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
                                        if ($booking->status == 3) {
                                            echo '<h5 class="label label-danger">Đã gửi mail thanh toán KS</h5>';
                                        } else {
                                            if ($booking->payment) {
                                                if ($booking->mail_type == 1) {
                                                    echo '<h5 class="label label-warning">ĐL đã thanh toán, chưa thanh toán KS</h5>';
                                                } elseif ($booking->mail_type == 2) {
                                                    echo '<h5 class="label label-warning">Công nợ ĐL, chưa thanh toán KS</h5>';
                                                }
                                            } else {
                                                echo '<h5 class="label label-warning">ĐL đã thanh toán, chưa thanh toán KS</h5>';
                                            }
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
                                        <a type="button" class="btn btn-xs btn-successbtn-log" data-ctl="dashboards" data-role="accountant" data-title="6"
                                           data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBooking', $booking->id]) ?>">Ủy nhiệm chi thanh toán</a>
                                        <?php if ($booking->pay_hotel_type == 0): ?>
                                            <a type="button" class="btn btn-xs btn-warning btn-log" data-ctl="dashboards" data-role="accountant" data-title="7"
                                               data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>"
                                               href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBookingHotelDebt', $booking->id]) ?>">Công nợ khách sạn</a>
                                        <?php endif; ?>
                                        <?php if  ($booking->pay_hotel_type == 1): ?>
                                            <button type="button" class="btn btn-xs btn-success btn-log" data-ctl="dashboards" data-role="accountant" data-title="8"
                                                    data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>"
                                                    onclick="sendEmailV2(this, <?= $booking->id ?>, <?= E_PAY_OBJECT ?>)">
                                                <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i> Gửi mail ủy nhiệm chi thanh toán
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger btn-log" data-ctl="dashboards" data-role="accountant" data-title="9"
                                                    data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>" onclick="changeStatusDone(this, <?= $booking->id ?>)">
                                                <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i> Hoàn thành
                                            </button>
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
