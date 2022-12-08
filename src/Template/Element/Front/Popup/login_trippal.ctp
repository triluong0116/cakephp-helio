<div class="modal fade" id="loginViaTrippal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Đăng nhập bằng tài khoản Mustgo</h4>
            </div>
            <div class="modal-body">
                <form id="loginTrippal">
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" placeholder="Tên tài khoản/Email" name="username">
                        <p id="error_username" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control popup-voucher" placeholder="Mật khẩu" name="password">
                        <p id="error_password" class="error-messages"></p>
                    </div>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <button type="button" class="form-control btn btn-submit" onclick="Frontend.loginViaTrippal(this)"><i class="fas fa-spinner fa-pulse hidden"></i> Đăng nhập</button>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>