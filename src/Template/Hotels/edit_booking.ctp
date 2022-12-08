<?php

use App\View\Helper\SystemHelper;

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
    <form id="hotelBookingForm">
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="bg-white p15">
                    <input type="hidden" name="hotel_id" value="<?= $booking->item_id ?>">
                    <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                    <div id="list-room-booking">
                        <p id="error_incorrect_info" class="error-messages"></p>
                        <?php foreach ($booking->booking_rooms as $key => $bookingRoom): ?>
                            <input type="hidden" name="booking_rooms[<?= $key ?>][id]" value="<?= $bookingRoom->id ?>">
                            <fieldset class="booking-room-item" style="position: relative">
                                <legend class="pc">Hạng Phòng</legend>
                                <p class="sp text-center ">Hạng phòng</p>
                                <a class="fieldset-close-button sp" href="#"
                                   onclick="Frontend.deleteItem(this, '.booking-room-item');">
                                    <i class="text-danger fas fa-times"></i>
                                </a>
                                <div class="col-sm-offset-34 col-sm-2 col-sm-2 col-xs-offset-32 col-xs-4 text-right pc">
                                    <a href="#" onclick="Frontend.deleteItem(this, '.booking-room-item');" class="mt10">
                                        <i class="text-danger fas fa-times"></i>
                                    </a>
                                </div>
                                <div class="row mt30 mt05-sp">
                                    <div class="col-sm-22 col-xs-36">
                                        <div class="form-group mb05-sp">
                                            <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp pc">Hạng phòng<span
                                                    class="text-grey fs12"></span></p>
                                            <select class="form-control popup-voucher" id="agency_room"
                                                    name="booking_rooms[<?= $key ?>][room_id]">
                                                <?php foreach ($rooms as $k => $val): ?>
                                                    <option
                                                        value="<?= $k ?>" <?= $bookingRoom->room_id == $k ? 'selected' : '' ?>><?= $val ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p id="error_booking_rooms_<?= $key ?>_room_id" class="error-messages"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-14 col-xs-36">
                                        <div class="form-group mb05-sp">
                                            <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Số phòng<span
                                                    class="text-grey fs12"></span></p>
                                            <!--                                            <input class="form-control popup-voucher inputmask-number" type="text" id="room_amount" placeholder="Số Phòng" name="booking_rooms[0][num_room]" value="">-->
                                            <select class="form-control popup-voucher select-no-arrow"
                                                    name="booking_rooms[<?= $key ?>][num_room]">
                                                <?php for ($i = 1; $i <= 50; $i++): ?>
                                                    <option
                                                        value="<?= $i ?>" <?= $bookingRoom->num_room == $i ? 'selected' : '' ?>><?= $i ?>
                                                        Phòng
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                            <p id="error_booking_rooms_0_num_room" class="error-messages"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt10-pc mt05-sp">
                                    <div class="col-sm-18 col-xs-18">
                                        <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Ngày Checkin</p>
                                        <div class='input-group date datepicker room-booking-sDate'>
                                        <span class="input-group-addon"><span
                                                class="far fa-calendar-alt main-color"></span></span>
                                            <input type='text' readonly="readonly"
                                                   name="booking_rooms[<?= $key ?>][start_date]"
                                                   class="form-control popup-voucher" placeholder="Thời gian đi"
                                                   value="<?= date_format($bookingRoom->start_date, 'd-m-Y') ?>"/>
                                        </div>
                                        <p id="error_booking_rooms_0_start_date" class="error-messages"></p>
                                    </div>
                                    <div class="col-sm-18 col-xs-18">
                                        <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Ngày Checkout</p>
                                        <div class='input-group date datepicker room-booking-eDate'>
                                        <span class="input-group-addon"><span
                                                class="far fa-calendar-alt main-color"></span></span>
                                            <input type='text' readonly="readonly"
                                                   name="booking_rooms[<?= $key ?>][end_date]"
                                                   class="form-control popup-voucher" placeholder="Thời gian về"
                                                   value="<?= date_format($bookingRoom->end_date, 'd-m-Y') ?>"/>
                                        </div>
                                        <p id="error_booking_rooms_0_end_date" class="error-messages"></p>
                                    </div>
                                </div>
                                <div class="row mt10-pc mt05-sp">
                                    <div class="col-sm-18 col-xs-36">
                                        <div class="row">
                                            <div class="col-sm-18 col-xs-18">
                                                <div class="form-group mb05-sp">
                                                    <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Số người
                                                        lớn<span
                                                            class="text-grey fs12"></span></p>
                                                    <select class="form-control popup-voucher select-no-arrow"
                                                            name="booking_rooms[<?= $key ?>][num_adult]">
                                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                                            <option
                                                                value="<?= $i ?>" <?= $bookingRoom->num_adult == $i ? 'selected' : '' ?>><?= $i ?>
                                                                Người lớn
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <p id="error_booking_rooms_<?= $key ?>_num_adult"
                                                       class="error-messages"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-18 col-xs-18">
                                                <div class="form-group mb05-sp">
                                                    <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Số trẻ em<span
                                                            class="text-grey fs12"></span></p>
                                                    <!--                                                    <input class="form-control popup-voucher inputmask-number booking-num-child" type="text" placeholder="Số trẻ em" name="booking_rooms[0][num_children]" value="">-->
                                                    <select
                                                        class="form-control popup-voucher select-no-arrow booking-num-child"
                                                        name="booking_rooms[<?= $key ?>][num_children]">
                                                        <?php for ($i = 0; $i <= 50; $i++): ?>
                                                            <option
                                                                value="<?= $i ?>" <?= $bookingRoom->num_children == $i ? 'selected' : '' ?>><?= $i ?>
                                                                Trẻ em
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <p id="error_booking_rooms_<?= $key ?>_num_children"
                                                       class="error-messages"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-36">
                                                <input type="hidden" name="booking_rooms[<?= $key ?>][num_people]">
                                                <p id="error_booking_rooms_0_num_people" class="error-messages"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-18 col-xs-36">
                                        <div class="row">
                                            <p class="text-super-dark fs14 fs12-sp ml15 mb15 mb05-sp">Tuổi của các
                                                bé<span
                                                    class="text-grey fs12"></span></p>
                                            <input type="hidden" name="booking_rooms[<?= $key ?>][num_child_error]">
                                            <p id="error_booking_rooms_<?= $key ?>_num_child_error"
                                               class="error-messages"></p>
                                            <div class="list-child-age">
                                                <?php for ($i = 0; $i < $bookingRoom->num_children; $i++): ?>
                                                    <?php
                                                    $jsonAge = json_decode($bookingRoom->child_ages, true);
                                                    ?>
                                                    <div class="col-sm-9 col-xs-9 no-pad-left-sp">
                                                        <div class="form-group vertical-center mb05-sp">
                                                            <p class="col-sm-4 pc text-center col-form-label"><?= ($i + 1) ?></p>
                                                            <div class="col-sm-28 col-xs-36">
                                                                <select
                                                                    class="form-control popup-voucher children-age-selector select-no-arrow"
                                                                    name="booking_rooms[<?= $key ?>][child_ages][]">
                                                                    <?php for ($j = 0; $j <= 18; $j++): ?>
                                                                        <option
                                                                            value="<?= $j ?>" <?= $jsonAge && $jsonAge[$i] == $j ? 'selected' : '' ?>><?= $j ?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                            <input type="hidden" name="booking_rooms[<?= $key ?>][num_child_error]">
                                            <p id="error_booking_rooms_0_num_child_error" class="error-messages"></p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="booking-value room-price" name="booking-room">
                            </fieldset>
                        <?php endforeach; ?>
                    </div>

                    <div class="row mt10">
                        <div class="col-sm-6 text-white">
                            <a class="btn btn-submit" id="btn-add-room" href="#">
                                <i class="fas fa-spinner fa-pulse hidden"></i>
                                <span class="fs18">Thêm hạng phòng</span>
                                <br/>
                            </a>
                        </div>
                    </div>
                    <div class="row mt15">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Họ và tên đoàn trưởng<span
                                        class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="full_name" placeholder="Họ và tên"
                                       name="full_name" value="<?= $booking->full_name ?>">
                                <p id="error_full_name" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Số điện thoại trưởng đoàn<span
                                        class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="phone" placeholder="Số điện thoại"
                                       name="phone" value="<?= $booking->phone ?>">
                                <p id="error_phone" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Email<span class="text-grey fs12"></span>
                                </p>
                                <input class="form-control popup-voucher" placeholder="Email" name="email"
                                       required="required" value="<?= $booking->email ?>">
                                <p id="error_email" class="error-messages"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt15">
                        <div class="col-sm-18 col-xs-18 text-left">
                            <div class="ml15">
                                <h4 class="text-left fs16-sp">Phụ thu</h4>
                            </div>
                        </div>
                        <div class="col-sm-18 col-xs-18 text-right">
                            <div class="mr15">
                                <h4 class="text-right fs16-sp">Giá phụ thu</h4>
                            </div>
                        </div>
                    </div>
                    <hr class="mb15">
                    <div id="booking-list-surcharges">
                        <div id="list-auto-surcharge">
                            <?php dd($arr_booking_surcharges) ?>
                            <?php foreach ($autoSurcharges as $key => $surcharge): ?>
                                <div class="normal-surcharge-item">
                                    <div class="row ml15 mt15 mb15 mr15 ml0-sp mr0-sp">
                                        <input type="hidden" name="booking_surcharges[<?= $key ?>][surcharge_type]"
                                               value="<?= $surcharge['surcharge_type'] ?>">
                                        <input type="hidden" name="booking_surcharges[<?= $key ?>][id]"
                                               value="<?= $surcharge['id'] ?>">
                                        <input type="hidden" name="booking_surcharges[<?= $key ?>][price]" value=""
                                               id="<?= SystemHelper::getSurchargeId($surcharge['surcharge_type'], $surcharge['other_slug']) ?>"
                                               class="booking-value">
                                        <div class="col-sm-18 col-xs-18 text-left">
                                            <p class="fs16 fs12-sp"><i data-toggle="tooltip"
                                                                       title="<?= $surcharge['description'] ?>"><?= SystemHelper::getSurchargeName($surcharge['surcharge_type']) ?></i>
                                            </p>
                                        </div>
                                        <div class="col-sm-18 col-xs-18 text-right">
                                            <p class="fs16 main-color fs12-sp"
                                               id="<?= SystemHelper::getSurchargeId($surcharge['surcharge_type'], $surcharge['other-slug'], false) ?>"><?= number_format($surcharge['fee'], false) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="list-normal-surcharge">
                            <?php foreach ($normalSurcharges as $key => $surcharge): ?>
                                <div class="normal-surcharge-item">
                                    <div class="row ml15 mr15 mb15 mt15 ml0-sp mr0-sp vertical-center">
                                        <div class="col-sm-12 col-xs-12 text-left">
                                            <input type="hidden" name="booking_surcharges[<?= $key ?>][id]"
                                                   value="<?= $surcharge->id ?>">
                                            <p class="fs16 fs12-sp">
                                                <input type="checkbox" class="iCheck surcharge-check"
                                                       name="booking_surcharges[<?= $key ?>][surcharge_type]"
                                                       value="<?= $surcharge->surcharge_type ?>" <?= isset($arr_booking_surcharges[$surcharge->surcharge_type]) ? 'checked' : '' ?>>
                                                <i data-toggle="tooltip" title="<?= $surcharge['description'] ?>">
                                                    <?php if ($surcharge->surcharge_type != SUR_OTHER): ?>
                                                        <?= SystemHelper::getSurchargeName($surcharge->surcharge_type) ?>
                                                    <?php else: ?>
                                                        <?= $surcharge->other_name ?>
                                                    <?php endif; ?>
                                                </i>
                                            </p>
                                            <input type="hidden" name="booking_surcharges[<?= $key ?>][surcharge_type]"
                                                   value="<?= $surcharge->surcharge_type ?>">
                                        </div>
                                        <div class="col-sm-12 col-xs-16">
                                            <div class="surcharge-normal-quantity" <?= isset($arr_booking_surcharges[$surcharge->surcharge_type]) ? 'style="display: block"' : '' ?>>
                                                <div class="form-group">
                                                    <?php if ($surcharge->surcharge_type == SUR_CHECKIN_SOON || $surcharge->surcharge_type == SUR_CHECKOUT_LATE): ?>
                                                        <div class="row vertical-center">
                                                            <div class="col-sm-18 col-xs-10">
                                                                <p class="text-right col-form-label fs12-sp">Giờ</p>
                                                            </div>
                                                            <div class="col-sm-18 col-xs-26">
                                                                <div class='input-group date timepicker'>
                                                                    <input type='text'
                                                                           class="form-control popup-voucher normal-surcharge-value"
                                                                           name="booking_surcharges[<?= $key ?>][quantity]" value="<?= isset($arr_booking_surcharges[$surcharge->surcharge_type]) ? $arr_booking_surcharges[$surcharge->surcharge_type]['quantity'] : '' ?>">
                                                                    <span class="input-group-addon"><span
                                                                            class="glyphicon glyphicon-time"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="row vertical-center">
                                                            <div class="col-sm-18 col-xs-10">
                                                                <p class="text-right col-form-label fs12-sp">Số lượng</p>
                                                            </div>
                                                            <div class="col-sm-18 col-xs-26">
                                                                <select
                                                                    class="form-control popup-voucher normal-surcharge-value"
                                                                    id="amount"
                                                                    name="booking_surcharges[<?= $key ?>][quantity]">
                                                                    <option value=""></option>
                                                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                                                        <option value="<?= $i ?>" <?= isset($arr_booking_surcharges[$surcharge->surcharge_type]) && $arr_booking_surcharges[$surcharge->surcharge_type]['quantity'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xs-8 text-right">
                                            <input type="hidden" class="booking-value surcharge-price"
                                                   id="<?= SystemHelper::getSurchargeId($surcharge['surcharge_type'], $surcharge['other_slug']) ?>"
                                                   name="booking_surcharges[<?= $key ?>][price]"
                                                   value="<?= $surcharge->price ?>"/>
                                            <p class="fs16 fs12-sp main-color"><span class="normal-surcharge-fee"
                                                                                     id="<?= SystemHelper::getSurchargeId($surcharge['surcharge_type'], $surcharge['other_slug'], false) ?>">0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if ($hotel->is_special == 1): ?>
                                <input type="hidden" name="is_special" value="1">
                                <div class="row mt10 ml15 mr15 mb10 ml0-sp mr0-sp">
                                    <div class="col-sm-36">
                                        <div class="form-group">
                                            <p class="text-super-dark fs14 mb15">Danh sách đoàn<span
                                                    class="text-grey fs12"></span></p>
                                            <textarea class="form-control" rows="5" name="information" <?= $booking->information ?>></textarea>
                                        </div>
                                    </div>
                                    <p id="error_information" class="error-messages ml15 mr15"></p>
                                </div>
                            <?php endif; ?>
                            <div class="row ml15 mr15 mb10 mr0-sp ml0-sp">
                                <div class="col-sm-36">
                                    <div class="form-group">
                                        <p class="text-super-dark fs14 mb15">Yêu cầu thêm<span
                                                class="text-grey fs12"></span></p>
                                        <textarea class="form-control" rows="5" name="other" id="comment" <?= $booking->other ?>></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BOOKING CHO KHACH -->
            <div class="container">
                <?php if ($this->request->getSession()->read('Auth.User.role_id') == 3 && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                    <div class="row mt20">
                        <div class="col-sm-18 col-xs-36">
                            <div class="bg-white p10">
                                <p class="text-super-dark fs14" id="booking-str"></p>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-18 mt10-sp text-center">
                            <div class="bg-white p10">
                                <p class="text-super-dark fs14 mb5">Giá tiền</p>
                                <p class="text-super-dark fs14"><span id="total_booking_price"></span> VNĐ</p>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-18 mt10-sp text-center">
                            <div class="bg-white p10">
                                <p class="text-super-dark fs14 mb5">Giá tiền lãi</p>
                                <p class="text-super-dark fs14"><span id="total_booking_revenue"></span> VNĐ</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt20 row-eq-height">
                        <div class="col-sm-27 col-xs-36">
                            <div class="bg-white p05">
                                <p class="text-super-dark fs14 mb5">Hình thức thanh toán</p>
                                <div class="form-group row ml20 mt10">
                                    <p class="fs12 pc">
                                        <input type="radio" class="iCheck payment-method-check"
                                               value="<?= CUSTOMER_PAY ?>" name="payment_method" <?= $booking->payment_method == CUSTOMER_PAY ? 'checked' : '' ?>> Khách hàng thanh toán
                                        trực tiếp cho Mustgo</i>
                                        <span class="fs12 ml20"><input type="radio" class="iCheck payment-method-check"
                                                                       value="<?= AGENCY_PAY ?>" name="payment_method" <?= $booking->payment_method == AGENCY_PAY ? 'checked' : '' ?>>  Đại lý thu hộ cho Mustgo</i></span>
                                    </p>
                                    <div class="sp">
                                        <div>
                                            <input type="radio" class="iCheck payment-method-check"
                                                   value="<?= CUSTOMER_PAY ?>" name="payment_method"> Khách hàng thanh
                                            toán trực tiếp cho Mustgo</i>
                                        </div>
                                        <div>
                                            <input type="radio" class="iCheck payment-method-check"
                                                   value="<?= AGENCY_PAY ?>" name="payment_method"> Đại lý thu hộ cho
                                            Mustgo</i>
                                        </div>
                                    </div>
                                    <p id="error_payment_method" class="error-messages"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-36 mt10-sp">
                            <div class="text-white full-height">
                                <button type="button"
                                        class="btn btn-request-booking btn-block semi-bold text-white full-height"
                                        id="requestBooking">
                                    <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row mt20">
                        <div class="col-sm-16">
                            <div class="bg-white p10">
                                <p class="text-super-dark fs14" id="booking-str"></p>
                            </div>
                        </div>
                        <div class="col-sm-11 text-center">
                            <div class="bg-white p10">
                                <p class="text-super-dark fs14 mb5">Giá tiền</p>
                                <p class="text-super-dark fs14"><span id="total_booking_price"></span> VNĐ</p>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="text-white">
                                <button type="button"
                                        class="btn btn-request-booking btn-block semi-bold text-white full-height"
                                        id="requestBooking">
                                    <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
