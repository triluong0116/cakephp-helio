<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking[]|\Cake\Collection\CollectionInterface $bookings
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?= __('Bookings') ?></h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Mã Booking</th>
                    <th scope="col">Loại hình</th>
                    <th scope="col">Tên Loại hình</th>
                    <th scope="col">Trưởng đoàn</th>
                    <th scope="col">Số ĐT</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $key => $booking): ?>
                    <tr>
                        <td><?= $this->Number->format($key + 1) ?></td>
                        <td><?= $booking->code ?></td>
                        <td>
                            <?php
                            switch ($booking->type) {
                                case COMBO:
                                    echo 'Combo';
                                    break;
                                case VOUCHER:
                                    echo 'Voucher';
                                    break;
                                case LANDTOUR:
                                    echo 'Land Tour';
                                    break;
                                case HOTEL:
                                    echo 'Khách sạn';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?= $booking->has('combos') ? $booking->combos->name : '' ?>
                            <?= $booking->has('vouchers') ? $booking->vouchers->name : '' ?>
                            <?= $booking->has('hotels') ? $booking->hotels->name : '' ?>
                            <?= $booking->has('land_tours') ? $booking->land_tours->name : '' ?>
                        </td>
                        <td><?= h($booking->full_name) ?></td>
                        <td><?= h($booking->phone) ?></td>
                        <td>
                            <?php
                            switch ($booking->status) {
                                case 0:
                                    echo 'Đang chờ CTV thanh toán';
                                    break;
                                case 1:
                                    echo 'CTV đã thanh toán, xin gửi mail xác nhận';
                                    break;
                                case 2:
                                    echo 'Đang chờ Admin thanh toán CTV';
                                    break;
                                case 3:
                                    echo 'Hoàn thành';
                                    break;
                            }
                            ?>
                        </td>
                        <td><?= h($booking->created) ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-xs btn-success"
                                    onclick="sendEmail(this, <?= $booking->id ?>,<?= E_PAY_AGENCY ?>)"><i
                                        class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i>
                                Gửi mail thanh toán
                            </button>
                            <?php if ($booking->status == 1 || $booking->status == 2 || $booking->status == 3): ?>
                                <button type="button" class="btn btn-xs btn-success"
                                        onclick="sendEmail(this, <?= $booking->id ?>, <?= E_BOOK_HOTEL ?>)"><i
                                            class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                            class="fa fa-envelope"></i> Gửi mail đặt phòng KS
                                </button>
                                <button type="button" class="btn btn-xs btn-success"
                                        onclick="sendEmail(this, <?= $booking->id ?>, <?= E_BOOK_AGENCY ?>)"><i
                                            class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                            class="fa fa-envelope"></i> Gửi mail xác nhận đặt phòng
                                </button>
                            <?php endif; ?>
                            <a type="button" class="btn btn-xs btn-primary"
                               href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning"
                               href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>