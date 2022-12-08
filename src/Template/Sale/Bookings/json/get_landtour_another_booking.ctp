<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);

echo $this->Form->control('num_adult', [
    'type' => 'text',
    'class' => 'form-control',
    'label' => 'Số lượng người lớn *',
    'required' => 'required',
    'onchange' => 'updateTotalPriceLandtour()',
    'value' => isset($booking->booking_landtour->num_adult) ? $booking->booking_landtour->num_adult : ''
]);
echo $this->Form->control('num_children', [
    'type' => 'text',
    'class' => 'form-control',
    'label' => 'Số lượng trẻ em *',
    'required' => 'required',
    'onchange' => 'addSelectChildAgeLandtour(this)',
    'value' => isset($booking->booking_landtour->num_children) ? $booking->booking_landtour->num_children : ''

]);
?>
<div id="list-child-age">
    <?php if (isset($booking->booking_landtour->child_ages)) : ?>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-10">
        <?php
        $option = !empty($booking->landtours->land_tour_surcharges) ? json_decode($booking->landtours->land_tour_surcharges[0]->options) : [];
        $sAge = !empty($option) ? $option[0]->start : 0;
        $eAge = !empty($option) ? $option[count($option) - 1]->end : 17;
        $listAge = [];
        for ($i = $sAge; $i <= $eAge; $i++) {
            $listAge[$i] = $i;
        }
        $child_ages = json_decode($booking->booking_landtour->child_ages, true);
        ?>
        <?php if ($child_ages && !empty($child_ages)) : ?>
            <?php foreach ($child_ages as $key => $child_age): ?>
                <div class="col-sm-2 col-md-2">
                    <?php
                    echo $this->Form->control('child.' . $key, [
                        'type' => 'select',
                        'class' => 'form-control select2',
                        'options' => $listAge,
                        'label' => $i + 1,
                        'required' => 'required',
                        'onchange' => 'updateTotalPriceLandtour()',
                        'value' => $child_age
                    ]);
                    ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="control-group">
    <div class="controls">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Check in *</label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <div class="input-prepend input-group">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                <input type="text" name="start_date" class="custom-singledate-picker form-control" value=""/>
            </div>
        </div>
    </div>
</div>

