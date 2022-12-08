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
            <div>
                <h2>Danh sách Người dùng</h2>
            </div>
            <div class="clearfix"></div>
            <?= $this->element('Backend/searchv3') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Screen Name</th>
                                <th>Vai trò</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $key => $user): ?>
                                <tr>
                                    <th scope="row"><?= $key + 1 ?></th>
                                    <td><?= $user->username ?></td>
                                    <td><?= $user->screen_name ?></td>
                                    <td><?= $user->role->name ?></td>
                                    <td><?= $user->email ?></td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $user->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'edit', $user->id]) ?>">Sửa</a>
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
