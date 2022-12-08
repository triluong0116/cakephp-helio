<div class="drive-surchage-item">
    <div class="row mt10">
        <div class="col-xs-11 col-sm-11">
            <?php
            echo $this->Form->control('land_tour_drivesurchages[0][name]', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteItem(this, '.drive-surchage-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6">
            <?php
            echo $this->Form->control('land_tour_drivesurchages[0][price_adult]', [
                'type' => 'text',
                'class' => 'form-control inputmask-number currency',
                'label' => 'Giá người lớn *',
                'required' => 'required'
            ]);
            ?>
        </div>
        <div class="col-xs-6 col-sm-6">
            <?php
            echo $this->Form->control('land_tour_drivesurchages[0][price_crowd]', [
                'type' => 'text',
                'class' => 'form-control inputmask-number currency',
                'label' => 'Giá đoàn',
                'required' => 'required'
            ]);
            ?>
        </div>
    </div>
</div>
