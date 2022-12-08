<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo[]|\Cake\Collection\CollectionInterface $combos
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <?php if ($this->request->is('get') && $this->request->getQuery('search')): ?>
                <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?php else: ?>
                <h2><?= __('Combos') ?></h2>
            <?php endif; ?>
            <?= $this->element('Backend/searchv3') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('#') ?></th>
                                <th><?= $this->Paginator->sort('Tên gói') ?></th>
                                <th><?= $this->Paginator->sort('Khuyến mãi') ?></th>
                                <th><?= $this->Paginator->sort('Điểm đi') ?></th>
                                <th><?= $this->Paginator->sort('Điểm đến') ?></th>
                                <th><?= $this->Paginator->sort('Thời gian') ?></th>
                                <th><?= $this->Paginator->sort('Đánh giá') ?></th>
                                <th class="actions"><?= __('Thực hiện') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($combos as $combo): ?>
                                <tr>
                                    <td><?= $this->Number->format($combo->id) ?></td>
                                    <td><?= $combo->name ?></td>
                                    <td><?= $combo->promote ?> %</td>
                                    <td><?= h($combo->departure->name) ?></td>
                                    <td><?= h($combo->destination->name) ?></td>
                                    <td><?= $combo->days ?></td>
                                    <td><?= $this->Number->format($combo->rating) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'view', $combo->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'edit', $combo->id]) ?>">Sửa</a>
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