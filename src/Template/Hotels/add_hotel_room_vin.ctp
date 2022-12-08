<fieldset class="booking-room-item" style="position: relative">
    <legend class="pc">Hạng Phòng</legend>
    <p class="sp text-center">Hạng phòng</p>
    <a class="fieldset-close-button sp" href="#" onclick="Frontend.deleteItem(this, '.booking-room-item');">
        <i class="text-danger fas fa-times"></i>
    </a>
    <div class="col-sm-offset-34 col-sm-2 col-sm-2 text-right pc">
        <a href="#" onclick="Frontend.deleteItem(this, '.booking-room-item');" class="mt10">
            <i class="text-danger fas fa-times"></i>
        </a>
    </div>
    <div class="row">
        <div class="clearfix"></div>
        <div class="col-sm-22">
            <div class="form-group mb05-sp">
                <p class="text-super-dark fs14 mb15 pc">Hạng phòng<span class="text-grey fs12"></span></p>
                <select class="form-control popup-voucher" id="agency_room" name="booking_rooms[0][room_id]">
                    <option value="">Chọn Hạng phòng</option>
                    <?php foreach ($rooms as $k => $val): ?>
                        <option value="<?= $k ?>"><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <p id="error_room_id" class="error-messages"></p>
            </div>
        </div>
    </div>
    <div class="row mt10-pc mb05-sp">
        <div class="col-sm-18 col-xs-18">
            <p class="text-super-dark fs14 mb15 fs12-sp mb05-sp">Ngày Checkin</p>
            <div class='input-group date datepicker' id="booking-start-date">
                <span class="input-group-addon"><span class="far fa-calendar-alt main-color"></span></span>
                <input type='text' readonly="readonly" name="booking_rooms[0][start_date]" class="form-control popup-voucher" placeholder="Thời gian đi"/>
            </div>
            <p id="" class="error-messages"></p>
        </div>
        <div class="col-sm-18 col-xs-18">
            <p class="text-super-dark fs14 mb15 fs12-sp mb05-sp">Ngày Checkout</p>
            <div class='input-group date datepicker' id="booking-end-date">
                <span class="input-group-addon"><span class="far fa-calendar-alt main-color"></span></span>
                <input type='text' readonly="readonly" name="booking_rooms[0][end_date]" class="form-control popup-voucher" placeholder="Thời gian về"/>
            </div>
            <p id="" class="error-messages"></p>
        </div>
    </div>
    <div class="col-sm-36 col-xs-36">
        <div class="form-group mb05-sp">
            <p class="text-super-dark fs14 fs12-sp mb15 mb05-sp">Gói Phòng<span
                    class="text-grey fs12"></span></p>
            <!--                                            <input class="form-control popup-voucher inputmask-number" type="text" id="room_amount" placeholder="Số Phòng" name="booking_rooms[0][num_room]" value="">-->
            <select disabled class="form-control popup-voucher select-no-arrow" name="booking_rooms[0][num_room]">
                <option value="<?= $listRoom['rateplan_code'] ?>"><?= $listRoom['rateplan_code'] ?></option>
            </select>
            <p id="error_booking_rooms_0_num_room"
               class="error-messages"></p>
        </div>
    </div>
    <input type="hidden" class="booking-value room-price" name="booking-room">
</fieldset>
