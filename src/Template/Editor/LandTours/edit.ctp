<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<?= $this->Form->create($landTour, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
    <h1>Thêm Land Tour</h1>
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thông tin Land Tour</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php
                echo $this->Form->control('name', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Tên Land Tour *',
                    'required' => 'required'
                ]);
                ?>
                <?php
                echo $this->Form->control('departure_id', [
                    'options' => $departures,
                    'class' => 'form-control select2',
                    'label' => 'Điểm đi *',
                    'required' => 'required'
                ]);
                echo $this->Form->control('destination_id', [
                    'options' => $destinations,
                    'class' => 'form-control select2',
                    'label' => 'Điểm đến *',
                    'required' => 'required'
                ]);
                echo $this->Form->control('phone', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Hotline *',
                    'required' => 'required'
                ]);
                echo $this->Form->control('organizer', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Đơn vị tổ chức *',
                    'required' => 'required'
                ]);
                ?>

                <div class="control-group">
                    <div class="controls">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Thời gian *</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type="text" name="reservation" class="custom-daterange-picker form-control" value="<?= $reservation ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
<!--                <div class="item form-group">-->
<!--                    <label class="control-label col-md-3 col-sm-3 col-xs-12">File hợp đồngs *</label>-->
<!--                    <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--                        <input type="file" name="contract_file"/>-->
<!--                        <input name="contract_file_edit" type="hidden" value='--><?php //echo $landTour->contract_file ?><!--'/>-->
<!--                    </div>-->
<!--                    --><?php //if ($landTour->contract_file) : ?>
<!--                        <div class="form-group">-->
<!--                            <div class="col-md-6 col-md-offset-3">-->
<!--                                <a href="--><?php //echo \Cake\Routing\Router::url('/', true) . $landTour->contract_file ?><!--" target="_blank">File hợp đồng</a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    --><?php //endif; ?>
<!--                </div>-->
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" name="thumbnail"/>
                        <input name="thumbnail_edit" type="hidden" value='<?= $landTour->thumbnail ?>'/>
                    </div>
                </div>
                <?php if ($landTour->thumbnail): ?>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <?= $this->Html->image('/' . $landTour->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Khuyễn mãi</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" name="promotion"/>
                        <input name="promotion_edit" type="hidden" value='<?= $landTour->promotion ?>'/>
                    </div>
                </div>
                <?php
                //                echo $this->Form->control('fb_content', [
                //                    'type' => 'textarea',
                //                    'class' => 'form-control',
                //                    'label' => 'Nội dung chia sẻ Facebook *',
                //                    'required' => 'required'
                //                ]);
                ?>
                <div class="text-center">
                    <label class="control-label">Danh sách Ảnh</label>
                </div>
                <div id="dropzone-upload" class="dropzone">
                </div>
                <input type="hidden" name="media" value='<?= $landTour->media ?>'/>
                <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
                <div class="clearfix"></div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Mô tả ngắn</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a class="btn btn-success" onclick="addCaption(this, '.list-caption', false)"><i class="fa fa-plus"></i> Thêm Mô tả <i class="fa fa-spinner fa-spin hidden"></i></a>
                <div class="list-caption">
                    <?php
                    if (isset($dataValiError['list_caption'])) {
                        $list_captions = $dataValiError['list_caption'];
                    } else if ($landTour->caption) {
                        $list_captions = json_decode($landTour->caption, true);
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
                                            'label' => 'mô tả *',
                                            'required' => 'required',
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
                <h2>Chi tiết Landtour</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Đánh giá</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class='starrr-existing' data-rating="<?= $landTour->rating ?>"></div>
                        <input type="hidden" name="rating" value="<?= $landTour->rating ?>"/>
                    </div>
                </div>
                <?php
                echo $this->Form->control('price', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Giá *',
                    'required' => 'required',
                ]);
                echo $this->Form->control('trippal_price', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Lợi nhuận của Mustgo *',
                    'required' => 'required',
                ]);
                echo $this->Form->control('customer_price', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Lợi nhuận đại lý bán cho khách lẻ *',
                    'required' => 'required',
                ]);
                echo $this->Form->control('promote', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Khuyến mại'
                ]);
                echo $this->Form->control('child_rate', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Giá trẻ em (%) *'
                ]);
                echo $this->Form->control('kid_rate', [
                    'type' => 'text',
                    'class' => 'form-control currency',
                    'label' => 'Giá em bé (%) *'
                ]);
                ?>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>Mô tả chi tiết</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <?php
                $json = json_decode($landTour->people_description, true);
                if(!$json){
                    $json['description_type'] = "";
                    $json['adult_description'] = "";
                    $json['child_description'] = "";
                    $json['kid_description'] = "";
                }
                ?>
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-9">
                        <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck" name="description_type" <?= $json['description_type'] == "" ? "checked" : "" ?> <?= $json['description_type'] == "age" ? "checked" : "" ?> value="age"> Tuổi</p>
                        <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck" name="description_type" <?= $json['description_type'] == "height" ? "checked" : "" ?>  value="height"> Chiều cao</i></p>
                    </div>
                </div>
                <?php
                echo $this->Form->control('adult_description', [
                    'type' => 'textarea',
                    'class' => 'form-control tinymce2',
                    'label' => 'Mô tả người lớn*',
                    'default' => $json['adult_description']
                ]);
                ?>
                <?php
                echo $this->Form->control('child_description', [
                    'type' => 'textarea',
                    'class' => 'form-control tinymce2',
                    'label' => 'Mô tả trẻ em*',
                    'default' => $json['child_description']
                ]);
                ?>
                <?php
                echo $this->Form->control('kid_description', [
                    'type' => 'textarea',
                    'class' => 'form-control tinymce2',
                    'label' => 'Mô tả em bé*',
                    'default' => $json['kid_description']
                ]);
                ?>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>Các gói Landtour</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div id="list-accessory" class="list-accessory-price">
                    <?php foreach($landTour->land_tour_accessories as $k => $accessory): ?>
                        <div class="accessory-item">
                            <div class="row mt10">
                                <input type="hidden" name="land_tour_accessories[<?= $k ?>][id]" value="<?= $accessory->id ?>">
                                <div class="col-sm-11">
                                    <label for="">Tên</label>
                                    <input type="text" class="form-control" required name="land_tour_accessories[<?= $k ?>][name]" value="<?= $accessory->name ?>">
                                </div>
                                <div class="col-sm-1 col-xs-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.accessory-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-sm-12 mt10">
                                    <label for="">Giá người lớn *</label>
                                    <input type="text" class="form-control inputmask-number" required name="land_tour_accessories[<?= $k ?>][adult_price]" value="<?= $accessory->adult_price ?>">
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-success text-center" onclick="addLandtourAccessory(this, '.list-accessory-price')"><i class="fa fa-plus"></i> Thêm</button>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>Phụ thu đưa đón</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div id="list-drive-surchage" class="list-drive-surchage-price">
                    <?php foreach($landTour->land_tour_drivesurchages as $k => $drivesurchage): ?>
                        <div class="drive-surchage-item">
                            <div class="row mt10">
                                <div class="col-xs-11 col-sm-11">
                                    <input type="hidden" name="land_tour_drivesurchages[<?= $k ?>][id]" value="<?= $drivesurchage->id ?>">
                                    <label for="">Tên</label>
                                    <input type="text" class="form-control" required name="land_tour_drivesurchages[<?= $k ?>][name]" value="<?= $drivesurchage->name ?>">
                                </div>
                                <div class="col-sm-1 col-xs-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.drive-surchage-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <label for="">Giá người lớn *</label>
                                    <input type="text" class="form-control currency" required name="land_tour_drivesurchages[<?= $k ?>][price_adult]" value="<?= number_format($drivesurchage->price_adult) ?>">
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <label for="">Giá đoàn</label>
                                    <input type="text" class="form-control currency" required name="land_tour_drivesurchages[<?= $k ?>][price_crowd]" value="<?= number_format($drivesurchage->price_crowd) ?>">
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-success text-center" onclick="addLandtourDriveSurchage(this, '.list-drive-surchage-price')"><i class="fa fa-plus"></i> Thêm</button>
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
                    <?php if ($landTour->email): ?>
                        <?php $emails = json_decode($landTour->email, true); ?>
                        <?php foreach ($emails as $email): ?>
                            <div class="email-item">
                                <hr/>
                                <div class="row">
                                    <div class="col-sm-10 col-xs-10">
                                        <?php
                                        echo $this->Form->control('list_email[' . $key . '][name]', [
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
                                                    <input type="checkbox" class="flat" name="list_email[<?= $key ?>][is_main]" <?= (isset($email['is_main']) && $email['is_main']) ? 'checked' : '' ?> value="1">
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
<!--        <div class="x_panel">-->
<!--            <div class="x_title">-->
<!--                <h2>Thêm Điều khoản</h2>-->
<!--                <div class="clearfix"></div>-->
<!--            </div>-->
<!--            <div class="x_content">-->
<!--                <a class="btn btn-success" onclick="addHotelTerm(this, '.list-term')"><i class="fa fa-plus"></i> Thêm Điều khoản <i class="fa fa-spinner fa-spin hidden"></i></a>-->
<!--                <div class="list-term">-->
<!--                    --><?php
//                    if (isset($dataValiError['list_term'])) {
//                        $list_terms = $dataValiError['list_term'];
//                    } else if ($landTour->term) {
//                        if ($landTour->term != null) {
//                            $list_terms = json_decode($landTour->term, true);
//                        } else {
//                            $list_terms = [];
//                        }
//                        $list_terms = json_decode($landTour->term, true);
//                    } else {
//                        $list_terms = [];
//                    }
//                    ?>
<!--                    --><?php //if (!empty($list_terms)): ?>
<!--                        --><?php //foreach ($list_terms as $key => $term): ?>
<!--                            <div class="term-item">-->
<!--                                <hr/>-->
<!--                                <div class="row">-->
<!--                                    <div class="col-sm-10 col-xs-10">-->
<!--                                        --><?php
//                                        echo $this->Form->control('list_term[' . $key . '][name]', [
//                                            'type' => 'text',
//                                            'class' => 'form-control',
//                                            'label' => 'Tiêu đề *',
//                                            'default' => $term['name'],
//                                            'required' => 'required',
//                                        ]);
//                                        echo $this->Form->control('list_term[' . $key . '][content]', [
//                                            'type' => 'textarea',
//                                            'class' => 'form-control tinymce2',
//                                            'label' => 'Nội dung *',
//                                            'default' => $term['content'],
//                                            'id' => 'term-' . $key,
//                                            'required' => 'required',
//                                        ]);
//                                        ?>
<!--                                    </div>-->
<!--                                    <div class="col-sm-2 col-sm-2 text-right">-->
<!--                                        <a href="#" onclick="deleteItem(this, '.term-item');" class="mt10">-->
<!--                                            <i class="text-danger fa fa-minus"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //endforeach; ?>
<!--                    --><?php //endif; ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="x_panel">
            <div class="x_title">
                <h2>Thêm Điều khoản</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a class="btn btn-success" onclick="addHotelTerm(this, '.list-term')"><i class="fa fa-plus"></i> Thêm Điều khoản <i class="fa fa-spinner fa-spin hidden"></i></a>
                <div class="list-term">
                    <?php
                    if (isset($dataValiError['list_term'])) {
                        $list_terms = $dataValiError['list_term'];
                    } else if ($landTour->term) {
                        $list_terms = json_decode($landTour->term, true);
                    } else {
                        $list_terms = [];
                    }
                    ?>
                    <?php if ($list_terms): ?>
                        <?php foreach ($list_terms as $key => $term): ?>
                            <div class="term-item">
                                <hr/>
                                <div class="row">
                                    <div class="col-sm-10 col-xs-10">
                                        <?php
                                        echo $this->Form->control('list_term[' . $key . '][name]', [
                                            'type' => 'text',
                                            'class' => 'form-control',
                                            'label' => 'Tiêu đề *',
                                            'default' => $term['name'],
                                            'required' => 'required',
                                        ]);
                                        echo $this->Form->control('list_term[' . $key . '][content]', [
                                            'type' => 'textarea',
                                            'class' => 'form-control tinymce2',
                                            'label' => 'Nội dung *',
                                            'default' => $term['content'],
                                            'id' => 'term-' . $key,
                                            'required' => 'required',
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-sm-2 col-sm-2 text-right">
                                        <a href="#" onclick="deleteItem(this, '.term-item');" class="mt10">
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
                    <?php if ($landTour->payment_information): ?>
                        <?php $informations = json_decode($landTour->payment_information, true); ?>
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
<?= $this->Form->end() ?>
