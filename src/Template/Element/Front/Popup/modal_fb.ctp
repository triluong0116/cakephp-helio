<!-- Modal -->
<div id="modal-loading-fb" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-facebook border-blue">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt85 mb85" >
                <div class="mb10">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="text-center">
                                <p class="text-dark mt50 fs20">Kết nối vào tài khoản <span class="main-color">Facebook</span></p>
                                <div class="spinner">
                                    <div class="bounce1"></div>
                                    <div class="bounce2"></div>
                                    <div class="bounce3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<!-- endModal -->
<!-- Modal -->
<div id="modal-fb-finish" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-facebook border-blue ">
            <div class="modal-header" >
                <button type="button" class="modal-close" data-dismiss="modal" ><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt85 mb85" style="height: 300px;">
                <div class="mb10 mt70">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-15">
                            <div class="square-image">
                                <img id="fb-avatar" class="img-circle" />
                            </div>
                        </div>
                        <div class="col-sm-36">
                            <div class="text-center text-dark fs20">
                                <p>Chúc mừng <span class="main-color" id="fb-name"></span> đã trở thành</p>
                                <p><span class="main-color">Cộng tác viên</span> của chúng tôi</p>
                            </div>
                            <div class="text-center mt30">
                                <a class="btn bg-blue text-white" onclick="Frontend.checkZaloStatus()">Cập nhật thông tin</a>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<!-- endModal -->

<!-- Modal -->
<div id="modal-fb-finish-2" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-facebook border-blue ">
            <div class="modal-header" >
                <button type="button" class="modal-close" data-dismiss="modal" ><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt85 mb85" style="height: 300px;">
                <div class="mb10 mt70">
                    <div class="row">
<!--                        <div class="col-sm-6 col-sm-offset-15">
                            <div class="square-image">
                                <img id="fb-avatar" class="img-circle" />
                            </div>
                        </div>-->
                        <div class="col-sm-36 mt70">
                            <div class="text-center text-dark fs20">
                                <p><span class="main-color">Bạn đã cập nhật thông tin cá nhân</span></p>
                            </div>
                            <div class="text-center mt30">
                                <a class="btn bg-blue text-white" href="">Tiếp tục</a>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<!-- endModal -->

<!-- Modal -->
<div id="modal-update-info" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-voucher border-bottom-blue ">
            <div class="modal-header" >
                <button type="button" class="modal-close" data-dismiss="modal" ><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt85 mb85" style="height: 300px;">
                <div class="mb10 mt70">
                    <form id="updateZalo">
                        <div class="form-group">
                            <p class="text-center fs22 mt50 mb30">Vui lòng nhập thông tin zalo của bạn</p>
                            <input type="text" class="form-control popup-voucher" id="zalo" placeholder="Zalo" name="zalo">
                            <p id="error_title" class="error-messages"></p>
                        </div>
                        <div class="row pt25 pb50">
                            <div class="col-sm-12 col-sm-offset-12">
                                <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.loadModalUpdateInfo()" value="Cập nhật">
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->