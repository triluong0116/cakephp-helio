<option value="">Chọn hạng phòng</option>
<?php foreach ($listRoom as $key => $room): ?>
    <option value="<?= $key ?>"><?= $room ?></option>
<?php endforeach; ?>

