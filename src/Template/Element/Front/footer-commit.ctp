<!-- Footer -->
<style>
    .logo-footer {
        margin-top: 0px !important;
    }
</style>
<?//= $this->element('/Front/message') ?>
<footer class="footer commit-footer">
    <div class="container-commit">
        <div class="logo-footer">
            <div class="row">
                <div class="col-sm-6 col-xs-36 col-sm-offset-3">
                    <a href="<?= \Cake\Routing\Router::url('/') ?>">
                        <img src="./webroot/frontend/logo-commint.png" class="w-150 pc" alt="Trippal"/>
                        <img src="./webroot/frontend/logo-commint.png" class="w-100 sp" alt="Trippal"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-content mt20">
            <div class="row">
                <div class="col-sm-10  col-xs-36 col-sm-offset-3">
                    <p class="text-footer fs18">Mustgo COMMIT là dòng thương hiệu cao cấp của Mustgo chuyên phân phối các khách sạn resort với những cam kết:</p>
                    <p class="text-footer fs18"><span><i class="fas fa-star color-yellow"></i></span> Giá tốt nhất thị trường</p>
                    <p class="text-footer fs18"><span><i class="fas fa-star color-yellow"></i></span> Dịch vụ tốt nhất thị trường</p>
                    <p class="text-footer fs18"><span><i class="fas fa-star color-yellow"></i></span> Thông tin chính xác nhất</p>
                    <p class="mt20 text-footer fs18">Copyright © 2015-2018. Design by <a class="color-yellow" href="#">Helitech Solutions</a></p>
                </div>
                <div class="col-sm-5  col-xs-36">
                    <p class="fs18 text-white semi-bold">VỀ MUSTGO</p>
                    <ul class="mt40 footer-menu">
                        <li><a href="<?= \Cake\Routing\Router::url('/cam-nang') ?>">Review</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/chinh-sach-rieng-tu-bao-mat') ?>">Chính sách bảo mật</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/dieu-khoan-su-dung') ?>">Quy chế hoạt động</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/giai-quyet-tranh-chap') ?>">Cơ chế giải quyết tranh chấp</a></li>
                    </ul>
                </div>
                <div class="col-sm-5  col-xs-36">
                    <p class="fs18 text-white semi-bold">HỖ TRỢ</p>
                    <ul class="mt40 footer-menu">
                        <li><a href="<?= \Cake\Routing\Router::url('/cau-hoi-thuong-gap') ?>">Câu hỏi thường gặp</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/chinh-sach-cam-ket-gia-tot-nhat') ?>">Chính sách cam kết giá tốt nhất</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/huong-dan-thanh-toan') ?>">Hướng dẫn quy trình thanh toán</a></li>
                        <li><a href="<?= \Cake\Routing\Router::url('/chinh-sach-cong-tac-vien') ?>">Chính sách Cộng tác viên</a></li>
                    </ul>
                </div>
                <div class="col-sm-10 col-xs-36">
                    <p class="fs18 text-white semi-bold mb40">HỖ TRỢ MIỄN PHÍ 24/7</p>
                    <p class="fs14 text-white ml20"><i class="fas fa-phone"></i> 092.5959.777</p>
                    <p class="fs14 text-white semi-bold">Tổng đài hỗ trợ 24/7 hoặc gửi email về địa chỉ <a class="color-yellow" href="mailto:support@mustgo.vn">support@mustgo.vn</a></p>
                    <div class="row">
                        <div class="col-sm-18 col-xs-18">
                            <div class="full-width-image mt10">
                                <a href='http://online.gov.vn/Home/WebDetails/63065'><img src="<?= $this->Url->assetUrl('frontend/img/logoSaleNoti.png') ?>"></a>
                            </div>
                        </div>
                        <div class="col-sm-18 col-xs-18">
                            <div class="full-width-image mt10">
                                <a href="http://online.gov.vn/Home/WebDetails/60425"><img src="<?= $this->Url->assetUrl('frontend/img/logoCCDV.png') ?>"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt20 mb20">
                <div class="col-sm-24 col-sm-offset-6">
                    <hr/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-36 text-center">
                    <p class="text-footer semi-bold fs18">CÔNG TY CP DU LỊCH LIÊN MINH VIỆT NAM</p>
                    <p class="text-footer mt10 regular fs14">Giấy phép kinh doanh số 0108205732 Đăng ký lần đầu ngày 29/03/2018 tại Hà Nội.</p>
                    <p class="text-footer mt10 regular fs14">Trụ sở chính: Số 122 Trần Đại Nghĩa, P Đồng Tâm, Q Hai Bà Trưng, TP Hà Nội.</p>
                    <p class="text-footer mt10 regular fs14">Địa chỉ giao dịch: Tầng 1, Đơn nguyên 1, Chung cư 43-45, Ngõ 130 Đốc Ngữ, Q Ba Đình, TP Hà Nội</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<div id="agency-noti-modal" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content modal-facebook border-blue">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt120 mb120 text-center">
                <p class="bold fs18">Bạn có yêu cầu về Combo</p>
            </div>
        </div>
    </div>
</div>

<?= $this->element('/Front/Popup/find-agency') ?>
<?= $this->element('/Front/Popup/modal_post_fb') ?>

