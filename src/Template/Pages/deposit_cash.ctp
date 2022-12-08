<!-- Start content -->
<?php
echo $this->Html->css('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'cssHeader']);
echo $this->Html->script('/frontend/libs/dropzone/dist/min/dropzone.min', ['block' => 'scriptBottom']);
$this->Html->scriptBlock('Dropzone.autoDiscover = false;', ['block' => 'scriptBottom']);
?>
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div>
                <center>
                    <div class="pt25 pb50">
                        <h2><span class="box-underline-center text-primary pb20">Nạp tiền</span></h2>
                    </div>
                </center>
            </div>
            <div class="pt05 pb30">
                <center>
                    <form id="recharge">
                        <div class="row bg-white text-left">
                            <div class="col-sm-18 mt15" style="border-right: 2px dashed lightgrey">
                                <div class=" col-sm-36 mt15">
                                    <p class="fs16 mb10 pl15 text-muted"><i class="fa-solid fa-credit-card"></i> Thông tin tài khoản doanh nghiệp</p>
                                    <p class="fs14 mb10 pl15 mt15 text-danger">
                                        <span class="fst-italic">Quý khách vui lòng thanh toán vào tài khoản dưới đây.</span>
                                    </p>
                                    <?php foreach ($bank_accounts as $key => $bank): ?>
                                        <?php if ($key == 0 || $key % 3 == 0): ?>
                                            <div class="row ml15 mr15 mb15 mt15 row-eq-height">
                                        <?php endif; ?>
                                        <div class="col-sm-36 bank-account-detail m10">
                                            <div class="text-center p20">
                                                <img src="<?= $this->Url->assetUrl($bank['bank_logo']) ?>">
                                                <p class="fs14 mt05"><?= $bank['bank_name'] ?></p>
                                                <p class="fs14">Tên TK: <?= $bank['account_name'] ?></p>
                                                <p class="fs14">Số TK: <?= $bank['account_number'] ?></p>
                                                <p class="fs14">Chi nhánh: <?= $bank['bank_branch'] ?></p>
                                            </div>
                                        </div>
                                        <?php if ($key % 3 == 2 || $key == count($bank_accounts) - 1): ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="col-sm-18 mt15">
                                <p class="fs16 mb10 pl15 mt15 text-muted"><i class="fa-solid fa-file"></i> Thông tin</p>
                                <div class=" col-sm-36 mt15">
                                    <p class="error-messages" id="error_title"></p>
                                    <div class="row">
                                        <div class="col-sm-36">
                                            <input type="text" name="title"
                                                   class="form-control popup-voucher" placeholder="Nhập tiêu đề">
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-sm-36 mt15">
                                    <p class="error-messages" id="error_amount"></p>
                                    <div class="row">
                                        <div class="col-sm-36">
                                            <input type="text" name="amount"
                                                   class="form-control popup-voucher currency" placeholder="Nhập số tiền nạp">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-36 mt15">
                                    <p class="error-messages" id="error_message"></p>
                                    <div class="row">
                                        <div class="col-sm-36">
                                            <input type="text" name="message"
                                                   class="form-control popup-voucher" readonly
                                                   value="<?= $code ?>" placeholder="Nội dung chuyển khoản">
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-sm-36 mt15">
                                    <div class="deligate-payment">
                                        <div class="row mb15 mt15">
                                            <h4 class="fs14 ml15 mb10">Ảnh UNC nạp tiền</h4>
                                            <p class="error-messages" id="error_image"></p>
                                            <div class="col-sm-36 text-center">
                                                <div id="dropzone-upload" class="dropzone drop-zone"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-sm-36 pt15 pb15">
                                    <div class="col-sm-12 col-sm-offset-12">
                                        <input type="button" name="btn-submit" class="form-control btn btn-submit"
                                               value="Submit" onclick="Frontend.recharge()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </center>
            </div>
        </div>
    </div>
</div>
