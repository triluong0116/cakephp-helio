<div class="icon-item">
    <hr />
    <div class="row">
        <div class="col-sm-5 col-xs-5">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12">Chọn Icon</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <select class="form-control font-awesome" name="list_icon[]">
                        <?php foreach ($captionIconLists as $k => $icon): ?>                        
                            <option value="<?= $k ?>"><?= $icon ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-sm-2 text-right">
            <a href="#" onclick="deleteItem(this, '.icon-item');" class="mt10">
                <i class="text-danger fa fa-minus" ></i>
            </a>
        </div>
    </div>
</div>