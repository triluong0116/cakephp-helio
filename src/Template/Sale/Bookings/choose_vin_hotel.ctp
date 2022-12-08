<div class="row">
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-12 mt10">
                <ul class="nav nav-tabs">
                    <?php for ($i = 0; $i < $numRoom; $i++): ?>
                        <li class="<?= $i == 0 ? 'active' : '' ?>"><a data-toggle="tab" href="#room-<?= $i ?>">Phòng <?= $i + 1 ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
            <div class="col-sm-12">
                <div class="tab-content">
                    <?php foreach ($singleVinChooseRoom as $i => $listRoom): ?>
                        <div id="room-<?= $i ?>" class="tab-pane <?= $i == 0 ? 'active' : '' ?>">
                            <?php foreach ($listRoom as $k => $room): ?>
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><?= $room['information']['name'] ?></h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-xs-12">
                                            <div class="x_content" style="display: none">
                                                <?php if (isset($room['package'])): ?>
                                                    <?php
                                                    $dataJson = [];
                                                    $dataJson['name'] = $room['information']['name'];
                                                    ?>
                                                    <input type="hidden" name="choose-room-<?= $k ?>" value='<?= json_encode($dataJson) ?>'>
                                                    <?php foreach ($room['package'] as $packageKey => $package): ?>
                                                        <div class="row">
                                                            <div class="col-sm-1 no-pad-right">
                                                                <p class="fs16 mb15 text-light-blue">
                                                                    <?php
                                                                    $price = $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']);
                                                                    $revenue = $package['customer_price'];
                                                                    $saleRevenue = $package['trippal_price'];
                                                                    ?>
                                                                    <input type="radio" class="iCheck vin-room-pick" name="package[<?= $i ?>]"
                                                                           data-rate-plan-code="<?= $package['rateAvailablity']['ratePlan']['rateCode'] ?>"
                                                                           data-room-type-code="<?= $package['rateAvailablity']['roomTypeCode'] ?>"
                                                                           data-allotment-id="<?= $package['rateAvailablity']['allotments'][0]['allotmentId'] ?>"
                                                                           data-package-name="<?= $package['rateAvailablity']['ratePlan']['name'] ?>"
                                                                           data-package-code="<?= $package['rateAvailablity']['ratePlanCode'] ?>"
                                                                           data-revenue="<?= $revenue ?>"
                                                                           data-sale-revenue="<?= $saleRevenue ?>"
                                                                           data-package-id="<?= $package['rateAvailablity']['propertyId'] ?>"
                                                                           data-rateplan-id="<?= $package['ratePlanID'] ?>"
                                                                           data-room-index="<?= $i ?>"
                                                                           data-room-key="<?= $k ?>"
                                                                           data-package-pice="<?= number_format($price) ?>"
                                                                           data-package-default-price="<?= $package['totalAmount']['amount']['amount'] ?>"
                                                                           data-package-left="<?= $package['amount_left'] ?>">
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <?php
                                                                $arrText = explode('-', $package['rateAvailablity']['ratePlan']['name']);
                                                                $packageName = '';
                                                                foreach ($arrText as $kText => $text) {
                                                                    $text = trim($text);
                                                                    $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                                                    $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                                                }
                                                                ?>
                                                                <p class="fs18" style="text-decoration: underline"><?= $packageName ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($dataRoom as $roomKey => $singleRoom): ?>
                <?php
                $total = $singleRoom['num_adult'] + $singleRoom['num_child'] + $singleRoom['num_kid'];
                ?>
                <div class="col-sm-12">
                    <h3>Phòng <?= $roomKey + 1 ?></h3>
                </div>
                <?php for ($i = 0; $i < $total; $i++): ?>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Họ và tên</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="vin_information[<?= $roomKey ?>][<?= $i ?>][name]" class="form-control" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày sinh</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="vin_information[<?= $roomKey ?>][<?= $i ?>][birthday]" class="form-control custom-singledate-picker" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thông tin đơn hàng</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php for ($i = 0; $i < $numRoom; $i++): ?>
                    <div class="single-room-detail" data-vinroom-price="0" data-vinroom-revenue="0" id="vin-room-<?= $i ?>" data-room-number="<?= $i ?>">

                    </div>
                    <div class="single-booking-vin-room-<?= $i ?> vin-bk-room">

                    </div>
                <?php endfor; ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="pull-right">
                            Tổng cộng
                        </h5>
                    </div>
                    <div class="col-sm-12">
                        <h5 class="pull-right text-orange fs24 semi-bold" id="">
                            <span id="totalVinBookingPrice">0</span> VNĐ
                        </h5>
                    </div>
                    <div class="col-sm-12">
                        <h5 class="pull-right">
                            Giảm giá
                        </h5>
                    </div>
                    <div class="col-sm-12">
                        <h5 class="pull-right text-orange fs24 semi-bold" id="">
                            <span id="totalDiscount">0</span> VNĐ
                        </h5>
                    </div>
                    <?php if ($this->request->session()->read('Auth.User.id') != $userId): ?>
                        <div class="col-sm-12">
                            <h5 class="pull-right">
                                Chiết khấu Đại Lý
                            </h5>
                        </div>
                        <div class="col-sm-12">
                            <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                <span id="totalVinBookingRevenue">0</span> VNĐ
                            </h5>
                        </div>
                        <div class="col-sm-12">
                            <h5 class="pull-right">
                                Đại lý phải thanh toán
                            </h5>
                        </div>
                        <div class="col-sm-12">
                            <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                <span id="totalAgencyPayVinBooking">0</span> VNĐ
                            </h5>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-6 col-xs-12 mt10">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thanh toán của Đại lý/Khách lẻ</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <h4 class="text-light-blue mb10">Phương thức thanh toán</h4>
                <p class="error-messages" id="error_type"></p>
                <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-check" name="payment[payment_type]" value="<?= PAYMENT_TRANSFER; ?>" data-field-id="payment-transfer" required> Chuyển khoản ngân hàng</i></p>
                <fieldset class="scheduler-border payment-fieldset" id="payment-transfer">
                    <p class="error-messages" id="error_invoice" ></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment[payment_invoice]" value="0" data-field-id="no-invoice" required> Không xuất hóa đơn</i></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment[payment_invoice]" value="1" data-field-id="has-invoice"> Xuất hóa đơn VAT</i></p>
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
                            <input type="hidden" name="media" value=''/>
                            <input type="hidden" name="list_image" value=''/>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

