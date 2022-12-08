<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Surcharge[]|\Cake\Collection\CollectionInterface $surcharges
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách Phụ Thu</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">
                                    Tên Phụ thu
                                </th>
                                <th scope="col">
                                    Loại Phụ thu
                                </th>
                                <th>
                                    Phụ thu tự động
                                </th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($surcharges as $key => $surcharge): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= h($surcharge->name) ?></td>
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
                                    <td>
                                        <?php if ($surcharge->is_auto): ?>
                                            <i class="fa fa-check-circle text-success"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Surcharges', 'action' => 'view', $surcharge->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Surcharges', 'action' => 'edit', $surcharge->id]) ?>">Sửa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
