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
            <h2>Danh sách Partner</h2>
            <?= $this->element('Backend/search') ?>
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
                                <th>Kích hoạt</th>
<!--                                <th>Khách sạn</th>-->
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
                                    <td>
                                        <?php if ($user->is_active == 1): ?>
                                            <i class="fa fa-check-circle text-success"></i>
                                        <?php endif ?>
                                    </td>
<!--                                    <td>--><?//= $user->hotel->name ?><!--</td>-->
                                    <td><?= $user->email ?></td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'viewPartner', $user->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'editPartner', $user->id]) ?>">Sửa</a>
                                        <?php if ($user->is_active == 1): ?>
                                            <form method="POST" id="change-<?= $user->id ?>"
                                                  action="changeActiveStatus/<?= $user->id ?>" style="display: none;">
                                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken'); ?>">
                                                <input type="hidden" name="is_active" value="0">
                                            </form>
                                            <a type="submit" class="btn btn-danger btn-xs"
                                               onclick="confirmChange('#change-<?= $user->id ?>');return false;">Disable</a>
                                        <?php else: ?>
                                            <form method="POST" id="change-<?= $user->id ?>"
                                                  action="changeActiveStatus/<?= $user->id ?>" style="display: none;">
                                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken'); ?>">
                                                <input type="hidden" name="is_active" value="1">
                                            </form>
                                            <a type="submit" class="btn btn-success btn-xs"
                                               onclick="confirmChange('#change-<?= $user->id ?>');return false;">Enable</a>
                                        <?php endif ?>
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
