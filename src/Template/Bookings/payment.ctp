<?php
echo $this->Html->css('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'cssHeader']);
echo $this->Html->script('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'scriptBottom']);
$this->Html->scriptBlock('Dropzone.autoDiscover = false;', ['block' => 'scriptBottom']);
?>
<div class="bg-grey" xmlns="http://www.w3.org/1999/html">
    <form id="paymentForm">
        <input type="hidden" name="booking_id" value="<?= $booking->id ?>"/>
        <div class="container pc">
            <div class="col-sm-36 mt30">
                <ul id="progress">
                    <li class="active text-left">1. Điền thông tin đặt hàng</a></li>
                    <li class="active text-center">2. Thanh toán</li>
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
                <span class="semi-bold box-underline-center fs24 pb20">THANH TOÁN ĐƠN HÀNG</span>
            </div>
        </div>
        <div class="payment-detail mb20">
            <div class="container">
                <div class="bg-white p20">
                    <?php if ($booking->type == HOTEL): ?>
                        <?= $this->element('Front/Payment/hotel') ?>
                    <?php endif; ?>
                    <?php if ($booking->type == LANDTOUR): ?>
                        <?= $this->element('Front/Payment/land_tour') ?>
                    <?php endif; ?>
                    <?php if ($booking->type == VOUCHER): ?>
                        <?= $this->element('Front/Payment/voucher') ?>
                    <?php endif; ?>
                    <?php if ($booking->type == HOMESTAY): ?>
                        <?= $this->element('Front/Payment/homestay') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="bg-white p20">
                    <h3 class="text-light-blue">Thông tin đơn hàng</h3>
                    <?php if ($booking->type == LANDTOUR && $booking->payment_method == MUSTGO_DEPOSIT): ?>
                        <div class="col-sm-36 mb15">
                            <p class="fs14 mb10">Tổng giá trị đơn hàng: <span
                                    class="text-red"><?= number_format($bookingPrice) ?> VNĐ</span></p>
                            <p class="fs14 mb10">Mustgo thu hộ: <span
                                    class="text-red"><?= number_format($booking->mustgo_deposit) ?> VNĐ</span></p>
                            <p class="fs14">Nội dung thanh toán: <span
                                    class="text-red">Thanh toán booking số <?= $booking->code ?></span></p>
                        </div>
                    <?php else: ?>
                        <div class="col-sm-36 mb15">
                            <p class="fs14 mb10">Tổng giá trị đơn hàng: <span
                                    class="text-red"><?= number_format($bookingPrice) ?> VNĐ</span></p>
                            <p class="fs14">Nội dung thanh toán: <span
                                    class="text-red">Thanh toán booking số <?= $booking->code ?></span></p>
                        </div>
                    <?php endif; ?>
                    <?php
                    $atm_and_qr = 0;
                    $visa = 0;
                    if ($booking->user_id != $booking->sale_id) {
                        $atm_and_qr = round($bookingPrice / (100 - 1.1) * 100 + 1760 - $bookingPrice);
                        $visa = round($bookingPrice / (100 - 2.75) * 100 + 7150 - $bookingPrice);
                    } else {
                        $atm_and_qr = round($bookingPrice / (100 - 1.1) * 100 + 1760 - $bookingPrice);
                        $visa = round($bookingPrice / (100 - 2.75) * 100 + 7150 - $bookingPrice);
                    }
                    ?>
                    <?php if ($booking->mustgo_deposit > $booking->price && $booking->type == LANDTOUR && $booking->payment_method == MUSTGO_DEPOSIT): ?>
                        <input type="hidden" name="images" value=''/>
                        <input type="hidden" name="type" value='0'/>
                    <?php else: ?>

                        <h3 class="text-light-blue mb10">Phương thức thanh toán</h3>
                        <p class="error-messages" id="error_type"></p>
                        <p class="fs16 mb15 text-light-blue"><input type="radio" style="display: block"
                                                                    class="iCheck payment-check" name="invoice"
                                                                    value="<?= 1; ?>"
                                                                    data-field-id="have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 1) ? 'checked' : '' ?> >
                            Xuất hóa đơn</i></p>
                        <fieldset class="scheduler-border payment-fieldset" id="have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 1) ? 'style="display: block"' : '' ?>>
                            <p class="error-messages" id="error_invoice"></p>
                            <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_BALANCE ?>">
                                    Thanh toán bằng số dư tài khoản
                                </p>
                                <p class="text-detail">(Không thu phí. Nhận được Mã đặt phòng ngay)</p>
                                <p class="error-messages" id="error_not_enough_balance"></p>
                            <?php endif; ?>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_TRANSFER ?>"
                                                                             data-field-id="payment-transfer-have-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice) ? 'checked' : '' ?> >
                                Chuyển khoản ngân hàng</i>
                            </p>
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
                                <div class="col-sm-24">
                                    <div class="form-group full-height">
                                            <textarea class="form-control" name="invoice_information"
                                                      style="height: 100%"><?= (isset($booking->payment->invoice_information)) ? $booking->payment->invoice_information : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="deligate-payment">
                                    <div class="row ml15 mr15 mb15 mt15">
                                        <div class="col-sm-36">
                                            <h4 class="text-light-blue mb10"> Ủy nhiệm đơn hàng</h4>
                                            <p class="fs14 mb10">Sau khi thanh toán đơn hàng bằng tài khoản ngân hàng, quý khách
                                                chụp lại hóa đơn thanh toán rồi gửi về cho mustgo. Mustgo sẽ xác nhận lại và liên hệ
                                                cho quý khách.</p>
                                            <p class="error-messages" id="error_images"></p>
                                        </div>
                                        <div class="col-sm-36 text-center">
                                            <div id="dropzone-upload" class="dropzone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_CREDIT ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_CREDIT) ? 'checked' : '' ?>>
                                Thẻ tín dụng / Ghi nợ
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/VS.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/MC.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/amex.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/JC.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($visa)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_ATM ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_ATM) ? 'checked' : '' ?>>
                                Thẻ ATM / Tài khoản ngân hàng
                                <img style="width: 6%" src="<?= $this->Url->assetUrl('/frontend/img/payment/atm_logo.png') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_QR ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_QR) ? 'checked' : '' ?>>
                                Thanh toán qua QR
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/qr_code.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                        </fieldset>
                        <p class="fs16 mb15 text-light-blue"><input type="radio" style="display: block"
                                                                    class="iCheck payment-check" name="invoice"
                                                                    value="<?= 0; ?>"
                                                                    data-field-id="no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 0) ? 'checked' : '' ?> >
                            Không xuất hóa đơn</i></p>
                        <fieldset class="scheduler-border payment-fieldset" id="no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice == 0) ? 'style="display: block"' : '' ?>>
                            <p class="error-messages" id="error_invoice"></p>
                            <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="type" value="<?= PAYMENT_BALANCE ?>">
                                    Thanh toán bằng số dư tài khoản
                                </p>
                                <p class="text-detail">(Không thu phí. Nhận được Mã đặt phòng ngay)</p>
                                <p class="error-messages" id="error_not_enough_balance"></p>
                            <?php endif; ?>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_TRANSFER ?>"
                                                                             data-field-id="payment-transfer-no-invoice" <?= (isset($booking->payment->invoice) && $booking->payment->invoice) ? 'checked' : '' ?> >
                                Chuyển khoản ngân hàng</p>
                            <div class="invoice-zone"
                                 id="payment-transfer-no-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_TRANSFER) ? 'style="display: block"' : '' ?>>
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
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_CREDIT ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_CREDIT) ? 'checked' : '' ?>>
                                Thẻ tín dụng / Ghi nợ
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/VS.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/MC.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/amex.svg') ?>">
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/JC.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($visa)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_ATM ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_ATM) ? 'checked' : '' ?>>
                                Thẻ ATM / Tài khoản ngân hàng
                                <img style="width: 6%" src="<?= $this->Url->assetUrl('/frontend/img/payment/atm_logo.png') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                            <p class="fs14 text-light-blue ml10"><input type="radio" class="iCheck invoice-check"
                                                                             name="type" value="<?= PAYMENT_ONEPAY_QR ?>"
                                                                             data-field-id="has-invoice" <?= (isset($booking->payment->type) && $booking->payment->type == PAYMENT_ONEPAY_QR) ? 'checked' : '' ?>>
                                Thanh toán qua QR
                                <img src="<?= $this->Url->assetUrl('/frontend/img/payment/qr_code.svg') ?>">
                            </p>
                            <p class="text-detail">(Phí <span class="text-red"><?= number_format($atm_and_qr)?> VNĐ</span>. Nhận được Mã đặt phòng ngay)</p>
                        </fieldset>
                    <?php endif; ?>
                    <div class="mt40 text-center text-white">
                        <button type="button" class="btn btn-request-booking semi-bold" id="requestPayment">Gửi yêu cầu</button>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
