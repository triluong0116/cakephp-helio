<?php for ($i = 0; $i < $numChildren; $i++): ?>
    <div class="col-sm-9 col-xs-9 no-pad-left-sp">
        <div class="form-group mb05-sp vertical-center">
            <p class="col-sm-4 pc text-center col-form-label"><?= ($i + 1) ?></p>
            <div class="col-sm-28 col-xs-36">
                <select class="form-control popup-voucher select-no-arrow" id="child_age" name="booking_rooms[0][child_ages][]">
                    <?php for ($j = 0; $j <= 18; $j++): ?>
                        <option value="<?= $j ?>"><?= $j ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>
<?php endfor; ?>
