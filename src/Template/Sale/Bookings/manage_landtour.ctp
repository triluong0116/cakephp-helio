<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <?= $this->Form->create(null, ['class' => 'form-inline', 'type' => 'get']) ?>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2 col-xs-12 mt15">Chọn ngày</label>
                        <div class="col-md-8 col-sm-10 col-xs-12 mt10">
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="current_day" class="custom-singledate-picker form-control" value="<?= $currentDay ?>"/>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success">Chọn</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <h1 class="text-center">Bảng điều hành doanh thu</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Sale</th>
                                <th>Đại lý</th>
                                <th>Diễn giải</th>
                                <th>Thông tin liên hệ, SĐT, Đón, Trả</th>
                                <th>Số lượng</th>
                                <th>Loại Tour</th>
                                <th>Giá Net</th>
                                <th>Mustgo thu khách</th>
                                <th>Công nợ đại lý</th>
                                <th>Doanh thu</th>
                                <th>Trạng thái</th>
                                <th>Lưu ý</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $numAdult = 0;
                            $numChildren = 0;
                            $numKid = 0;
                            $totalNet = 0;
                            $totalMGDeposit = 0;
                            $totalDept = 0;
                            $totalPeople = 0;
                            ?>
                            <?php foreach ($bookings as $k => $booking): ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= $booking->sale ? $booking->sale->screen_name : "" ?></td>
                                    <td><?= $booking->user ? $booking->user->screen_name : "Khách lẻ" ?></td>
                                    <td>
                                        <?php
                                        //                                        dd($booking);
                                        $note = "";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">" . $booking->land_tours->name . "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">" . "Điểm đón: " . ($booking->booking_landtour && $booking->booking_landtour->pick_up ? $booking->booking_landtour->pick_up->name : "") . " - " . ($booking->booking_landtour ? $booking->booking_landtour->detail_pickup : "") . "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">" . "Điểm trả: " . ($booking->booking_landtour && $booking->booking_landtour->drop_down ? $booking->booking_landtour->drop_down->name : "") . " - " . ($booking->booking_landtour ? $booking->booking_landtour->detail_drop : "") . "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">" . $booking->full_name . ": " . $booking->phone . "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Số khách: " . ($booking->booking_landtour && $booking->booking_landtour->num_adult != 0 ? $booking->booking_landtour->num_adult . " NL" : "") . ($booking->booking_landtour && $booking->booking_landtour->num_children != 0 ? " + " . $booking->booking_landtour->num_children . " TE" : "") . ($booking->booking_landtour && $booking->booking_landtour->num_kid != 0 ? " + " . $booking->booking_landtour->num_kid . " EB" : "") . "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Loại Tour: ";
                                        foreach ($booking->booking_landtour_accessories as $k => $accessory) {
                                            if ($k == 0) {
                                                $note .= $accessory->land_tour_accessory->name;
                                            } else {
                                                $note .= "; " . $accessory->land_tour_accessory->name;
                                            }

                                        }
                                        $note .= "</p>";
                                        $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Thu Hộ: " . ($booking->payment_method == MUSTGO_DEPOSIT ? number_format($booking->mustgo_deposit) : 0) . "</p>";

                                        if ($booking->note) {
                                            $note .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Note: " . $booking->note . "</p>";
                                        }
                                        if ($booking->status != 5) {
                                            $numAdult += $booking->booking_landtour ? $booking->booking_landtour->num_adult : 0;
                                            $numChildren += $booking->booking_landtour ? $booking->booking_landtour->num_children : 0;
                                            $numKid += $booking->booking_landtour ? $booking->booking_landtour->num_kid : 0;
                                            $totalPeople += $booking->booking_landtour ? $booking->booking_landtour->num_adult + $booking->booking_landtour->num_children + $booking->booking_landtour->num_kid : 0;
                                        }
                                        echo $note;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $html = "";
                                        $html .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Điểm đón: " . ($booking->booking_landtour && $booking->booking_landtour->pick_up ? $booking->booking_landtour->pick_up->name : "") . " - " . ($booking->booking_landtour ? $booking->booking_landtour->detail_pickup : "") . "</p>";
                                        $html .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Điểm trả: " . ($booking->booking_landtour && $booking->booking_landtour->drop_down ? $booking->booking_landtour->drop_down->name : "") . " - " . ($booking->booking_landtour ? $booking->booking_landtour->detail_drop : "") . "</p>";
                                        $html .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">Đại diện: " . $booking->full_name . "</p>";
                                        $html .= "<p" . ($booking->status == 5 ? " class='blur-text'" : "") . ">SĐT: " . $booking->phone . "</p>";
                                        echo $html;
                                        ?>
                                    </td>
                                    <td>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= ($booking->booking_landtour && $booking->booking_landtour->num_adult != 0 ? $booking->booking_landtour->num_adult . " NL" : "") . ($booking->booking_landtour && $booking->booking_landtour->num_children != 0 ? " + " . $booking->booking_landtour->num_children . " TE" : "") . ($booking->booking_landtour && $booking->booking_landtour->num_kid != 0 ? " + " . $booking->booking_landtour->num_kid . " EB" : "") ?>
                                        </p>
                                    </td>
                                    <td>
                                        <?php foreach ($booking->booking_landtour_accessories as $k => $accessory): ?>
                                            <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>"><?= $accessory->land_tour_accessory->name ?></p>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($booking->status != 5) {
                                            $totalNet += $booking->price;
                                        }
                                        ?>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= number_format($booking->price) ?>
                                        </p>
                                    </td>
                                    <td>
                                        <?php $booking->payment_method == MUSTGO_DEPOSIT && $booking->status != 5 ? $totalMGDeposit += $booking->mustgo_deposit : $totalMGDeposit += 0 ?>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= $booking->payment_method == MUSTGO_DEPOSIT ? number_format($booking->mustgo_deposit) : 0 ?>
                                        </p>
                                    </td>
                                    <td>
                                        <?php
                                        if ($booking->status != 5 && $booking->payment_method == MUSTGO_DEPOSIT) {
                                            $totalDept += $booking->mustgo_deposit - $booking->price + $booking->agency_discount;
                                        }
                                        ?>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= $booking->payment_method == MUSTGO_DEPOSIT ? number_format($booking->mustgo_deposit - $booking->price + $booking->agency_discount) : 0 ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= number_format($booking->sale_revenue) ?>
                                        </p>
                                    </td>
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
                                                    echo $booking->agency_pay == 1 ? '<h5 class="label label-warning">ĐL đã TT, chờ KT TT</h5>' : '<h5 class="label label label-default">Đã gửi mail xác nhận và đề nghị thanh toán, chờ đại lý thanh toán</h5>';
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
                                    <td>
                                        <p class="<?= $booking->status == 5 ? 'blur-text' : '' ?>">
                                            <?= $booking->note_agency ?>
                                        </p>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                        <?php if ($booking->status < 3): ?>
                                            <a type="button" class="btn btn-xs btn-warning"
                                               href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'edit', $booking->id]) ?>">Sửa</a>
                                            <?php
                                            echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $booking->id], ['confirm' => __('Bạn có chắc muốn xóa Đơn hàng: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']);
                                            ?>
                                            <?php
                                            echo $this->Form->postLink(__('Hủy'), ['action' => 'deny', $booking->id], ['confirm' => __('Bạn có chắc muốn hủy Đơn hàng: {0}?', $booking->name), 'class' => 'btn btn-xs btn-danger']);
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    Tổng
                                </td>
                                <td>
                                    <?= ($numAdult != 0 ? $numAdult . " NL" : "") . ($numChildren != 0 ? " + " . $numChildren . " TE" : "") . ($numKid != 0 ? " + " . $numKid . " EB" : "") ?>
                                </td>
                                <td>

                                </td>
                                <td>
                                    <?= number_format($totalNet) ?>
                                </td>
                                <td>
                                    <?= number_format($totalMGDeposit) ?>
                                </td>
                                <td>
                                    <?= number_format($totalDept) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
