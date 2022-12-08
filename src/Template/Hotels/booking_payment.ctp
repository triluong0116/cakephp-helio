<div class="bg-grey" xmlns="http://www.w3.org/1999/html">
    <form>
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
        <div class="combo-detail pb50">
            <div class="container ">
                <div class="bg-white p20">
                    <h3 class="text-light-blue">Thông tin đơn hàng</h3>
                    <div class="col-sm-36 mb15">
                        <p class="fs14 mb10">Tổng giá trị đơn hàng: <span class="text-red">1.500.000 VNĐ</span></p>
                        <p class="fs14">Nội dung thanh toán: <span class="text-red">Thanh toán booking</span></p>
                    </div>
                    <h3 class="text-light-blue mb10">Hình thức thanh toán</h3>
                    <p class="fs16 mb15 text-light-blue"><input type="checkbox" class="iCheck other-surcharge-check" value=""> Chuyển khoản ngân hàng</i></p>
                    <fieldset class="scheduler-border">
                        <div class="col-sm-36 mt10">
                            <p class="fs14 mb15 text-light-blue"><input type="checkbox" class="iCheck other-surcharge-check" value=""> Không xuất hóa đơn</i></p>
                            <p class="fs14 mb10">Quý khách vui lòng thanh toán hóa đơn bằng cách chuyển tiền vào những đian chỉ ngân hàng dưới đây</p>
                        </div>
                        <div class="row ml15 mr15 mb15 mt15">
                            <div class="col-sm-12">
                                <div class="bank-account-detail text-center p20">
                                    <img src="<?= $this->Url->assetUrl('frontend/img/vietcombank-logo.png') ?>">
                                    <p class="fs14">Ngân Hàng Vietcombank</p>
                                    <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                    <p class="fs14">Số TK: 19024705183012</p>
                                    <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="bank-account-detail text-center p20">
                                    <img src="<?= $this->Url->assetUrl('frontend/img/bidv-logo.png') ?>">
                                    <p class="fs14">Ngân Hàng BIDV</p>
                                    <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                    <p class="fs14">Số TK: 19024705183012</p>
                                    <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="bank-account-detail text-center p20">
                                    <img src="<?= $this->Url->assetUrl('frontend/img/techcombank-logo.png') ?>">
                                    <p class="fs14">Ngân Hàng Techcombank</p>
                                    <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                    <p class="fs14">Số TK: 19024705183012</p>
                                    <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                </div>
                            </div>
                        </div>
                        <div class="row ml15 mr15 mb15 mt15">
                            <div class="col-sm-12">
                                <div class="bank-account-detail text-center p20">
                                    <img src="<?= $this->Url->assetUrl('frontend/img/vpbank-logo.png') ?>">
                                    <p class="fs14">Ngân Hàng VPBank</p>
                                    <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                    <p class="fs14">Số TK: 19024705183012</p>
                                    <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="bank-account-detail text-center p20">
                                    <img src="<?= $this->Url->assetUrl('frontend/img/sacombank-logo.png') ?>">
                                    <p class="fs14">Ngân Hàng Sacombank</p>
                                    <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                    <p class="fs14">Số TK: 19024705183012</p>
                                    <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                </div>
                            </div>
                        </div>
                        <p class="fs14 mb15 text-light-blue ml10"><input type="checkbox" class="iCheck other-surcharge-check" value=""> Không xuất hóa đơn</i></p>
                        <div class="row ml15 mr15 mb15 mt15">
                            <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                            <div class="row-eq-height">
                                <div class="col-sm-12">
                                    <div class="bank-account-detail text-center pb20">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/techcombank-logo.png') ?>">
                                        <p class="fs14">Ngân Hàng Techcombank</p>
                                        <p class="fs14">Tên TK: Chu Việt Dũng</p>
                                        <p class="fs14">Số TK: 19024705183012</p>
                                        <p class="fs14">Chi nhánh: Hàng Đậu</p>
                                    </div>
                                </div>
                                <div class="col-sm-24">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="8"  placeholder="Thông tin xuất hóa đơn..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-light-blue mb10"> Ủy nhiệm đơn hàng</h4>
                            <p class="fs14 mb10">Sau khi thanh toán đơn hàng bằng tài khoản ngân hàng, quý khách chụp lại hóa đơn thanh toán rồi gửi về cho mustgo. Mustgo sẽ xác nhận
                                lại và liên hệ cho quý khách.</p>
                        </div>
                        <div class="row ml15 mr15 mb15 mt15">
                            <div class="col-sm-36 text-center">
                                <h2>DROPZONE IMG</h2>
                            </div>
                        </div>
                    </fieldset>
                    <p class="fs16 mb15 text-light-blue"><input type="checkbox" class="iCheck other-surcharge-check" value=""> Thanh toán tại văn phòng</i></p>
                    <fieldset class="scheduler-border">
                        <div class="row ml15 mr15 mb15 mt15">
                            <p class="fs14">Quý khách vui lòng đến địa chỉ bên dưới để thanh toán</p>
                            <p class="fs14">Địa chỉ:<span class="fs14 text-light-blue"> P.402 Tầng 4 tòa nhà Lake Side, số 71 phố Chùa Láng, phường Láng Thượng, quận Đống Đa, Hà Nội.</span></p>
                            <p class="fs14 mt10">Thời gian làm việc:</p>
                            <p class="fs14"><span class="fs14 text-light-blue">9h00 - 18h00</span> các ngày từ thứ 2 đến thứ 6</p>
                            <p class="fs14"><span class="fs14 text-light-blue">9h00 - 12h00</span> thứ 7</p>
                            <p class="fs14 mt10">Hotline: <span class="fs14 text-light-blue"></span>094.471.2662</p>
                        </div>
                    </fieldset>
                    <p class="fs16 mb15 text-light-blue"><input type="checkbox" class="iCheck other-surcharge-check" value=""> Thanh toán tại nhà</i></p>
                    <fieldset class="scheduler-border">
                        <div class="row ml15 mr15 mb15 mt15">
                            <p class="fs14 mb10">Quý khách vui lòng điền địa chỉ liên hệ để MustGo đến thanh toán</p>
                            <div class="col-sm-36">
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" placeholder="Địa chỉ liên hệ..."></textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>
</div>