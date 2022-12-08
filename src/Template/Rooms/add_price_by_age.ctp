<div class="age-price-item">
    <div class="row mt10">
        <div class="col-xs-3 col-sm-3">
            <?php
            echo $this->Form->control('land_tour_surcharges[0][options][0][start]', [
                'type' => 'text',
                'class' => 'form-control inputmask-number',
                'label' => 'Từ Tuổi *',
                'required' => 'required',
                'readonly' => true,
                'default' => $lastValue,
            ]);
            ?>
        </div>
        <div class="col-xs-3 col-sm-3">
            <?php
            echo $this->Form->control('land_tour_surcharges[0][options][0][end]', [
                'type' => 'text',
                'class' => 'form-control inputmask-number',
                'label' => 'Đến tuổi *',
                'required' => 'required',
                'onchange' => 'updateNextSiblingAge(this)'
            ]);
            ?>
        </div>
        <div class="col-xs-5 col-sm-5">
            <?php
            echo $this->Form->control('land_tour_surcharges[0][options][0][price]', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Đơn giá *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.age-price-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
    </div>
</div>

