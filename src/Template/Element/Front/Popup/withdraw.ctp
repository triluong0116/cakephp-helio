<div class="modal fade" id="withdraw" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Xin mời nhập thông tin</h4>
            </div>
            <div class="modal-body">
                <form id="addWithdraw">
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher currency" placeholder="Số tiền" name="amount">
                        <p id="error_amount" class="error-messages "></p>
                    </div>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <button type="button" class="form-control btn btn-submit" onclick="Frontend.processWithdraw(this)">
                                <i class="fas fa-spinner fa-pulse hidden"></i> Rút</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="finishWithdraw" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mt120 mb120" style="m">Bạn đã thao tác thành công</h1>
            </div>
        </div>
    </div>
</div>