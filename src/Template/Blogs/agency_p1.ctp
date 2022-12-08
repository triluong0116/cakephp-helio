<!-- Header Combo -->
<!-- End Header Combo -->
<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div class="col-sm-36">
                <center>
                    <div class="mt40">
                        <h2>Các lí do để trở thành</h2>
                    </div>
                    <div class="mt15 pb50">
                        <h2><b class="box-underline-center pb20">HUẤN LUYỆN VIÊN DU LỊCH ONLINE</b></h2>
                    </div>
                </center>
                <div class="box-shadow bg-white">
                    <div class="p30 fs13 mr20 ">
                        <?= $config->value ?>
                    </div>
                </div>
                <div class="pt30 pb30">
                    <?php if ($this->request->getSession()->read('Auth.User.id')): ?>
                        <center>
                            <a class="btn bg-blue text-white fs18 mb20 text-center" href="<?= \Cake\Routing\Router::url('/chinh-sach-cong-tac-vien-page-2') ?>">TIẾP TỤC</a>
                        </center>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-sm-offset-12 col-sm-13 bg-blue mb50">
                                <span class="btn text-white fs18 text-center" onclick="Frontend.checkLoginViaFacebook();">ĐĂNG KÝ LÀM HƯỚNG DẪN VIÊN DU LỊCH</span>
                            </div>
                            <div class="col-sm-offset-6 col-sm-24 mb50">
                                <hr style="border: 1px solid #0098d5">
                            </div>
                            <div class="col-sm-offset-12 col-sm-13 mb20 text-center">
                                <span class="text-super-dark semi-bold fs18">ĐĂNG NHẬP NẾU BẠN ĐÃ CÓ TÀI KHOẢN</span>
                            </div>

                            <div class="col-sm-offset-14 col-sm-8 bg-fb mb20 no-pad-left no-pad-right">
                                <span class="btn bg-fb text-white fs14r" onclick="Frontend.checkLoginViaFacebookv2()">&nbsp;&nbsp;<i class="fab fa-facebook-f"></i>&nbsp;&nbsp;ĐĂNG NHẬP BẰNG FACEBOOK </span>
                            </div>
                            <div class="col-sm-offset-14 col-sm-8 bg-white no-pad-left no-pad-right">
                                <a class="btn text-dark fs14" data-toggle="modal" data-target="#loginViaTrippal">&nbsp;&nbsp;ĐĂNG NHẬP BẰNG TÀI KHOẢN</a>
                            </div>
                            <div class="forget-password col-sm-offset-14 col-sm-8 bg-white no-pad-left no-pad-right mt10">
                                <a class="btn text-dark fs12" data-toggle="modal" data-target="#forgetPassword">Quên mật khẩu ?</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->element('Front/Popup/modal_fb') ?>
<?= $this->element('Front/Popup/login_trippal') ?>
<?= $this->element('Front/Popup/forget_password') ?>
<?= $this->element('Front/Popup/promote') ?>


<!-- End content -->