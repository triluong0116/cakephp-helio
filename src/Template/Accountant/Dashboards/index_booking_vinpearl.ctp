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
                                <th scope="col">Loại booking</th>
                                <th scope="col">Tên Loại hình</th>
                                <th scope="col">Trưởng đoàn</th>
                                <th scope="col">Số ĐT</th>
                                <th scope="col">Ngày đi</th>
                                <th scope="col">Trạng thái</th>
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
                                <tr>
                                    <td><?= $this->Number->format($key + 1) ?></td>
                                    <td><?= h(date_format($booking->created, 'd-m-Y')) ?></td>
                                    <td><?= $booking->user_id == $booking->sale_id ? "Khách lẻ" : $booking->user->screen_name ?></td>
                                    <td><?= $booking->code ?></td>
                                    <td>
                                        <?= $booking->creator_type == 1 ? "Booking sale tạo" : "Booking thuộc hệ thống" ?>
                                    </td>
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
                                    <td>
                                        <?= number_format($booking->price - $booking->sale_revenue - $booking->revenue) ?>
                                    </td>
                                    <td>
                                        <?php if ($booking->user_id == $booking->sale_id): ?>
                                            <?= number_format($booking->price - ($booking->agency_discount)) ?>
                                        <?php else: ?>
                                            <?= number_format($booking->price - ($booking->revenue + $booking->agency_discount)) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($booking->user_id == $booking->sale_id): ?>
                                            <?= number_format($booking->sale_revenue + $booking->revenue - ($booking->agency_discount)) ?>
                                        <?php else: ?>
                                            <?= number_format($booking->sale_revenue - $booking->agency_discount) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($booking->vinpayment) {
                                            if ($booking->vinpayment->type == PAYMENT_TRANSFER) {
                                                if ($booking->mail_type == 1) {
                                                    echo 'Đã thanh toán';
                                                }
                                            } else {
                                                echo 'Đã thanh toán';
                                            }
                                        }
                                        ?>
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
                                           href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'viewBookingVin', $booking->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'editPriceVin', $booking->id]) ?>">Sửa giá, UNC</a>
                                        <?php if ($booking->status == 3 || $booking->status == 4): ?>
                                            <a type="button" class="btn btn-xs btn-success"
                                               href="<?= $this->Url->build(['controller' => 'Dashboards', 'action' => 'updateBookingVinpearl', $booking->id]) ?>">Up UNC thanh toán khách sạn</a>
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
