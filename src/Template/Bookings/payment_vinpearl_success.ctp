<div class="bg-grey">
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
                    <li class="text-center active"><span class="fs15-sp">2. Thanh toán</span></li>
                    <li class="text-center active"><span class="fs15-sp">3. Hoàn tất</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="payment-detail pt20 pb20">
        <div class="container">
            <div class="bg-white p20">
                    <?php if ($booking->vinhmsbooking_rooms): ?>
                        <?php foreach ($booking->vinhmsbooking_rooms as $roomKey => $booking_room): ?>
                            <fieldset class="booking-room-item">
                                <legend>Hạng phòng <?= $roomKey + 1 ?></legend>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td><h4>Tên phòng: <?= $booking_room['vinhms_name'] ?></h4></td>
                                        </tr>
                                        <?php foreach ($booking_room['packages'] as $package): ?>
                                            <tr>
                                                <?php
                                                $arrText = explode('-', $package->vinhms_package_name);
                                                $packageName = '';
                                                foreach ($arrText as $kText => $text) {
                                                    $text = trim($text);
                                                    $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                                    $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                                }
                                                ?>
                                                <td class="bold">Gói đặt: <?= $packageName ?></td>
                                            </tr>
                                            <tr>
                                                <td>Mã gói: <?= $package->vinhms_package_code ?></td>
                                            </tr>
                                            <tr class="pc">
                                                <td>Ngày đi: <?= date('d-m-Y', strtotime($package->checkin)) ?></td>
                                                <td>Ngày về: <?= date('d-m-Y', strtotime($package->checkout)) ?></td>
                                            </tr>
                                            <tr class="sp">
                                                <td>Ngày đi: <?= date('d-m-Y', strtotime($package->checkin)) ?></td>
                                            </tr>
                                            <tr class="sp">
                                                <td>Ngày về: <?= date('d-m-Y', strtotime($package->checkout)) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td>Số người lớn: <?= $booking_room['num_adult'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Số trẻ em: <?= $booking_room['num_child'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Số em bé: <?= $booking_room['num_kid'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        <?php endforeach; ?>
                    <?php endif ?>
                <table class="table">
                    <tr>
                        <td style="min-width: 20%"><span class="pc">Họ và tên Trưởng đoàn</span> <span class="sp">Trưởng đoàn</span></td>
                        <td><?= $booking->sur_name . " " . $booking->first_name ?></td>
                    </tr>
                    <tr>
                        <td>Số điện thoại</td>
                        <td><?= $booking->phone ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?= $booking->email ?></td>
                    </tr>
                    <tr>
                        <td>Lưu ý</td>
                        <td><?= nl2br($booking->note) ?></td>
                    </tr>
                </table>
                <?php
                $listMemberRoom = json_decode($booking->vin_information, true);
                ?>
                <?php if ($listMemberRoom): ?>
                    <h3>Danh sách đoàn</h3>
                    <div class="pl15 pr15">
                        <?php foreach ($listMemberRoom as $kRoom => $room): ?>
                            <h3>Phòng <?= $kRoom + 1 ?></h3>
                            <?php foreach ($room as $member): ?>
                                <table class="table">
                                    <tr>
                                        <td>Họ tên: <?= $member['name'] ?></td>
                                        <td>Ngày sinh: <?= $member['birthday'] ?></td>
                                    </tr>
                                </table>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <div class="combo-detail pb20">
        <div class="container ">
            <div class="bg-white p20" style="min-height: 200px">
                <?php if ($payment->type == PAYMENT_ONEPAY_CREDIT || $payment->type == PAYMENT_ONEPAY_ATM || $payment->type == PAYMENT_ONEPAY_QR): ?>
                    <h3 class="text-light-blue">Mã đơn hàng Vinpearl: <span class="text-dark"><?= $booking->reservation_id ?></span></h3>
                <?php else: ?>
                    <?php if ($booking->reservation_id): ?>
                        <h3 class="text-light-blue">Mã đơn hàng Vinpearl: <span class="text-dark"><?= $booking->reservation_id ?></span></h3>
                    <?php endif; ?>
                <?php endif; ?>
                <h3 class="text-light-blue">Thông tin đơn hàng</h3>
                <div class="col-sm-36 mb15">
                    <div class="col-sm-18">
                        <p class="fs14 mb10">Tổng giá trị đơn hàng: </p>
                    </div>
                    <div class="col-sm-18">
                        <p class="fs14 mb10"><span class="text-red"><?= number_format($bookingPrice - $booking->sale_discount - $booking->agency_discount) ?> VNĐ</span></p>
                    </div>
                    <?php if ($booking->user_id != $booking->sale_id): ?>
                        <div class="col-sm-18">
                            <p class="fs14 mb10">Chiết khấu đại lý: </p>
                        </div>
                        <div class="col-sm-18">
                            <p class="fs14 mb10"><span class="text-red"><?= number_format($booking->revenue) ?> VNĐ</span></p>
                        </div>
                        <div class="col-sm-18">
                            <p class="fs14 mb10">Giá bán Đại lý: </p>
                        </div>
                        <div class="col-sm-18">
                            <p class="fs14 mb10"><span class="text-red"><?= number_format($bookingPrice - $booking->revenue - $booking->sale_discount - $booking->agency_discount) ?> VNĐ</span></p>
                        </div>
                    <?php endif; ?>
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
                                case 99:
                                    $status = "Hủy giao dịch.";
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

    <div class="container pb20 mb20">
        <div class="bg-white pt50 pb50 ">
            <p style="text-align: justify" class="main-color fs20 pl30 pr30 pt10"> Cảm ơn bạn đã sử dụng hệ thống Vinpearl Booking Online của Mustgo. Chúng tôi đã nhận được đơn hàng và Ủy nhiện chi thanh toán. Mustgo sẽ
                gửi đặt phòng qua Vinpearl tại thời điểm xác nhận tiền nổi trên tài khoản Mustgo. Tình trạng phòng có thể hết trước khi Mustgo Xác định tiền nổi trên tài khoản. Quý khách vui lòng liên
                hệ Sale để được hỗ trợ:</p>
            <?php if ($sale): ?>
                <p class="main-color fs20 pl30 pr30 pt10">Sale Admin: <?= $sale->screen_name ?></p>
                <p class="main-color fs20 pl30 pr30 pt10">Số điện thoại: <?= $sale->phone ?></p>
            <?php else: ?>
                <p class="main-color fs20 pl30 pr30 pt10">Số điện thoại: 092.5959.777</p>
            <?php endif; ?>
        </div>
    </div>
</div>
