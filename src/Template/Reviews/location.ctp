<!-- Home Location -->
<div class="home-location mb50">
    <div class="home-location-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">TẤT CẢ ĐIỂM ĐẾN</span>
    </div>
    <div class="container mt50">
        <div class="list-location p30 bg-white">
            <?php foreach ($locations as $key => $location): ?>
                <?php if ($key == 0 || $key % 3 == 0): ?>
                    <div class="row row-eq-height">
                <?php endif; ?>
                <div class="col-sm-12 col-xs-36 mb30">
                    <div class="location-item">
                        <div class="rectangle-image">
                            <a onclick="Frontend.findAgencyWithoutLoadingVer2(this);" data-href="<?= \Cake\Routing\Router::url(['_name' => 'review.location_detail', 'slug' => $location->slug]) ?>">
                                <?php if (file_exists($location->thumbnail)): ?>
                                    <img class="image" src="<?= $this->Url->assetUrl('/' . $location->thumbnail) ?>" />
                                <?php else: ?>
                                    <img class="image" src="<?= $this->Url->assetUrl('frontend/img/no-thumbnail.png') ?>" />
                                <?php endif; ?>
                            </a>
                            <div class="caption" data-href="<?= \Cake\Routing\Router::url(['_name' => 'review.location_detail', 'slug' => $location->slug]) ?>" onclick="Frontend.findAgencyWithoutLoadingVer2(this);">
                                <div class="location-title">
                                    <a class="fs20 semi-bold text-white text-uppercase box-underline-center-white" href="<?= \Cake\Routing\Router::url(['_name' => 'review.location_detail', 'slug' => $location->slug]) ?>">
                                        <?= $location->name ?>
                                    </a>
                                </div>
                                <p class="location-more-info text-white fs12 regular"><i class="fas fa-search"></i> <?= $location->review_count . ' review tại ' . $location->name ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($key % 3 == 2 || $key == (count($locations) - 1)): ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div>
</div>
<!-- End Home Location -->