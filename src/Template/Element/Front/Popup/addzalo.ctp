<div id="zalo" class="modal fade modal-fb" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-voucher border-bottom-blue ">
            <div class="modal-header" >
                <button type="button" class="modal-close" data-dismiss="modal" ><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body mt85 mb85" style="height: 300px;">
                <div class="mb10 mt70">
                    <form id="editZalo">
                        <div class="form-group">
                            <p class="text-center fs22 mt50 mb30">Vui lòng nhập thông tin zalo của bạn</p>
                            <input type="text" class="form-control popup-voucher" id="zalo" placeholder="Zalo" name="zalo" value="<?= $user->zalo ?>">
                            <p id="error_title" class="error-messages"></p>
                        </div>
                        <div class="row pt25 pb50">
                            <div class="col-sm-12 col-sm-offset-12">
                                <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.editZalo()" value="Cập nhật">
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="finishZalo" role="dialog">
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