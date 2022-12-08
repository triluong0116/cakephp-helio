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
                                <th scope="col">Giá NET</th>
                                <th scope="col">Doanh thu</th>
                                <th scope="col">Mustgo thu hộ</th>
                                <th scope="col">Đại lý TT</th>
                                <th scope="col">Xác nhận Đại lý CK</th>
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
                                        echo '<h5 class="label label-warning">Đại lý đã thanh toán, chờ kế toán thanh toán cho ks</h5>';
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
                                    <td>
                                        <?= $booking->agency_pay == 1 ? 'Rồi' : 'Chưa' ?>
                                    </td>
                                    <td>
                                        <?php
                                        switch ($booking->confirm_agency_pay) {
                                            case 0:
                                                echo "Chưa CK";
                                                break;
                                            case 1:
                                                echo "Đã đặt cọc";
                                                break;
                                            case 2:
                                                echo "Đã CK";
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-success"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBooking', $booking->id]) ?>">Ủy nhiệm chi thanh toán</a>
                                        <?php if ($booking->pay_hotel == 1): ?>
                                            <button type="button" class="btn btn-xs btn-success"
                                                    onclick="sendEmailV2(this, <?= $booking->id ?>, <?= E_PAY_OBJECT ?>)">
                                                <i
                                                    class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                                    class="fa fa-envelope"></i> Gửi mail ủy nhiệm chi thanh toán
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
