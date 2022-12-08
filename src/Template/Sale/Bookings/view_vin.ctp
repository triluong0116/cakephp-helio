<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h('Thông tin Booking') ?></h3>
    <table class=" table table-responsive table-striped">
        <tr>
            <th width="20%" scope="row"><?= __('Created') ?></th>
            <td><?= h($booking->created) ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Đại lý') ?></th>
            <td>
                <?php
                if ($booking->user_id == $booking->sale_id) {
                    echo "Khách lẻ";
                } else {
                    echo $booking->user ? $booking->user->screen_name : "Khách lẻ";
                }
                ?>
            </td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Tên loại hình') ?></th>
            <td><?=
                $booking->hotel->name
                ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Mã booking') ?></th>
            <td><?= $booking->code ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Full Name') ?></th>
            <td><?= h($booking->sur_name . " " . $booking->first_name) ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('SĐT') ?></th>
            <td><?= h($booking->phone) ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Email') ?></th>
            <td><?= h($booking->email) ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Lưu ý') ?></th>
            <td><?= nl2br($booking->note) ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Địa phương') ?></th>
            <td><?php
                echo $booking->hotel->location->name;
                ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Số đêm') ?></th>
            <td><?= date_diff($booking->start_date, $booking->end_date)->days ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Check in') ?></th>
            <td><?= date_format($booking->start_date, 'd-m-Y') ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Check out') ?></th>
            <td><?= date_format($booking->end_date, 'd-m-Y') ?></td>
        </tr>
        <tr>
            <th width="20%" scope="row">
                <p><?= __('Danh sách đoàn và ngày sinh trẻ em') ?></p>
            </th>
            <td>
                <?php $listPeopleRoom = json_decode($booking->vin_information, true) ?>
                <?php if ($listPeopleRoom): ?>
                    <?php foreach ($listPeopleRoom as $kRoom => $room): ?>
                        <h3>Phòng <?= $kRoom + 1 ?></h3>
                        <?php foreach ($room as $member): ?>
                            <p>Họ tên: <?= $member['name'] . ", ngày sinh: " . $member['birthday'] ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?= __('Lưu ý') ?></th>
            <td><?= h($booking->note) ?></td>
        </tr>
    </table>
    <h4><?= h('Danh sách hạng phòng') ?></h4>
    <?php foreach ($booking->vinhmsbooking_rooms as $booking_room): ?>
        <em>Hạng Phòng: </em><b><?= $booking_room['vinhms_name'] ?></b>
        <table class="table table-responsive table-striped">
            <tbody>
            <tr>
                <th width="20%" scope="row">Số người lớn</th>
                <td><?= $booking_room['num_adult'] ?></td>
            </tr>
            <tr>
                <th width="20%" scope="row">Số trẻ em</th>
                <td><?= $booking_room['num_child'] ?></td>
            </tr>
            <tr>
                <th width="20%" scope="row">Số em bé</th>
                <td><?= $booking_room['num_kid'] ?></td>
            </tr>
            <tr>
                <th width="20%" scope="row">Gói đặt</th>
                <td>
                    <?php foreach ($booking_room['packages'] as $package): ?>
                    <p><b>Gói: <?= $package->vinhms_package_name ?></b></p>
                    <p><b>Mã: <?= $package->vinhms_package_code ?></b></p>
                    <p>Ngày đi: <?= date('d/m/Y', strtotime($package->checkin)) ?></p>
                    <p>Ngày về: <?= date('d/m/Y', strtotime($package->checkout)) ?></p>
                    <?php endforeach; ?>
                </td>
            </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
    <h4><?= h('Thông tin giá bán và thanh toán') ?></h4>
    <?php
    $price = $booking->price;
    ?>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Giá gốc trả khách sạn') ?></th>
            <td><?= number_format($price - $booking->revenue - $booking->sale_revenue) ?></td>
        </tr>
        <tr>
            <?php if ($booking->sale_id == $booking->user_id): ?>
                <th scope="row"><?= __('Giá bán Khách lẻ') ?></th>
                <td><?= number_format($price) ?></td>
            <?php else: ?>
                <th scope="row"><?= __('Giá bán Đại lý') ?></th>
                <td><?= number_format($price - $booking->revenue - $booking->agency_discount - $booking->sale_discount) ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><?= __('Doanh thu') ?></th>
            <td>
                <?php
                if ($booking->sale_id == $booking->user_id) {
                    echo number_format($booking->sale_revenue + $booking->revenue - $booking->agency_discount - $booking->sale_discount);
                } else {
                    echo number_format($booking->sale_revenue - $booking->agency_discount - $booking->sale_discount);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giảm giá đại lý/khách lẻ') ?></th>
            <td><?= number_format($booking->agency_discount) ?></td>
        </tr>
    </table>
    <h4><?= h('Thông tin thanh toán của Đại lý') ?></h4>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Lưu ý gửi Đại lý') ?></th>
            <td><?= h($booking->note_agency) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tình trạng Đại lý thanh toán') ?></th>
            <td>
                <?php
                if ($booking->vinpayment) {
                    if ($booking->vinpayment->type == PAYMENT_TRANSFER) {
                        if ($booking->mail_type == 1) {
                            echo 'Đã thanh toán(UNC)';
                        } elseif ($booking->mail_type == 2) {
                            echo "Công nợ";
                        } else {
                            echo "Chưa thanh toán";
                        }
                    } else {
                        if ($booking->vinpayment->onepaystatus == 0) {
                            echo "Đã thanh toán (OnePay)";
                        } else {
                            echo "Chưa thanh toán (OnePay)";
                        }
                    }
                } else {
                    echo "Chưa thanh toán";
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nội dung thanh toán của Đại lý/Khách') ?></th>
            <td>Thanh toán booking mã <?= $booking->code ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh chụp hóa đơn thanh toán của Đại lý hoặc khách lẻ') ?></th>
            <td>
                <?php
                $list_images = [];
                if ($payment) {
                    $list_images = json_decode($payment->images);
                }
                ?>
                <?php if ($list_images): ?>
                    <div class="row row-eq-height mt30">
                        <div class="col-sm-36 col-xs-36 ">
                            <div class="combo-slider">
                                <div class="box-image">
                                    <div class="imgs_gird grid_6_small">
                                        <div id="customer-pay-photo" class="lightgallery2">
                                            <?php

                                            $other = count($list_images) - 4;
                                            ?>
                                            <?php if ($list_images): ?>
                                                <?php foreach ($list_images as $key => $image): ?>
                                                    <?php
                                                    $class = '';

                                                    if ($key <= 3) {
                                                        $class = 'img item_' . $key;
                                                        $class .= ' medium-small';
                                                        if ($key == 3) {
                                                            $class .= ' end';
                                                        }
                                                    } else {
                                                        $class = 'hide';
                                                    }
                                                    ?>
                                                    <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                        <?php if ($key > 2): ?>
                                                            <span class="other-small">+<?= $other ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <h4><?= h('Thông tin thanh toán cho Khách sạn/Đối tác') ?></h4>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Thanh toán Khách sạn/Đối tác') ?></th>
            <td>
                <?php if ($booking->pay_hotel_type == 1): ?>
                    Đã thanh toán
                <?php elseif ($booking->pay_hotel_type == 2): ?>
                    Công nợ
                <?php else: ?>
                    Chưa thanh toán
                <?php endif; ?>
            </td>
        </tr>
        <?php if (!isset($payment) || (isset($payment) && ($payment->pay_object == PAY_HOTEL || $payment->pay_object == 0))): ?>
            <tr>
                <?php
                $infors = json_decode($booking->hotel->payment_information, true);
                $payment_information = '';
                if ($infors) {
                    foreach ($infors as $infor) {
                        $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                    }
                }
                ?>
                <th scope="row"><?= __('Thông tin thanh toán') ?></th>
                <td><?= $payment_information ?></td>
            </tr>
        <?php endif; ?>
        <?php if (isset($payment)): ?>
            <tr>
                <?php if ($payment->pay_object == PAY_HOTEL): ?>
                    <th scope="row"><?= __('Loại hóa đơn của Khách sạn') ?></th>
                    <td>
                        <?php
                        if (isset($payment)) {
                            echo $payment->check_type == NO_CHECK ? "Không có hóa đơn" : "Có hóa đơn";
                        }
                        ?>
                    </td>
                <?php elseif ($payment->pay_object == PAY_PARTNER): ?>
                    <th scope="row"><?= __('Thông tin đối tác') ?></th>
                    <td>
                        <?php
                        $partnerInformation = json_decode($payment->partner_information);
                        ?>
                        <p>Tên tài khoản: <?= $partnerInformation->name ?></p>
                        <p>Số tài khoản: <?= $partnerInformation->number ?></p>
                        <p>Chi nhánh ngân hàng: <?= $partnerInformation->bank ?></p>
                        <p>Email : <?= $partnerInformation->email ?></p>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('Nội dung thanh toán cho Khách sạn/Đối tác') ?></th>
            <td><?= $booking->reservation_id ?> <?= $this->System->stripVN($booking->first_name) ?> <?= $this->System->stripVN($booking->sur_name) ?> <?= date('dm', strtotime($booking->start_date)) ?> <?= date('dm', strtotime($booking->end_date)) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh chụp hóa đơn thanh toán với khách sạn hoặc đối tác') ?></th>
            <td>
                <?php
                $list_partner_images = [];
                if ($payment) {
                    $list_partner_images = json_decode($payment->payment_photo);
                }
                ?>
                <?php if ($list_partner_images): ?>
                    <div class="row row-eq-height mt30">
                        <div class="col-sm-36 col-xs-36 ">
                            <div class="combo-slider">
                                <div class="box-image">
                                    <div class="imgs_gird grid_6_small">
                                        <div id="partner-pay-photo" class="lightgallery2">
                                            <?php
                                            $other = count($list_partner_images) - 4;
                                            ?>
                                            <?php foreach ($list_partner_images as $key => $image): ?>
                                                <?php
                                                $class = '';

                                                if ($key <= 3) {
                                                    $class = 'img item_' . $key;
                                                    $class .= ' medium-small';
                                                    if ($key == 3) {
                                                        $class .= ' end';
                                                    }
                                                } else {
                                                    $class = 'hide';
                                                }
                                                ?>
                                                <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                    <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                    <?php if ($key > 3): ?>
                                                        <span class="other-small">+<?= $other ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ghi chú thanh toán') ?></th>
            <td><?= $booking->note_for_hotel_payment ?></td>
        </tr>
    </table>

    <div class="row">
        <h4><?= __('Other') ?></h4>
        <?php if ($booking->other): ?>
            <?= $this->Text->autoParagraph(h($booking->other)); ?>
        <?php else: ?>
            Không
        <?php endif; ?>
    </div>
</div>

<!-- booking Log -->
<div class="x_panel">
    <div class="x_title">
        <h2>Booking Logs</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php
        foreach ($bookingLogs as $bookingLog): ?>
            <div class="row">
                <div class="col-sm-1">
                    <p><?= date_format($bookingLog->created, "d/m/Y H:i:s") ?></p>
                </div>
                <div class="col-sm-1">
                    <p><?= $bookingLog->u['screen_name'] ?></p>
                </div>
                <div class="col-sm-1">
                    <?php
                    switch ($bookingLog->u['role_id']) {
                        case 1:
                            echo "Admin";
                            break;
                        case 2:
                            echo "Sale";
                            break;
                        case 3:
                            echo "CTV";
                            break;
                        case 7:
                            echo "Accountant";
                            break;
                    }
                    ?>
                </div>
                <div class="col-sm-1">
                    <p><?= $bookingLog->title ?></p>
                </div>
                <div class="col-sm-5">
                    <p><?= $bookingLog->comment ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="#" id="commentLog">
            <div class="row">
                <div class="col-sm-12">
                    <h4>comment</h4>
                </div>
                <div class="col-sm-12">
                    <textarea name="log-cmt" class="form-control" id="log-cmt" rows="5"></textarea>
                    <div class="clearfix h6"></div>
                </div>
                <div class="col-sm-12">
                    <a class="btn btn-success" onclick="saveCommentLog(this)" data-title="1" data-ctl="bookings" data-role="sale" data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">Gửi Comment</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end booking Log -->
