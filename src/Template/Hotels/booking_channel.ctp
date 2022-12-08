<?php

use App\View\Helper\SystemHelper;

?>
<style>
    .panel-heading.panel-price-heading {
        padding: 10px;
    }

    .panel-body.panel-price-body {
        background-color: #f5f5f5 !important;
        border-top: none !important;
        padding-top: 0px !important;
    }

    .panel.panel-price {
        border: none;
    }
</style>
<div class="bg-grey" xmlns="http://www.w3.org/1999/html">
    <div class="container pc">
        <div class="col-sm-36 mt30">
            <ul id="progress">
                <li class="active text-left">1. Điền thông tin đặt hàng</li>
                <li class="text-center ">2. Thanh toán</li>
                <li class="text-right">3. Hoàn tất</li>
            </ul>
        </div>
    </div>
    <div class="container sp">
        <div class="row">
            <div class="col-xs-36 mt30">
                <ul id="progress">
                    <li class="active text-center"><span class="booking-progress-text">1. Điền thông tin</span></li>
                    <li class="text-center"><span class="booking-progress-text">2. Thanh toán</span></li>
                    <li class="text-center"><span class="booking-progress-text">3. Hoàn tất</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container pb40 pb10-sp">
        <div class="combo-detail-title mt50 mt10-sp text-center">
            <span class="semi-bold box-underline-center fs24 pb05-sp pb20">THÔNG TIN ĐẶT PHÒNG</span>
        </div>
    </div>
    <?= $this->Form->create(null, ['id' => 'hotelChannelBookingForm']) ?>
    <!--    <form id="hotelVinBookingForm">-->
    <input type="hidden" name="start_date" value="<?= $data['fromDate'] ?>">
    <input type="hidden" name="end_date" value="<?= $data['end_date'] ?>">
    <input type="hidden" name="revenue" value="<?= $totalRevenue ?>">
    <input type="hidden" name="sale_revenue" value="<?= $totalSaleRevenue ?>">
    <input type="hidden" name="hotel_id" value="<?= $hotel->id ?>">
    <input type="hidden" name="channel_booking_type" value="<?= $data['channel_booking_type'] ?>">
    <?php if ($data['channel_booking_type'] == 1): ?>
        <?php foreach ($data['channel_room'] as $vinKey => $vinRoom): ?>
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_adult]" value="<?= $vinRoom['num_adult'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_kid]" value="<?= $vinRoom['num_kid'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_child]" value="<?= $vinRoom['num_child'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][room_id]" value="<?= $vinRoom['id'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][name]" value="<?= $vinRoom['name'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][price]" value="<?= $vinRoom['price'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][default_price]" value="<?= $vinRoom['default_price'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][package_id]" value="<?= $vinRoom['package_id'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][package_code]" value="<?= $vinRoom['code'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][package_name]" value="<?= $vinRoom['package_name'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][rateplan_id]" value="<?= $vinRoom['rateplan_id'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][allotment_id]" value="<?= $vinRoom['allotment_id'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][room_type_code]" value="<?= $vinRoom['room_type_code'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][rateplan_code]" value="<?= $vinRoom['rateplan_code'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][revenue]" value="<?= $vinRoom['revenue'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][sale_revenue]" value="<?= $vinRoom['sale_revenue'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][date_range]" value='<?= $vinRoom['date_range'] ?>'>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($data['channel_room'] as $vinKey => $vinRoom): ?>
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_adult]" value="<?= $vinRoom['num_adult'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_kid]" value="<?= $vinRoom['num_kid'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][num_child]" value="<?= $vinRoom['num_child'] ?>">
            <input type="hidden" name="channel_room[<?= $vinKey ?>][name]" value="<?= $vinRoom['name'] ?>">
            <?php foreach ($vinRoom['package'] as $pK => $package): ?>
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][room_id]" value="<?= $package['room_key'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][price]" value="<?= $package['package_pice'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][default_price]" value="<?= $package['default_price'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][package_id]" value="<?= $package['package_id'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][package_code]" value="<?= $package['package_code'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][package_name]" value="<?= $package['package_name'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][rateplan_id]" value="<?= $package['rateplan_id'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][allotment_id]" value="<?= $package['allotment_id'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][room_type_code]" value="<?= $package['room_type_code'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][rateplan_code]" value="<?= $package['rateplan_code'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][start_date]" value="<?= $package['start_date'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][end_date]" value="<?= $package['end_date'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][revenue]" value="<?= $package['revenue'] ?>">
                <input type="hidden" name="channel_room[<?= $vinKey ?>][package][<?= $pK ?>][sale_revenue]" value="<?= $package['sale_revenue'] ?>">
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="combo-detail pb50">
        <div class="container ">
            <div class="row">
                <div class="col-sm-24 bg-white">
                    <p class="fs18 text-main-blue mt20">Thông tin người đặt chỗ</p>
                    <input type="hidden" name="sale_id" value="<?= $user ? $user->parent_id : 0 ?>">
                    <input type="hidden" name="user_id" value="<?= $user ? $user->id : 0 ?>">
                    <p class="mt10 fs16">Danh xưng</p>
                    <span class="fs16 mb15 mr15"> Ông  <input type="radio" class="iCheck" name="gender" value="1"></span>
                    <span class="fs16 mb15 ml15 mr15"> Bà  <input type="radio" class="iCheck" name="gender" value="2"></span>
                    <span class="fs16 mb15 ml15 mr15"> Khác  <input type="radio" class="iCheck" name="gender" value="3"></span>
                    <div class="row mt20">
                        <div class="col-sm-24">
                            <p class="fs16">Họ và tên đoàn trưởng</p>
                            <div class="row">
                                <div class="col-sm-18 mt10">
                                    <input type="text" class="form-control popup-voucher" name="first_name" placeholder="Tên">
                                    <p id="error_first_name" class="error-messages"></p>
                                </div>
                                <div class="col-sm-18 mt10">
                                    <input type="text" class="form-control popup-voucher" name="sur_name" placeholder="Tên đệm">
                                    <p id="error_sur_name" class="error-messages"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <p class="fs16 mt10-sp">Số điện thoại</p>
                            <div class="row">
                                <div class="col-sm-36 mt10">
                                    <input type="text" class="form-control popup-voucher" name="phone" placeholder="Số điện thoại" >
                                    <p id="error_phone" class="error-messages"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt20">
                        <div class="col-sm-24">
                            <p class="fs16 ">Địa chỉ (Quốc gia, tỉnh, thành phố)</p>
                            <div class="row">
                                <div class="col-sm-18 mt10">
                                    <input type="text" class="form-control popup-voucher" name="nationality" placeholder="Nhập quốc tịch">
                                </div>
                                <div class="col-sm-18 mt10">
                                    <input type="text" class="form-control popup-voucher" name="nation" placeholder="Nhập quốc gia">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <p class="fs16 mt10-sp">Email</p>
                            <div class="row">
                                <div class="col-sm-36 mt10">
                                    <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                        <input type="text" class="form-control popup-voucher" name="email" placeholder="Email" value="<?= $this->request->getSession()->read('Auth.User.email') ?>">
                                    <?php else: ?>
                                        <input type="text" class="form-control popup-voucher" name="email" placeholder="Email">
                                    <?php endif; ?>
                                    <p id="error_email" class="error-messages"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-36">
                            <hr class="mt40 mb10">
                        </div>
                    </div>
                    <div class="row mt20">
                        <div class="col-sm-36">
                            Lưu ý
                        </div>
                        <div class="col-sm-36">
                            <textarea class="form-control popup-voucher" name="note" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-36">
                            <p class="fs16 mt20 mb10">
                                Thông tin thành viên trong đoàn
                            </p>
                        </div>
                        <div class="list-vin-information">
                            <?php foreach ($data['channel_room'] as $roomKey => $vinRoom): ?>
                                <?php $totalPeople = $vinRoom['num_adult'] + $vinRoom['num_kid'] + $vinRoom['num_child'] ?>
                                <div class="col-sm-36">
                                    <h3>Phòng <?= $roomKey + 1 ?></h3>
                                </div>
                                <?php for ($i = 0; $i < $totalPeople; $i++): ?>
                                    <div class="single-vin-information">
                                        <div class="col-sm-8 mt10">
                                            <p class="fs16 pull-right-pc mt05">Thành viên <span class="vin-infor-index"></span>:</p>
                                        </div>
                                        <div class="col-sm-11 mt10">
                                            <input type="text" class="form-control popup-voucher" required name="channel_information[<?= $roomKey ?>][<?= $i ?>][name]" placeholder="Họ và tên">
                                        </div>
                                        <div class="col-sm-11 mt10">
                                            <input type='text' readonly="readonly" required name="channel_information[<?= $roomKey ?>][<?= $i ?>][birthday]" class="form-control popup-voucher date datepicker" placeholder="Ngày sinh"/>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-sm-36">
                            <hr class="mt40 mb10">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-18">
                            <span class="fs18 text-main-blue mt20">Thông tin đưa đón</span>
                        </div>
                        <div class="col-sm-18">
                            <a class="accordion-toggle text-main-blue collapsed fs18 pull-right-pc" data-toggle="collapse" data-parent="#accordion-term" href="#collapse-information">
                                Ẩn thông tin đưa đón
                            </a>
                        </div>
                    </div>
                    <div id="collapse-information" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row mt10">
                                <p>Phí dịch vụ đón/tiễn sân bay áp dụng theo quy trình của từng khách sạn. Thông tin chi tiết vui lòng liên hệ Bộ phận Đặt phòng</p>
                                <div class="col-sm-18">
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Yêu cầu đón khách</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <select name="pickup[order]" class="form-control popup-voucher">
                                                <option value="0">NO</option>
                                                <option value="1">YES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Phương tiện</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <select name="pickup[transportation]" class="form-control popup-voucher">
                                                <option value="Bus">Bus</option>
                                                <option value="Car">Car</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Mã trạm</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="pickup[station_code]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Mã chuyến bay</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="pickup[flight_code]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Số người</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="pickup[num_people]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Ngày đến</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="pickup[date]" class="form-control popup-voucher datepicker">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Giờ đón khách</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="pickup[time]" class="form-control popup-voucher timepicker">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-36 mb10">
                                            <p>Ghi chú đón khách</p>
                                        </div>
                                        <div class="col-sm-36">
                                            <textarea class="form-control" rows="5" name="pickup[note]"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-18">
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Yêu cầu tiễn khách</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <select name="dropdown[order]" class="form-control popup-voucher">
                                                <option value="0">NO</option>
                                                <option value="1">YES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Phương tiện</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <select name="dropdown[transportation]" class="form-control popup-voucher">
                                                <option value="Bus">Bus</option>
                                                <option value="Car">Car</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Mã trạm</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="dropdown[station_code]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Mã chuyến bay</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="dropdown[flight_code]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Số người</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="dropdown[num_people]" class="form-control popup-voucher">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Ngày đến</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="dropdown[date]" class="form-control popup-voucher datepicker">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-18 mt05">
                                            <p>Giờ đón khách</p>
                                        </div>
                                        <div class="col-sm-18">
                                            <input type="text" name="dropdown[time]" class="form-control popup-voucher timepicker">
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-36 mb10">
                                            <p>Ghi chú tiễn khách</p>
                                        </div>
                                        <div class="col-sm-36">
                                            <textarea class="form-control" rows="5" name="dropdown[note]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="bg-white">
                                <div class="p10">
                                    <div class="information-header">
                                        <p class="text-main-blue">Thông tin đặt phòng</p>
                                        <p class="semi-bold mt10"><?= $hotel->name ?></p>
                                        <p class="w100 mt10 fs12"><span><?= date('d/m/Y', strtotime($data['fromDate'])) ?></span> - <span><?= date('d/m/Y', strtotime($data['end_date'])) ?></span> <span class="pull-right semi-bold"><?= $data['date_diff']->days + 1 ?> ngày <?= $data['date_diff']->days ?> đêm</span></p>
                                        <p class="mt05 fs12"><?= $data['num_adult'] ?> Người lớn, <?= $data['num_child'] ?> trẻ em, <?= $data['num_kid'] ?> em bé</p>
                                        <p class="mt05 fs12"><?= $data['num_room'] ?> phòng</p>
                                    </div>
                                    <div class="detail-room-information mt10">
                                        <div class="panel-group" id="accordion-term">
                                            <div class="panel panel-price panel-default">
                                                <div class="panel-heading panel-price-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapsed semi-bold fs16" data-toggle="collapse" data-parent="#accordion-term" href="#collapseTerm">
                                                            Thông tin phòng
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTerm" class="panel-collapse collapse">
                                                    <div class="panel-body panel-price-body">
                                                        <div class="row">
                                                            <?php if ($data['channel_booking_type'] == 1): ?>
                                                                <?php foreach ($data['channel_room'] as $roomKey => $room): ?>
                                                                    <div class="single-room-detail col-sm-36">
                                                                        <div class="row">
                                                                            <div class="col-sm-20 mt10">
                                                                                <p class="fs14 bold">Phòng <?= $roomKey + 1 ?>: <?= $room['name'] ?></p>
                                                                            </div>
                                                                            <div class="col-sm-16 mt10">
                                                                                <p class="pull-right fs14 bold"><?= $room['price'] ?> VNĐ</p>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p>Gói: <?= $room['code'] ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <?php foreach ($data['channel_room'] as $roomKey => $room): ?>
                                                                    <div class="single-room-detail col-sm-36">
                                                                        <div class="row">
                                                                            <div class="col-sm-20 mt10">
                                                                                <p class="fs14 bold">Phòng <?= $roomKey + 1 ?>: <?= $room['name'] ?></p>
                                                                            </div>
                                                                            <?php
                                                                             dd($data);
                                                                            $roomTotalPrice = 0;
                                                                            foreach ($room['package'] as $pK => $package) {
                                                                                $roomTotalPrice += $package['default_price'] + $package['revenue'];
                                                                            }
                                                                            ?>
                                                                            <div class="col-sm-16 mt10">
                                                                                <p class="pull-right fs14 bold"><?= number_format($roomTotalPrice) ?> VNĐ</p>
                                                                            </div>
                                                                            <?php foreach ($room['package'] as $pK => $package): ?>
                                                                                <div class="col-sm-20">
                                                                                    <p>Gói: <?= $package['package_code'] ?></p>
                                                                                </div>
                                                                                <div class="col-sm-16">
                                                                                    <p class="pull-right"><?= date('d/m', strtotime($package['start_date'])) . ' - ' . date('d/m', strtotime($package['end_date'])) ?></p>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer-room-information row">
                                        <div class="col-sm-36 ">
                                            <p class="pull-right">
                                                Tổng cộng
                                            </p>
                                        </div>
                                        <div class="col-sm-36 ">
                                            <p class="pull-right text-orange fs24 semi-bold">
                                                <?= number_format($totalPrice) ?>  <?= $data['currency'] ?>
                                            </p>
                                        </div>
                                        <?php if ($this->request->session()->read('Auth.User.role_id') == 3): ?>
                                            <div class="col-sm-36 mt10 ">
                                                <p class="pull-right">
                                                    Đại Lý phải thanh toán
                                                </p>
                                            </div>
                                            <div class="col-sm-36 ">
                                                <p class="pull-right text-orange fs24 semi-bold">
                                                    <?= number_format($totalPrice ) ?>  <?= $data['currency'] ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-sm-36">
                                            <p class="pull-right fs13">(Giá đã bao gồm phí dịch vụ và thuế GTGT)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-24 mt20 no-pad-right no-pad-left">
                    <div class="w100">
                        <button type="button" class="btn w100 btn-payment text-uppercase" id="channelBookingPayment">Đặt phòng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    </form>-->
    <?php $this->Form->end() ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
