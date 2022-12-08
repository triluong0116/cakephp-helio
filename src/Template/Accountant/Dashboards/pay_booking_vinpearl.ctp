<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking[]|\Cake\Collection\CollectionInterface $bookings
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2 class="col-sm-12"><?= __('Bookings') ?></h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
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
                                            if ($booking->vinpayment->type == PAYMENT_TRANSFER) {
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
                                        <?php if (!$booking->reservation_id && $booking->vinpayment && $booking->vinpayment->images && $booking->vinpayment->type == PAYMENT_TRANSFER): ?>
                                            <button type="button" class="btn btn-xs btn-warning" onclick="openModalAddCode(<?= $booking->id ?>)">
                                                <i class="fa fa-spin fa-spinner hidden"></i> Gẵn mã Vinpearl
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($booking->status != 4): ?>
                                            <?php if ($booking->vinpayment && ($booking->vinpayment->type == PAYMENT_TRANSFER)): ?>
                                                <button type="button" onclick="commitBookingVinpearl(this, <?= $booking->id ?>, 1)" class="btn btn-xs btn-success btn-log" data-ctl="dashboards" data-role="accountant" data-title="11"
                                                        data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">
                                                    <i class="fa fa-spin fa-spinner hidden"></i> Xác nhận tiền nổi, gửi booking, trả code
                                                </button>
                                                <button type="button" onclick="commitBookingVinpearl(this, <?= $booking->id ?>, 2)" class="btn btn-xs btn-danger btn-log" data-ctl="dashboards" data-role="accountant" data-title="12"
                                                        data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">
                                                    <i class="fa fa-spin fa-spinner hidden"></i> Công nợ đại lý, gửi booking, trả code
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                        echo $this->Form->postLink(__('Hủy'), ['action' => 'denyVin', $booking->id], ['confirm' => __('Bạn có chắc muốn hủy Đơn hàng: {0}?', $booking->code), 'class' => 'btn btn-xs btn-danger']);
                                        ?>
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
<div class="modal fade" id="modalVinCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="booking-code"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Code Vinpearl</label>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="">
                                        <input type="hidden" name="booking_id" value="">
                                        <input type="text" name="reservation_id" class="form-control" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-log" data-ctl="dashboards" data-role="accountant" data-title="10"
                        data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>" onclick="saveVinpearlCode()">Save changes</button>
            </div>
        </div>
    </div>
</div>
