<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div>
                <center>

                    <div class="pt25 pb50">
                        <h2><span class="box-underline-center pb20 text-primary">Thông tin cá nhân</span></h2>
                    </div>
                </center>
            </div>
            <div class="pt05 pb30">
                <center>
                    <div style="margin-left: -15px;margin-right: -15px;">
                        <div class="bg-primary p15 text-left" id="privateInfo">
                            <span>Thông tin cá nhân</span>
                        </div>
                    </div>
                    <div class="row bg-white">
                        <div class="p30 ">
                            <div class="col-sm-18 border-right">
                                <div class="row">
                                    <div class=" col-sm-36">
                                        <div>
                                            <p class="text-left text-muted mb15 fs20"><i
                                                    class="fa-solid fa-user-pen"></i> Thay đổi thông tin cá nhân</p>
                                            <form id="usereditinfo" enctype="multipart/form-data" type="file">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="screen_name" placeholder="Vui lòng nhập tên hiển thị"
                                                           name="screen_name" value="<?php
                                                    if ($user->screen_name) {
                                                        echo $user->screen_name;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="phone" placeholder="Vui lòng nhập số điện thoại mới"
                                                           name="phone" value="<?php
                                                    if ($user->phone) {
                                                        echo $user->phone;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="phone" placeholder="Vui lòng nhập email mới" name="email"
                                                           value="<?php
                                                           if ($user->email) {
                                                               echo $user->email;
                                                           }
                                                           ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="bank_master" placeholder="Vui lòng tên chủ tài khoản"
                                                           name="bank_master" value="<?php
                                                    if ($user->bank_master) {
                                                        echo $user->bank_master;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="bank_code" placeholder="Vui lòng nhập số tài khoản"
                                                           name="bank_code" value="<?php
                                                    if ($user->bank_code) {
                                                        echo $user->bank_code;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="bank_name" placeholder="Vui lòng nhập tên ngân hàng"
                                                           name="bank_name" value="<?php
                                                    if ($user->bank) {
                                                        echo $user->bank_name;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="bank" placeholder="Vui lòng nhập chi nhánh ngân hàng"
                                                           name="bank" value="<?php
                                                    if ($user->bank) {
                                                        echo $user->bank;
                                                    }
                                                    ?>">
                                                    <p id="error_info" class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <div class="drop-zone">
                                                        <span class="drop-zone__prompt">Kéo thả ảnh vào đây hoặc <span
                                                                class="text-primary">tải lên tập tin</span></span>
                                                        <input type="file"
                                                               class="form-control popup-voucher user-infor border-blue-input drop-zone__input"
                                                               id="drop-zone__input" name="avatar">
                                                    </div>

                                                    <?php if ($user->avatar): ?>
                                                        <img class="w-100"
                                                             src="<?= $this->Url->assetUrl($user->avatar) ?>">
                                                    <?php endif ?>
                                                    <p id="error_info" class="error-messages"></p>

                                                </div>
                                                <div class="row pt15 pb15">
                                                    <div class="col-sm-12 col-sm-offset-12">
                                                        <input type="button" name="btn-submit"
                                                               class="form-control btn btn-submit" value="Lưu thay đổi"
                                                               onclick="Frontend.changeUserInfo()">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-18 mt40-sp">
                                <div class="row">
                                    <div class="col-sm-36">
                                        <div class="col-sm-36 mb20 no-pad-left no-pad-right">
                                            <p class="text-left text-muted mb15 fs20 "><i class="fa-solid fa-lock"></i>
                                                Đổi mật khẩu</p>
                                            <form id="usereditpass">
                                                <div class="form-group">
                                                    <input type="password"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="password" placeholder="Nhập mật khẩu mới"
                                                           name="password">
                                                    <p class="error-messages"></p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="password"
                                                           class="form-control popup-voucher user-infor border-blue-input"
                                                           id="re-password" placeholder="Nhập lại mật khẩu"
                                                           name="re_password">
                                                    <p id="error_re_password" class="error-messages"></p>
                                                </div>
                                                <div class="row pt15 pb15">
                                                    <div class="col-sm-12 col-sm-offset-12">
                                                        <input type="button" name="btn-submit"
                                                               class="form-control btn btn-submit" value="Lưu thay đổi"
                                                               onclick="Frontend.changeUserPassword()">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <p class="text-left text-muted mb15 fs20 "><i class="fa-solid fa-server"></i>
                                            Đồng bộ dữ liệu</p>
                                        <div class=" col-sm-36 bg-fb mb20 no-pad-left no-pad-right">
                                            <span class="btn bg-fb text-white fs14r"
                                                  onclick="Frontend.connectFacebook()">&nbsp;&nbsp;<i
                                                    class="fab fa-facebook-f"></i>&nbsp;&nbsp;ĐỒNG BỘ TÀI KHOẢN FACEBOOK </span>
                                        </div>
                                        <div class=" col-sm-36 bg-zalo mb20 no-pad-left no-pad-right">
                                            <span class="btn  text-white fs14r" onclick="Frontend.addZalo()">&nbsp;&nbsp;CẬP NHẬT TÀI KHOẢN ZALO</span>
                                        </div>

                                    </div>
                                    <div class="col-sm-36">
                                        <div class="mb20 no-pad-left no-pad-right">
                                            <hr>
                                            <p class="fs20 mt15 mb20 text-left text-muted"><i
                                                    class="fa-solid fa-link"></i> Link giới thiệu CTV:</p>
                                            <p class="border-link main-color"><?= $this->Url->build('/?ref=' . $user->ref_code, true); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>
<?= $this->element('Front/Popup/addzalo') ?>
