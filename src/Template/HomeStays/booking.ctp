<?php

?>
<div class="bg-grey" xmlns="http://www.w3.org/1999/html">
    <div class="container pc">
        <div class="col-sm-36 mt30">
            <ul id="progress">
                <li class="active text-left">1. Điền thông tin đặt hàng</li>
                <li class="text-center">2. Thanh toán</li>
                <li class="text-right">3. Hoàn tất</li>
            </ul>
        </div>
    </div>
    <div class="container sp">
        <div class="row">
            <div class="col-xs-36 mt30">
                <ul id="progress">
                    <li class="active text-center"><span class="fs15-sp">1. Điền thông tin</span></li>
                    <li class="text-center"><span class="fs15-sp">2. Thanh toán</span></li>
                    <li class="text-center"><span class="fs15-sp">3. Hoàn tất</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container pb40">
        <div class="combo-detail-title mt50 text-center">
            <span class="semi-bold box-underline-center fs24 pb05-sp pb20">THÔNG TIN ĐẶT PHÒNG</span>
        </div>
    </div>
    <form id="homeStayBookingForm">
        <input type="hidden" class="booking-value" name="revenue" value="">
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="bg-white p15">
                    <input type="hidden" name="item_id" value="<?= $homeStay->id ?>">
                    <div class="row mt15">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 mb15">Họ và tên đoàn trưởng<span
                                            class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="full_name" placeholder="Họ và tên"
                                       name="full_name">
                                <p id="error_full_name" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 mb15">Số điện thoại trưởng đoàn<span
                                            class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="phone" placeholder="Số điện thoại"
                                       name="phone">
                                <p id="error_phone" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 mb15">Email<span class="text-grey fs12"></span></p>
                                <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                    <input class="form-control popup-voucher" placeholder="Email" name="email"
                                           value="<?= $this->request->getSession()->read('Auth.User.email') ?>">
                                <?php else: ?>
                                    <input class="form-control popup-voucher" placeholder="Email" name="email"
                                           required="required">
                                <?php endif; ?>
                                <p id="error_email" class="error-messages"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-sm-18 col-xs-18">
                            <span class="text-super-dark fs14">Ngày Check in</span>
                            <div class='input-group date datepicker mt15 homestay' id="start-date-picker">
                                            <span class="input-group-addon">
                                                <span class="far fa-calendar-alt main-color"></span>
                                            </span>
                                <input type='text' name="start_date" class="form-control popup-voucher"
                                       value="<?= $fromDate ?>"/>
                            </div>
                            <p id="error_start_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-18 col-xs-18">
                            <span class="text-super-dark fs14">Ngày Check out</span>
                            <div class='input-group date datepicker mt15 homestay' id="end-date-picker">
                                            <span class="input-group-addon">
                                                <span class="far fa-calendar-alt main-color"></span>
                                            </span>
                                <input type='text' name="end_date" class="form-control popup-voucher border-blue"
                                       value="<?= $toDate ?>"/>
                            </div>
                            <p id="error_end_date" class="error-messages"></p>
                        </div>
                        <p id="error_date" class="error-messages"></p>
                    </div>
                    <div class="row mt10">
                        <div class="col-sm-36">
                            <div class="form-group">
                                <p class="text-super-dark fs14 mb15">Yêu cầu thêm<span class="text-grey fs12"></span></p>
                                <textarea class="form-control" rows="5" name="other"></textarea>
                            </div>
                        </div>
                        <p id="error_other" class="error-messages"></p>
                    </div>
                </div>
            </div>
            <!-- BOOKING CHO KHACH -->
            <?php if ($this->request->getSession()->read('Auth.User.role_id') == 3 && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                <div class="container">
                    <div class="row mt20 row-eq-height">
                        <div class="col-sm-18 col-xs-36 flex">
                            <div class="bg-white p10 full-width ">
                                <p class="text-super-dark fs14" id="booking_result"></p>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-18 mt10-sp text-center flex">
                            <div class="bg-white p10 full-width ">
                                <p class="text-super-dark fs14 mb5">Giá tiền</p>
                                <p class="text-super-dark fs14"><span id="total_booking_price"></span></p>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-18 mt10-sp text-center flex">
                            <div class="bg-white p10 full-width">
                                <p class="text-super-dark fs14 mb5">Giá tiền lãi</p>
                                <p class="text-super-dark fs14"><span id="total_booking_profit"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt20">
                        <div class="col-sm-27">
                            <div class="bg-white p05">
                                <p class="text-super-dark fs14 mb5">Hình thức thanh toán</p>
                                <div class="form-group row ml20 mt10">
                                    <p class="fs12 pc">
                                        <input type="radio" class="iCheck payment-method-check" value="<?= CUSTOMER_PAY ?>" name="payment_method"> Khách hàng thanh toán trực tiếp cho Mustgo</i>
                                        <span class="fs12 ml20"><input type="radio" class="iCheck payment-method-check" value="<?= AGENCY_PAY ?>" name="payment_method">  Đại lý thu hộ cho Mustgo</i></span
                                    </p>
                                    <div class="sp">
                                        <div>
                                            <input type="radio" class="iCheck payment-method-check" value="<?= CUSTOMER_PAY ?>" name="payment_method"> Khách hàng thanh toán trực tiếp cho Mustgo</i>
                                        </div>
                                        <div>
                                            <input type="radio" class="iCheck payment-method-check" value="<?= AGENCY_PAY ?>" name="payment_method">  Đại lý thu hộ cho Mustgo</i>
                                        </div>
                                    </div>
                                    <p id="error_payment_method" class="error-messages"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-request-booking btn-block semi-bold text-white full-height" id="requestHomeStayBooking">
                                <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="container">
                    <div class="row mt20 row-eq-height">
                        <div class="col-sm-16 flex">
                            <div class="bg-white p10 full-width ">
                                <p class="text-super-dark fs14" id="booking_result"></p>
                            </div>
                        </div>
                        <div class="col-sm-11 text-center flex">
                            <div class="bg-white p10 full-width ">
                                <p class="text-super-dark fs14 mb5">Giá tiền</p>
                                <p class="text-super-dark fs14"><span id="total_booking_price"></span></p>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="text-white">
                                <button type="button" class="btn btn-request-booking btn-block semi-bold text-white full-height" id="requestHomeStayBooking">
                                    <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </form>
</div>
