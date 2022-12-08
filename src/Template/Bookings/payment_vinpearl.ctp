<?php

use App\View\Helper\SystemHelper;

?>
<?php
echo $this->Html->css('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'cssHeader']);
echo $this->Html->script('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'scriptBottom']);
$this->Html->scriptBlock('Dropzone.autoDiscover = false;', ['block' => 'scriptBottom']);
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
                <li class="text-center active">2. Thanh toán</li>
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
    <?php
    $atm_and_qr = 0;
    $visa = 0;
    if ($booking->user_id != $booking->sale_id) {
        $price = $booking->price - $booking->revenue;
        $atm_and_qr = round($price / (100 - 1.1) * 100 + 1760 - $price);
        $visa = round($price / (100 - 2.75) * 100 + 7150 - $price);
    } else {
        $atm_and_qr = round($booking->price / (100 - 1.1) * 100 + 1760 - $booking->price);
        $visa = round($booking->price / (100 - 2.75) * 100 + 7150 - $booking->price);
    }
    ?>
    <div class="container pb40 pb10-sp">
        <div class="combo-detail-title mt50 mt10-sp text-center">
            <span class="semi-bold box-underline-center fs24 pb05-sp pb20">THÔNG TIN THANH TOÁN</span>
        </div>
    </div>
    <form id="hotelVinBookingForm">
        <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="row">
                    <div class="col-sm-24 bg-white">
                        <h3 class="text-light-blue mb10">Phương thức thanh toán</h3>
                        <p class="error-messages" id="error_type"></p>
                        <p class="fs16 text-light-blue"><input type="radio" style="display: block" class="iCheck payment-check" name="invoice" value="<?= 1 ?>" data-field-id="have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 1) ? 'checked' : '' ?> >
                            Xuất hóa đơn
                        </p>
                        <fieldset class="scheduler-border payment-fieldset" id="have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 1) ? 'style="display: block"' : '' ?>>
                            <p class="error-messages" id="error_invoice"></p>
                            <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_BALANCE ?>" data-field-id="payment-balance-have-invoice">
                                    Thanh toán bằng số dư tài khoản - Số dư: <span class="text-red"><?= number_format($balance) ?> VNĐ</span>
                                </p>
                                <p class="text-detail">(Không thu phí. Nhận được Mã đặt phòng ngay)</p>
                                <div class="invoice-zone" id="payment-balance-have-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_TRANSFER) ? 'style="display: block"' : '' ?>>
                                    <div class="col-sm-36">
                                        <p>Thông tin hóa đơn thanh toán</p>
                                        <div class="form-group full-height">
                                            <textarea class="form-control w-100" name="invoice_information_balance"
                                                      style="height: 100%"><?= (isset($booking->payment->invoice_information)) ? $booking->payment->invoice_information : '' ?></textarea>
                                        </div>
                                        <p class="error-messages" id="error_invoice_information"></p>
                                    </div>
                                </div>
                                <p class="error-messages" id="error_not_enough_balance"></p>
                            <?php endif; ?>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_TRANSFER ?>" data-field-id="payment-transfer-have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice) ? 'checked' : '' ?> >
                                Chuyển khoản ngân hàng
                            </p>
                            <p class="text-detail">(Không thu phí. Phòng được đặt khi Mustgo xác nhận tiền đã nổi trên tài khoản. Tình trạng phòng có thể hết trong khi chờ Mustgo Xác nhận)</p>
                            <div class="invoice-zone" id="payment-transfer-have-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_TRANSFER) ? 'style="display: block"' : '' ?>>
                                <p class="fs14 mb10 pl15"> <spam>Quý khách vui lòng thanh toán vào tài khoản dưới đây. </spam><br> Lưu ý: Nếu người thụ hưởng trên hóa đơn là “<i>Công ty ABC</i>” thì tiền thanh toán phải chuyển khoản từ tài khoản “<i>Công ty ABC</i>”</p>
                                <?php foreach ($bank_invoices as $key => $bank): ?>
                                    <?php if ($key == 0 || $key % 3 == 0): ?>
                                        <div class="row ml15 mr15 mb15 mt15 row-eq-height">
                                    <?php endif; ?>
                                    <div class="col-sm-12 bank-account-detail m10">
                                        <div class="text-center p20">
                                            <img src="<?= $this->Url->assetUrl($bank['bank_logo']) ?>">
                                            <p class="fs14 mt05"><?= $bank['bank_name'] ?></p>
                                            <p class="fs14">Tên TK: <?= $bank['account_name'] ?></p>
                                            <p class="fs14">Số TK: <?= $bank['account_number'] ?></p>
                                            <p class="fs14">Chi nhánh: <?= $bank['bank_branch'] ?></p>
                                        </div>
                                    </div>
                                    <?php if ($key % 3 == 2 || $key == count($bank_invoices) - 1): ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="col-sm-36">
                                    <p>Thông tin hóa đơn thanh toán</p>
                                    <div class="form-group full-height">
                                            <textarea class="form-control w-100" name="invoice_information"
                                                      style="height: 100%"><?= (isset($booking->payment->invoice_information)) ? $booking->payment->invoice_information : '' ?></textarea>
                                    </div>
                                    <p class="error-messages" id="error_invoice_information"></p>
                                </div>
                                <div class="deligate-payment">
                                    <div class="row ml15 mr15 mb15 mt15">
                                        <div class="col-sm-36">
                                            <h4 class="text-light-blue mb10"> Ủy nhiệm đơn hàng</h4>
                                            <p class="fs14 mb10">Sau khi thanh toán đơn hàng bằng tài khoản ngân hàng, quý khách
                                                chụp lại hóa đơn thanh toán rồi gửi về cho mustgo. Mustgo sẽ xác nhận lại và liên hệ
                                                cho quý khách.</p>
                                        </div>
                                        <p class="error-messages" id="error_images"></p>
                                        <div class="col-sm-36 text-center">
                                            <div id="dropzone-upload" class="dropzone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_CREDIT ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_CREDIT) ? 'checked' : '' ?>>
                                Thẻ tín dụng / Ghi nợ
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/VS.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/MC.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/amex.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/JC.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($visa) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_ATM ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_ATM) ? 'checked' : '' ?>>
                                Thẻ ATM / Tài khoản ngân hàng
                                <img style="width: 6%" src="<?= $this->Url->assetUrl('/frontend/img/payment/atm_logo.png') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_QR ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_QR) ? 'checked' : '' ?>>
                                Thanh toán qua QR
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/qr_code.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                        </fieldset>
                        <p class="fs16 text-light-blue mt15 mb15"><input type="radio" style="display: block" class="iCheck payment-check" name="invoice" value="0" data-field-id="no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 0) ? 'checked' : '' ?> >
                            Không xuất hóa đơn</i></p>
                        <fieldset class="scheduler-border payment-fieldset" id="no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 1) ? 'style="display: block"' : '' ?>>
                            <p class="error-messages" id="error_invoice"></p>
                            <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_BALANCE ?>">
                                    Thanh toán bằng số dư tài khoản - Số dư: <span class="text-red"><?= number_format($balance) ?> VNĐ</span>
                                </p>
                                <p class="text-detail">(Không thu phí. Nhận được Mã đặt phòng ngay)</p>
                                <p class="error-messages" id="error_not_enough_balance"></p>
                            <?php endif; ?>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_TRANSFER ?>" data-field-id="payment-transfer-no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice) ? 'checked' : '' ?> >
                                Chuyển khoản ngân hàng
                            </p>
                            <p class="text-detail">(Không thu phí. Phòng được đặt khi Mustgo xác nhận tiền đã nổi trên tài khoản. Tình trạng phòng có thể hết trong khi chờ Mustgo Xác nhận)</p>
                            <div class="invoice-zone" id="payment-transfer-no-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_TRANSFER) ? 'style="display: block"' : '' ?>>
                                <p class="fs14 mb10">Quý khách vui lòng thanh toán hóa đơn bằng cách chuyển tiền vào những
                                    địa chỉ ngân hàng dưới đây</p>
                                <?php foreach ($banks as $key => $bank): ?>
                                    <?php if ($key == 0 || $key % 3 == 0): ?>
                                        <div class="row ml15 mr15 mb15 mt15 row-eq-height">
                                    <?php endif; ?>
                                    <div class="col-sm-12 bank-account-detail m10">
                                        <div class="text-center p20">
                                            <img src="<?= $this->Url->assetUrl($bank['bank_logo']) ?>">
                                            <p class="fs14 mt05"><?= $bank['bank_name'] ?></p>
                                            <p class="fs14">Tên TK: <?= $bank['account_name'] ?></p>
                                            <p class="fs14">Số TK: <?= $bank['account_number'] ?></p>
                                            <p class="fs14">Chi nhánh: <?= $bank['bank_branch'] ?></p>
                                        </div>
                                    </div>
                                    <?php if ($key % 3 == 2 || $key == count($banks) - 1): ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="deligate-payment">
                                    <div class="row ml15 mr15 mb15 mt15">
                                        <h4 class="text-light-blue mb10"> Ủy nhiệm đơn hàng</h4>
                                        <p class="fs14 mb10">Sau khi thanh toán đơn hàng bằng tài khoản ngân hàng, quý khách
                                            chụp lại hóa đơn thanh toán rồi gửi về cho mustgo. Mustgo sẽ xác nhận lại và liên hệ
                                            cho quý khách.</p>
                                        <p class="error-messages" id="error_images"></p>
                                        <div class="col-sm-36 text-center">
                                            <div id="dropzone-upload" class="dropzone">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_CREDIT ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_CREDIT) ? 'checked' : '' ?>>
                                Thẻ tín dụng / Ghi nợ
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/VS.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/MC.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/amex.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/JC.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($visa) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_ATM ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_ATM) ? 'checked' : '' ?>>
                                Thẻ ATM / Tài khoản ngân hàng
                                <img style="width: 6%" src="<?= $this->Url->assetUrl('/frontend/img/payment/atm_logo.png') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_ONEPAY_QR ?>" data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_QR) ? 'checked' : '' ?>>
                                Thanh toán qua QR
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/qr_code.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr) ?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                        </fieldset>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-36">
                                <div class="bg-white">
                                    <div class="p10">
                                        <div class="information-header">
                                            <p class="text-main-blue">Thông tin đặt phòng</p>
                                            <p class="semi-bold mt10"><?= $hotel->name ?></p>
                                            <p class="w100 mt10 fs12"><span><?= date_format($booking->start_date, 'd/m/Y') ?></span> - <span><?= date_format($booking->end_date, 'd/m/Y') ?></span> <span class="pull-right semi-bold"><?= $date->days + 1 ?> ngày <?= $date->days ?> đêm</span></p>
                                            <p class="mt05 fs12"><?= $numAdult ?> Người lớn, <?= $numChild ?> trẻ em, <?= $numKid ?> em bé</p>
                                            <p class="mt05 fs12"><?= $numRoom ?> phòng</p>
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
                                                                <?php $total = 0; ?>
                                                                <?php foreach ($booking->vinhmsbooking_rooms as $roomKey => $room): ?>
                                                                    <div class="single-room-detail">
                                                                        <div class="col-sm-20 col-xs-20 mt10">
                                                                            <p class="fs14">Phòng <?= $roomKey + 1 ?>: <?= $room->vinhms_name ?></p>
                                                                        </div>
                                                                        <div class="col-sm-16 col-xs-16 mt10">
                                                                            <p class="pull-right fs14"><?= number_format($room->price + $room->revenue + $room->sale_revenue ) ?> VNĐ</p>
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
                                            <div class="col-sm-36 col-xs-36">
                                                <p class="pull-right">
                                                    Tổng cộng
                                                </p>
                                            </div>
                                            <div class="col-sm-36 col-xs-36">
                                                <p class="pull-right text-orange fs24 semi-bold">
                                                    <?= number_format($booking->price - $booking->sale_discount - $booking->agency_discount) ?> VNĐ
                                                </p>
                                            </div>
                                            <?php if ($booking->user_id != $booking->sale_id): ?>
                                                <div class="col-sm-36 col-xs-36 mt10">
                                                    <p class="pull-right">
                                                        Chiết khấu Đại Lý
                                                    </p>
                                                </div>
                                                <div class="col-sm-36 col-xs-36">
                                                    <p class="pull-right text-orange fs24 semi-bold">
                                                        <?= number_format($booking->revenue) ?> VNĐ
                                                    </p>
                                                </div>
                                                <div class="col-sm-36 col-xs-36 mt10">
                                                    <p class="pull-right">
                                                        Đại Lý phải thanh toán
                                                    </p>
                                                </div>
                                                <div class="col-sm-36 col-xs-36">
                                                    <p class="pull-right text-orange fs24 semi-bold">
                                                        <?= number_format($booking->price - $booking->revenue - $booking->agency_discount - $booking->sale_discount) ?> VNĐ
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-sm-36 col-xs-36">
                                                <p class="pull-right fs13">(Giá đã bao gồm phí dịch vụ và thuế GTGT)</p>
                                            </div>
                                            <div class="col-sm-24 col-xs-24 mt10">
                                                <p class="fs18 fs14-sp">Nội dung thanh toán:</p>
                                            </div>
                                            <div class="col-sm-12 col-xs-12 mt10">
                                                <p class="pull-right fs18 fs14-sp text-orange"><?= $booking->code ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-24 mt20 no-pad-right no-pad-left">
                        <div class="w100">
                            <button type="button" class="btn w100 btn-payment text-uppercase" id="requestVinPayment">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $list_images = [];
        if ($booking->payment) {
            if ($booking->payment->images) {
                $list_images = $booking->payment->images;
            }
        }
        ?>
        <?php if ($list_images): ?>
            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
            <input type="hidden" name="images" value='<?= $list_images ?>'/>
        <?php else: ?>
            <input type="hidden" name="images" value=''/>
        <?php endif; ?>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
