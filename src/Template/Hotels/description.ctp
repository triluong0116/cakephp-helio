<div class="basic-info bg-white p30">
    <div class="pt20 semi-bold fs16">
        <?php
        $list_captions = json_decode($hotel->caption, true);
        ?>
        <?php if ($list_captions): ?>
            <?php foreach ($list_captions as $caption): ?>
                <?php if (is_array($caption)): ?>
                    <li><i class="fas fa-check main-color fs20"></i>&nbsp;&nbsp;&nbsp;<?= $caption['content'] ?>
                    </li>
                <?php else: ?>
                    <li>
                        <i class="fas fa-check main-color fs20"></i>&nbsp;&nbsp;&nbsp;<?= $caption ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>