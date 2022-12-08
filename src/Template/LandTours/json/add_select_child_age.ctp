<?php for ($i = 0; $i < $numChildren; $i++): ?>
    <div class="col-sm-9 col-xs-9 no-pad-left-sp">
        <div class="form-group vertical-center-pc">
            <p class="col-sm-4 text-center col-form-label pc"><?= ($i + 1) ?></p>
            <div class="col-sm-28 col-xs-36">
                <select class="form-control popup-voucher select-no-arrow" id="child_age" name="child_ages[]">
                    <?php for ($j = 0; $j <= 18; $j++): ?>
                        <option value="<?= $j ?>"><?= $j ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>
<?php endfor; ?>
