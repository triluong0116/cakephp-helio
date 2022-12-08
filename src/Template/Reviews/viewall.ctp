<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
?>


<!-- Start content -->

<div class="list-combo mb20">
    <div class="container mt30">
        <div class="row">
            <div class="col-sm-9">
                <?= $this->element('Front/sidebar_filter') ?>
            </div>
            <div class="col-sm-27">
                <div class="list-location mb30 pc">
                    <div class="row">
                        <div class="col-sm-22">
                            <div class="mt20 pb20 ml20">
                                <span class="fs16 pb05">Sắp xếp theo</span>
                            </div>
                        </div>
                        <div class="col-sm-14">
                            <div class="row">
                                <div class="col-sm-18 mt20">
                                    <a class="fs16 btn-sort text-dark pb10" onclick="Frontend.sortLink('ASC');">Giá tăng dần <i class="fas fa-long-arrow-alt-up"></i></a>
                                </div>
                                <div class="col-sm-18 mt20">
                                    <a class="fs16 btn-sort text-dark pb10" onclick="Frontend.sortLink('DESC');">Giá giảm dần <i class="fas fa-long-arrow-alt-down"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-location mb30 mt30 sp">
                    <div class="row">
                        <div class="col-xs-36">
                            <div class="mt20 text-center">
                                <span class="fs20 pb05">Sắp xếp theo</span>
                            </div>
                        </div>
                        <div class="col-xs-36">
                            <div class="col-xs-18 mt20 mb20">
                                <a class="fs16 btn-sort text-dark pl10 pb10" onclick="Frontend.sortLink('ASC');">Giá tăng dần <i class="fas fa-long-arrow-alt-up"></i></a>
                            </div>
                            <div class="col-xs-18 mt20 mb20 text-right">
                                <a class="fs16 btn-sort text-dark pl15 pb10" onclick="Frontend.sortLink('DESC');">Giá giảm dần <i class="fas fa-long-arrow-alt-down"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-location mb30">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="mt20 pb30 text-center">
                                <span class="semi-bold fs24 box-underline-center pb05"><?= $category->name ?></span>
                            </div>
                            <div class="p15">
                                <?php foreach ($listReviews as $key => $review) : ?>
                                    <?php if ($key == 0 || $key % 3 == 0) : ?>
                                        <div class="row row-eq-height">
                                    <?php endif; ?>
                                    <div class="col-sm-12 mb15">
                                        <div class="review-item border-bottom-blue">
                                            <div class="wrap-content rectangle-image-v2">
                                                <a href="<?= \Cake\Routing\Router::url(['_name' => 'review.view', 'slug' => $review->slug]) ?>">
                                                    <?php if (file_exists($review->thumbnail)): ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('/' . $review->thumbnail) ?>"/>
                                                    <?php else: ?>
                                                        <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>"/>
                                                    <?php endif; ?>
                                                </a>
                                                <div class="top-left text-white">
                                                    <p class="fs14"><i class="fas fa-map-marker-alt text-pink fs16"></i><?= $review->location->name; ?></p>
                                                </div>
                                                <div class="middle-button">
                                                    <div class="button-text text-center ">
                                                        <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'review.view', 'slug' => $review->slug]) ?>">XEM NGAY</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-detail overhidden pb10">
                                                <div class="semi-bold review-name">
                                                    <a class="semi-bold text-super-dark fs16" href="<?= \Cake\Routing\Router::url(['_name' => 'review.view', 'slug' => $review->slug]) ?>"></a>
                                                    <p class="pc"><?= $this->System->splitByWords($review->title, 15) ?></p>
                                                    <p class="sp"><?= $this->System->splitByWords($review->title, 25) ?></p>
                                                </div>
                                                <div class="review-location">
                                                    <?php $location = json_decode($review->place, true); ?>
                                                    <p class="pc fs12"><i class="fas fa-map-marker-alt text-pink fs12"></i>
                                                        <?= $this->System->splitByWords($location[0]['name'] . ", " . $location[0]['address'], 25) ?>
                                                    </p>
                                                    <p class="sp fs12"><i class="fas fa-map-marker-alt text-pink fs12"></i>
                                                        <?= $this->System->splitByWords($location[0]['name'] . ", " . $location[0]['address'], 25) ?>
                                                    </p>
                                                </div>
                                                <div class="pl05 pr05">
                                                    <div class="row fs13">
                                                        <div class="mt05 col-sm-36 col-xs-36 no-pad-right">
                                                            <p class="pc "><span class="fs17 text-orange"><?= number_format($review->price_start) . " - " . number_format($review->price_end) ?></span>
                                                                <sup class="text-black fs12">đ</sup></p>
                                                            <p class="sp "><span class="fs17 text-orange"><?= number_format($review->price_start) . " - " . number_format($review->price_end) ?></span><sup class="text-black fs12">đ</sup></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($key % 3 == 2) : ?>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach; ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- End content -->