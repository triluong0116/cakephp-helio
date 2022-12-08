<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h($booking->title) ?></h3>
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
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= h($booking->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lưu ý') ?></th>
            <td><?= h($booking->note) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá gốc') ?></th>
            <td><?php
                if ($booking->booking_type == 1) {
                    if ($booking->type == HOTEL) {
                        echo number_format($booking->price - $booking->revenue - $booking->hotels->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount);
                    }
                    if ($booking->type == HOMESTAY) {
                        echo number_format($booking->price - $booking->revenue - $booking->home_stays->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount);
                    }
                    if ($booking->type == LANDTOUR) {
                        echo number_format($booking->price - $booking->revenue - $booking->land_tours->trippal_price * $booking->amount);
                    }
                    if ($booking->type == VOUCHER) {
                        echo number_format($booking->price - $booking->revenue - $booking->vouchers->trippal_price * $booking->amount * date_diff($booking->start_date, $booking->end_date)->days);
                    }
                } else {
                    echo number_format($booking->price - $booking->revenue);
                }
                ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá bán Đại lý') ?></th>
            <td><?php
                if ($booking->booking_type == SYSTEM_BOOKING && $booking->sale_id != $booking->user_id) {
                    if ($booking->type == HOTEL) {
                        echo number_format($booking->price - $booking->revenue);
                    }
                    if ($booking->type == HOMESTAY) {
                        echo number_format($booking->price - $booking->revenue);
                    }
                    if ($booking->type == LANDTOUR) {
                        echo number_format($booking->price - $booking->revenue);
                    }
                    if ($booking->type == VOUCHER) {
                        echo number_format($booking->price - $booking->revenue);
                    }
                } elseif($booking->booking_type == SYSTEM_BOOKING && $booking->sale_id == $booking->user_id) {
                    echo 0;
                } elseif($booking->booking_type == ANOTHER_BOOKING){
                    echo number_format($booking->price);
                }
                ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doanh thu') ?></th>
            <td><?php
                if ($booking->booking_type == SYSTEM_BOOKING && $booking->sale_id != $booking->user_id) {
                    if ($booking->type == HOTEL) {
                        echo number_format($booking->hotels->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount);
                    }
                    if ($booking->type == HOMESTAY) {
                        echo number_format($booking->home_stays->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount);
                    }
                    if ($booking->type == LANDTOUR) {
                        echo number_format($booking->land_tours->trippal_price * $booking->amount);
                    }
                    if ($booking->type == VOUCHER) {
                        echo number_format($booking->vouchers->trippal_price * $booking->amount * date_diff($booking->start_date, $booking->end_date)->days);
                    }
                } elseif($booking->booking_type == SYSTEM_BOOKING && $booking->sale_id == $booking->user_id) {
                    if ($booking->type == HOTEL) {
                        echo number_format($booking->hotels->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount + $booking->revenue);
                    }
                    if ($booking->type == HOMESTAY) {
                        echo number_format($booking->home_stays->price_agency * date_diff($booking->start_date, $booking->end_date)->days * $booking->amount + $booking->revenue);
                    }
                    if ($booking->type == LANDTOUR) {
                        echo number_format($booking->land_tours->trippal_price * $booking->amount + $booking->revenue);
                    }
                    if ($booking->type == VOUCHER) {
                        echo number_format($booking->vouchers->trippal_price * $booking->amount * date_diff($booking->start_date, $booking->end_date)->days + $booking->revenue);
                    }
                } elseif($booking->booking_type == ANOTHER_BOOKING){
                    echo number_format($booking->revenue);
                }
                ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tổng đơn chưa phụ thu') ?></th>
            <td><?= number_format($booking->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu người lớn') ?></th>
            <td><?= number_format($booking->adult_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu trẻ em') ?></th>
            <td><?= number_format($booking->children_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu ngày lễ') ?></th>
            <td><?= number_format($booking->holiday_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu khác') ?></th>
            <td><?= number_format($booking->other_fee) ?>đ</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tổng số tiền booking') ?></th>
            <td><?= number_format($booking->price + $booking->adult_fee + $booking->children_fee + $booking->holiday_fee + $booking->other_fee+ $booking->car+ $booking->service) ?></td>
        </tr
        <tr>
            <th scope="row"><?= __('Hãng xe') ?></th>
            <td><?= h($booking->car) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lưu ý gửi Đại lý') ?></th>
            <td><?= h($booking->service) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đại lý Cọc') ?></th>
            <td><?= $booking->customer_deposit / 1000000 ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đại lý TT') ?></th>
            <td><?= $booking->agency_pay == 1 ? 'Rồi' : 'Chưa' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('TT KS') ?></th>
            <td><?= $booking->pay_hotel == 1 ? 'Rồi' : 'Chưa' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nội dung thanh toán') ?></th>
            <td><?= $booking->payment_content ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hạn thanh toán') ?></th>
            <td><?= date_format($booking->payment_deadline, 'd-m-Y') ?></td>
        </tr>
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
    </table>
    <div class="row">
        <h4><?= __('Other') ?></h4>
        <?= $this->Text->autoParagraph(h($booking->other)); ?>
    </div>
</div>
