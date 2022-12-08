<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
if (isset($dataValiError['booking_surcharges'])) {
    $booking_surcharges = dataValiError['booking_surcharges'];
} elseif (isset($booking->booking_surcharges) && !empty($booking->booking_surcharges)) {
    $booking_surcharges = $booking->booking_surcharges;
} else {
    $booking_surcharges = [];
}
?>
<div id="hotelBookingForm">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thông tin booking</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php
                echo $this->Form->control('item_id', [
                    'type' => 'select',
                    'class' => 'form-control select2',
                    'options' => $listObjs,
                    'empty' => 'Chọn Khách sạn',
                    'label' => 'Danh sách Khách sạn *',
                    'required' => 'required',
                    'id' => 'booking-hotel-select',
                    'onchange' => 'bookingChangeHotel(this)',
                    'data-booking-id' => (isset($booking->id)) ? $booking->id : '',
                    'default' => (isset($booking->item_id)) ? $booking->item_id : ''
                ]);
                ?>
                <div id="list-hotel-item">

                </div>
                <div class="col-md-6">
                    <span id="error-choose-room"></span><br>
                    <button type="button" class="btn btn-success" onclick="addHotelRoom('#list-hotel-item', false)">
                        Thêm hạng phòng
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Giá phụ thu</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <div id="hotel-list-surcharges">

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Bổ sung thông tin</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php
                echo $this->Form->control('price', [
                    'type' => 'text',
                    'readonly' => true,
                    'class' => 'form-control currency',
                    'label' => 'Giá trị đơn hàng',
                    'default' => (isset($booking->price)) ? $booking->price : 0
                ]);
                echo $this->Form->control('full_name', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Họ Tên Khách hàng *',
                    'required' => 'required',
                    'default' => (isset($booking->full_name)) ? $booking->full_name : ''
                ]);
                echo $this->Form->control('phone', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Số ĐT *',
                    'required' => 'required',
                    'default' => (isset($booking->phone)) ? $booking->phone : ''
                ]);
                echo $this->Form->control('email', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Email *',
                    'required' => 'required',
                    'default' => (isset($booking->email)) ? $booking->email : $email
                ]);
                echo $this->Form->control('hotel_code', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Mã Booking khách sạn',
                    'default' => (isset($booking->hotel_code)) ? $booking->hotel_code : ''
                ]);
                echo $this->Form->control('information', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => 'Danh sách đoàn',
                    'value' => (isset($booking->information)) ? $booking->information : ''
                ]);

                echo $this->Form->control('other', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => 'Yêu cầu thêm',
                    'value' => (isset($booking->other)) ? $booking->other : ''
                ]);
                echo $this->Form->control('note', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => 'Lưu ý cho khách sạn',
                    'value' => (isset($booking->note)) ? $booking->note : ''
                ]);
                echo $this->Form->control('note_agency', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => 'Lưu ý gửi Đại lý',
                    'value' => (isset($booking->note_agency)) ? $booking->note_agency : ''
                ]);
//                echo $this->Form->control('sale_discount', [
//                    'type' => 'text',
//                    'class' => 'form-control currency',
//                    'label' => 'Tăng lợi nhuận Sale',
//                    'default' => (isset($booking->sale_discount)) ? number_format($booking->sale_discount) : ''
//                ]);
//                echo $this->Form->control('agency_discount', [
//                    'type' => 'text',
//                    'class' => 'form-control currency',
//                    'label' => 'Giảm giá cho Đại lý',
//                    'default' => (isset($booking->agency_discount)) ? number_format($booking->agency_discount) : ''
//                ]);
                ?>
            </div>

<!--            --><?php //dd($booking->payment_deadline); ?>
<!--            <div>-->
<!--                <label for="">Hạn ngày thanh toán</label>-->
<!--                <div class="input-group date">-->
<!--                   <input type="text" class="form-control" readonly placeholder="" value="25/08/2022" />-->
<!--                   <div class="input-group-append">-->
<!--                       <span class="input-group-text">-->
<!--                           <i class="fa fa-clock-o" aria-hidden="true"></i>-->
<!--                           <i class="la la-calendar-check-o"></i>-->
<!--                       </span>-->
<!--                   </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="controls">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Hạn ngày thanh toán</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="input-prepend input-group">
                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                        <input type="text" name="paymentDeadline" class="custom-singledate-picker form-control" value="<?= isset($booking->payment_deadline ) ? $booking->payment_deadline->format('d/m/Y') : ''  ?>" />
                    </div>
                </div>
            </div>

            <input type="hidden" name="customer_deposit" value="0">
            <input type="hidden" name="agency_pay" value="0">
        </div>
    </div>
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thanh toán của Đại lý/Khách lẻ</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <h4 class="text-light-blue mb10">Phương thức thanh toán</h4>
                <?php if (!$payment || ($payment && $payment->type == 0)): ?>
                    <p class="error-messages" id="error_type"></p>
                    <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-check"
                                                                name="payment_type" value="<?= PAYMENT_TRANSFER; ?>"
                                                                data-field-id="payment-transfer"> Chuyển khoản ngân
                        hàng</i></p>
                    <fieldset class="scheduler-border payment-fieldset" id="payment-transfer">
                        <p class="error-messages" id="error_invoice"></p>
                        <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                         name="payment_invoice" value="0"
                                                                         data-field-id="no-invoice"> Không xuất hóa
                            đơn</i></p>
                        <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                         name="payment_invoice" value="1"
                                                                         data-field-id="has-invoice"> Xuất hóa đơn
                            VAT</i></p>
                        <div class="invoice-zone" id="has-invoice">
                            <div class="row ml15 mr15 mb15 mt15">
                                <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa
                                    chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                                <p class="error-messages" id="error_invoice_information"></p>
                                <div class="row-eq-height">
                                    <div class="col-sm-12">
                                        <div class="form-group full-height">
                                            <textarea class="form-control" placeholder="Thông tin xuất hóa đơn..."
                                                      name="payment_invoice_information"
                                                      style="height: 100%"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php else: ?>
                    <?php if ($payment->type == PAYMENT_TRANSFER): ?>
                        <p class="fs16 mb15 text-light-blue"><i class="fa fa-check"></i> Chuyển khoản ngân hàng</p>
                        <?php if ($payment->invoice == 0): ?>
                            <p class="fs14 mb15 text-light-blue ml10"><i class="fa fa-check"></i> Không xuất hóa đơn</p>
                        <?php endif; ?>
                        <?php if ($payment->invoice == 1): ?>
                            <p class="fs14 mb15 text-light-blue ml10"><i class="fa fa-check"></i> Xuất hóa đơn VAT</p>
                            <p class="fs14 mb15 text-light-blue ml10">Thông tin xuât hóa
                                đơn: <?= $payment->invoice_information ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="deligate-payment">
                    <div class="row ml15 mr15 mb15 mt15">
                        <h4 class="fs14 mb10">Ảnh hóa đơn thanh toán</h4>
                        <p class="error-messages" id="error_images"></p>
                        <div class="col-sm-36 text-center">
                            <div id="dropzone-upload" class="dropzone">
                            </div>
                            <input type="hidden" name="media" value='<?= $list_images ?>'/>
                            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thanh toán cho Khách sạn/Đối tác</h2>
                <div class="clearfix"></div>
            </div>
            <input type="hidden" name="pay_hotel_text" value=" ">
            <input type="hidden" name="payment_deadline" value="<?= date('d/m/Y') ?>">
            <div class="x_content">
                <div class="clearfix"></div>
                <div class="control-group">
                    <div class="row">
                        <label for="" class="control-label col-sm-2 col-md-2">Chọn đối tượng thanh toán</label>
                        <div class="col-sm-4">
                            <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-for-hotel"
                                                                        name="pay_object" <?= isset($payment) && $payment->pay_object == PAY_HOTEL ? 'checked' : '' ?>
                                                                        value="<?= PAY_HOTEL ?>"
                                                                        data-field-id="pay-for-hotel"> Thanh toán cho
                                khách sạn</i></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-for-hotel"
                                                                        name="pay_object" <?= isset($payment) && $payment->pay_object == PAY_PARTNER ? 'checked' : '' ?>
                                                                        value="<?= PAY_PARTNER ?>"
                                                                        data-field-id="pay-for-partner"> Thanh toán cho
                                đối tác</i></p>
                        </div>
                    </div>
                </div>
                <?php
                $payHotelInfor = false;
                $payPartnerInfor = false;
                if (isset($payment)) {
                    if ($payment->pay_object == PAY_HOTEL) {
                        $payPartnerInfor = true;
                    }
                    if ($payment->pay_object == PAY_PARTNER) {
                        $payHotelInfor = true;
                    }
                    if ($payment->pay_object == 0) {
                        $payHotelInfor = true;
                        $payPartnerInfor = true;
                    }
                } else {
                    $payHotelInfor = true;
                    $payPartnerInfor = true;
                }
                ?>
                <fieldset
                    class="scheduler-border paytype-information <?= $payHotelInfor ? 'payment-paytype-hotel' : '' ?>"
                    id="pay-for-hotel">
                    <p class="error-messages" id="error_invoice"></p>
                    <div class="col-sm-2 col-md-2"></div>
                    <div class="col-sm-10 col-md-10">
                        <p class="fs16 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                         name="check_type" <?= (isset($payment) && $payment->pay_object == PAY_HOTEL && $payment->check_type == NO_CHECK) ? 'checked' : '' ?>
                                                                         value="<?= NO_CHECK ?>"> Không hóa đơn</i></p>
                        <p class="fs16 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                         name="check_type" <?= (isset($payment) && $payment->pay_object == PAY_HOTEL && $payment->check_type == HAVE_CHECK) ? 'checked' : '' ?>
                                                                         value="<?= HAVE_CHECK ?>"> Có hóa đơn</i></p>
                    </div>
                    <div id="object-payment-information">

                    </div>
                </fieldset>
                <fieldset
                    class="scheduler-border paytype-information <?= $payPartnerInfor ? 'payment-paytype-hotel' : '' ?>"
                    id="pay-for-partner">
                    <?php
                    if ($payment) {
                        $information = json_decode($payment->partner_information);
                    }
                    echo $this->Form->control('partner_name', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Tên tài khoản *',
                        'default' => isset($information) ? $information->name : ''
                    ]);
                    echo $this->Form->control('partner_number', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số tài khoản *',
                        'default' => isset($information) ? $information->number : ''
                    ]);
                    echo $this->Form->control('partner_bank', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Chi nhánh ngân hàng *',
                        'default' => isset($information) ? $information->bank : ''
                    ]);
                    echo $this->Form->control('partner_email', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Email *',
                        'default' => isset($information) ? $information->email : ''
                    ]);
                    ?>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="text-center">
            <button type="button" class="btn btn-success " id="saveBooking" onclick="checkVadidate(this)"
                    data-ctl="bookings" data-role="sale" data-title="2"
                    data-id="<?= isset($booking) ? $booking->id : "" ?>" data-code="<?= isset($booking) ? $booking->code : "" ?>">Lưu</button>
        </div>
    </div>
</div>
