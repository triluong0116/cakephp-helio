<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking[]|\Cake\Collection\CollectionInterface $bookings
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <div class="row">
                <h2 class="col-sm-12"><?= __('Bookings') ?></h2>
                <?= $this->element('Backend/search') ?>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Đại lý</th>
                                <th scope="col">Mã Booking</th>
                                <th scope="col">Tên Loại hình</th>
                                <th scope="col">Trưởng đoàn</th>
                                <th scope="col">Số ĐT</th>
                                <th scope="col">Ngày đi</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bookings as $key => $booking): ?>
                                <tr>
                                    <td><?= $this->Number->format($key + 1) ?></td>
                                    <td><?= h(date_format($booking->created, 'd-m-Y')) ?></td>
                                    <td><?= $booking->user_id == $booking->sale_id ? "Khách lẻ" : $booking->user->screen_name ?></td>
                                    <td><?= $booking->code ?></td>
                                    <td>
                                        <?= $booking->hotel->name ?>
                                    </td>
                                    <td><?= h($booking->first_name . " " . $booking->sur_name) ?></td>
                                    <td><?= h($booking->phone) ?></td>
                                    <td><?= h(date_format($booking->start_date, 'd-m-Y')) ?></td>
                                    <td>
                                        <?php
                                        if ($booking->status == 1) {
                                            if ($booking->vinpayment && $booking->vinpayment->images) {
                                                echo '<h5 class="label label-primary">Đại lý mới đặt, đã up UNC</h5>';
                                            }
                                            if ($booking->vinpayment && ($booking->vinpayment->type == PAYMENT_ONEPAY_QR || $booking->vinpayment->type == PAYMENT_ONEPAY_ATM || $booking->vinpayment->type == PAYMENT_ONEPAY_CREDIT)) {
                                                if ($booking->vinpayment->onepaystatus == 0) {
                                                    echo '<h5 class="label label-danger">Hoàn thành</h5>';
                                                } else {
                                                    echo '<h5 class="label label-primary">Đại lý mới đặt, thanh toán Onepay không thành công</h5>';
                                                }
                                            }
                                            if (!$booking->vinpayment || (!$booking->vinpayment->images && $booking->vinpayment->type == PAYMENT_TRANSFER)) {
                                                echo '<h5 class="label label-primary">Đại lý mới đặt, chưa up UNC</h5>';
                                            }
                                        }
                                        if ($booking->status == 2) {
                                            if ($booking->vinpayment->type == PAYMENT_TRANSFER){
                                                if ($booking->mail_type == 1) {
                                                    echo '<h5 class="label label-warning">ĐL đã thanh toán, chưa thanh toán KS</h5>';
                                                } elseif ($booking->mail_type == 2) {
                                                    echo '<h5 class="label label-warning">Công nợ ĐL, chưa thanh toán KS</h5>';
                                                }
                                            } else {
                                                echo '<h5 class="label label-warning">ĐL đã thanh toán, chưa thanh toán KS</h5>';
                                            }
                                        }
                                        if ($booking->status == 4) {
                                            echo '<h5 class="label label-danger">Hoàn thành</h5>';
                                        }
                                        if ($booking->status == 5) {
                                            echo '<h5 class="label label-danger">Đã hủy</h5>';
                                        }
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'viewBookingVin', $booking->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-success"
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBookingVinpearl', $booking->id]) ?>">Up ủy nhiệm chi thanh toán</a>
                                        <?php if ($booking->pay_hotel_type == 0): ?>
                                            <a type="button" class="btn btn-xs btn-warning btn-log" data-ctl="dashboards" data-role="accountant" data-title="7"
                                               data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>"
                                               href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBookingVinpearlDebt', $booking->id]) ?>">Công nợ khách sạn</a>
                                        <?php endif; ?>
                                        <?php if ($booking->pay_hotel_type == 1): ?>
                                            <button type="button" onclick="sendPaymentToVin(this, <?= $booking->id ?>)" class="btn btn-xs btn-success">
                                                <i class="fa fa-spin fa-spinner hidden"></i> Gửi mail thanh toán cho khách sạn
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
