<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
echo $this->Html->script('/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min', ['block' => 'scriptBottom']);
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách Người dùng</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Screen Name</th>
                                <th>Vai trò</th>
                                <th>Điểm</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $key => $user): ?>
                                <tr>
                                    <th scope="row"><?= $key + 1 ?></th>
                                    <td><?= $user->screen_name ?></td>
                                    <td><?= $user->role->name ?></td>
                                    <td><?= $user->score_test ?></td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'edit', $user->id]) ?>">Duyệt</a>
                                        <?php
                                        echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $user->id], ['confirm' => __('Bạn có chắc muốn xóa Người dùng # {0}?', $user->id), 'class' => 'btn btn-xs btn-danger']);
                                        ?>
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
