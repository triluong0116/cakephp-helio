<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
]);
?>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Thông tin phòng</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-5">
                    <p>Tiện ích</p>
                    <?php foreach ($dataRoom['extends'] as $extend): ?>
                        <?php if (isset($extend['icon'])): ?>
                            <a href="<?= $extend['icon'] ?>" target="_blank"><?= $extend['name'] ?></a>
                            <br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="col-sm-5">
                    <p>Ảnh</p>
                    <?php foreach ($dataRoom['thumbnails'] as $thumbnail): ?>
                        <?php if (isset($thumbnail['url'])): ?>
                            <a href="<?= $thumbnail['url'] ?>" target="_blank"><?= $thumbnail['url'] ?></a>
                            <br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <br/>
            <div class="extends-zone">
                <?php if ($dataVinRoom): ?>
                    <?php
                    $dataExtend = json_decode($dataVinRoom->extends, true);
                    ?>
                    <?php if ($dataExtend): ?>
                        <?php foreach ($dataExtend as $k => $singleData): ?>
                            <div class="single-extend">
                                <hr/>
                                <div class="row">
                                    <div class="col-sm-10 col-xs-10">
                                        <div class="mb10">
                                            <div class="col-sm-3">
                                                <label>Ảnh Tiện ích</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="file" name="extends[<?= $k ?>][image]">
                                                <input class="form-control" type="hidden" name="extends[<?= $k ?>][image_edit]" value="<?= $singleData['image'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-sm-2 text-right">
                                        <a href="#" onclick="deleteItem(this, '.single-extend');" class="mt10">
                                            <i class="text-danger fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="col-sm-10 col-xs-10">
                                        <div class="mb10">
                                            <div class="col-sm-3">
                                                <label>Tên tiện ích</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input class="form-control" name="extends[<?= $k ?>][content]" value="<?= $singleData['content'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
            <button type="button" class="btn btn-success" onclick="addVinExtends(this, '.extends-zone')">Thêm tiện ích</button>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media" value='<?= $dataVinRoom ? $dataVinRoom->thumbnail : "" ?>'/>
            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
            <button type="submit" class="btn btn-success mt10">
                Save
            </button>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
