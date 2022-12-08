<?php

use App\View\Helper\SystemHelper;

?>
<style>
    #header-menu ul.nav li a:hover::after, #header-menu ul.main-menu li a::after {
        background: #E8B86C;
    }

    .dropdown a:hover {
        color: #E8B86C !important;
    }

    .button-text a {
        background-color: #E8B86C;
    }

    .address-commit {
        z-index: 10;
    }

    .footer-content hr {
        border-top: 3px solid #E8B86C;
    }

    #header-menu ul.nav li a:hover::after, #header-menu ul.nav li.active a::after {
        top: 115%;
    }

    #header-menu ul.main-menu li a::after {
        height: 2px;
    }

    body {
        font-family: 'Poppins_light', sans-serif;
        background-color: #f2f2f2;
    }

    a.text-light-grey:hover {
        color: #cccccc;
    }

</style>
<div class="container-fluid pl0 pr0">
    <div class="banner-commit no-mar-left no-mar-right">
        <div class="pc">
            <img src="./webroot/frontend/mustgo-commit.png" alt="" width="1920" height="940" class="w-100">
        </div>
        <div class="sp">
            <img src="./webroot/frontend/mustgo-commit-mobile.png" alt="" class="w-100">
        </div>
    </div>
</div>
<?php
$index = 0;

$numPeople = "1 Phòng-1NL-0TE-0EB";
$dataVinRoom[] = [
    'num_adult' => 1,
    'num_child' => 0,
    'num_kid' => 0
];
$sDate = date('d-m-Y', strtotime('today'));
$eDate = date('d-m-Y', strtotime('tomorrow'));
$fromDate = str_replace('-', '/', $sDate) . " - " . str_replace('-', '/', $eDate);

foreach ($listCommit as $key => $commit):
    ?>
    <?php
    if ($index % 2 == 0):
        ?>
        <div class=" bg-black">
            <div class="container-commit pos-relative">
                <div class="commit-location-header pos-absolute w-100 pc">
                    <div class="row">
                        <div class="col-md-8 col-lg-offset-3">
                            <h2 class="fs24 color-yellow title-commit-left">
                                <?= $key ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="commit-location-header w-100 sp">
                    <div class="row">
                        <div class="col-md-8 ">
                            <h2 class="fs24 color-yellow text-center">
                                <?= $key ?>
                            </h2>
                            <hr class="color-yellow title-bd-bottom">
                        </div>
                    </div>
                </div>
                <div class="commit-location-content">
                    <div class="row">
                        <div class="pos-relative mt40">
                            <div class="col-md-24 col-lg-offset-9">
                                <?php foreach ($commit as $key => $hotelCommit): ?>
                                    <?php
                                    $url = "";
                                    if ($hotelCommit->is_vinhms == 1) {
                                        $url = \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotelCommit->slug, 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]);
                                    } else {
                                        $url = \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotelCommit->slug]);
                                    }
                                    ?>
                                    <?php if ($key % 4 == 0): ?>
                                        <div class="row">
                                    <?php endif; ?>
                                    <div class="col-md-12 col-lg-9 pb50">
                                        <div class="single-commit-hotel border-bottom-orange">
                                            <div class="pos-relative commit-button wrap-content rectangle-image-v2">
                                                <div class="address-commit pos-absolute pl10 pt05 pr25 pb05">
                                                    <p class="fs14 text-white"><span><i class="fas fa-map-marker-alt mr05"></i></span> <?= $hotelCommit->location->name ?></p>
                                                </div>
                                                <a href="<?= $url ?>">
                                                    <?php if (file_exists($hotelCommit->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $hotelCommit->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= $url ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="row pos-absolute b0 r0 l0 commit-start mr0-i ml0-i pt10 pb10">
                                                    <p class="col-md-18 col-xs-18 w fs12 text-white pl05"><?= $this->System->splitByWords($hotelCommit->name, 15) ?> </p>
                                                    <p class="col-md-18 col-xs-18 star-rating text-right combo-rating pr05" data-point="<?= $hotelCommit->rating ?>"></p>
                                                </div>
                                            </div>
                                            <div class="mb10 p05">
                                                <a href="<?= $url ?>" class="semi-bold mb10 pt10 pb10 text-light-grey"><?= $this->System->splitByWords($hotelCommit->name, 25) ?></a>
                                                <div class="row">
                                                    <p class="col-xs-15 col-sm-15 fs14 pr0 text-grey"><span><i class="fas fa-calendar-alt text-grey fs14 pr05"></i></span> <?= date("d-m-Y") ?>
                                                    </p>
                                                    <?php if ($hotelCommit->singlePrice): ?>
                                                        <p class="col-xs-10 col-sm-10 fs14 color-yellow pr0 pl0 text-right"><?= $hotelCommit->singlePrice ?></p>
                                                        <p class="col-xs-10 col-sm-10 fs14 pl0 text-grey text-right">đ/phòng</p>
                                                    <?php else: ?>
                                                        <p class="col-xs-19 col-sm-19 fs14 color-yellow pr0 pl0 text-right">Hết phòng</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($key % 4 == 3 || $key == count($commit) - 1): ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else:
        ?>
        <div class="bg-img bg-black">
            <div class="container-commit pos-relative">
                <div class="commit-location-header pos-absolute w-100 pc">
                    <div class="row">
                        <div class="col-md-8 col-lg-offset-25 ">
                            <h2 class="fs24 color-yellow title-commit-right text-right">
                                <?= $key ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="commit-location-header w-100 sp">
                    <div class="row">
                        <div class="col-md-8 ">
                            <h2 class="fs24 color-yellow text-center">
                                <?= $key ?>
                            </h2>
                            <hr class="color-yellow title-bd-bottom">
                        </div>
                    </div>
                </div>
                <div class="commit-location-content">
                    <div class="row">
                        <div class="pos-relative mt40">
                            <div class="col-md-24 col-lg-offset-3">
                                <?php foreach ($commit as $key => $hotelCommit): ?>
                                    <?php
                                    $url = "";
                                    if ($hotelCommit->is_vinhms == 1) {
                                        $url = \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotelCommit->slug, 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]);
                                    } else {
                                        $url = \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $hotelCommit->slug]);
                                    }
                                    ?>
                                    <?php if ($key % 4 == 0): ?>
                                        <div class="row">
                                    <?php endif; ?>
                                    <div class="col-md-12 col-lg-9 pb50">
                                        <div class="single-commit-hotel border-bottom-orange">
                                            <div class="pos-relative commit-button wrap-content rectangle-image-v2">
                                                <div class="address-commit pos-absolute pl10 pt05 pr25 pb05">
                                                    <p class="fs14 text-white"><span><i class="fas fa-map-marker-alt mr05"></i></span> <?= $hotelCommit->location->name ?></p>
                                                </div>
                                                <a href="<?= $url ?>">
                                                    <?php if (file_exists($hotelCommit->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $hotelCommit->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= $url ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                                <div class="row pos-absolute b0 r0 l0 commit-start mr0-i ml0-i pt10 pb10">
                                                    <p class="col-md-18 col-xs-18 w fs12 text-white pl05"><?= $this->System->splitByWords($hotelCommit->name, 15) ?> </p>
                                                    <p class="col-md-18 col-xs-18 star-rating text-right combo-rating pr05" data-point="<?= $hotelCommit->rating ?>"></p>
                                                </div>
                                            </div>
                                            <div class="mb10 p05">
                                                <a href="<?= $url ?>" class="semi-bold mb10 pt10 pb10 text-light-grey"><?= $this->System->splitByWords($hotelCommit->name, 25) ?></a>
                                                <div class="row">
                                                    <p class="col-xs-15 fs14 pr0 text-grey"><span><i class="fas fa-calendar-alt text-grey fs14 pr05"></i></span> <?= date("d-m-Y") ?>
                                                    </p>
                                                    <?php if ($hotelCommit->singlePrice): ?>
                                                        <p class="col-xs-10 col-sm-10 fs14 color-yellow pr0 pl0 text-right"><?= $hotelCommit->singlePrice ?></p>
                                                        <p class="col-xs-10 col-sm-10 fs14 pl0 text-grey text-right">đ/phòng</p>
                                                    <?php else: ?>
                                                        <p class="col-xs-19 col-sm-19 fs14 color-yellow pr0 pl0 text-right">Hết phòng</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($key % 4 == 3 || $key == count($commit) - 1): ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>

    <?php
    $index++;
endforeach;
?>


