<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="p20">
        <div class="row">
            <div>
                <center>

                    <div class="pt25 pb50">
                        <h2><span class="box-underline-center pb20">Thông tin Booking</span></h2>
                    </div>
                </center>
            </div>
            <div class="pt05 pb30">
                <center>
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-sm-offset-9 col-sm-6">
                                <div class='input-group date datepicker' id="start-date-picker">
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                    <input type='text' name="fromDate" class="form-control"
                                           placeholder="Thời gian bắt đầu"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class='input-group date datepicker' id="end-date-picker">
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                    <input type='text' name="toDate" class="form-control"
                                           placeholder="Thời gian kết thúc"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success pt05 pb05">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </center>
            </div>
            <div class="chia">
                <div class="nav-center">
                    <ul class="nav nav-tabs">
                        <li><a href="#Booking" data-toggle="tab">Đơn hàng</a></li>
                        <li><a href="#BookingVin" data-toggle="tab">Đơn Vinpearl</a></li>
                        <li><a href="#Landtour" data-toggle="tab">Landtour</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane" id="Booking">
                        <div class="box-white mb30 p15">
                            <div class="filter-header mb15">
                                <div class="row vertical-center mr0-i ml0-i">
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Ngày đặt</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Mã Booking</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Tên đơn hàng</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Trạng thái</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Tên khách hàng</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Check in</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Check Out</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Số phòng</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Số đêm</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Giá đại lý</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark"></p>
                                    </div>
                                    <div class="clear-fix"></div>
                                </div>
                                <div id="agency_filter" class="accordion filter-accordion">
                                    <?php if ($datas): ?>
                                        <?php foreach ($datas as $key => $data): ?>
                                            <?php if ($data->type != LANDTOUR): ?>
                                                <div class="panel" data-room-id="<?= $data->id ?>"
                                                     onclick="Frontend.filterHighlight(this, true);">
                                                    <div
                                                        class="row pt10 pb10 mr0-i ml0-i text-center panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                                                        <div class="col-sm-3">
                                                            <?= date_format($data->created, 'd-m-Y') ?>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php if ($data->payment): ?>
                                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.reviewPayment', 'code' => $data->code]) ?>"><?= $data->code ?></a>
                                                            <?php else: ?>
                                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $data->code]) ?>"><?= $data->code ?></a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?= $data->has('home_stays') ? $data->home_stays->name : '' ?>
                                                            <?= $data->has('vouchers') ? $data->vouchers->name : '' ?>
                                                            <?= $data->has('hotels') ? $data->hotels->name : '' ?>
                                                            <?= $data->has('land_tours') ? $data->land_tours->name : '' ?>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php
                                                            if ($data->booking_type == SYSTEM_BOOKING) {
                                                                switch ($data->status) {
                                                                    case 0:
                                                                        echo 'CTV mới đặt';
                                                                        break;
                                                                    case 3:
                                                                        echo 'Hoàn thành';
                                                                        break;
                                                                    case 4:
                                                                        echo 'Hoàn thành';
                                                                        break;
                                                                    default:
                                                                        echo 'Đơn hàng đang được xử lý';
                                                                        break;
                                                                }
                                                            } elseif ($data->booking_type == ANOTHER_BOOKING) {
                                                                echo 'Hoàn thành';
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-3"><?= $data->full_name ?></div>
                                                        <div class="col-sm-3"><?= date_format($data->start_date, 'd-m-Y') ?></div>
                                                        <div class="col-sm-3"><?= date_format($data->end_date, 'd-m-Y') ?></div>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            $totalRoom = 0;
                                                            if ($data->booking_rooms) {
                                                                foreach ($data->booking_rooms as $k => $room) {
                                                                    $totalRoom += $room->num_room;
                                                                }
                                                            }
                                                            echo $totalRoom;
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            echo date_diff($data->start_date, $data->end_date)->days;
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            echo number_format($data->total_price);
                                                            ?><sup>đ</sup>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <?php if ($data->payment): ?>
                                                                <a class="detail text-center" href="<?= \Cake\Routing\Router::url(['_name' => 'booking.reviewPayment', 'code' => $data->code]) ?>">Chi tiết</a>
                                                            <?php else: ?>
                                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $data->code]) ?>">Chi tiết</a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="clear-fix"></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="BookingVin">
                        <div class="box-white mb30 p15">
                            <div class="filter-header mb15">
                                <div class="row mr0-i ml0-i">
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Ngày đặt</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Mã Booking</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Tên đơn hàng</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center semi-bold text-super-dark">Trạng thái</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Tên khách hàng</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Check in</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Check out</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Số phòng</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Số đêm</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Giá đại lý</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark"></p>
                                    </div>
                                    <div class="clear-fix"></div>
                                </div>
                                <div id="agency_filter" class="accordion filter-accordion">
                                    <?php if ($listVinBookings): ?>
                                        <?php foreach ($listVinBookings as $key => $data): ?>
                                            <div class="panel" data-room-id="<?= $data->id ?>"
                                                 onclick="Frontend.filterHighlight(this, true);">
                                                <div class="row pt10 pb10 mr0-i ml0-i text-center panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                                                    <div class="col-sm-3">
                                                        <p class="text-center">
                                                            <?= date_format($data->created, 'd-m-Y') ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <p class="text-center">
                                                            <?= $data->code ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?= $data->hotel->name ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        if ($data->status == 1) {
                                                            if ($data->vinpayment && $data->vinpayment->images) {
                                                                echo 'Đại lý mới đặt, đã up UNC';
                                                            }
                                                            if ($data->vinpayment && ($data->vinpayment->type == PAYMENT_ONEPAY_QR || $data->vinpayment->type == PAYMENT_ONEPAY_ATM || $data->vinpayment->type == PAYMENT_ONEPAY_CREDIT)) {
                                                                if ($data->vinpayment->onepaystatus == 0) {
                                                                    echo 'Đại lý mới đặt, thanh toán Onepay thành công';
                                                                } else {
                                                                    echo 'Đại lý mới đặt, thanh toán Onepay không thành công';
                                                                }
                                                            }
                                                            if (!$data->vinpayment || !$data->vinpayment->images) {
                                                                echo 'Đại lý mới đặt, chưa up UNC';
                                                            }
                                                        } elseif ($data->status == 4 || $data->status == 2) {
                                                            echo 'Hoàn thành';
                                                        } elseif ($data->status == 5) {
                                                            echo 'Đã hủy';
                                                        } else {
                                                            echo 'Đơn hàng đang được xử lý';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-3"><?= $data->first_name . " " . $data->sur_name ?></div>
                                                    <div class="col-sm-3"><p><?= date_format($data->start_date, 'd-m-Y') ?></p></div>
                                                    <div class="col-sm-3"><p><?= date_format($data->end_date, 'd-m-Y') ?></p></div>
                                                    <div class="col-sm-2">
                                                        <?= count($data->vinhmsbooking_rooms) ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <?= date_diff($data->start_date, $data->end_date)->days ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <p><?= number_format($data->price - $data->revenue - $data->agency_discount - $data->sale_discount) ?>đ</p>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <?php if ($data->vinpayment): ?>
                                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.reviewVinPayment', 'code' => $data->code]) ?>"
                                                               class="detail text-center">Chi tiết
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.paymentVinpearl', 'code' => $data->code]) ?>"
                                                               class="detail text-center">Chi tiết</a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="clear-fix"></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="Landtour">
                        <div class="box-white mb30 p15">
                            <div class="filter-header mb15">
                                <div class="row vertical-center mr0-i ml0-i">
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Ngày đặt</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Mã Booking</p>
                                    </div>
                                    <div class="col-sm-5">
                                        <p class="text-center semi-bold text-super-dark">Tên đơn hàng</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Trạng thái</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Tên khách hàng</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark">Ngày đi</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-center semi-bold text-super-dark">Diễn giải</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-center semi-bold text-super-dark">Thông tin liên hệ</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Số lượng</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Giá đại lý</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Mustgo thu hộ</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark">Chênh lệch</p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="text-center semi-bold text-super-dark"></p>
                                    </div>
                                    <div class="clear-fix"></div>
                                </div>
                                <div id="agency_filter" class="accordion filter-accordion">
                                    <?php if ($datas): ?>
                                        <?php foreach ($datas as $key => $data): ?>
                                            <?php if ($data->type == LANDTOUR): ?>
                                                <div class="panel" data-room-id="<?= $data->id ?>"
                                                     onclick="Frontend.filterHighlight(this, true);">
                                                    <div
                                                        class="row pt10 pb10 mr0-i ml0-i text-center panel-row <?= ($key == 0) ? 'panel-bg-blue' : '' ?>">
                                                        <div class="col-sm-3"><?= date_format($data->created, 'd-m-Y') ?></div>
                                                        <div class="col-sm-2"><a
                                                                href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $data->code]) ?>"><?= $data->code ?></a>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <?= $data->has('home_stays') ? $data->home_stays->name : '' ?>
                                                            <?= $data->has('vouchers') ? $data->vouchers->name : '' ?>
                                                            <?= $data->has('hotels') ? $data->hotels->name : '' ?>
                                                            <?= $data->has('land_tours') ? $data->land_tours->name : '' ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            if ($data->booking_type == SYSTEM_BOOKING) {
                                                                switch ($data->status) {
                                                                    case 0:
                                                                        echo 'CTV mới đặt';
                                                                        break;
                                                                    case 1:
                                                                        echo 'Chờ KS xác nhận phòng rồi gửi mail đề nghị thanh toán';
                                                                        break;
                                                                    case 2:
                                                                        echo $data->agency_pay ? 'CTV đã thanh toán, xin gửi mail xác nhận' : 'Đang chờ CTV thanh toán';
                                                                        break;
                                                                    case 3:
                                                                        echo ($data->payment_method == AGENCY_PAY) ? 'Hoàn thành' : 'Hoàn thành (chờ Admin cộng lãi)';
                                                                        break;
                                                                    case 4:
                                                                        echo 'Hoàn thành';
                                                                        break;
                                                                    case 5:
                                                                        echo 'Đã hủy';
                                                                        break;
                                                                }
                                                            } elseif ($data->booking_type == ANOTHER_BOOKING) {
                                                                echo 'Hoàn thành';
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-3"><?= $data->full_name ?></div>
                                                        <div class="col-sm-3"><p class="<?= $data->status == 5 ? "blur-text" : "" ?>"><?= date_format($data->start_date, 'd-m-Y') ?></p></div>
                                                        <div class="col-sm-4">
                                                            <?php
                                                            $note = "";
                                                            $note .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">" . $data->land_tours->name . "</p>";
                                                            $note .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">" . $data->full_name . ": " . $data->phone . "</p>";
                                                            $note .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">Số khách: " . ($data->booking_landtour->num_adult != 0 ? $data->booking_landtour->num_adult . " NL" : "") . ($data->booking_landtour->num_children != 0 ? " + " . $data->booking_landtour->num_children . " TE" : "") . ($data->booking_landtour->num_kid != 0 ? " + " . $data->booking_landtour->num_kid . " EB" : "") . "</p>";
                                                            if ($data->note) {
                                                                $note .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">Note: " . $data->note . "</p>";
                                                            }
                                                            echo $note;
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <?php
                                                            $html = "";
                                                            $html .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">Điểm đón: " . ($data->booking_landtour->pick_up ? $data->booking_landtour->pick_up->name : "") . " - " . $data->booking_landtour->detail_pickup . "</p>";
                                                            $html .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">Điểm trả: " . ($data->booking_landtour->drop_down ? $data->booking_landtour->drop_down->name : "") . " - " . $data->booking_landtour->detail_drop . "</p>";
                                                            $html .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">Đại diện: " . $data->full_name . "</p>";
                                                            $html .= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">SĐT: " . $data->phone . "</p>";
                                                            echo $html;
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?= "<p" . ($data->status == 5 ? " class='blur-text'" : "") . ">" . ($data->booking_landtour->num_adult != 0 ? $data->booking_landtour->num_adult . " NL" : "") . ($data->booking_landtour->num_children != 0 ? " + " . $data->booking_landtour->num_children . " TE" : "") . ($data->booking_landtour->num_kid != 0 ? " + " . $data->booking_landtour->num_kid . " EB" : "") . "</p>" ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <p class="<?= $data->status == 5 ? "blur-text" : "" ?>"><?= number_format($data->price) ?><sup>đ</sup></p>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <p class="<?= $data->status == 5 ? "blur-text" : "" ?>">
                                                                <?php
                                                                if ($data->payment_method == AGENCY_PAY) {
                                                                    echo 0;
                                                                } elseif ($data->payment_method == MUSTGO_DEPOSIT) {
                                                                    echo number_format($data->mustgo_deposit);
                                                                }
                                                                ?>
                                                                <sup>đ</sup>
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <p class="<?= $data->status == 5 ? "blur-text" : "" ?>">
                                                                <?php
                                                                if ($data->payment_method == AGENCY_PAY) {
                                                                    echo 0;
                                                                } elseif ($data->payment_method == MUSTGO_DEPOSIT) {
                                                                    echo number_format($data->mustgo_deposit - $data->price);
                                                                }
                                                                ?>
                                                                <sup>đ</sup>
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-2"><a
                                                                class="detail text-center collapsed"
                                                                data-toggle="collapse"
                                                                data-target="#<?= $data->id ?>"
                                                                data-parent="#filter_result">
                                                                Chi tiết <span class="filter-dropdown-arrow"></span>
                                                            </a></div>
                                                        <div class="clear-fix"></div>
                                                    </div>
                                                    <div id="<?= $data->id ?>"
                                                         class="collapse filter-accordion-content p15 bg-light-grey">
                                                        <div class="row">
                                                            <div class="col-sm-16">
                                                                <div class="row">
                                                                    <div class="col-sm-10">
                                                                        <p class="semi-bold text-super-dark">CTV
                                                                            Cọc</p>
                                                                    </div>
                                                                    <div class="col-sm-26">
                                                                        Cọc <?= $data->customer_deposit / 1000000 ?>
                                                                        tr
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                </div>
                                                                <?php if ($data->status == 2): ?>
                                                                    <div class="row">
                                                                        <div class="col-sm-10">
                                                                            <p class="semi-bold text-super-dark">
                                                                                Thanh toán</p>
                                                                        </div>
                                                                        <div class="col-sm-26">
                                                                            <a href="<?= \Cake\Routing\Router::url(['_name' => 'booking.payment', 'code' => $data->code]) ?>"
                                                                               class="btn btn-success btn-xs">Thanh
                                                                                toán</a>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-sm-20">
                                                                <div class="row">
                                                                    <div class="col-sm-10">
                                                                        <p class="semi-bold text-super-dark">CTV
                                                                            TT</p>
                                                                    </div>
                                                                    <div class="col-sm-16">
                                                                        <?= ($data->agency_pay == 1) ? 'Rồi' : 'Chưa' ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-10">
                                                                        <p class="semi-bold text-super-dark">TT
                                                                            Khách sạn</p>
                                                                    </div>
                                                                    <div class="col-sm-10">
                                                                        <?= ($data->pay_hotel == 1) ? 'Rồi' : 'Chưa' ?>
                                                                    </div>
                                                                    <?php if ($data->status < 3): ?>
                                                                        <div class="form-group col-sm-16">
                                                                            <div class="pull-right">
                                                                                <?php
                                                                                echo $this->Form->postLink(__('Hủy đơn'), ['action' => 'denyBooking', 'controller' => 'Bookings', $data->id], ['confirm' => __('Bạn có chắc muốn hủy đơn hàng: {0}?', $data->code), 'class' => 'btn btn-danger']);
                                                                                ?>
                                                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'landtour.editBooking', 'code' => $data->code]) ?>"
                                                                                   class="btn btn-primary ">Sửa đơn
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="row mr0-i ml0-i mt10 mb10">
                                    <div class="col-sm-30">
                                        <p class="text-center bold text-light-blue">TỔNG LÃI</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-center semi-bold text-super-dark"><?= number_format($totalLandtourRevenue) ?>
                                            <sup>đ</sup></p>
                                    </div>

                                    <div class="clear-fix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
