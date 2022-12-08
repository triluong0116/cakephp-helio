<div class="form-group">
    <p class="text-super-dark fs14 mb15">Chọn Fanpage</p>
    <select name="fanpage_id" class="form-control popup-voucher">
        <option></option>
        <?php foreach ($fanpages as $key => $fanpage): ?>
            <option value="<?= $key ?>"><?= $fanpage ?></option>
        <?php endforeach; ?>
    </select>
    <p id="error_title" class="error-messages"></p>
</div>