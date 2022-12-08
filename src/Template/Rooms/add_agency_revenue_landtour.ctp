<div class="agency-revenue-item">
    <div class="row mt10">
        <div class="col-xs-11 col-sm-11">
            <?php
            echo $this->Form->control('agency_revenue[0][revenue]', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận Đại lý *',
                'required' => 'required',
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
            echo $this->Form->control('agency_revenue[0][user_id]', [
                'type' => 'select',
                'class' => 'form-control select2',
                'label' => 'Chọn đại lý *',
                'required' => 'required',
                'multiple' => true,
                'options' => $listAgency
            ]);
            ?>
        </div>
    </div>
</div>
