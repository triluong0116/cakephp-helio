<div class="config overhidden">
    <div class="col-sm-36 text-left">
        <a class="fs13 text-dark regular">
            <i class="fas fa-search"></i> Tìm kiếm:&nbsp;<span class="content-search text-main"><?= $keyword ?></span>
        </a>
    </div>
</div>
<div class="list-search">
    <?php if (isset($results['hotel']) && !empty($results['hotel'])): ?>
        <?php foreach ($results['hotel'] as $hotel): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <a onclick="Frontend.parseNormalName('<?= $hotel->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                        <p class="pull-left bold text-left">Khách sạn:
                            <?= $this->System->hightLightTextSearch($hotel->name, $keyword) ?>
                        </p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
    <?php if (isset($results['homestay']) && !empty($results['homestay'])): ?>
        <?php foreach ($results['homestay'] as $homestay): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <a onclick="Frontend.parseNormalName('<?= $homestay->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                        <p class="pull-left bold text-left">Homestay:
                            <?= $this->System->hightLightTextSearch($homestay->name, $keyword) ?>
                        </p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
    <?php if (isset($results['landtour']) && !empty($results['landtour'])): ?>
        <?php foreach ($results['landtour'] as $landtour): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <a onclick="Frontend.parseNormalName('<?= $landtour->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                        <p class="pull-left bold text-left">Landtour:
                            <?= $this->System->hightLightTextSearch($landtour->name, $keyword) ?>
                        </p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
    <?php if (isset($results['voucher']) && !empty($results['voucher'])): ?>
        <?php foreach ($results['voucher'] as $voucher): ?>
            <div class="row p05 search-item-autocomplete no-mar-right no-mar-left vertical-center">
                <div class="col-sm-36 col-xs-36">
                    <a onclick="Frontend.parseNormalName('<?= $voucher->name ?>')" class="fs13 text-super-dark regular text-capitalize">
                        <p class="pull-left bold text-left">Voucher:
                            <?= $this->System->hightLightTextSearch($voucher->name, $keyword) ?>
                        </p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
</div>
<hr/>
