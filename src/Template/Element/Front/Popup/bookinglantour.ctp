<div class="modal fade" id="booking" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content -->
        <div class="modal-content modal-voucher ">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Chi tiết gói LandTour</h4>
            </div>
            <div class="modal-body">
                <form id="addBooking">
                    <div class="fs16 mb15">
                        <p>Vui lòng điền thông tin của bạn</p>
                    </div>
                    <input type="hidden" name="user_id" value="">
                    <input type="hidden" name="item_id" value="<?= $combo->id ?>">
                    <input type="hidden" name="type" value="<?= LANDTOUR ?>">
                    <input type="hidden" name="days_attended" value="">
                    <!--
                    <div class="form-group hidden">
                        <p class="text-super-dark fs14 mb15">Họ và tên <span class="text-grey fs12">(Chú ý có khoảng cách giữa họ và tên của bạn)</span></p>
                        <input type="text" class="form-control popup-voucher" id="agency_name" placeholder="Họ và tên" name="full_name">
                        <p id="error_time" class="error-messages"></p>
                    </div>-->
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Số Pax <span class="text-grey fs12"></span></p>
                        <input type="text" class="form-control popup-voucher inputmask-number" id="agency_amount" placeholder="Số Combo" name="amount">
                        <p id="error_phone" class="error-messages"></p>
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
                    <div class="row mb20">
                        <div class="col-sm-18">
                            <p class="text-super-dark fs14 mb15">Ngày đi</p>
                            <div class='input-group date datepicker' id="booking-start-date">
                                <span class="input-group-addon">
                                    <span class="far fa-calendar-alt main-color"></span>
                                </span>
                                <input type='text' name="start_date" class="form-control popup-voucher" placeholder="Thời gian đi" />                                
                            </div>
                        </div>
                        <div class="col-sm-18">
                            <div class='input-group date datepicker' id="booking-end-date">
                                <input type='hidden' name="end_date" class="form-control popup-voucher" readonly placeholder="Thời gian về" />                                
                            </div>
                        </div>
                        <p id="error_date" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <p class="text-super-dark fs14 mb15">Yêu cầu thêm </p>
                        <textarea class="form-control popup-voucher border-light-blue" id="agency_demand" placeholder="Yêu cầu thêm" name="other"></textarea>
                        <p id="error_other" class="error-messages"></p>
                    </div>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.addBookingLandtour()" value="Gửi">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="finish-booking" role="dialog">
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