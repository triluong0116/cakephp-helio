<?php
echo $this->Html->css('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'cssHeader']);
echo $this->Html->script('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'scriptBottom']);
$this->Html->scriptBlock('Dropzone.autoDiscover = false;', ['block' => 'scriptBottom']);
?>
<div class="bg-grey" xmlns="http://www.w3.org/1999/html">
    <div class="container pc">
        <div class="col-sm-36 mt30">
            <ul id="progress">
                <li class="active text-left">1. Điền thông tin đặt hàng</a></li>
                <li class="active text-center">2. Thanh toán</li>
                <li class="active text-right">3. Hoàn tất</li>
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
            <div class="bg-white p20" style="min-height: 200px">
                <h3 class="text-light-blue">Thông tin đơn hàng</h3>
                <div class="col-sm-36 mb15">
                    <div class="col-sm-18">
                        <p class="fs14 mb10">Tổng giá trị đơn hàng: </p>
                    </div>
                    <div class="col-sm-18">
                        <p class="fs14 mb10"><span class="text-red"><?= number_format($bookingPrice) ?> VNĐ</span></p>
                    </div>
                    <div class="col-sm-18">
                        <p class="fs14">Nội dung thanh toán: </p>
                    </div>
                    <div class="col-sm-18">
                        <p class="fs14"><span class="text-red">Thanh toán booking số <?= $booking->code ?></span></p>
                    </div>
                </div>
                <?php if ($payment->type == PAYMENT_ONEPAY_CREDIT || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_QR): ?>
                    <h3 class="text-light-blue">Tình trạng thanh toán</h3>
                    <div class="col-sm-36 mb15">
                        <div class="col-sm-18">
                            <p class="fs14 mb10">Trạng thái thanh toán OnePay: </p>
                        </div>
                        <div class="col-sm-18">
                            <?php
                            switch ($arrayOnepayData['vpc_TxnResponseCode']) {
                                case 0:
                                    $status = "Giao dịch thanh toán thành công.";
                                    break;
                                case 300:
                                    $status = "Giao dịch đang chờ xử lý.";
                                    break;
                                case 100:
                                    $status = "Giao dịch đang tiến hành hoặc chưa thanh toán.";
                                    break;
                                default:
                                    $status = "Giao dịch không thanh toán thành công.";
                                    break;
                            }
                            ?>
                            <p class="fs14 mb10"><span class="text-red"><?= $status ?></span></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($booking->mustgo_deposit > $booking->price && $booking->type == LANDTOUR && $booking->payment_method == MUSTGO_DEPOSIT): ?>
                <?php else: ?>
                    <h3 class="text-light-blue mb10">Hình thức thanh toán</h3>
                    <?php if (isset($payment) && ($payment->type == PAYMENT_ONEPAY_CREDIT || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_QR)): ?>
                        <p class="fs16 mb15 text-light-blue"><i class="fas fa-check"></i> Thanh toán OnePay</p>
                    <?php endif; ?>
                    <?php if (isset($payment) && $payment->type == PAYMENT_TRANSFER): ?>
                        <p class="fs16 mb15 text-light-blue"><i class="fas fa-check"></i> Chuyển khoản ngân hàng</p>
                    <?php endif; ?>
                    <?php if (isset($payment) && $payment->type == PAYMENT_TRANSFER): ?>
                        <fieldset class="scheduler-border">
                            <?php if (isset($payment) && $payment->invoice == 0): ?>
                                <p class="fs14 mb15 text-light-blue ml10"><i class="fas fa-check"></i> Không xuất hóa đơn</p>
                            <?php endif; ?>
                            <?php if (isset($payment) && $payment->invoice == 0): ?>
                                <div>
                                    <p class="fs14 mb10">Quý khách vui lòng thanh toán hóa đơn bằng cách chuyển tiền vào những đian chỉ ngân hàng dưới đây</p>
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
                                        <?php if ($key % 3 == 2): ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($payment) && $payment->invoice == 1): ?>
                                <p class="fs14 mb15 text-light-blue ml10"><i class="fas fa-check"></i> Xuất hóa đơn VAT</i></p>
                                <div>
                                    <div class="row ml15 mr15 mb15 mt15">
                                        <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                                        <div class="row-eq-height">
                                            <div class="col-sm-12">
                                                <?php if ($bank_invoice): ?>
                                                    <div class="bank-account-detail text-center p20">
                                                        <img src="<?= $this->Url->assetUrl($bank_invoice['bank_logo']) ?>">
                                                        <p class="fs14 mt05"><?= $bank_invoice['bank_name'] ?></p>
                                                        <p class="fs14">Tên TK: <?= $bank_invoice['account_name'] ?></p>
                                                        <p class="fs14">Số TK: <?= $bank_invoice['account_number'] ?></p>
                                                        <p class="fs14">Chi nhánh: <?= $bank_invoice['bank_branch'] ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-24">
                                                <div class="form-group full-height">
                                                    <h4>Thông tin xuất hóa đơn:</h4>
                                                    <p><?= $payment->invoice_information ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </fieldset>
                    <?php endif; ?>
                    <?php if ($payment->images): ?>
                        <div class="deligate-payment">
                            <div class="row ml05 mr05 mb15 mt15">
                                <h4 class="text-light-blue mb10"> Ủy nhiệm đơn hàng</h4>
                                <p class="fs14 mb10">Sau khi thanh toán đơn hàng bằng tài khoản ngân hàng, quý khách chụp lại hóa đơn thanh toán rồi gửi về cho mustgo. Mustgo sẽ xác nhận lại và liên hệ cho quý khách.</p>
                                <p class="error-messages" id="error_images"></p>
                                <div class="col-sm-36 text-center">
                                    <?php if (isset($payment)): ?>
                                        <?php
                                        $list_images = json_decode($payment->images, true);
                                        ?>
                                        <?php if ($list_images): ?>
                                            <div class="row row-eq-height mt30">
                                                <div class="col-sm-36 col-xs-36 ">
                                                    <div class="combo-slider">
                                                        <div class="box-image">
                                                            <div class="imgs_gird grid_6_small">
                                                                <div class="lightgallery2">
                                                                    <?php

                                                                    $other = count($list_images) - 4;
                                                                    ?>
                                                                    <?php if ($list_images): ?>
                                                                        <?php foreach ($list_images as $key => $image): ?>
                                                                            <?php
                                                                            $class = '';

                                                                            if ($key <= 3) {
                                                                                $class = 'img item_' . $key;
                                                                                $class .= ' medium-small';
                                                                                if ($key == 3) {
                                                                                    $class .= ' end';
                                                                                }
                                                                            } else {
                                                                                $class = 'hide';
                                                                            }
                                                                            ?>
                                                                            <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                                                <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                                                <?php if ($key > 2): ?>
                                                                                    <span class="other-small">+<?= $other ?></span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
