<!-- Header Combo -->
<!-- End Header Combo -->
<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div class="col-sm-36">
                <center>
                    <div class="mt40">
                        <h2>Đào tạo</h2>
                    </div>
                    <div class="mt15 pb50">
                        <h2><b class="box-underline-center pb20">HUẤN LUYỆN VIÊN DU LỊCH ONLINE</b></h2>
                    </div>
                </center>
                <div class="box-shadow bg-white pb15">
                    <?php foreach ($configs as $key => $config): ?>
                        <div class="pl30 pb05 pt15 fs13 ">
                            <p><span class="fs16"><?= $key+1 ?></span>. <a href="<?= \Cake\Routing\Router::url(['_name' => 'blog.view', 'slug' => $config->slug]) ?>" class="fs16"><?= $config->title ?></a></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="pt30 pb30">
                    <center>
                        <a href="<?= $this->Url->build('/chinh-sach-cong-tac-vien-page-4') ?>" class="btn bg-blue text-white fs18 mb20">BẮT ĐẦU KIỂM TRA</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End content -->