

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
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
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
                                            case HOMESTAY:
                                                echo 'Homestay';
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
                                        <?php if ($booking['item_id']) : ?>
                                            <?= $booking->has('home_stays') ? $booking->home_stays->name : '' ?>
                                            <?= $booking->has('vouchers') ? $booking->vouchers->name : '' ?>
                                            <?= $booking->has('hotels') ? $booking->hotels->name : '' ?>
                                            <?= $booking->has('land_tours') ? $booking->land_tours->name : '' ?>
                                        <?php else: ?>
                                            <?= $booking->object_name ?>
                                        <?php endif; ?>

                                    </td>
                                    <td><?= h($booking->full_name) ?></td>
                                    <td><?= h($booking->phone) ?></td>
                                    <td>
                                        <?php
                                        if ($booking->booking_type == SYSTEM_BOOKING) {
                                            switch ($booking->status) {
                                                case 0:
                                                    echo '<h5 class="label label-primary">Đại lý mới đặt</h5>';
                                                    break;
                                                case 1:
                                                    echo '<h5 class="label label-default">Chờ KS gửi mail xác nhận rồi gửi mail thanh toán và xác nhận đặt phòng</h5>';
                                                    break;
                                                case 2:
                                                    echo $booking->agency_pay == 1 ? '<h5 class="label label-warning">Đại lý đã thanh toán, chờ kế toán thanh toán cho ks</h5>' : '<h5 class="label label label-default">Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán</h5>';
                                                    break;
                                                case 3:
                                                    $status = '';
                                                    if ($booking->agency_pay == 1) {
                                                        $status = ($booking->payment_method == AGENCY_PAY || $booking->sale_id == $booking->user_id) ? '<h5 class="label label-danger">Hoàn thành</h5>' : '<h5 class="label label-warning">Hoàn thành</h5>';
                                                    } else {
                                                        $status = "<h5 class='label label-default'>Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán</h5>";
                                                    }
                                                    if($booking->pay_hotel == 0){
                                                        $status = '<h5 class="label label label-default">Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán</h5>';
                                                    }
                                                    echo $status;
                                                    break;
                                                case 4:
                                                    echo '<h5 class="label label-danger">Hoàn thành</h5>';
                                                    break;
                                            }
                                        } elseif ($booking->booking_type == ANOTHER_BOOKING) {
                                            echo 'Hoàn thành';
                                        }
                                        ?>
                                    </td>
                                    <td><?= h($booking->created) ?></td>
                                    <td class="actions">
                                        <?php if ($booking->sale_id == 0): ?>
                                            <button type="button" class="btn btn-xs btn-warning"
                                                    onclick="getBooking(this,<?= $booking->id ?>)"
                                                    class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                                    class="fa fa-envelope"></i><b> LẤY BOOKING NÀY</b>
                                            </button>
                                        <?php else: ?>
                                            <?php if ($booking->booking_type == SYSTEM_BOOKING): ?>
                                                <button type="button" class="btn btn-xs btn-success"
                                                        onclick="sendEmailV2(this, <?= $booking->id ?>, <?= E_BOOK_HOTEL ?>)">
                                                    <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                                        class="fa fa-envelope"></i> Gửi mail đặt phòng KS
                                                </button>
                                                <?php if ($booking->status != 0): ?>
                                                    <button type="button" class="btn btn-xs btn-success"
                                                            onclick="sendEmailV2(this, <?= $booking->id ?>,<?= E_PAY_AGENCY ?>)">
                                                        <i
                                                            class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i
                                                            class="fa fa-envelope"></i>
                                                        Gửi mail thanh toán và mail xác nhận đặt phòng
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <a type="button" class="btn btn-xs btn-primary"
                                               href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                            <?php if ($booking->status < 4): ?>
                                                <a type="button" class="btn btn-xs btn-warning"
                                                   href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
                                            <?php endif; ?>
                                            <?php
                                            echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $booking->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']);
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

