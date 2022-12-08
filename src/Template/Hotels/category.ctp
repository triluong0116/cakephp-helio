<div class="">
    <div class="row">
        <?php foreach ($hotel->categories as $key => $category): ?>
            <div class="col-sm-18 funitures fs16">
                <p>
                    <i class="fas fa-check text-blue"></i> <?= $category->name ?>
                </p>
            </div>
            <?php if ($key % 2 == 1): ?>
                <div class="clearfix"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>