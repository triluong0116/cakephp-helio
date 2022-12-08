<div id="modal-post-facebook" class = "modal fade" role = "dialog">
    <div class="modal-dialog">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Đăng lên Fanpage</h4>
            </div>
            <div class="modal-body">
                <form id="post-to-facebook">
                    <input type="hidden" name="object_type" value="" />
                    <input type="hidden" name="object_id" />
                    <input type="hidden" name="fb_post_type" value="2" />
                    <div id="postFBSelectFanpage">
                        
                    </div>
                    <center><span id="postFbLoading" class="fs30 main-color hidden"><i class="fas fa-spinner fa-pulse"></i></span></center>
                    <div class="row pt25 pb25">
                        <div class="col-sm-12 col-sm-offset-12">
                            <input type="button" name="btn-submit" class="form-control btn btn-submit" onclick="Frontend.postFacebook()" value="Gửi">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>