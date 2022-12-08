<style>
    .popup-input-room {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<div class="form-horizontal form-label-left">
    <?= $this->Form->create(null, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Sửa giá Booking <?= $vinBooking->code ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Số tiền khách thanh toán</label>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                            <div class="">
                                                <input type="text" name="customer_pay" class="form-control currency" value="<?= $vinBooking->sale_id == $vinBooking->user_id ? $vinBooking->price - $vinBooking->agency_discount : $vinBooking->price - $vinBooking->revenue - $vinBooking->agency_discount - $vinBooking->sale_discount ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-sm-12">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Số tiền thanh toán khách sạn</label>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                            <div class="">
                                                <input type="text" name="hotel_pay" class="form-control currency" value="<?= $vinBooking->price - $vinBooking->revenue - $vinBooking->sale_revenue ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="text-light-blue mb10">Phương thức thanh toán</h4>
                        <p class="error-messages" id="error_type"></p>
                        <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-check" <?= $vinPayment && $vinPayment->type == PAYMENT_TRANSFER ? 'checked' : '' ?> name="payment[payment_type]" value="<?= PAYMENT_TRANSFER; ?>" data-field-id="payment-transfer"> Chuyển khoản ngân hàng</i></p>
                        <fieldset class="scheduler-border payment-fieldset" id="payment-transfer" <?= $vinPayment && $vinPayment->type == PAYMENT_TRANSFER ? 'style="display: block !important"' : '' ?>>
                            <p class="error-messages" id="error_invoice"></p>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" <?= $vinPayment && $vinPayment->invoice == 0 ? 'checked' : '' ?>  name="payment[payment_invoice]" value="0" data-field-id="no-invoice"> Không xuất hóa đơn</i></p>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" <?= $vinPayment && $vinPayment->invoice == 1 ? 'checked' : '' ?> name="payment[payment_invoice]" value="1" data-field-id="has-invoice"> Xuất hóa đơn VAT</i></p>
                            <div class="invoice-zone" id="has-invoice">
                                <div class="row ml15 mr15 mb15 mt15">
                                    <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                                    <p class="error-messages" id="error_invoice_information"></p>
                                    <div class="row-eq-height">
                                        <div class="col-sm-12">
                                            <div class="form-group full-height">
                                                <textarea class="form-control" placeholder="Thông tin xuất hóa đơn..." name="payment[payment_invoice_information]" style="height: 100%"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="deligate-payment">
                            <div class="row ml15 mr15 mb15 mt15">
                                <h4 class="fs14 mb10">Ảnh hóa đơn thanh toán</h4>
                                <p class="error-messages" id="error_images"></p>
                                <div class="col-sm-36 text-center">
                                    <div id="dropzone-upload" class="dropzone">
                                    </div>
                                    <input type="hidden" name="media" value='<?= $listPaymentImages ?>'/>
                                    <input type="hidden" name="list_image" value='<?= $listPaymentImages ?>'/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-log"  data-title="3" data-ctl="bookings" data-role="sale" data-id="<?= $vinBooking->id ?>" data-code="<?= $vinBooking->code ?>">
        Lưu
    </button>
    <?= $this->Form->end() ?>
</div>
