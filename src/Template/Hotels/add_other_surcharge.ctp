<div class="normal-surcharge-item">
    <input type="hidden" name="booking_surcharges[0][surcharge_type]" value="<?= SUR_OTHER ?>">
    <div class="row mb20">
        <div class="col-sm-20">
            <div class="form-group vertical-center">
                <p class="col-sm-18 text-right col-form-label">Tên phụ thu</p>
                <div class="col-sm-18">
                    <input type='text' class="form-control popup-voucher" name="booking_surcharges[0][other_name]"/>
                </div>
            </div>
        </div>
        <div class="col-sm-16">
            <div class="form-group vertical-center">
                <p class="col-sm-18 text-right col-form-label">Giá</p>
                <div class="col-sm-18">
                    <input type='text' class="form-control popup-voucher currency other-surcharge-price booking-value" name="booking_surcharges[0][price]"/>
                </div>
            </div>
        </div>
    </div>
</div>
