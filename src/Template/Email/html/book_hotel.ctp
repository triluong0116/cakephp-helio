<p><strong>&nbsp;</strong></p>
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
<h3>K&iacute;nh gửi qu&yacute; <?= $partner ?>:&nbsp;
    <?php
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
    ?>
</h3>
<?php
if ($booking->type != LANDTOUR) {
    $objName = "phòng";
} else {
    $objName = "landtour";
}
?>
<p><strong>Mustgo.vn </strong> cảm ơn quý khách sạn   vì sự hợp tác trong thời gian qua. </p>
<p><strong>Mustgo.vn </strong> gửi thông tin đặt phòng và xuất hoá đơn như sau </p>
<strong style="color: red">THÔNG TIN XUẤT HÓA ĐƠN:</strong>
<p><span style="width: 150px;">Tên công ty: </span> <strong style="color: red">CÔNG TY CỔ PHẦN DU LỊCH LIÊN MINH VIỆT NAM</strong></p>
<p><span style="width: 150px;">Mã số thuế: </span><strong style="color: red"> 0108205732</strong></p>
<p><span style="width: 150px;">Địa chỉ xuất hóa đơn :</span><a href="https://goo.gl/maps/Fi3m16woT5nN2bog7" target="_blank" style="color: red;font-weight: bold;">Số 122 Trần Đại Nghĩa , P. Đồng Tâm, Q. Hai Bà Trưng, Hà Nội </a></p>
<p><span style="width: 150px;">Hóa đơn điện tử vui lòng gửi về email:  </span> <strong style="color: red">hoadon@mustgo.vn</strong> </p>
<p><span style="width: 150px;">Hóa đơn giấy vui lòng gửi theo thông tin </span><strong style="color: #00B0F0">Ms Hà - Tel 0964679040 - Số 43/45 Ngõ 130 Đốc Ngữ, Vĩnh Phúc, Ba Đình, Hà Nội </strong></p>

<p><strong>THÔNG TIN ĐẶT PHÒNG:</strong>:</p>
<h1>&nbsp;</h1>
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
    <tr>
        <td>
            <p><em>Lưu ý: </em></p>
        </td>
        <td>
            <p>&nbsp;<?= $booking->note ?></p>
        </td>
    </tr>
    <?php if (!$booking_rooms): ?>
        <?php if ($booking->booking_landtour) : ?>
            <tr>
                <td><em>Số lượng</em></td>
                <td><?= $booking->booking_landtour->num_adult ?> NL, <?= $booking->booking_landtour->num_children ?> TE, <?= $booking->booking_landtour->num_kid ?> EB</td>
            </tr>
            <tr>
                <td><em>Điểm đón</em></td>
                <td><?= ($booking->booking_landtour->pick_up ? $booking->booking_landtour->pick_up->name : "") . " - " . $booking->booking_landtour->detail_pickup ?></td>
            </tr>
            <tr>
                <td><em>Điểm trả</em></td>
                <td><?= ($booking->booking_landtour->drop_down ? $booking->booking_landtour->drop_down->name : "") . " - " . $booking->booking_landtour->detail_drop ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td>
                    <p><em>Số ph&ograve;ng:</em></p>
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
        <?php endif; ?>
        <tr>
            <td>
                <p><em>Ngày đi:</em></p>
            </td>
            <td>
                <p>&nbsp;<?= date_format($booking->start_date, "d-m-Y") ?></p>
            </td>
        </tr>
        <?php if ($booking->type != LANDTOUR): ?>
            <tr>
                <td>
                    <p><em>Ngày về:</em><em></em></p>
                </td>
                <td>
                    <p>&nbsp;<?= date_format($booking->end_date, "d-m-Y") ?></p>
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($booking->type == LANDTOUR): ?>
            <tr>
                <td>
                    <p><em>Giá Net đại lý:</em><em></em></p>
                </td>
                <td>
                    <p>&nbsp;<?= number_format($booking->price) ?></p>
                </td>
            </tr>
            <?php if ($booking->payment_method == MUSTGO_DEPOSIT) ?>
                <tr>
                <td>
                    <p><em>Mustgo thu hộ</em><em></em></p>
                </td>
                <td>
                <p>&nbsp;<?= number_format($booking->mustgo_deposit) ?></p>
            </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>
                <p><em>Tình trạng thanh toán:</em><em></em></p>
            </td>
            <td>
                <?php if ($booking->type != LANDTOUR): ?>
                    <p>&nbsp;<?= $booking->is_paid == 1 ? "Đã thanh toán" : "Chưa thanh toán" ?></p>
                <?php else: ?>
                    <p>&nbsp;<?= $booking->agency_pay == 1 ? "Đã thanh toán" : "Chưa thanh toán" ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php if ($booking->type == LANDTOUR): ?>
            <tr>
                <td><em>Loại Tour</em></td>
                <td>
                    <?php foreach ($booking->booking_landtour_accessories as $accessory): ?>
                        <p><?= $accessory->land_tour_accessory->name ?></p>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($booking->type != LANDTOUR): ?>
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
    <?php endif; ?>
    </tbody>
</table>
<br>
<?php if (is_array($booking_rooms) && !empty($booking_rooms)): ?>
    <h2>Chi tiết Booking</h2>
    <?php foreach ($booking_rooms as $key => $booking_room): ?>
        <div>
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
                </tbody>
            </table>
        </div>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($booking->type != LANDTOUR): ?>
    <?php if (isset($surcharges)): ?>
        <h2>Danh sách Phụ thu</h2>
        <table style="margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <?php if (is_array($surcharges) && !empty($surcharges)): ?>
                <?php if ($booking->hotels): ?>
                    <?php foreach ($surcharges as $surcharge): ?>
                        <tr>
                            <td><em><?= \App\View\Helper\SystemHelper::getSurchargeName($surcharge->surcharge_type) ?></em></td>
                            <td>
                                <?php
                                switch ($surcharge->surcharge_type) {
                                    case SUR_WEEKEND:
                                        $text = '';
                                        foreach ($booking_rooms as $booking_room) {
                                            if ($booking_room['numb_weekend'] > 0) {
                                                $text .= '</em><b>' . $booking_room->room->name . ':</b> ' . $booking_room['numb_weekend'] . ' ngày cuối tuần </em><br>';
                                            }
                                        }
                                        echo $text;
                                        break;
                                    case SUR_HOLIDAY:
                                        $text = '';
                                        foreach ($booking_rooms as $booking_room) {
                                            if ($booking_room['numb_holiday'] > 0) {
                                                $text .= '</em><b>' . $booking_room->room->name . ':</b> ' . $booking_room['numb_holiday'] . ' ngày lễ </em><br>';
                                            }
                                        }
                                        echo $text;
                                        break;
                                    case SUR_ADULT:
                                        $text = '';
                                        foreach ($booking_rooms as $booking_room) {
                                            $roomTotalMaxAdult = $booking_room->room->max_adult * $booking_room['num_room'];
                                            $roomTotalAdult = $booking_room->room->num_adult * $booking_room['num_room'];
                                            $roomTotalMaxPeople = $booking_room->room->max_people * $booking_room['num_room'];
                                            if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                                                if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                                                    if ($booking_room['num_adult'] >= $roomTotalAdult) {
                                                        $numb_adult = $booking_room['num_adult'] - $roomTotalAdult;
                                                    }
                                                }
                                            }
                                            $text .= '</em><b>' . "Hạng phòng " . $booking_room->room->name . ':</b> ' . $numb_adult . ' người lớn </em><br>';
                                        }
                                        echo $text;
                                        break;
                                    case SUR_CHILDREN:
                                        $text = '';
                                        foreach ($booking_rooms as $booking_room) {
                                            $text .= '</em><b>' . "Hạng phòng " . $booking_room->room->name . ':</b> ';
                                            $child_ages = [];
                                            $roomTotalMaxAdult = $booking_room->room->max_adult * $booking_room['num_room'];
                                            $roomTotalAdult = $booking_room->room->num_adult * $booking_room['num_room'];
                                            $roomTotalChildren = $booking_room->room->num_children * $booking_room['num_room'];
                                            $roomTotalMaxPeople = $booking_room->room->max_people * $booking_room['num_room'];
                                            if ($roomTotalMaxPeople >= ($booking_room['num_adult'] + $booking_room['num_children'])) {
                                                if ($roomTotalMaxAdult >= $booking_room['num_adult']) {
                                                    if (isset($booking_room['child_ages'])) {
                                                        $child_ages = json_decode($booking_room['child_ages']);
                                                        if ($booking_room['num_adult'] < $roomTotalAdult) {
                                                            $bonusAdult = ($roomTotalAdult - $booking_room['num_adult']);
                                                            rsort($booking_room['child_ages']);
                                                            $child_ages = array_slice($booking_room['child_ages'], $bonusAdult, count($booking_room['child_ages']) - $bonusAdult);
                                                        }
                                                        $total_standard_child = $booking_room['num_room'] * $booking_room->room->num_children;
                                                        $countStandardChild = 1;
                                                        foreach ($child_ages as $key => $age) {
                                                            if ($age <= $booking_room->room->standard_child_age) {
                                                                $countStandardChild++;
                                                                unset($child_ages[$key]);
                                                                if ($countStandardChild > $total_standard_child) {
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        $newAges = array_values($child_ages);
                                                    }
                                                }
                                            }
                                            foreach ($child_ages as $key => $child_age) {
                                                $text .= ' ' . $child_age . ' tuổi';
                                            }
                                            $text .= '</em><br>';
                                        }
                                        echo $text;
                                        break;
                                    case SUR_BONUS_BED:
                                        echo $surcharge->quantity . ' giường phụ';
                                        break;
                                    case SUR_BREAKFAST:
                                        echo $surcharge->quantity . ' bữa sáng';
                                        break;
                                    case SUR_CHECKIN_SOON:
                                    case SUR_CHECKOUT_LATE:
                                        echo $surcharge->quantity;
                                        break;
                                    case SUR_OTHER:
                                        echo $surcharge->quantity . ' phụ thu khác';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif ($booking->land_tours): ?>
                    <tr>
                        <td><em>Phụ thu trẻ em</em></td>
                        <td>
                            <?php
                            $text = $surcharges[0]->num_children . ' trẻ em';
                            $child_ages = json_decode($surcharges[0]->child_ages, true);
                            if ($child_ages && !empty($child_ages)) {
                                foreach ($child_ages as $key => $child_age) {
                                    $text .= ' ' . $child_age . ' tuổi';
                                }
                            }
                            echo $text;
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php elseif ($booking->landtour): ?>
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
    <?php endif; ?>
<?php endif; ?>
<br>
<?php if ($booking->hotels && $booking->hotels->is_special): ?>
    <h2>Thông tin thêm</h2>
    <table style="margin-left: auto; margin-right: auto;" border="1">
        <tbody>
        <tr>
            <td>Danh sách đoàn và ngày sinh trẻ em</td>
            <td><p style="white-space: pre-line;white-space: pre-wrap;"><?= $booking->information ?></p></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
<p><em>&nbsp;</em></p>
<p><strong>Mong nhận phản hồi sớm từ quý <?= $partner ?>.</strong></p>
<p><em>&nbsp;</em></p>

<?= $signature ?>
