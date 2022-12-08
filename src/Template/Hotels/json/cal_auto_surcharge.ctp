<?php use App\View\Helper\SystemHelper;

foreach ($auto_surcharges as $key => $surcharge):?>
    <div class="normal-surcharge-item">
        <div class="row ml15 mt15 mb15 mr15">
            <input type="hidden" name="booking_surcharges[<?= $key?>][surcharge_type]" value="<?= $surcharge['id']?>">
            <input type="hidden" name="booking_surcharges[<?= $key?>][price]" value="<?= $surcharge['fee']?>" class="booking-value">
            <div class="col-sm-18 text-left">
                <p class="fs16"><i><?= SystemHelper::getSurchargeName($surcharge['id']) ?></i></p>
            </div>
            <div class="col-sm-18 text-right">
                <p class="fs16 main-color"><?= number_format($surcharge['fee'])?> VNĐ</p>
            </div>
        </div>
    </div>

<?php endforeach;?>
