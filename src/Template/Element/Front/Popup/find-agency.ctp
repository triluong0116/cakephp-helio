<div class="modal fade" id="findAgencyLoading" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content p50 border-bottom-blue">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <img class="" src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" style="width: 30%"/>
            </div>
            <div class="modal-body mt30">
                <p class="text-center fs23"><b>Chúng tôi đang tìm kiếm <span class="text-blue">Cộng Tác Viên</span> phù hợp với bạn</b></p>
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="agencyChoosing" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content p50 border-bottom-blue6">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <img class="" src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" style="width: 30%"/>
            </div>
            <div class="modal-body mt30">
                <p class="text-center fs18"><b>Khách hàng <span class="text-blue">MUSTGO</span> cần tìm Hướng dẫn viên online</b></p>
                <p class="text-center fs23">Bạn có đồng ý hỗ trợ họ?</p>
                <div class="row mt40">
                    <div class="col-sm-12 pc"></div>
                    <div class="col-sm-6 col-xs-18 text-center">
                        <div class="square-image">
                            <button class="btn btn-submit" id="agencyAcceptedRequest" onclick="Frontend.agencyAccept(this)" data-finger-print="" data-timestamp="">Đồng ý</button>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-18 text-center">
                        <div class="square-image">
                            <button class="btn btn-danger">Từ chối</button>
                        </div>
                    </div>
                    <div class="col-sm-12 pc"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="foundAgency" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg pc">

        <!-- Modal content-->
        <div class="modal-content p20 border-bottom-blue">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <img class="" src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" style="width: 30%"/>
            </div>
            <div class="modal-body pt30">
                <p class="text-center fs25"><b>Hướng dẫn viên online của <span class="text-light-blue">MUSTGO</span> đã sẵn sàng tư vấn cho bạn</b></p>
                <div class="row">
                    <div class="col-sm-14"></div>
                    <div class="col-sm-8 pt20">
                        <div class="square-image">
                            <img id="foundedAgencyAvatar" class="img-circle" src="">
                        </div>
                    </div>
                    <div class="col-sm-14"></div>
                </div>
                <div class="pb20 pt10 text-center ">
                    <a class="text-light-blue fs18" id="foundedAgencyName" href=""><b></b></a>
                </div>
                <div class="text-center">
                    <p class="fs25"><b>Liên hệ với Hướng dẫn viên thông qua</b></p>
                    <div class="pt25">
                        <div class="row">
                            <div class="col-sm-9"></div>
                            <div class="col-sm-6">
                                <div class="square-image">
                                    <a id="foundedAgencyFB" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-facebook.png') ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="square-image">
                                    <a id="foundedAgencyZalo" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-zalo.png') ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="square-image">
                                    <a id="foundedAgencyPhone" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-phone.png') ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-9"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-dialog modal-sm sp">

        <!-- Modal content-->
        <div class="modal-content p20 border-bottom-blue">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                <img class="" src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" style="width: 30%"/>
            </div>
            <div class="modal-body pt30">
                <p class="text-center fs20"><b>Hướng dẫn viên online của <span class="text-light-blue">MUSTGO</span> đã sẵn sàng tư vấn cho bạn</b></p>
                <div class="row">
                    <div class="col-xs-8"></div>
                    <div class="col-xs-20 pt20">
                        <div class="square-image">
                            <img id="foundedAgencyAvatar" class="img-circle" src="">
                        </div>
                    </div>
                </div>
                <div class="pb20 pt10 text-center ">
                    <a class="text-light-blue fs18" id="foundedAgencyName" href=""><b></b></a>
                </div>
                <div class="text-center">
                    <p class="fs20"><b>Liên hệ với Hướng dẫn viên thông qua</b></p>
                    <div class="pt25">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="square-image">
                                    <a id="foundedAgencyFB" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-facebook.png') ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="square-image">
                                    <a id="foundedAgencyZalo" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-zalo.png') ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="square-image">
                                    <a id="foundedAgencyPhone" href="" target="_blank">
                                        <img src="<?= $this->Url->assetUrl('frontend/img/icon-phone.png') ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>