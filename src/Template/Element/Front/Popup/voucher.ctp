<div class="modal fade" id="addingvoucher" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Đăng bán voucher</h4>
            </div>
            <div class="modal-body">
                <form id="addVoucher">
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="voucher" placeholder="Voucher" name="title">
                        <p id="error_title" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="time" placeholder="Thời hạn" name="time">
                        <p id="error_time" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="price_voucher" placeholder="Giá bán" name="price">
                        <p id="error_price" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="name" placeholder="Tên" name="full_name">
                        <p id="error_full_name" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="phone" placeholder="Số điện thoại" name="phone">
                        <p id="error_phone" class="error-messages"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control popup-voucher" id="email" placeholder="Gmail" name="email">
                        <p id="error_email" class="error-messages"></p>
                    </div>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.addVoucher()"
                                   value="Gửi">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>