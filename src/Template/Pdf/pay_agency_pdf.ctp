<?php
$partner = '';
switch ($booking->type) {
    case HOTEL:
    case VOUCHER:
        $partner = 'khách sạn';
        break;
    case HOMESTAY:
    case LANDTOUR:
        $partner = 'đối tác';
}
?>
<h3>K&iacute;nh gửi qu&yacute; kh&aacute;ch h&agrave;ng: <?= $booking->full_name ?> </h3>
<p><br/><strong> Mustgo.vn </strong> trân trọng cảm ơn quý khách đã lựa chọn
    <?php
    if ($booking->vouchers) {
        echo $booking->vouchers->name;
    }
    if ($booking->home_stays) {
        echo $booking->home_stays->name;
    }
    if ($booking->hotels) {
        echo $booking->hotels->name;
    }
    if ($booking->land_tours) {
        echo $booking->land_tours->name;
    }
    ?> l&agrave;m điểm đến sắp tới cho chuyến đi của m&igrave;nh.<br/> <strong><em>Mustgo.vn</em></strong> tr&acirc;n
    trọng gửi đến qu&yacute; kh&aacute;ch h&agrave;ng phiếu y&ecirc;u cầu thanh to&aacute;n cho giao dịch booking số:
    <strong><?= $booking->code ?></strong> trên.<br/> Qu&yacute; kh&aacute;ch vui l&ograve;ng thanh to&aacute;n trong v&ograve;ng
    03 tiếng để ph&ograve;ng được giữ cho qu&yacute; kh&aacute;ch bởi v&igrave; t&igrave;nh trạng ph&ograve;ng c&oacute;
    thể thay đổi bất kỳ l&uacute;c n&agrave;o.<br/> Chi tiết thanh to&aacute;n như sau:<br/></p>
<hr>
<?php if (!$booking->combos): ?>
    <p><strong>T&ecirc;n <?= $partner ?>: <?php
            if ($booking->hotels) {
                echo $booking->hotels->name;
            }
            if ($booking->vouchers) {
                echo $booking->vouchers->hotel->name;
            }
            if ($booking->home_stays) {
                echo $booking->home_stays->name;
            }
            if ($booking->land_tours) {
                echo $booking->land_tours->organizer;
            }
            ?>.<br/> Địa chỉ: <?php
            if ($booking->hotels) {
                echo $booking->hotels->address;
            }
            if ($booking->vouchers) {
                echo $booking->vouchers->hotel->address;
            }
            if ($booking->home_stays) {
                echo $booking->home_stays->address;
            }
            if ($booking->land_tours) {
                echo isset($booking->land_tours->destination) ? $booking->land_tours->destination->name : '';
            }
            ?>
        </strong></p>
<?php endif; ?>

<?php if ($booking->combos): ?>
    <?php foreach ($booking->combos->hotels as $hotel): ?>
        <p><strong>T&ecirc;n <?= $partner ?>: <?= $hotel->name ?> </strong></p>
    <?php endforeach; ?>
<?php endif; ?>
<p><strong>Hướng dẫn vi&ecirc;n online: <?= isset($booking->user) ? $booking->user->screen_name : '' ?></strong></p>
<p><strong>Số điện thoại HDV: <?= isset($booking->user) ? $booking->user->phone : '' ?></strong></p>
<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <tr>
        <td>
            <p><strong><u></u></strong></p>
        </td>
        <td>
            <p><strong><u>Th&ocirc;ng tin kh&aacute;ch h&agrave;ng</u></strong></p>
        </td>
    </tr>
    <?php if ($booking->type == HOTEL): ?>
        <tr>
            <td>
                <p><em>Mã booking của <?= $partner ?> (nếu c&oacute;): </em></p>
            </td>
            <td>
                <p>&nbsp;<?= $booking->hotel_code ?></p>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>
            <p><em>Tên trưởng đoàn: </em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->full_name ?></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Số điện thoại trưởng đoàn: </em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->phone ?></p>
        </td>
    </tr>
    <?php if ($booking->hotels && $booking->hotels->is_special): ?>
        <tr>
            <td>
                <p><em>Danh sách đoàn và ngày sinh trẻ em</em></p>
            </td>
            <td><p style="white-space: pre-line;white-space: pre-wrap;"><?= $booking->information ?></p></td>
        </tr>
    <?php endif; ?>
    <?php if (!$booking_rooms): ?>
        <?php if ($booking->type != LANDTOUR): ?>
            <tr>
                <td>
                    <p><em>Số phòng</em></p>
                </td>
                <td>
                    <p>&nbsp;<?php
                        if ($booking->vouchers) {
                            echo $booking->amount;
                        }
                        if ($booking->hotels) {
                            echo $booking->amount;
                        }
                        if ($booking->home_stays) {
                            echo $booking->amount;
                        }
                        if ($booking->land_tours) {
                            echo $booking->amount;
                        }
                        ?></p>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td>
                    <p><em>Số người lớn</em></p>
                </td>
                <td>
                    <p>&nbsp;<?= $booking->booking_landtour->num_adult ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><em>Số trẻ em</em></p>
                </td>
                <td>
                    <p>&nbsp;<?= $booking->booking_landtour->num_children ?></p>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>
                <p><em>Ngày đi:</em></p>
            </td>
            <td>
                <p>&nbsp;<?= date_format($booking->start_date, "d-m-Y") ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>Ngày về:</em><em>:</em></p>
            </td>
            <td>
                <p>&nbsp;<?= date_format($booking->end_date, "d-m-Y") ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>H&atilde;ng xe (nếu c&oacute;):</em></p>
            </td>
            <td>
                <p>&nbsp;<?= $booking->car ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>Dịch vụ cộng th&ecirc;m ( nếu c&oacute;)</em></p>
            </td>
            <td>
                <p>&nbsp;<?= $booking->service ?></p>
            </td>
        </tr>
        <?php if ($booking->type == LANDTOUR): ?>
            <tr>
                <td>
                    <p><em>Tổng đơn chưa phụ thu</em></p>
                </td>
                <td>
                    <p>&nbsp;<?= number_format($booking->booking_landtour->num_adult * ($booking->land_tours->price + $booking->land_tours->trippal_price + $booking->land_tours->customer_price)) ?>đ</p>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
    </tbody>
</table>
<br>
<?php if (is_array($booking_rooms) && !empty($booking_rooms)): ?>
    <h2>Chi tiết Booking</h2>
    <?php foreach ($booking_rooms as $key => $booking_room): ?>
        <b>Hạng Phòng thứ <?= $key + 1 ?> </b>
        <table style="margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
                <td><em>Tên Hạng phòng</em></td>
                <td><?= $booking_room->room->name ?></td>
            </tr>
            <tr>

                <td><em>Check In</em></td>
                <td><?= date_format($booking_room->start_date, 'd-m-Y') ?></td>
            </tr>
            <tr>
                <td><em>Check Out</em></td>
                <td><?= date_format($booking_room->end_date, 'd-m-Y') ?></td>
            </tr>
            <tr>
                <td><em>Số phòng</em></td>
                <td><?= $booking_room->num_room ?></td>
            </tr>
            <tr>
                <td><em>Số người lớn</em></td>
                <td><?= $booking_room->num_adult ?></td>
            </tr>
            <tr>
                <td><em>Số trẻ em</em></td>
                <td><?= $booking_room->num_children ?></td>
            </tr>
            <?php if (isset($booking_room->child_ages)) : ?>
                <?php
                $child_ages = json_decode($booking_room->child_ages, true);
                ?>
                <?php if ($child_ages && !empty($child_ages)) : ?>
                    <tr>
                        <td rowspan="<?= count($child_ages) + 1 ?>">Tuổi các bé</td>
                        <td>Bao gồm:</td>
                    </tr>
                    <?php foreach ($child_ages as $key => $child_age): ?>
                        <tr>
                            <td><?= $child_age ?> tuổi</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
            <tr>
                <td><em>Tổng tiền hạng phòng chưa bao gồm phụ thu</em></td>
                <td><?= $booking->payment_method == CUSTOMER_PAY ? number_format($booking_room->price) : number_format($booking_room->price - $booking_room->revenue * $booking_room->num_room) ?>đ</td>
            </tr>
            </tbody>
        </table>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (is_array($surcharges) && !empty($surcharges)): ?>
    <h2>Chi tiết Phụ thu</h2>
<?php endif; ?>
<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <?php if (is_array($surcharges) && !empty($surcharges)): ?>
        <?php if ($booking->type == LANDTOUR): ?>
            <tr>
                <td><em>Phụ thu trẻ em</em></td>
                <?php
                $allRev = 0;
                if ($booking->payment_method == CUSTOMER_PAY) {
                    $allRev = $booking->land_tours->price + $booking->land_tours->trippal_price + $booking->land_tours->customer_price;
                } elseif ($booking->payment_method == AGENCY_PAY) {
                    $allRev = $booking->land_tours->price + $booking->land_tours->trippal_price;
                }
                $bkLandtourChild = $booking->price - $allRev * $booking->booking_landtour->num_adult
                ?>
                <td><?= number_format($bkLandtourChild) ?>đ</td>
            </tr>
        <?php else: ?>
            <?php foreach ($surcharges as $surcharge): ?>
                <tr>
                    <td><em><?= \App\View\Helper\SystemHelper::getSurchargeName($surcharge->surcharge_type) ?></em></td>
                    <td><?= number_format($surcharge->price) ?>đ</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php elseif ($booking->hotels): ?>
        <tr>
            <td>
                <p><em>Phụ thu th&ecirc;m người lớn: </em></p>
            </td>
            <td>
                <p>&nbsp;<?= number_format($booking->adult_fee) ?>đ</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>Phụ thu trẻ em: </em></p>
            </td>
            <td>
                <p>&nbsp;<?= number_format($booking->children_fee) ?>đ</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>Phụ thu ngày lễ: </em></p>
            </td>
            <td>
                <p>&nbsp;<?= number_format($booking->holiday_fee) ?>đ</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><em>Phụ thu kh&aacute;c: </em></p>
            </td>
            <td>
                <p>&nbsp;<?= number_format($booking->other_fee) ?>đ</p>
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<?php if (!$booking->user_id == 0 && $booking->payment_method == AGENCY_PAY): ?>
    <h2>Giảm giá</h2>
    <table style="margin-left: auto; margin-right: auto;" border="1">
        <tbody>
        <tr>
            <?php if($booking->type != LANDTOUR): ?>
                <th scope="row"><?= __('Giảm giá cho Đại lý') ?></th>
            <?php else: ?>
                <th scope="row"><?= __('Tăng giảm chiết khấu đại lý') ?></th>
            <?php endif; ?>
            <td><?= number_format($booking->agency_discount) ?></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
<h2>Tổng hợp</h2>
<table style="margin-left: auto; margin-right: auto;" border="1">
    <tbody>
    <?php
    $totalSurchargePrice = 0;
    if (is_array($surcharges) && !empty($surcharges)) {
        foreach ($surcharges as $surcharge) {
            $totalSurchargePrice += $surcharge->price;
        }
    }
    $totalPrice = $booking->price
        + ($booking->adult_fee ? $booking->adult_fee : 0)
        + ($booking->children_fee ? $booking->children_fee : 0)
        + ($booking->holiday_fee ? $booking->holiday_fee : 0)
        + ($booking->other_fee ? $booking->other_fee : 0)
        + $totalSurchargePrice
    ?>
    <tr>
        <td><em>Tổng giá đơn hàng</em></td>
        <td><?= number_format($totalPrice) ?> đ</td>
    </tr>
    <tr>
        <td><em>Nội dung thanh toán</em></td>
        <td>Thanh toán booking mã <?= $booking->code ?></td>
    </tr>
    <?php if ($booking->user_id && !$booking->user_id == 12 && $booking->payment_method == AGENCY_PAY): ?>
        <tr>
            <?php if($booking->type != LANDTOUR): ?>
                <th scope="row"><?= __('Giảm giá cho Đại lý') ?></th>
            <?php else: ?>
                <th scope="row"><?= __('Tăng giảm chiết khấu đại lý') ?></th>
            <?php endif; ?>
            <td><?= number_format($booking->agency_discount) ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<hr>
<strong>TH&Ocirc;NG TIN THANH TO&Aacute;N:</strong>
<strong>Link thanh toán: </strong>
<a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $booking->code], true) ?>"><?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $booking->code], true) ?> </a>
<div>
    <strong>Thanh to&aacute;n trực tiếp bằng tiền mặt </strong>
    <div style="margin:5px 50px 5px 50px">
        <p>- P.402 Tầng 04 T&ograve;a nh&agrave; Lake side số 71 phố Ch&ugrave;a L&aacute;ng, Phường L&aacute;ng Thượng,
            Quận Đồng
            Đa, H&agrave; Nội.<br/> Thời gian l&agrave;m việc: 9:00 &ndash; 18:00 c&aacute;c ng&agrave;y từ thứ 2 đến
            thứ 6 v&agrave;
            9:00 &ndash; 12:00 Thứ 7</p>
    </div>
</div>
<p>
    <strong>THANH TOÁN BẰNG CHUYỂN KHOẢN:</strong>
</p>
<div>
    <strong>T&Agrave;I KHOẢN C&Aacute; NH&Acirc;N (Nếu Quý khách không lấy hóa đơn):</strong>
    <div style="margin: 5px 50px 5px 50px">
        <?php if (isset($bankAccounts) && is_array($bankAccounts)) : ?>
            <?php foreach ($bankAccounts as $account): ?>
                <p>
                    <strong>- <?= $account['bank_name'] ?></strong>
                    <br/>
                    <?= $account['account_name'] ?>
                    <br/>
                    Số TK:
                    <?= $account['account_number'] ?>
                    <br/>
                    Chi nhánh sở giao dịch
                    <?= $account['bank_branch'] ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <strong>TÀI KHOẢN DOANH NGHIỆP (Nếu quý khách lấy hóa đơn)</strong>
    <br>
    Chủ tài khoản: Công ty Cổ Phần Du Lịch Liên Minh Việt Nam
    <br>
    Số TK: <?= $bankInvoice['account_number'] ?>
    <br/>
    Mở tại <?= $bankInvoice['bank_name'] ?> chi nhánh <?= $bankInvoice['bank_branch'] ?>.
    <br>
</div>
<hr>
<div>
    <p style="text-align: center">
        <strong>
            Mustgo.vn kính chúc quý khách hàng có một chuyến đi tuyệt vời.
            <br>
            Chúng tôi luôn sẵn sàng phục vụ quý khách.
        </strong>
    </p>
</div>
