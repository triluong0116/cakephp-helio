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
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($booking->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đại lý') ?></th>
            <td><?= h($booking->user->screen_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tên loại hình') ?></th>
            <td><?php
                if ($booking->item_id != 0) {
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
                } else {
                    echo $booking->object_name;
                }
                ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gói đặt') ?></th>
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
                ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mã booking') ?></th>
            <td><?= $booking->code ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Full Name') ?></th>
            <td><?= h($booking->full_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('SĐT') ?></th>
            <td><?= h($booking->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($booking->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày đi') ?></th>
            <td><?= h(date_format($booking->start_date, 'd-m-Y')) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Địa phương') ?></th>
            <td><?php
                if ($booking->item_id != 0) {
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
                }
                ?></td>
        </tr>
        <?php if ($booking->type != LANDTOUR): ?>
            <?php if (empty($booking->booking_rooms)) : ?>
                <tr>
                    <th scope="row"><?= __('Số phòng') ?></th>
                    <td><?= $booking->amount ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Số đêm') ?></th>
                    <td><?= date_diff($booking->start_date, $booking->end_date)->days ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('in') ?></th>
                    <td><?= date_format($booking->start_date, 'd-m-Y') ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('out') ?></th>
                    <td><?= date_format($booking->end_date, 'd-m-Y') ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Hạng phòng') ?></th>
                    <td><?= h($booking->room_level) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Số lượng người') ?></th>
                    <td><?= h($booking->people_amount) ?></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($booking->type == LANDTOUR) : ?>
            <tr>
                <th scope="row">Số người lớn</th>
                <td><?= $booking->booking_landtour->num_adult ?></td>
            </tr>
            <tr>
                <th scope="row">Số trẻ em</th>
                <td><?= $booking->booking_landtour->num_children ?></td>
            </tr>
            <tr>
                <th scope="row">Số em bé</th>
                <td><?= $booking->booking_landtour->num_kid ?></td>
            </tr>
            <tr>
                <th scope="row">Loại Landtour</th>
                <td>
                    <?php foreach ($booking->booking_landtour_accessories as $accessory): ?>
                        <p><?= $accessory->land_tour_accessory->name ?></p>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <th scope="row">Địa điểm đón</th>
                <td><?= $booking->booking_landtour->pick_up->name ?> - Chi tiết: <?= $booking->booking_landtour->detail_pickup ?></td>
            </tr>
            <tr>
                <th scope="row">Địa điểm trả</th>
                <td><?= $booking->booking_landtour->drop_down->name ?> - Chi tiết: <?= $booking->booking_landtour->detail_drop ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($booking->hotels && $booking->hotels->is_special): ?>
            <tr>
                <th scope="row">
                    <p><?= __('Danh sách đoàn và ngày sinh trẻ em') ?></p>
                </th>
                <td><p class="textarea-line-break"><?= $booking->information ?></p></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('Lưu ý') ?></th>
            <td><?= h($booking->note) ?></td>
        </tr>
    </table>
    <?php if (!empty($booking->booking_rooms)): ?>
        <h4><?= h('Danh sách hạng phòng') ?></h4>
        <?php foreach ($booking->booking_rooms as $booking_room): ?>
            <em>Hạng Phòng: </em><b><?= $booking_room->room->name ?></b>
            <table class="table table-responsive table-striped">
                <tbody>
                <tr>
                    <th scope="row">Check In</th>
                    <td><?= date_format($booking_room->start_date, 'd-m-Y') ?></td>
                </tr>
                <tr>
                    <th scope="row">Check Out</th>
                    <td><?= date_format($booking_room->end_date, 'd-m-Y') ?></td>
                </tr>
                <tr>
                    <th scope="row">Số phòng</th>
                    <td><?= $booking_room->num_room ?></td>
                </tr>
                <tr>
                    <th scope="row">Số người lớn</th>
                    <td><?= $booking_room->num_adult ?></td>
                </tr>
                <tr>
                    <th scope="row">Số trẻ em</th>
                    <td><?= $booking_room->num_children ?></td>
                </tr>
                </tbody>
                <?php if (isset($booking_room->child_ages)) : ?>
                    <?php
                    $child_ages = json_decode($booking_room->child_ages, true);
                    ?>
                    <?php if ($child_ages && !empty($child_ages)) : ?>
                        <tr>
                            <th rowspan="<?= count($child_ages) + 1 ?>">Tuổi các bé</th>
                            <td>Bao gồm:</td>
                        </tr>
                        <?php foreach ($child_ages as $key => $child_age): ?>
                            <tr>
                                <td><?= $child_age ?> tuổi</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($booking->booking_surcharges)): ?>
        <h4>Chi tiết Phụ thu</h4>
        <table class="table table-responsive table-striped">
            <tbody>
            <?php foreach ($booking->booking_surcharges as $surcharge): ?>
                <tr>
                    <th><?= \App\View\Helper\SystemHelper::getSurchargeName($surcharge->surcharge_type) ?>
                    </th>
                    <td><?= number_format($surcharge->price) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <h4><?= h('Thông tin giá bán và thanh toán') ?></h4>
    <?php
    $price = $booking->price;
    ?>
    <?php
    switch ($booking->type) {
        case HOTEL:
            $objName = "khách sạn";
            break;
        case HOMESTAY:
            $objName = "homestay";
            break;
        case LANDTOUR:
            $objName = "landtour";
            break;
        case VOUCHER:
            $objName = "khách sạn";
            break;
    }
    ?>
    <table class=" table table-responsive table-striped">
        <?php if ($booking->type != LANDTOUR): ?>
            <?php
            $totalSurchargePrice = 0;
            foreach ($booking->booking_surcharges as $surcharge) {
                $totalSurchargePrice += $surcharge->price;
            }
            ?>
            <tr>
                <th scope="row"><?= __('Tổng số tiền booking khách hoặc đại lý thanh toán') ?></th>
                <?php if ($booking->type != LANDTOUR): ?>
                    <td><?php
                        if ($booking->sale_id != $booking->user_id) {
                            echo number_format($booking->price + $totalSurchargePrice - $booking->revenue);
                        } else {
                            echo number_format($booking->price + $totalSurchargePrice);
                        }
                        ?>
                    </td>
                <?php else: ?>
                    <td><?= number_format($booking->price
                            + ($booking->adult_fee ? $booking->adult_fee : 0)
                            + ($booking->children_fee ? $booking->children_fee : 0)
                            + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                            + ($booking->other_fee ? $booking->other_fee : 0)) ?>
                    </td>
                <?php endif; ?>
            </tr
            <tr>
                <th scope="row"><?= __('Tổng số tiền phải thanh toán cho ' . $objName) ?></th>
                <td>
                    <?php if ($booking->sale_id != $booking->user_id): ?>
                        <?= number_format($booking->price
                            + ($booking->adult_fee ? $booking->adult_fee : 0)
                            + ($booking->children_fee ? $booking->children_fee : 0)
                            + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                            + ($booking->other_fee ? $booking->other_fee : 0)
                            + $totalSurchargePrice
                            - $booking->revenue
                            - $booking->sale_revenue
                        ) ?>
                    <?php else: ?>
                        <?= number_format($booking->price
                            + ($booking->adult_fee ? $booking->adult_fee : 0)
                            + ($booking->children_fee ? $booking->children_fee : 0)
                            + ($booking->holiday_fee ? $booking->holiday_fee : 0)
                            + ($booking->other_fee ? $booking->other_fee : 0)
                            + $totalSurchargePrice
                            - $booking->revenue
                            - $booking->sale_revenue
                        ) ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?= __('Doanh thu') ?></th>
                <td>
                    <?php
                    if (($booking->sale_id == $booking->user_id)) {
                        echo number_format($booking->sale_revenue + $booking->revenue);
                    } else {
                        echo number_format($booking->sale_revenue);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <?php if ($booking->type != LANDTOUR): ?>
                    <th scope="row"><?= __('Tăng lợi nhuận Sale') ?></th>
                <?php else: ?>
                    <th scope="row"><?= __('Tăng giảm giá NET') ?></th>
                <?php endif; ?>
                <td><?= number_format($booking->sale_discount) ?></td>
            </tr>
            <tr>
                <?php if ($booking->type != LANDTOUR): ?>
                    <th scope="row"><?= __('Giảm giá cho Đại lý') ?></th>
                <?php else: ?>
                    <th scope="row"><?= __('Tăng giảm chiết khấu đại lý') ?></th>
                <?php endif; ?>
                <td><?= number_format($booking->agency_discount) ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <th scope="row"><?= __('Giá Net Đại lý') ?></th>
                <td><?= number_format($booking->price) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Phương thức thanh toán') ?></th>
                <td>
                    <?= $booking->payment_method == AGENCY_PAY ? "Đại lý thanh toán cho Mustgo" : "" ?>
                    <?= $booking->payment_method == MUSTGO_DEPOSIT ? "Mustgo thu hộ" : "" ?>
                </td>
            </tr>
            <?php if ($booking->payment_method == MUSTGO_DEPOSIT): ?>
                <tr>
                    <th scope="row"><?= __('Số tiền thu hộ') ?></th>
                    <td>
                        <?= number_format($booking->mustgo_deposit) ?>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <th scope="row"><?= __('Phụ thu đưa đón') ?></th>
                <td><?= number_format($booking->booking_landtour->drive_surchage) ?></td>
            </tr>
            <tr>
                <?php if ($booking->type != LANDTOUR): ?>
                    <th scope="row"><?= __('Tăng lợi nhuận Sale') ?></th>
                <?php else: ?>
                    <th scope="row"><?= __('Tăng giảm giá NET') ?></th>
                <?php endif; ?>
                <td><?= number_format($booking->sale_discount) ?></td>
            </tr>
            <tr>
                <?php if ($booking->type != LANDTOUR): ?>
                    <th scope="row"><?= __('Giảm giá cho Đại lý') ?></th>
                <?php else: ?>
                    <th scope="row"><?= __('Tăng giảm chiết khấu đại lý') ?></th>
                <?php endif; ?>
                <td><?= number_format($booking->agency_discount) ?></td>
            </tr>
        <?php endif; ?>
    </table>
    <h4><?= h('Thông tin thanh toán của Đại lý') ?></h4>
    <table class=" table table-responsive table-striped">
        <?php if ($booking->type != LANDTOUR): ?>
            <tr>
                <th scope="row"><?= __('Lưu ý gửi Đại lý') ?></th>
                <td><?= h($booking->note_agency) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Tình trạng Đại lý thanh toán') ?></th>
                <td>
                    <?php
                    if ($payment) {
                        if ($payment->type != PAYMENT_ONEPAY_CREDIT && $payment->type != PAYMENT_ONEPAY_ATM && $payment->type != PAYMENT_ONEPAY_QR) {
                            if ($payment->images) {
                                if ($booking->mail_type == 1) {
                                    echo 'Đã thanh toán(UNC)';
                                } elseif ($booking->mail_type == 0) {
                                    echo "Đã thanh toán, chưa xác nhận";
                                }
                            } else {
                                if ($booking->mail_type == 2) {
                                    echo 'Công nợ';
                                } else {
                                    echo "Chưa thanh toán";
                                }
                            }
                        } else {
                            if ($payment->onepaystatus == 0) {
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
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('Nội dung thanh toán của Đại lý/Khách ') ?></th>
            <td>Thanh toán Booking mã: <?= $booking->code ?></td>
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
    <?php if ($booking->type != LANDTOUR): ?>
        <h4><?= h('Thông tin thanh toán cho Khách sạn/Đối tác') ?></h4>
        <table class=" table table-responsive table-striped">
            <tr>
                <th scope="row"><?= __('Thanh toán Khách sạn/Đối tác') ?></th>
                <td>
                    <?php
                    switch ($booking->pay_hotel_type) {
                        case 0:
                            echo "Chưa thanh toán";
                            break;
                        case 1:
                            echo "Đã thanh toán";
                            break;
                        case 2:
                            echo "Công nợ";
                            break;
                    }
                    ?>
                </td>
            </tr>
            <?php if (!isset($payment) || (isset($payment) && ($payment->pay_object == PAY_HOTEL || $payment->pay_object == 0))): ?>
                <tr>
                    <?php
                    if ($booking->item_id == 0) {
                        $infors = json_decode($booking->information, true);
                        $payment_information = '';
                        if ($infors) {
                            foreach ($infors as $infor) {
                                $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                            }
                        }
                    } else {
                        if ($booking->hotels) {
                            $infors = json_decode($booking->hotels->payment_information, true);
                            $payment_information = '';
                            if ($infors) {
                                foreach ($infors as $infor) {
                                    $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                                }
                            }
                        }
                        if ($booking->vouchers) {
                            $infors = json_decode($booking->vouchers->payment_information, true);
                            $payment_information = '';
                            if ($infors) {
                                foreach ($infors as $infor) {
                                    $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                                }
                            }
                        }
                        if ($booking->home_stays) {
                            $infors = json_decode($booking->home_stays->payment_information, true);
                            $payment_information = '';
                            if ($infors) {
                                foreach ($infors as $infor) {
                                    $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                                }
                            }
                        }
                        if ($booking->land_tours) {
                            $infors = json_decode($booking->land_tours->payment_information, true);
                            $payment_information = '';
                            if ($infors) {
                                foreach ($infors as $infor) {
                                    $payment_information .= '<p>Tên TK: ' . $infor['username'] . '</p>' . '<p>Số TK: ' . $infor['user_number'] . '</p>' . '<p>Ngân hàng: ' . $infor['user_bank'] . '</p>';
                                }
                            }
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
                <td>
                    Mustgo tk bk <?= $booking->hotel_code ?>
                </td>
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
    <?php endif; ?>

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
                    <a class="btn btn-success" onclick="saveCommentLog(this)" data-title="1" data-ctl="dashboards" data-role="admin" data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">Gửi Comment</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end booking Log -->

