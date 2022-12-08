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
                                <th scope="col">Ngày tạo</th>
                                <?php if ($this->request->session()->read('Auth.User.role_id') == 5): ?>
                                    <th scope="col">Sửa lần cuối</th>
                                <?php endif; ?>
                                <th scope="col">Mã Booking</th>
                                <th scope="col">Loại hình</th>
                                <th scope="col">Tên Loại hình</th>
                                <th scope="col">Trưởng đoàn</th>
                                <th scope="col">Số ĐT</th>
                                <?php if ($this->request->session()->read('Auth.User.role_id') == 5): ?>
                                    <th scope="col">Ngày đi</th>
                                <?php endif; ?>
                                <?php if ($this->request->session()->read('Auth.User.role_id') == 2): ?>
                                    <th scope="col">Check In</th>
                                    <th scope="col">Check Out</th>
                                <?php endif; ?>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($bookings as $key => $booking): ?>
                                <tr>
                                    <td><?= $this->Number->format($key + 1) ?></td>
                                    <td><?= h(date_format($booking->created, 'd-m-Y')) ?></td>
                                    <?php if ($this->request->session()->read('Auth.User.role_id') == 5): ?>
                                        <td><?= h(date_format($booking->modified, 'd-m-Y H:i:s')) ?></td>
                                    <?php endif; ?>
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
                                    <?php if ($this->request->session()->read('Auth.User.role_id') == 5): ?>
                                        <td><?= h(date_format($booking->start_date, 'd-m-Y')) ?></td>
                                    <?php endif; ?>
                                    <?php if ($this->request->session()->read('Auth.User.role_id') == 2): ?>
                                        <td><?= h(date_format($booking->start_date, 'd-m-Y')) ?></td>
                                        <td><?= h(date_format($booking->end_date, 'd-m-Y')) ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <?php
                                        if ($booking->booking_type == SYSTEM_BOOKING && $booking->type != LANDTOUR) {
                                            switch ($booking->status) {
                                                case 0:
                                                    echo '<h5 class="label label-primary">Đại lý mới đặt</h5>';
                                                    break;
                                                case 1:
                                                    echo '<h5 class="label label-default">Chờ KS mail XN, gửi mail XN và ĐNTT</h5>';
                                                    break;
                                                case 2:
                                                    if ($booking->mail_type == 0){
                                                        if($booking->payment && $booking->payment->images) {
                                                            echo '<h5 class="label label-default">ĐL đã TT, chờ KT xác nhận tiền nổi</h5>';
                                                        } else {
                                                            echo '<h5 class="label label-default">Đã gửi mail xác nhận và đề nghị TT, Chờ ĐL thanh toán</h5>';
                                                        }
                                                    } else {
                                                        echo '<h5 class="label label-warning">ĐL đã TT, chờ KT TT</h5>';
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
                                        } elseif ($booking->booking_type == SYSTEM_BOOKING && $booking->type == LANDTOUR) {
                                            $status = '';
                                            if ($booking->status == 0 && $booking->payment_method == MUSTGO_DEPOSIT) {
                                                if ($booking->mustgo_deposit > $booking->price) {
                                                    $status = '<h5 class="label label-primary">Đại lý mới đặt, thu hộ cao hơn giá trị đơn</h5>';
                                                }
                                                if ($booking->mustgo_deposit < $booking->price) {
                                                    $status = '<h5 class="label label-primary">Đại lý mới đặt, thu hộ thấp hơn giá trị đơn</h5>';
                                                }
                                            }
                                            if ($booking->status == 0 && $booking->payment_method == CUSTOMER_PAY) {
                                                if ($booking->agency_pay == 1) {
                                                    $status = '<h5 class="label label-primary">Khách lẻ mới đặt, đã thanh toán</h5>';
                                                }
                                                if ($booking->agency_pay == 0) {
                                                    $status = '<h5 class="label label-primary">Khách lẻ mới đặt, chưa thanh toán</h5>';
                                                }
                                            }
                                            if ($booking->status == 0 && $booking->payment_method == AGENCY_PAY) {
                                                if ($booking->agency_pay == 1) {
                                                    $status = '<h5 class="label label-primary">Đại lý mới đặt, đã thanh toán</h5>';
                                                }
                                                if ($booking->agency_pay == 0) {
                                                    $status = '<h5 class="label label-primary">Đại lý mới đặt, chưa thanh toán</h5>';
                                                }
                                            }
                                            if ($booking->status == 1) {
                                                if ($booking->agency_pay == 1) {
                                                    $status = '<h5 class="label label-primary">Đã gửi mail đặt tour, đã thanh toán</h5>';
                                                }
                                                if ($booking->agency_pay == 0) {
                                                    $status = '<h5 class="label label-primary">Đã gửi mail đặt tour, chưa thanh toán</h5>';
                                                }
                                            }
                                            if ($booking->status == 3 || $booking->status == 4) {
                                                $status = '<h5 class="label label-danger">Hoàn thành</h5>';
                                            }
                                            if ($booking->status == 5) {
                                                $status = '<h5 class="label label-danger">Đã hủy</h5>';
                                            }
                                            echo $status;
                                        } elseif ($booking->booking_type == ANOTHER_BOOKING) {
                                            echo '<h5 class="label label-danger">Hoàn thành</h5>';
                                        }
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <?php if ($booking->sale_id == 0): ?>
                                            <button type="button" class="btn btn-xs btn-warning"
                                                    onclick="getBooking(this,<?= $booking->id ?>)">
                                                    <i class="fa fa-spin fa-spinner d-none"></i>&nbsp;<i
                                                    class="fa fa-envelope"></i><b> LẤY BOOKING NÀY</b>
                                            </button>
                                        <?php else: ?>
                                            <?php if ($booking->status != 5): ?>
                                                <?php if ($booking->booking_type == SYSTEM_BOOKING): ?>
                                                    <?php if ($booking->status != -1 && $this->request->session()->read('Auth.User.role_id') == 2): ?>
                                                        <button type="button" class="btn btn-xs btn-success"
                                                                onclick="sendEmailV2(this, <?= $booking->id ?>, <?= E_BOOK_HOTEL ?>)">
                                                            <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i> Gửi mail đặt phòng KS
                                                        </button>
                                                        <?php if ($booking->status >= 1): ?>
                                                            <button type="button" class="btn btn-xs btn-success"
                                                                    onclick="sendEmailV2(this, <?= $booking->id ?>,<?= E_PAY_AGENCY ?>)">
                                                                <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i>
                                                                Gửi mail thanh toán và mail xác nhận đặt phòng
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php elseif ($this->request->session()->read('Auth.User.role_id') == 5 && $booking->status < 3): ?>
                                                        <button type="button" class="btn btn-xs btn-success"
                                                                onclick="sendEmailV2(this, <?= $booking->id ?>, <?= E_BOOK_HOTEL ?>)">
                                                            <i class="fa fa-spin fa-spinner hidden"></i>&nbsp;<i class="fa fa-envelope"></i> Gửi mail đặt Landtour và thanh toán
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <a type="button" class="btn btn-xs btn-primary"
                                               href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                            <?php if ($booking->status < 3): ?>
                                                <a type="button" class="btn btn-xs btn-warning"
                                                   href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
                                                <a type="button" class="btn btn-xs btn-warning"
                                                   href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'editV2', $booking->id]) ?>">Sửa V2</a>
                                                <?php
                                                echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $booking->id], ['confirm' => __('Bạn có chắc muốn xóa Đơn hàng: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']);
                                                ?>
                                                <?php if ($this->request->session()->read('Auth.User.role_id') == 5): ?>
                                                    <?php
                                                    echo $this->Form->postLink(__('Hủy'), ['action' => 'deny', $booking->id], ['confirm' => __('Bạn có chắc muốn hủy Đơn hàng: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']);
                                                    ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
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
