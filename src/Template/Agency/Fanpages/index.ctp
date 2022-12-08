<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Fanpage[]|\Cake\Collection\CollectionInterface $fanpages
 */
$helper = $fbGlobal->getRedirectLoginHelper();
$baseUrl = $this->Url->build('/', true);
$permissions = ['email', 'user_location', 'user_birthday', 'manage_pages', 'publish_pages'];
$loginUrl = $helper->getLoginUrl($baseUrl . 'agency/fanpages/get_user_facebook_info', $permissions);
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách Fanpage</h2>
            <a class="btn btn-primary pull-right" href=" <?= htmlspecialchars($loginUrl) ?>"> <i class="fa fa-facebook-official"></i> Liên kết với Facebook!</a>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên Fanpage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fanpages as $key => $fanpage): ?>
                        <tr>
                            <td scope="row"><?= $key + 1 ?></td>
                            <td><?= $fanpage->name ?></td>
                            <td>
                                <a type="button" class="btn btn-xs btn-success" target="_blank" href="http://facebook.com/<?= $fanpage->page_id ?>">
                                    <i class="fa fa-eye"></i> Xem
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
