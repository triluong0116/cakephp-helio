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
    <form id="hotelVinBookingForm">
        <input type="hidden" name="start_date" value="<?= $data['start_date'] ?>">
        <input type="hidden" name="end_date" value="<?= $data['end_date'] ?>">
        <input type="hidden" name="revenue" value="<?= $totalRevenue ?>">
        <input type="hidden" name="sale_revenue" value="<?= $totalSaleRevenue ?>">
        <input type="hidden" name="hotel_id" value="<?= $hotel->id ?>">
        <?php foreach ($data['vin_room'] as $vinKey => $vinRoom): ?>
            <input type="hidden" name="vin_room[<?= $vinKey ?>][num_adult]" value="<?= $vinRoom['num_adult'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][num_kid]" value="<?= $vinRoom['num_kid'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][num_child]" value="<?= $vinRoom['num_child'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][room_id]" value="<?= $vinRoom['id'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][name]" value="<?= $vinRoom['name'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][price]" value="<?= $vinRoom['price'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][package_id]" value="<?= $vinRoom['package_id'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][package_code]" value="<?= $vinRoom['code'] ?>">
            <input type="hidden" name="vin_room[<?= $vinKey ?>][rateplan_id]" value="<?= $vinRoom['rateplan_id'] ?>">
        <?php endforeach; ?>
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="row">
                    <div class="col-sm-24 bg-white">
                        <p class="fs18 text-main-blue mt20">Thông tin người đặt chỗ</p>
                        <dov class="row">
                            <div class="col-sm-12">
                                <p class="fs16">Đại lý</p>
                            </div>
                            <div class="col-sm-12">
                                <p class="fs16">Giảm giá Sale</p>
                            </div>
                            <div class="col-sm-12">
                                <p class="fs16">Giảm giá Đại lý</p>
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="hidden" value="<?= $this->request->getSession()->read('Auth.User.id') ?>" name="sale_id">
                                <select class="form-control popup-voucher select2" name="user_id">
                                    <option value="<?= $this->request->getSession()->read('Auth.User.id') ?>">Khách lẻ</option>
                                    <?php foreach ($listAgency as $agency): ?>
                                        <option value="<?= $agency->id ?>"><?= $agency->screen_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher inputmask-number" value="" name="sale_discount">
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher inputmask-number" value="" name="agency_discount">
                            </div>
                        </dov>
                        <p class="mt10 fs16">Danh xưng</p>
                        <span class="fs16 mb15 mr15"> Ông  <input type="radio" class="iCheck" name="gender" value="1"></span>
                        <span class="fs16 mb15 ml15 mr15"> Bà  <input type="radio" class="iCheck" name="gender" value="2"></span>
                        <span class="fs16 mb15 ml15 mr15"> Khác  <input type="radio" class="iCheck" name="gender" value="3"></span>
                        <div class="row mt20">
                            <div class="col-sm-24">
                                <p class="fs16">Họ và tên đoàn trưởng</p>
                            </div>
                            <div class="col-sm-12">
                                <p class="fs16">Số điện thoại</p>
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="first_name" placeholder="Tên">
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="sur_name" placeholder="Tên đệm">
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="phone" placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="col-sm-24">
                                <p class="fs16">Địa chỉ (Quốc gia, tỉnh, thành phố)</p>
                            </div>
                            <div class="col-sm-12">
                                <p class="fs16">Email</p>
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="nationality" placeholder="Nhập quốc tịch">
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="nation" placeholder="Nhập quốc gia">
                            </div>
                            <div class="col-sm-12 mt10">
                                <input type="text" class="form-control popup-voucher" name="email" placeholder="Email">
                            </div>
                            <div class="col-sm-36">
                                <hr class="mt40 mb10">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-36">
                                <p class="fs16 mt20 mb10">
                                    Thông tin thành viên trong đoàn
                                </p>
                            </div>
                            <div class="list-vin-information">
                                <?php foreach ($data['vin_room'] as $roomKey => $vinRoom): ?>
                                    <?php $totalPeople = $vinRoom['num_adult'] + $vinRoom['num_kid'] + $vinRoom['num_child'] ?>
                                    <div class="col-sm-36">
                                        <h3>Phòng <?= $roomKey + 1 ?></h3>
                                    </div>
                                    <?php for ($i = 0; $i < $totalPeople; $i++): ?>
                                        <div class="single-vin-information">
                                            <div class="col-sm-8 mt10">
                                                <p class="fs16 pull-right mt05">Thành viên <span class="vin-infor-index"></span>:</p>
                                            </div>
                                            <div class="col-sm-11 mt10">
                                                <input type="text" class="form-control popup-voucher" required name="vin_information[<?= $roomKey ?>][<?= $i ?>][name]" placeholder="Họ và tên">
                                            </div>
                                            <div class="col-sm-11 mt10">
                                                <input type='text' readonly="readonly" required name="vin_information[<?= $roomKey ?>][<?= $i ?>][birthday]" class="form-control popup-voucher date datepicker" placeholder="Ngày sinh"/>
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
                                <a class="accordion-toggle text-main-blue collapsed fs18 pull-right" data-toggle="collapse" data-parent="#accordion-term" href="#collapse-information">
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
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Phương tiện</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Mã trạm</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Mã chuyến bay</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Số người</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Ngày đến</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Giờ đón khách</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-36 mb10">
                                                <p>Ghi chú đón khách</p>
                                            </div>
                                            <div class="col-sm-36">
                                                <textarea class="form-control" rows="5" name="information"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-18">
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Yêu cầu tiễn khách</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Phương tiện</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Mã trạm</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Mã chuyến bay</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Số người</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Ngày đến</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-18 mt05">
                                                <p>Giờ đón khách</p>
                                            </div>
                                            <div class="col-sm-18">
                                                <input type="text" class="form-control popup-voucher">
                                            </div>
                                        </div>
                                        <div class="row mt10">
                                            <div class="col-sm-36 mb10">
                                                <p>Ghi chú tiễn khách</p>
                                            </div>
                                            <div class="col-sm-36">
                                                <textarea class="form-control" rows="5" name="information"></textarea>
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
                                            <p class="semi-bold mt10">Vinpearl Resort & Spa Hạ Long</p>
                                            <p class="w100 mt10 fs12"><span><?= date('d/m/Y', strtotime($data['start_date'])) ?></span> - <span><?= date('d/m/Y', strtotime($data['end_date'])) ?></span> <span class="pull-right semi-bold"><?= $data['date_diff']->days + 1 ?> ngày <?= $data['date_diff']->days ?> đêm</span></p>
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
                                                                <?php foreach ($data['vin_room'] as $roomKey => $room): ?>
                                                                    <div class="single-room-detail">
                                                                        <div class="col-sm-20 mt10">
                                                                            <p class="fs14">Phòng <?= $roomKey + 1 ?>: <?= $room['name'] ?></p>
                                                                        </div>
                                                                        <div class="col-sm-16 mt10">
                                                                            <p class="pull-right fs14"><?= $room['price'] ?> VNĐ</p>
                                                                        </div>
                                                                        <div class="col-sm-36">
                                                                            <p>Gói: <?= $room['code'] ?></p>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer-room-information row">
                                            <div class="col-sm-36">
                                                <p class="pull-right">
                                                    Tổng cộng
                                                </p>
                                            </div>
                                            <div class="col-sm-36">
                                                <p class="pull-right text-orange fs24 semi-bold">
                                                    <?= number_format($totalPrice) ?> VNĐ
                                                </p>
                                            </div>
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
                            <button type="button" class="btn w100 btn-payment text-uppercase" id="vinBookingPayment">Đặt phòng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
