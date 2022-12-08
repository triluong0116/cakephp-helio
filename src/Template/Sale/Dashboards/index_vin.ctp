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
                <div class="col-sm-6">
                    <?= $this->Form->create(null, ['class' => 'form-inline']) ?>
                    <div class="form-group">
                        <label for="">Chọn năm</label>
                        <select name="year" class="form-control select2">
                            <?php for ($i = 2018; $i <= 2030; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button class="btn btn-success">Chọn</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <?php if ($this->request->getSession()->read('Auth.User.role_id') == 2): ?>
            <?php foreach ($bookinsByMonth as $monthKey => $singleMonth): ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Danh sách Doanh thu Booking tháng: <?= $monthKey ?></h2>
                        <?php $totalProfit = 0; ?>
                        <?php if (isset($singleMonth['booking']) && !empty($singleMonth['booking'])) {
                            foreach ($singleMonth['booking'] as $key => $booking) {
                                if ($booking->sale_id != $booking->user_id) {
                                    $totalProfit += $booking->sale_revenue;
                                } else {
                                    $totalProfit += $booking->sale_revenue + $booking->revenue;
                                }

                            }
                        }
                        ?>
                        <h2>&nbsp; Tổng số lãi: <?= number_format($totalProfit) ?> </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <?php if (isset($singleMonth['booking'])): ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12">
                                <div class="x_content table-responsive" style="display: none">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ngày tạo</th>
                                            <th scope="col">Đại lý</th>
                                            <th scope="col">Khách sạn</th>
                                            <th scope="col">Mã Booking</th>
                                            <th scope="col">Khách hàng</th>
                                            <th scope="col">Địa phương</th>
                                            <th scope="col">Số phòng</th>
                                            <th scope="col">Số ngày</th>
                                            <th scope="col">In</th>
                                            <th scope="col">Out</th>
                                            <th scope="col">Giá gốc</th>
                                            <th scope="col">Giá bán Đại lý</th>
                                            <th scope="col">Doanh thu</th>
                                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($singleMonth['booking'] as $key => $booking): ?>
                                            <?php $booking->days_attended = date_diff($booking->start_date, $booking->end_date) ?>
                                            <tr>
                                                <td><?= ++$key ?></td>
                                                <td><?= date_format($booking->created, "d/m/Y h:m:s") ?></td>
                                                <td><?= $booking->user_id != $booking->sale_id ? $booking->user->screen_name : "Khách lẻ" ?></td>
                                                <td><?php
                                                    echo $booking->hotel->name;
                                                    ?></td>
                                                <td><?= $booking->code ?></td>
                                                <td><?= $booking->first_name . " " . $booking->sur_name ?></td>
                                                <td><?php
                                                    echo $booking->hotel->location->name;
                                                    ?></td>
                                                <td>
                                                    <?= count($booking->vinhmsbooking_rooms) ?>
                                                </td>
                                                <td>
                                                    <?= date_diff($booking->start_date, $booking->end_date)->days ?>
                                                </td>
                                                <td>
                                                    <?= date_format($booking->start_date, 'd-m-Y') ?>
                                                </td>
                                                <td>
                                                    <?= date_format($booking->end_date, 'd-m-Y') ?>
                                                </td>
                                                <td><?= number_format($booking->price - $booking->sale_revenue - $booking->revenue) ?></td>
                                                <td><?php
                                                    if ($booking->sale_id != $booking->user_id) {
                                                        echo number_format($booking->price - $booking->revenue);
                                                    } else {
                                                        echo number_format($booking->price);
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    if (($booking->sale_id == $booking->user_id)) {
                                                        echo number_format($booking->sale_revenue + $booking->revenue);
                                                    } else {
                                                        echo number_format($booking->sale_revenue);
                                                    }
                                                    ?></td>
                                                <td class="actions">
                                                    <a type="button" class="btn btn-xs btn-primary"
                                                       href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'viewVin', $booking->id]) ?>">Xem</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php elseif ($this->request->getSession()->read('Auth.User.role_id') == 5): ?>
            <?php foreach ($bookinsByMonth as $monthKey => $singleMonth): ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Danh sách Doanh thu Booking tháng: <?= $monthKey ?></h2>
                        <?php $totalProfit = 0; ?>
                        <?php if (isset($singleMonth['booking']) && !empty($singleMonth['booking'])) {
                            foreach ($singleMonth['booking'] as $key => $booking) {
                                if ($booking->sale_id != $booking->user_id) {
                                    $totalProfit += $booking->sale_revenue;
                                } else {
                                    $totalProfit += $booking->sale_revenue + $booking->revenue;
                                }

                            }
                        }
                        ?>
                        <h2>&nbsp; Tổng số lãi: <?= number_format($totalProfit) ?> </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <?php if (isset($singleMonth['booking'])): ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12">
                                <div class="x_content table-responsive" style="display: none">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ngày tạo Booking</th>
                                            <th scope="col">Đại lý</th>
                                            <th scope="col">Tên loại hình</th>
                                            <th scope="col">Mã Booking</th>
                                            <th scope="col">Khách hàng</th>
                                            <th scope="col">Loại hình</th>
                                            <th scope="col">Điểm đón</th>
                                            <th scope="col">Điểm trả</th>
                                            <th scope="col">Giá Net đại lý</th>
                                            <th scope="col">Mustgo thu hộ</th>
                                            <th scope="col">Doanh thu</th>
                                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($singleMonth['booking'] as $key => $booking): ?>
                                            <?php $booking->days_attended = date_diff($booking->start_date, $booking->end_date) ?>
                                            <tr>
                                                <td><?= ++$key ?></td>
                                                <td><?= date_format($booking->created, "d/m/Y h:m:s") ?></td>
                                                <td><?= $booking->user->screen_name ?></td>
                                                <td><?php
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
                                                    ?></td>
                                                <td><?= $booking->code ?></td>
                                                <td><?= $booking->full_name ?></td>
                                                <td>
                                                    <?php foreach ($booking->booking_landtour_accessories as $k => $accessory): ?>
                                                        <p><?= $accessory->land_tour_accessory->name ?></p>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <?= $booking->booking_landtour->pick_up->name ?>
                                                </td>
                                                <td>
                                                    <?= $booking->booking_landtour->drop_down->name ?>
                                                </td>
                                                <td>
                                                    <?= number_format($booking->price) ?>
                                                </td>
                                                <td>
                                                    <?= $booking->payment_method == MUSTGO_DEPOSIT ? number_format($booking->mustgo_deposit) : 0 ?>
                                                </td>
                                                <td>
                                                    <?= number_format($booking->sale_revenue) ?>
                                                </td>
                                                <td class="actions">
                                                    <a type="button" class="btn btn-xs btn-primary"
                                                       href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'view', $booking->id]) ?>">Xem</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


</div>
