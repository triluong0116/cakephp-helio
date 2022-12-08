<div class="modal fade" id="booking" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content -->
        <div class="modal-content modal-voucher ">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Chi tiết gói Combo</h4>
            </div>
            <div class="modal-body">
                <form id="addBooking">
                    <div class="fs16 mb15">
                        <p>Vui lòng điền thông tin Combo</p>
                    </div>
                    <input type="hidden" name="user_id" value="">
                    <input type="hidden" name="item_id" value="<?= $combo->id ?>">
                    <input type="hidden" name="type" value="<?= COMBO ?>">
                    <input type="hidden" name="days_attended" value="">
                    <!--
                    <div class="form-group hidden">
                        <p class="text-super-dark fs14 mb15">Họ và tên <span class="text-grey fs12">(Chú ý có khoảng cách giữa họ và tên của bạn)</span></p>
                        <input type="text" class="form-control popup-voucher" id="agency_name" placeholder="Họ và tên" name="full_name">
                        <p id="error_time" class="error-messages"></p>
                    </div>-->
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Số Combo <span class="text-grey fs12">(Tối thiều 2 Combo)</span></p>
                        <input type="text" class="form-control popup-voucher inputmask-number" id="agency_amount" placeholder="Số Combo" name="amount" min="2">
                        <p id="error_phone" class="error-messages"></p>
                    </div>
                    <div class="row mb20">
                        <div class="col-sm-18">
                            <p class="text-super-dark fs14 mb15">Ngày Checkin</p>
                            <div class='input-group date datepicker' id="booking-start-date">
                                <span class="input-group-addon">
                                    <span class="far fa-calendar-alt main-color"></span>
                                </span>
                                <input type='text' name="start_date" class="form-control popup-voucher" placeholder="Thời gian đi" />
                            </div>
                        </div>
                        <div class="col-sm-18">
                            <p class="text-super-dark fs14 mb15">Ngày Checkout</p>
                            <div class='input-group date datepicker' id="booking-end-date">
                                <span class="input-group-addon">
                                    <span class="far fa-calendar-alt main-color"></span>
                                </span>
                                <input type='text' name="end_date" class="form-control popup-voucher" readonly placeholder="Thời gian về" />                                
                            </div>
                        </div>
                        <p id="error_date" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Họ và tên Trưởng đoàn <span class="text-grey fs12">(Chú ý có khoảng cách giữa họ và tên)</span></p>
                        <input type="text" class="form-control popup-voucher" id="agency_name" placeholder="Họ và tên" name="full_name">
                        <p id="error_full_name" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Số điện thoại Trưởng đoàn<span class="text-grey fs12"></span></p>
                        <input type="text" class="form-control popup-voucher" id="agency_phone" placeholder="Số điện thoại" name="phone">
                        <p id="error_phone" class="error-messages"></p>
                    </div>
                    <?php
                    $isSpecialHotel = false;
                    foreach ($combo->hotels as $hotel) {
                        if ($hotel->is_special == 1) {
                            $isSpecialHotel = true;
                        }
                    }
                    ?>                    
                    <?php if ($isSpecialHotel): ?>
                        <div class="form-group">
                            <p class="text-super-dark fs14 mb15">Danh sách đoàn, ngày sinh của trẻ em </p>
                            <textarea rows="10" class="form-control popup-voucher border-light-blue" id="agency_demand" placeholder="Yêu cầu thêm" name="other"></textarea>
                            <p id="error_other" class="error-messages"></p>
                        </div>
                    <?php endif; ?>                  
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Yêu cầu thêm </p>
                        <textarea rows="10" class="form-control popup-voucher border-light-blue" id="agency_demand" placeholder="Yêu cầu thêm" name="other"></textarea>
                        <p id="error_other" class="error-messages"></p>
                    </div>                      
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.addBookingCombo()" value="Gửi">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade list-location" id="finish-booking" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content -->
        <div class="modal-content modal-voucher p100">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>

            </div>
            <div class="modal-body">
                <center><p class="bold fs20 main-color">Bạn đã thao tác thành công!</p></center>
            </div>
        </div>
    </div>
</div>