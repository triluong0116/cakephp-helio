<div class="col-xs-12 col-md-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Lợi nhuận mustgo bán cho đại lý.</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addAgencyRevenueLandtour(this, '.list-agency-revenue')"><i
                    class="fa fa-plus"></i>Thêm giá <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div id="e-loading-icon" class="text-center">
                <img src="<?= $this->Url->assetUrl('backend/img/e-loading.gif') ?>" style="width: 100px;">
            </div>
            <div id="list-revenue" class="list-agency-revenue">
                <?php $key = 0 ?>
                <?php foreach ($listPrice as $k => $price): ?>
                    <div class="agency-revenue-item">
                        <div class="row mt10">
                            <div class="col-xs-11 col-sm-11">
                                <?php
                                echo $this->Form->control('agency_revenue[' . $key . '][revenue]', [
                                    'type' => 'text',
                                    'class' => 'form-control currency',
                                    'label' => 'Lợi nhuận Đại lý *',
                                    'required' => 'required',
                                    'default' => number_format($k)
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-1 col-xs-1 text-right">
                                <a href="#" onclick="deleteItem(this, '.agency-revenue-item');" class="mt10">
                                    <i class="text-danger fa fa-minus"></i>
                                </a>
                            </div>
                            <div class="col-xs-11 col-sm-11">
                                <?php
                                echo $this->Form->control('agency_revenue[' . $key . '][user_id]', [
                                    'type' => 'select',
                                    'class' => 'form-control select2',
                                    'label' => 'Chọn đại lý *',
                                    'required' => 'required',
                                    'multiple' => true,
                                    'options' => $listAgency,
                                    'default' => $price['user_id']
                                ]);
                                $key++;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="ln_solid"></div>
        </div>
    </div>
</div>
