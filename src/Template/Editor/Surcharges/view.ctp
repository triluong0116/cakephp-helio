<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Surcharge $surcharge
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tên Phụ thu') ?></th>
            <td><?= h($surcharge->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Loại phụ thu') ?></th>
            <td>
                <?php
                switch ($surcharge->type) {
                    case 1:
                        echo "Số lượng";
                        break;
                    case 2:
                        echo "Thời gian";
                        break;
                    default:
                        echo "";
                        break;
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phụ thu tự động') ?></th>
            <td>
                <?php if ($surcharge->is_auto): ?>
                    <i class="fa fa-check-circle text-success"></i>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
