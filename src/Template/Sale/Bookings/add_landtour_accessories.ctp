<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]) ?>
<div class="row">
    <div class="col-sm-offset-2 col-sm-10">
        <?php foreach ($landtourAccessories as $k => $accessory): ?>
            <div class="col-sm-12">
                <p class="fs16 mb15 text-light-blue"><input type="checkbox" class="iCheck checkbox-iCheck" name="accessroy[]" value="<?= $accessory->id ?>"><?= $accessory->name ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-5 mb10">
        <h3>Địa điểm đón *</h3>
        <div class="control-group">
            <div class="row">
                <?php foreach ($landtourDriveSurchages as $k => $driveSurchage): ?>
                    <div class="col-sm-12">
                        <p class="fs16 mb15 text-light-blue"><input type="radio" required class="iCheck radio-iCheck-pick" name="drive_surchage_pickup" value="<?= $k ?>"><?= $driveSurchage ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb10">
                <label for="">Chi tiết điểm đón</label>
                <input class="form-control" name="booking_landtour[detail_pickup]">
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <h3>Địa điểm trả *</h3>
        <div class="control-group">
            <div class="row">
                <?php foreach ($landtourDriveSurchages as $k => $driveSurchage): ?>
                    <div class="col-sm-12">
                        <p class="fs16 mb15 text-light-blue"><input type="radio" required class="iCheck radio-iCheck-drop" name="drive_surchage_drop" value="<?= $k ?>"><?= $driveSurchage ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb10">
                <label for="">Chi tiết điểm trả</label>
                <input class="form-control" name="booking_landtour[detail_drop]">
            </div>
        </div>
    </div>
</div>

