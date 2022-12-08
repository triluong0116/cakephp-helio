<div class="modal fade" id="forgetPassword" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Quên mật khẩu</h4>
            </div>
            <div class="modal-body">
                <form id="forgetTrippal">
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" placeholder="Email" name="email">
                        <p id="error_email" class="error-messages"></p>
                    </div>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <button type="button" class="form-control btn btn-submit" onclick="Frontend.forgetPassword(this)"><i class="fas fa-spinner fa-pulse hidden"></i> Lấy lại mật khẩu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="forgotPassword-sent-success" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content -->
        <div class="modal-content modal-voucher p100">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>

            </div>
            <div class="modal-body">
                <center><p class="bold fs20 main-color">Mật khẩu đã được gửi đến email của bạn. Vui lòng check email để lấy password mới.</p></center>
            </div>
        </div>
    </div>
</div>