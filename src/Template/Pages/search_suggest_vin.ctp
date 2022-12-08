<div class="config overhidden">
    <div class="col-sm-36 text-left">
        <a class="fs13 text-dark regular">
            <i class="fas fa-search"></i> Tìm kiếm:&nbsp;<span class="content-search text-main"><?= $keyword ?></span>
        </a>
    </div>
</div>
<div class="list-search">
    <?php if (isset($results['locations']) && !empty($results['locations'])): ?>
        <?php foreach ($results['locations'] as $location): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <p class="pull-left bold">Địa điểm:
                        <a onclick="Frontend.parseVinName('<?= $location->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                            <?= $this->System->hightLightTextSearch($location->name, $keyword) ?>
                        </a>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
    <?php if (isset($results['hotels']) && !empty($results['hotels'])): ?>
        <?php foreach ($results['hotels'] as $hotel): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <p class="pull-left bold">Khách sạn:
                        <a onclick="Frontend.parseVinName('<?= $hotel->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                            <?= $this->System->hightLightTextSearch($hotel->name, $keyword) ?>
                        </a>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
</div>
<hr/>
