<!-- Header Menu -->
<?php
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
?>

<div class="header">
    <div class="container-commit">
        <div class="row">
            <div class="col-md-28 col-lg-offset-4">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand mt05" href="<?= \Cake\Routing\Router::url('/') ?>">
                            <img src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" alt="Trippal"/>
                        </a>
                    </div>
                    <div class="row">
                        <div id="header-menu" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav main-menu">
                                <li class="<?= $currentController == "Hotels" && ($currentAction == "location" || $currentAction == "view") ? "active" : "" ?>">
                                    <a href="<?= $this->Url->build('/khach-san/dia-diem') ?>">Khách sạn</a>
                                </li>
                                <li class="<?= $currentController == "Hotels" && $currentAction == "commit" ? "active" : "" ?>"><a href="<?= $this->Url->build('/commit') ?>">Mustgo COMMIT</a></li>
                                <?php if ($this->request->getSession()->read('Auth.User')): ?>
                                    <li class="<?= $currentController == "Hotels" && ($currentAction == "viewVinpearl" || $currentAction == "listVinpearlHotels" || $currentAction == "searchVinpearlHotels") ? "active" : "" ?>"><a class="position-relative" href="<?= $this->Url->build('/vinpearl') ?>">VINPEARL</a></li>
                                <?php endif; ?>
                                <li class="<?= $currentController == "LandTours" ? "active" : "" ?>">
                                    <a href="<?= $this->Url->build('/land-tour/dia-diem') ?>">LandTour</a>
                                </li>
                                <li><a class="no-underline" href="<?= $this->Url->build('/vemaybaygiare') ?>">Vé Máy bay</a></li>
                                <li class="d-none <?= $currentController == "Reviews" ? "active" : "" ?>"><a href="<?= $this->Url->build('/cam-nang/dia-diem') ?>">Review</a></li>
                                <li class="<?= $currentController === "Blogs" || $currentController === "Users" ? "active" : "" ?>"><a href="<?= $this->Url->build(['controller' => 'Blogs', 'action' => 'agencyP1']) ?>">Đại lý</a></li>
                            </ul>
                            <?php if (!$this->request->getSession()->read('Auth.User')): ?>
                                <ul class="nav navbar-nav navbar-right navbar-hotline-menu">
                                    <li>
                                        <a class="header-hotline fs16" href="tel:0925959777" rel="nofollow">
                                            <i class="fas fa-phone fs20"></i> 092.5959.777 <br>
                                        </a>
                                        <span class="navbar-description workingTime">(Hỗ trợ miễn phí <strong>24/7</strong>)</span>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <ul class="nav navbar-nav navbar-right list-tool">

                                    <li class="pt10-sp">
                                        <div class="dropdown ml50-sp" style=" display: block; padding: 8px">
                                            <a href="#" class="fs15 text-super-dark" data-toggle="dropdown"><?= $this->request->getSession()->read('Auth.User.screen_name') ?><span class="caret"></span></a>
                                            <ul class="dropdown-menu account">
                                                <?php if ($this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                                                    <li><a href="<?= $this->Url->build('/data-board') ?>">Thông tin Booking</a></li>
                                                    <li><a href="<?= $this->Url->build('/thong-tin-ca-nhan') ?>">Thông tin cá nhân</a></li>
                                                    <li><a href="<?= $this->Url->build('/nap-tien') ?>">Nạp tiền</a></li>
                                                    <li><a href="<?= $this->Url->build('/lich-su-nap-tien') ?>">Quản lý giao dịch</a></li>
                                                    <li><a target="_blank" href="<?= $this->Url->build('/vemaybaygiare') ?>">Tìm chuyến bay</a></li>
                                                    <li><a target="_blank" href="<?= $this->Url->build('/xesanbaygiare') ?>">Thuê xe có lái</a></li>
                                                <?php endif; ?>
                                                <li><a href="<?= $this->Url->build('/dang-xuat') ?>">Đăng xuất</a></li>
                                            </ul>
                                        </div>
                                    </li>

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
<?php if (!isset($headerType)): ?>
    <?= $this->element('Front/headerSlider') ?>
<?php else: ?>
    <?= $this->element('Front/headerBreadcrumb') ?>
<?php endif; ?>
