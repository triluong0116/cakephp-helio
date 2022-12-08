<div class="accessory-item">
    <div class="row mt10">
        <div class="col-xs-11 col-sm-11">
            <?php
            echo $this->Form->control('land_tour_accessories[0][name]', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.accessory-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
        <div class="col-xs-12 col-sm-12">
            <?php
            echo $this->Form->control('land_tour_accessories[0][adult_price]', [
                'type' => 'text',
                'class' => 'form-control inputmask-number',
                'label' => 'Giá người lớn *',
                'required' => 'required'
            ]);
            ?>
        </div>
    </div>
</div>
