<div class="row">
    <?php for ($i = 0; $i < $numChildren; $i++): ?>
        <div class="col-sm-3">
            <div class="item form-group">
                <label class="col-sm-4 control-label text-left"><?= ($i + 1) ?></label>
                <div class="col-sm-8">
                    <select class="form-control" name="booking_rooms[0][child_ages][]">
                        <option></option>
                        <?php for ($j = 0; $j <= 18; $j++): ?>
                            <option value="<?= $j ?>"><?= $j ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>

