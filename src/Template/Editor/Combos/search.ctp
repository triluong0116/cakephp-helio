<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combos[]|\Cake\Collection\CollectionInterface $combos
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th >
                            combo
                        </th>
                        <th >
                            Price
                        </th>
                        <th >
                            Trippal price
                        </th>
                        <th >
                            Customer
                        </th>
                        <th >
                            Departure
                        </th>
                        <th >
                            Destination
                        </th>
                        <th >
                            Rating
                        </th>
                        <th >
                            Ảnh đại diện
                        </th>
                        <th >
                            Date Start
                        </th>
                        <th >
                            Date end
                        </th>
                        <th >
                            Created
                        </th>
                        <th class="actions">
                            <?= __('Actions') ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combos as $key => $combo): ?>
                        <tr>
                            <td><?= ++$key ?></td>
                            <td><?= h($combo->name) ?></td>
                            <td><?= $combo->price ?></td>
                            <td><?= $combo->trippal_price ?></td>
                            <td><?= $combo->customer_price ?></td>
                             <td><?= $combo->has('departure') ? $this->Html->link($combo->departure->name, ['controller' => 'Locations', 'action' => 'view', $combo->departure->id]) : '' ?></td>
                            <td><?= $combo->has('destination') ? $this->Html->link($combo->destination->name, ['controller' => 'Locations', 'action' => 'view', $combo->destination->id]) : '' ?></td>
                            <td><?= $combo->rating ?></td>
                            <td><?php
                                $this->Html->image('/'.$combo->thumbnail, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                                ?>
                            </td>
                            <td><?= h($combo->date_start) ?></td>
                            <td><?= h($combo->date_end) ?></td>
                            <td><?= h($combo->created) ?></td>
                            <td class="actions">
                                <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'view', $combo->id]) ?>">Xem</a>
                                <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Combos', 'action' => 'edit', $combo->id]) ?>">Sửa</a>
                                <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $combo->id], ['confirm' => __('Bạn có chắc muốn xóa Room: {0}?', $combo->name), 'class' => 'btn btn-xs btn-danger']); ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
