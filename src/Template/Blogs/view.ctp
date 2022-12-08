<!-- Header Combo -->
<!-- End Header Combo -->
<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div class="col-sm-36">
                <center>
                    <div class="pb50">
                        <div class="mt25 pb15 ">
                            <span class="box-underline-center pb10 fs24"><?= $blog->title ?></span>
                        </div>
                    </div>
                </center>
                <div class="box-shadow bg-white">
                    <div class="p30 fs16 mr20 ">
                        <?= $blog->description ?>
                    </div>
                </div>
                <div class="pt30 pb30">
                    <center>
                        <a href="<?= $this->Url->build('/chinh-sach-cong-tac-vien-page-3') ?>" class="btn bg-blue text-white fs18 mb20">QUAY LẠI</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End content -->