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
<?= $this->Form->create($hotel, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
            <h2>Thêm Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?php
            echo $this->Form->control('location_id', [
                'options' => $locations,
                'class' => 'form-control select2',
                'label' => 'Địa điểm *',
                'required' => 'required'
            ]);
            ?>
            <?php
            echo $this->Form->control('name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tiêu đề *',
                'required' => 'required'
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Mã Vinpearl</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" onchange="getHotelCode(this)">
                        <option> Chọn khách sạn</option>
                        <?php if ($listHotelVinpearl): ?>
                            <?php foreach ($listHotelVinpearl as $singleHotel): ?>
                                <option value="<?= $singleHotel['id'] ?>"><?= $singleHotel['name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <?php
            echo $this->Form->control('vinhms_code', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Mã khách sạn *',
                'required' => 'required',
                'default' => $hotel->vinhms_code
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Khách sạn đặc biệt</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="checkbox" class="flat" <?= ($hotel->is_special == 1) ? 'checked' : '' ?> name="is_special" value="1">
                        </label>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->control('hotline', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Hotline *',
                'required' => 'required',
            ]);
            echo $this->Form->control('address', [
                'class' => 'form-control',
                'label' => 'Địa chỉ *',
                'required' => 'required',
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Loại lợi nhuận Mustgo*</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $hotel->price_agency_type == 0 ? 'checked' : '' ?> name="price_agency_type" value="0"> Cố định</i></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $hotel->price_agency_type == 1 ? 'checked' : '' ?> name="price_agency_type" value="1"> Theo %</i></p>
                </div>
            </div>
            <?php
            echo $this->Form->control('price_agency', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận của Mustgo *',
                'required' => 'required',
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Loại lợi nhuận Đại lý*</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $hotel->price_customer_type == 0 ? 'checked' : '' ?> name="price_customer_type" value="0"> Cố định</i></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $hotel->price_customer_type == 1 ? 'checked' : '' ?> name="price_customer_type" value="1"> Theo %</i></p>
                </div>
            </div>
            <?php
            echo $this->Form->control('price_customer', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận cho Đại lý *',
                'required' => 'required',
            ]);
            ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail"/>
                    <input name="thumbnail_edit" type="hidden" value='<?= $hotel->thumbnail ?>'/>
                </div>
            </div>
            <?php if ($hotel->thumbnail): ?>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <?= $this->Html->image('/' . $hotel->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh Banner *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="banner"/>
                    <input name="banner_edit" type="hidden" value='<?= $hotel->banner ?>'/>
                </div>
            </div>
            <?php if ($hotel->banner): ?>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <?= $this->Html->image('/' . $hotel->banner, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh</label>
            </div>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media" value='<?= $hotel->media ?>'/>
            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 text-center">
                <button type="submit" class="btn btn-success" id="blog-submit" onclick="tinyMCE.triggerSave();">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Tiện ích khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <?php foreach ($listAccessory as $k => $accessory): ?>
                <div class="col-sm-12">
                    <a href="<?= $accessory['icon'] ?>" target="_blank"><?= $accessory['name'] ?></a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="extends-zone">
                <?php
                $dataExtend = json_decode($hotel->extends, true);
                ?>
                <?php if ($dataExtend): ?>
                    <?php foreach ($dataExtend as $k => $singleData): ?>
                        <div class="single-extend">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <div class="mb10">
                                        <div class="col-sm-3 mb10">
                                            <label>Ảnh Tiện ích</label>
                                        </div>
                                        <div class="col-sm-9 mb10">
                                            <input class="form-control" type="file" name="extends[<?= $k ?>][image]">
                                            <input class="form-control" type="hidden" name="extends[<?= $k ?>][image_edit]" value="<?= $singleData['image'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right mb10">
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
            </div>
            <button type="button" class="btn btn-success" onclick="addVinExtends(this, '.extends-zone')">Thêm tiện ích</button>
        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Nhà hàng & Bar</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-sm-2">
                <label>Tiêu đề</label>
            </div>
            <?php
            if (isset($dataValiError['list_vin_caption'])) {
                $list_vin_captions = $dataValiError['list_vin_caption'];
            } else if ($hotel->vinhms_caption) {
                $list_vin_captions = json_decode($hotel->vinhms_caption, true);
            } else {
                $list_vin_captions = [];
            }
            ?>
            <div class="col-sm-10">
                <input type="text" name="list_vin_caption[tittle]" class="form-control" value="<?= isset($list_vin_captions['title']) ? $list_vin_captions['title'] : '' ?>">
            </div>
            <a class="btn btn-success" onclick="addVinCaption(this, '.list-vin-caption')"><i class="fa fa-plus"></i> Thêm nhà hàng & bar <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-vin-caption">
                <?php if ($list_vin_captions && isset($list_vin_captions['caption'])): ?>

                    <?php foreach ($list_vin_captions['caption'] as $key => $caption): ?>
                        <div class="caption-combo-item">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <div class="mb10">
                                        <div class="col-sm-3">
                                            <label>Ảnh Tiện nghi</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" name="list_vin_caption[caption][<?= $key ?>][image]">
                                            <input class="form-control" type="hidden" name="list_vin_caption[caption][<?= $key ?>][image_edit]" value="<?= $caption['image'] ?>">
                                        </div>
                                    </div>
                                    <?php
                                    echo $this->Form->control('list_vin_caption.caption.' . $key . '.content', [
                                        'type' => 'textarea',
                                        'class' => 'form-control tinymce',
                                        'label' => 'Mô tả *',
                                        'required' => true,
                                        'rows' => 2,
                                        'default' => (is_array($caption)) ? $caption['content'] : $caption
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.caption-combo-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Hội họp và sự kiện</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-sm-3">
                <label>Tiêu đề</label>
            </div>
            <?php
            $meetingData = json_decode($hotel->vinhms_meeting, true);
            ?>
            <div class="col-sm-9">
                <input type="text" name="vinhms_meeting[tittle]" class="form-control" value="<?= $meetingData ? $meetingData['tittle'] : '' ?>">
            </div>
            <div class="col-sm-12 col-xs-12 mt10">
                <?php
                echo $this->Form->control('vinhms_meeting.content', [
                    'type' => 'textarea',
                    'class' => 'form-control tinymce',
                    'label' => 'Mô tả *',
                    'rows' => 2,
                    'default' => $meetingData ? $meetingData['content'] : ''
                ]);
                ?>
            </div>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh hội họp và sự kiện</label>
            </div>
            <div id="dropzone-upload-meeting" class="dropzone">
            </div>
            <input type="hidden" name="vinhms_meeting_media" value='<?= $meetingData ? $meetingData["media"] : "" ?>'/>
            <input type="hidden" name="vinhms_meeting_list_image" value='<?= $list_meeting_images ?>'/>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Email</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addEmail(this, '.list-email')"><i class="fa fa-plus"></i> Thêm Email <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-email">
                <?php if ($hotel->email): ?>
                    <?php $emails = json_decode($hotel->email, true); ?>
                    <?php foreach ($emails as $key => $email): ?>
                        <div class="email-item">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <?php
                                    echo $this->Form->control('list_email['.$key.'][name]', [
                                        'templates' => [
                                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                            'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                                            'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                                        ],
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Email *',
                                        'required' => 'required',
                                        'default' => $email['name']
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="checkbox" class="flat" name="list_email[<?= $key ?>][is_main]" <?= (isset($email['is_main']) && $email['is_main']) ? 'checked' : ''?> 1>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-sm-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.email-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Thông tin thanh toán</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addPayment(this, '.list-payment')"><i class="fa fa-plus"></i>Thêm Thông tin <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-payment">
                <?php if ($hotel->payment_information): ?>
                    <?php $informations = json_decode($hotel->payment_information, true); ?>
                    <?php foreach ($informations as $key => $payment): ?>
                        <div class="room-item">
                            <hr style="margin-top: 5px">
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Tên chủ tài khoản</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input class="form-control" type="text" name="list_payment[<?= $key ?>][username]" value="<?= $payment['username'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Số tài khoản</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input class="form-control" rows="2" name="list_payment[<?= $key ?>][user_number]" value="<?= $payment['user_number'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngân hàng</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input class="form-control" rows="2" name="list_payment[<?= $key ?>][user_bank]" value="<?= $payment['user_bank'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.room-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Mô tả Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addCaption(this, '.list-caption')"><i class="fa fa-plus"></i> Thêm Mô tả ngắn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-caption">
                <?php
                if (isset($dataValiError['list_caption'])) {
                    $list_captions = $dataValiError['list_caption'];
                } else if ($hotel->caption) {
                    $list_captions = json_decode($hotel->caption, true);
                } else {
                    $list_captions = [];
                }
                ?>
                <?php if ($list_captions): ?>
                    <?php foreach ($list_captions as $key => $caption): ?>
                        <div class="caption-combo-item">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <?php
                                    echo $this->Form->control('list_caption.' . $key . '.content', [
                                        'type' => 'textarea',
                                        'class' => 'form-control tinymce',
                                        'label' => 'Mô tả *',
                                        'required' => true,
                                        'rows' => 2,
                                        'default' => (is_array($caption)) ? $caption['content'] : $caption
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.caption-combo-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>