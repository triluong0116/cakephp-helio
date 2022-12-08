<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo[]|\Cake\Collection\CollectionInterface $combos
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách Combo</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên Combo</th>
                        <th>Điểm Xuất phát</th>
                        <th>Điểm đến</th>
                        <th>Giá</th>
                        <th>Giá cho CTV</th>
                        <th>Giá cho KH</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combos as $key => $combo): ?>
                        <tr>
                            <th scope="row"><?= $key + 1 ?></th>
                            <td><?= $combo->name ?></td>
                            <td><?= $combo->has('departure') ? $this->Html->link($combo->departure->name, ['controller' => 'Locations', 'action' => 'view', $combo->departure->id]) : '' ?></td>
                            <td><?= $combo->has('destination') ? $this->Html->link($combo->destination->name, ['controller' => 'Locations', 'action' => 'view', $combo->destination->id]) : '' ?></td>
                            <td><?= $this->Number->format($combo->price) ?></td>
                            <td><?= $this->Number->format($combo->trippal_price) ?></td>
                            <td><?= $this->Number->format($combo->customer_price) ?></td>                            
                            <td>
                                <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Locations', 'action' => 'view', $combo->id]) ?>">
                                    <i class="fa fa-eye"></i> Xem
                                </a>
                                <a type="button" class="btn btn-xs btn-success" onclick="showModalPostFacebook(this);" data-object-type="<?= COMBO ?>" data-object-id="<?= $combo->id ?>">
                                    <i class="fa fa-share"></i> Đăng lên Facebook
                                </a>                                
                            </td>
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