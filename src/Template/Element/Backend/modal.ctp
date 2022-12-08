<div class="modal fade" id="modal-post-facebook" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="post-to-facebook">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Đăng bài lên Facebook</h4>
                </div>
                <div class="modal-body"> 
                    <input type="hidden" name="object_type" />
                    <input type="hidden" name="object_id" />
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Hình thức đăng lên <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-10">
                                <select class="form-control" name="fb_post_type" onchange="selectTypePostFacebook(this)">
                                    <option value="">Chọn Hình thức</option>
                                    <!--<option value="1">Trang cá nhân</option>-->
                                    <option value="2">Fanpage</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-2">
                                <div class="modal-loading">
                                    <i class="fa fa-spin fa-spinner hidden"></i>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div id="list-result">

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="postFacebook(this)">Đăng bài <i class="fa fa-spin fa-spinner hidden"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>