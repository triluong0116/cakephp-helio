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
            <span class="semi-bold box-underline-center fs24 pb05-sp pb20">THÔNG TIN ĐẶT LANDTOUR</span>
        </div>
    </div>
    <form id="landTourBookingForm">
        <input type="hidden" class="booking-value" name="revenue" value="">
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="bg-white p15">
                    <input type="hidden" name="land_tour_id" value="<?= $landTour->id ?>">
                    <div class="row mt10">
                        <div class="col-sm-9 col-xs-36 mb05-sp">
                            <span class="text-super-dark fs12-sp fs14 mb05-sp">Ngày đi</span>
                            <div class='input-group date datepicker mt15 mt05-sp homestay' id="start-date-picker">
                                <span class="input-group-addon"><span
                                        class="far fa-calendar-alt main-color"></span></span>
                                <input type='text' name="start_date" class="form-control popup-voucher"
                                       value="<?= $fromDate ?>"/>
                            </div>
                            <p id="error_start_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-9 col-xs-36 mb0-sp">
                            <div class="form-group">
                                <p class="text-super-dark fs12-sp fs14 mb15 mb05-sp">Số người lớn<span
                                        class="text-grey fs12"></span></p>
                                <select class="form-control popup-voucher select-no-arrow" name="num_adult"
                                        onchange="Frontend.calLandtourDriveSurcharge()">
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>" <?= $num_adult == $i ? 'selected' : '' ?>><?= $i ?>
                                            Người lớn
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <p id="error_num_adult" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-36 mb0-sp">
                            <div class="row">
                                <div class="col-sm-36 col-xs-36">
                                    <div class="form-group">
                                        <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Số trẻ em<span
                                                class="text-grey fs12"></span></p>
                                        <!--                                        <input class="form-control popup-voucher inputmask-number landtour-booking-num-child" placeholder="Số trẻ em" name="num_children" value="">-->
                                        <select
                                            class="form-control popup-voucher landtour-booking-num-child select-no-arrow"
                                            name="num_children">
                                            <?php for ($i = 0; $i <= 50; $i++): ?>
                                                <option
                                                    value="<?= $i ?>" <?= $num_children == $i ? 'selected' : '' ?>><?= $i ?>
                                                    Trẻ em
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                        <p id="error_num_children" class="error-messages"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-36 mb0-sp">
                            <div class="row">
                                <div class="col-sm-36 col-xs-36">
                                    <div class="form-group">
                                        <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Số em bé<span
                                                class="text-grey fs12"></span></p>
                                        <!--                                        <input class="form-control popup-voucher inputmask-number landtour-booking-num-child" placeholder="Số trẻ em" name="num_children" value="">-->
                                        <select
                                            class="form-control popup-voucher landtour-booking-num-child select-no-arrow"
                                            name="num_kid">
                                            <?php for ($i = 0; $i <= 50; $i++): ?>
                                                <option
                                                    value="<?= $i ?>" <?= $num_children == $i ? 'selected' : '' ?>><?= $i ?>
                                                    Em bé
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                        <p id="error_num_kid" class="error-messages"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt10-pc mt05-sp">
                        <div class="col-sm-36 col-xs-36">
                            <?php if (count($landTour->land_tour_accessories) > 0): ?>
                                <?php foreach ($landTour->land_tour_accessories as $k => $access): ?>
                                    <div class="col-sm-36">
                                        <p class="fs16 mb15 text-light-blue">
                                            <input type="checkbox" class="iCheck" <?= in_array($access->id, $accessory) ? 'checked' : '' ?> name="accessory[]" value="<?= $access->id ?>">
                                            <?= $access->name ?></i></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt15">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Họ và tên đoàn trưởng<span
                                        class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="full_name" placeholder="Họ và tên"
                                       name="full_name">
                                <p id="error_full_name" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Số điện thoại trưởng đoàn<span
                                        class="text-grey fs12"></span></p>
                                <input class="form-control popup-voucher" id="phone" placeholder="Số điện thoại"
                                       name="phone">
                                <p id="error_phone" class="error-messages"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <p class="text-super-dark fs14 fs12-sp mb15">Email<span class="text-grey fs12"></span>
                                </p>
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
                    <div class="row mt15">
                        <div class="col-sm-18 col-xs-18 text-left">
                            <div class="ml15">
                                <h4 class="text-left fs14-sp">Đón trả</h4>
                            </div>
                        </div>
                    </div>
                    <hr class="mb15">
                    <div class="row mt15">
                        <div class="col-sm-36">
                            <div class="form-group">
                                <div class="row ml40">
                                    <p class="text-super-dark fs14 fs12-sp mb15">Địa chỉ đón khách<span
                                            class="text-grey fs12"></span></p>
                                    <div class="col-sm-12">
                                        <select class="form-control popup-voucher select-no-arrow" name="pickup_id"
                                                onchange="Frontend.calLandtourDriveSurcharge()">
                                            <option selected="true" disabled="disabled">Chọn Điểm đón</option>
                                            <?php if (count($landTour->land_tour_drivesurchages) > 0): ?>
                                                <?php foreach ($landTour->land_tour_drivesurchages as $k => $drivesurchage): ?>
                                                    <option value="<?= $drivesurchage->id ?>">
                                                        <?= $drivesurchage->name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <p id="error_pickup_id" class="error-messages"></p>
                                    </div>
                                    <div class="col-sm-24"><input class="form-control popup-voucher"
                                                                  placeholder="Địa chỉ " name="detail_pickup">
                                    </div>
                                </div>
                                <div class="form-group mt30">
                                    <div class="row ml40">
                                        <p class="text-super-dark fs14 fs12-sp mb15">Địa chỉ trả khách<span
                                                class="text-grey fs12"></span></p>
                                        <div class="col-sm-12">
                                            <select class="form-control popup-voucher select-no-arrow" name="drop_id"
                                                    onchange="Frontend.calLandtourDriveSurcharge()">
                                                <option selected="true" disabled="disabled">Chọn Điểm trả</option>
                                                <?php if (count($landTour->land_tour_drivesurchages) > 0): ?>
                                                    <?php foreach ($landTour->land_tour_drivesurchages as $k => $drivesurchage): ?>
                                                        <option value="<?= $drivesurchage->id ?>">
                                                            <?= $drivesurchage->name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <p id="error_drop_id" class="error-messages"></p>
                                        </div>
                                        <div class="col-sm-24"><input class="form-control popup-voucher"
                                                                      placeholder="Địa chỉ " name="detail_drop">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt15">
                                <div class="col-sm-18 col-xs-18 text-left">
                                    <div class="ml15">
                                        <h4 class="text-left fs14-sp">Phụ thu</h4>
                                    </div>
                                </div>
                                <div class="col-sm-18 col-xs-18 text-right">
                                    <div class="mr15">
                                        <h4 class="text-right fs14-sp">Giá phụ thu</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb15">
                            <div class="normal-surcharge-item">
                                <div class="row vertical-center">
                                    <div class="col-sm-16 col-xs-18 text-left">
                                        <p class="fs16 fs13-sp text-center-sp"><i data-toggle="tooltip"
                                                                                  title="Phụ thu đưa đón">Phụ
                                                thu đưa đón</i></p>
                                    </div>
                                    <div class="col-sm-16 col-xs-18 text-right">
                                        <p class="fs16 fs13-sp main-color"><span
                                                id="total_booking_drive_surcharge"></span> VNĐ
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt10">
                                <div class="col-sm-36">
                                    <div class="form-group">
                                        <p class="text-super-dark fs14 mb15">Yêu cầu thêm<span
                                                class="text-grey fs12"></span>
                                        </p>
                                        <textarea class="form-control" rows="5" name="other"></textarea>
                                    </div>
                                </div>
                                <p id="error_other" class="error-messages"></p>
                            </div>
                        </div>
                    </div>
                    <!-- BOOKING CHO KHACH -->
                    <?php if ($this->request->getSession()->read('Auth.User.role_id') == 3 && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>

                        <div class="row mt20 row-eq-height">
                            <div class="col-sm-18 flex">
                                <div class="bg-white p10 full-width ">
                                    <p class="text-super-dark fs14" id="booking_result"></p>
                                </div>
                            </div>
                            <div class="col-sm-9 text-center flex">
                                <div class="bg-white p10 full-width ">
                                    <p class="text-super-dark fs14 mb5">Giá tiền</p>
                                    <p class="text-super-dark fs14"><span id="total_booking_price"></span></p>
                                </div>
                            </div>
                            <div class="col-sm-9 text-center flex">
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
                                    <div class="form-group row mt10">
                                        <div class="col-sm-16 col-xs-36">
                                            <input type="radio" class="iCheck payment-method-check" value="<?= AGENCY_PAY ?>" name="payment_method"> Đại lý thanh toán cho Mustgo</i>
                                        </div>
                                        <div class="col-sm-16 col-xs-36">
                                            <input type="radio" class="iCheck payment-method-check" value="<?= MUSTGO_DEPOSIT ?>" name="payment_method">  Mustgo thu hộ</i>
                                        </div>
                                        <p id="error_payment_method" class="error-messages"></p>
                                    </div>
                                    <div class="form-group row ml10 mt10 mustgo-deposit" style="display: none">
                                        <div class="col-sm-32 col-xs-36">
                                            <p class="text-super-dark fs14 fs12-sp mb15 mt15">Số tiền thu hộ<span class="text-grey fs12"></span></p>
                                            <input class="form-control popup-voucher" onkeyup="Frontend.calLandTourTotalPrice()" id="full_name" placeholder="Số tiền thu hộ" value="0" name="mustgo_deposit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <button type="button"
                                        class="btn btn-request-booking btn-block semi-bold text-white full-height"
                                        id="requestLandTourBooking">
                                    <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
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
                                    <button type="button"
                                            class="btn btn-request-booking btn-block semi-bold text-white full-height"
                                            id="requestLandTourBooking">
                                        <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php endif ?>
                </div>
    </form>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
