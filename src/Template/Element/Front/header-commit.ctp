<!-- Header Menu -->
<?php
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
?>
<div class="header box-shadow-no pos-absolute l0 r0  opacity-70">
    <div class="container-commit">
        <div class="row">
            <div class="col-md-30 col-lg-offset-3 ">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar bg-yellow-gold"></span>
                            <span class="icon-bar bg-yellow-gold"></span>
                            <span class="icon-bar bg-yellow-gold"></span>
                        </button>
                        <a class="navbar-brand mt05" href="<?= \Cake\Routing\Router::url('/') ?>">
                            <img src="./webroot/frontend/logo-commint.png" alt="Trippal"/>
                        </a>
                    </div>
                    <div class="row">
                        <div id="header-menu" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav main-menu commit-nav">
                                <li class="<?= $currentController == "Hotels" && ($currentAction == "location" || $currentAction == "view") ? "active" : "" ?>"><a class="color-commit" href="<?= $this->Url->build('/khach-san/dia-diem') ?>">Khách sạn</a></li>
                                <li class=" <?= $currentController == "Hotels" ? "active" : "" ?>"><a class="color-commit"  href="<?= $this->Url->build('/khach-san/dia-diem') ?>">Mustgo COMMIT</a></li>
                                <li class="<?= $currentController == "Hotels" && ($currentAction == "viewVinpearl" || $currentAction == "listVinpearlHotels" || $currentAction == "searchVinpearlHotels") ? "active" : "" ?>"><a class="position-relative color-commit" href="<?= $this->Url->build('/vinpearl') ?>">VINPEARL</a></li>
                                <li><a class="color-commit" href="<?= $this->Url->build('/vemaybaygiare') ?>">Vé Máy bay</a></li>
                                <li class="<?= $currentController == "LandTours" ? "active" : "" ?> dropdown">
                                    <a class="color-commit" href="<?= $this->Url->build('/land-tour/dia-diem') ?>">Landtour</a>
                                </li>
                                <li class="d-none <?= $currentController == "Reviews" ? "active" : "" ?>"><a class="color-commit" href="<?= $this->Url->build('/cam-nang/dia-diem') ?>">Review</a></li>
                                <li class="<?= $currentController === "Blogs" || $currentController === "Users" ? "active" : "" ?>"><a class="color-commit" href="<?= $this->Url->build(['controller' => 'Blogs', 'action' => 'agencyP1']) ?>">Đại lý</a></li>
                            </ul>
                            <?php if (!$this->request->getSession()->read('Auth.User')): ?>
                                <ul class="nav navbar-nav navbar-right navbar-hotline-commit">
                                    <li>
                                        <a class="header-hotline fs16 " href="tel:0925959777" rel="nofollow">
                                            <i class="fas fa-phone fs20"></i> 092.5959.777 <br>
                                        </a>
                                        <span class="navbar-description workingTime">(Hỗ trợ miễn phí <strong>24/7</strong>)</span>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <ul class="nav navbar-nav navbar-right list-tool">
                                    <li class="avatar-header" style="float: left;">
                                        <div class="square-image" style=" display: block;">
                                            <?php
                                            $imageAvatar = $this->request->getSession()->read('Auth.User.avatar');
                                            if (strpos($imageAvatar, 'http') === 0) {
                                                $avatar = $imageAvatar;
                                            } else {
                                                if (file_exists($imageAvatar)) {
                                                    $avatar = $this->Url->assetUrl($imageAvatar);
                                                } else {
                                                    $avatar = $this->Url->assetUrl('/frontend/img/noavatar.jpg');
                                                }
                                            }
                                            ?>
                                            <img class="img-circle" src="<?= $avatar ?>">
                                        </div>
                                    </li>
                                    <li class="pt10-sp">
                                        <div class="dropdown ml50-sp" style=" display: block; padding: 8px">
                                            <a href="#" class="fs15 semi-bold text-super-dark" data-toggle="dropdown"><?= $this->request->getSession()->read('Auth.User.screen_name') ?><span class="caret"></span></a>
                                            <ul class="dropdown-menu account">
                                                <?php if ($this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                                                    <li><a href="<?= $this->Url->build('/data-board') ?>">Thông tin Booking</a></li>
                                                    <li><a href="<?= $this->Url->build('/thong-tin-ca-nhan') ?>">Thông tin cá nhân</a></li>
                                                    <li><a target="_blank" href="<?= $this->Url->build('/vemaybaygiare') ?>">Tìm chuyến bay</a></li>
                                                    <li><a target="_blank" href="<?= $this->Url->build('/xesanbaygiare') ?>">Thuê xe có lái</a></li>
                                                <?php endif; ?>
                                                <li><a href="<?= $this->Url->build('/dang-xuat') ?>">Đăng xuất</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--/.nav-collapse -->
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End Header Menu -->


