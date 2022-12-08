<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
//if ($this->request->getData()) {
//    $dataValiError = $this->request->getData();
//} else {
//    $dataValiError = [];
//}
?>
<?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file', 'id' => 'formPriceRoom']) ?>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Chọn Landtour</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php
                $this->Form->setTemplates([
                    'formStart' => '<form class="" {{attrs}}>',
                    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                    'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                    'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}</textarea></div>',
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                    'checkContainer' => ''
                ]);
                ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Chọn Landtour *</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control select2" name="landtour_id" onchange="getListRevenueLandtour(this)">
                            <option selected="true" disabled="disabled">Chọn Landtour</option>
                            <?php foreach ($listLandtours as $k => $landtour): ?>
                                <option value="<?= $landtour->id ?>"><?= $landtour->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr />
            </div>
        </div>
    </div>

</div>
<div class="row" id="list-revenue-agency">
    <div class="col-xs-12 col-md-6">
        <div id="e-loading-icon" class="text-center">
            <img src="<?= $this->Url->assetUrl('backend/img/e-loading.gif')?>" style="width: 100px;">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="x_panel">
            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

