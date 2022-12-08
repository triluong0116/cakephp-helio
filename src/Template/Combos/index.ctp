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
                                <span class="fs16 pb05" >Sắp xếp theo</span>
                            </div>
                        </div>
                        <div class="col-sm-14">
                            <div class="row">
                                <div class="col-sm-18 mt20">
                                    <a class="fs16 btn-sort text-dark <?= (isset($sortPrice) && $sortPrice == 'ASC') ? 'active' : '' ?> pb10" onclick="Frontend.sortLink('ASC');">Giá tăng dần <i class="fas fa-long-arrow-alt-up"></i></a>
                                </div>
                                <div class="col-sm-18 mt20">
                                    <a class="fs16 btn-sort text-dark <?= (isset($sortPrice) && $sortPrice == 'DESC') ? 'active' : '' ?> pb10" onclick="Frontend.sortLink('DESC');">Giá giảm dần <i class="fas fa-long-arrow-alt-down"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-location mb30 mt30 sp">
                    <div class="row">
                        <div class="col-xs-36">
                            <div class="mt20 text-center">
                                <span class="fs20 pb05" >Sắp xếp theo</span>
                            </div>
                        </div>
                        <div class="col-xs-36">
                            <div class="col-xs-18 mt20 mb20">
                                <a class="fs16 btn-sort text-dark pl10 <?= (isset($sortPrice) && $sortPrice == 'ASC') ? 'active' : '' ?> pb10" onclick="Frontend.sortLink('ASC');" >Giá tăng dần <i class="fas fa-long-arrow-alt-up"></i></a>
                            </div>
                            <div class="col-xs-18 mt20 mb20 text-right">
                                <a class="fs16 btn-sort text-dark pl15 <?= (isset($sortPrice) && $sortPrice == 'DESC') ? 'active' : '' ?> pb10" onclick="Frontend.sortLink('DESC');" >Giá giảm dần <i class="fas fa-long-arrow-alt-down"></i></a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="list-location">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="mt20 pb30 text-center">
                                <span class="semi-bold fs24 box-underline-center pb05" >COMBO</span>
                            </div>
                            <div class="p15">                                
                                <div class="row">
                                    <?php foreach ($combos as $indexCombo => $combo): ?>
                                        <div class="col-sm-12 mb15">
                                            <div class="combo-item border-bottom-blue">
                                                <div class="wrap-content rectangle-image-v2">
                                                    <a href="<?= \Cake\Routing\Router::url(['_name' => 'combo.view', 'slug' => $combo->slug]) ?>">
                                                        <?php if (file_exists($combo->thumbnail)): ?>
                                                            <img class="image" src="<?= $this->Url->assetUrl('/' . $combo->thumbnail) ?>" />
                                                        <?php else: ?>
                                                            <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>" />
                                                        <?php endif; ?>     
                                                    </a>
                                                    <div class="top-left text-white">
                                                        <p><i class="fas fa-map-marker-alt text-pink fs16"></i>&nbsp;&nbsp;<?= $combo->departure->name ?></p>
                                                    </div>
                                                    <div class="top-right text-white">
                                                        <p><i class="fas fa-chevron-circle-right main-color fs16"></i>&nbsp;&nbsp;<?= $combo->destination->name ?></p>
                                                    </div>
                                                    <div class="middle-button">
                                                        <div class="button-text text-center ">
                                                            <a class="fs12" href="<?= \Cake\Routing\Router::url(['_name' => 'combo.view', 'slug' => $combo->slug]) ?>">XEM NGAY</a>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="image-bottom-content combo-rating fs13 pl05">
                                                        <?php foreach ($combo->hotels as $key => $hotel) : ?>
                                                            <div class="row">
                                                                <div class="col-sm-22 col-xs-25">
                                                                    <p class="pc"><?= $this->System->splitByWords($hotel->name, 15) ?></p>
                                                                    <p class="sp"><?= $this->System->splitByWords($hotel->name, 25) ?></p>
                                                                </div>
                                                                <div class="col-sm-14 col-xs-11">
                                                                    <p class="star-rating text-right" data-point="<?= $hotel->rating ?>"></p>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="combo-detail overhidden pb10">
                                                    <div class="semi-bold voucher-name">
                                                        <a class="semi-bold text-super-dark fs16" href="<?= \Cake\Routing\Router::url(['_name' => 'combo.view', 'slug' => $combo->slug]) ?>"><?= $this->System->splitByWords($combo->name, 75) ?></a>
                                                    </div>
                                                    <div class="pl05 pr05">
                                                        <div class="row fs13">
                                                            <div class="col-sm-17 col-xs-17 no-pad-right">
                                                                <p class="fs12"><span class="text-light-blue">Ngày đi:</span> 
                                                                    <?php
                                                                    $currentDay = date('d-m-Y');
                                                                    echo $currentDay;
                                                                    ?>
                                                                </p>
                                                                <p class=""><i class="far fa-clock text-light-blue "></i> <?= $combo->days ?></p>

                                                            </div>
                                                            <div class="col-sm-19 col-xs-19 no-pad-left text-right">
                                                                <span class="fs12 text-grey"><span class="text-orange semi-bold"><?= number_format(($combo->totalPrice + $combo->addition_fee)/2) ?></span> đ/khách</span>
                                                                <span><p><del><?= number_format(round(((($combo->totalPrice + $combo->addition_fee)) / (100 - $combo->promote) * 100)/2, -3)) ?> đ/khách</del></p></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($indexCombo % 3 == 2): ?>

                                            <div class="clearfix"></div>                                    
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End content -->