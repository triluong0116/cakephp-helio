<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DepositLog[]|\Cake\Collection\CollectionInterface $depositLog
 */
echo $this->Html->script('/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min', ['block' => 'scriptBottom']);
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <div class="row">
                <div class="col-md-4">
                    <h2 class="">Danh sách Người dùng</h2>
                </div>
                <div class="col-md-offset-11">
                    <a type="button" class="btn btn-primary" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'depositNew']) ?>">Nạp tiền đai lý</a>
                </div>
            </div>
            <div class="row">
                <?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>
                <?php
                $this->Form->setTemplates([
                    'formStart' => '<form class="" {{attrs}}>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                    'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                    'inputContainer' => '<div class="item form-group col-sm-3">{{content}}</div>',
                    'inputContainerError' => '<div class="item form-group col-sm-3">{{content}}{{error}}</div>',
                    'checkContainer' => ''
                ]);
                echo $this->Form->control('role_id', [
                    'empty' => 'Chọn Đại lý',
                    'type' => 'select',
                    'options' => $users,
                    'class' => 'form-control select2 ',
                    'label' => 'Chọn Đại lý'
                ]);
                echo $this->Form->control('code', [
                    'class' => 'form-control',
                    'label' => 'Mã',
                    'value' => $code ? $code : '',
                ]);
                ?>
                <div class="col-sm-3">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="status">Chọn trạng thái</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="status" class="form-control select2" id="status" tabindex="-1" aria-hidden="true" >
                            <option value="" <?= $status == 3 ? 'selected' : '' ?> >Chọn trạng thái</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Đã hủy</option>
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Đã duyệt</option>
                            <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Chưa duyệt</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Chọn</button>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên đại lý</th>
                                    <th>Mã</th>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung chuyển khoản</th>
                                    <th>Số tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ảnh</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($deposits as $key => $deposit): ?>
                                    <tr>
                                        <th scope="row"><?= $key + 1 ?></th>
                                        <td><?= $deposit->user->screen_name ?></td>
                                        <td><?= $deposit->code ?></td>
                                        <td><?= $deposit->title ?></td>
                                        <td><?= $deposit->message ?></td>
                                        <td><?= number_format($deposit->amount) ?> VNĐ</td>
                                        <td><?php
                                            if ($deposit->status == 2) {
                                                echo "<p> Chưa duyệt </p>";
                                            } elseif ($deposit->status == 0) {
                                                echo "<p> Đã hủy </p>";
                                            } else {
                                                echo "<p> Đã duyệt</p>";
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            //                                            dd(json_decode($deposit->images));
                                            $list_images = [];
                                            if (json_decode($deposit->images)) {
                                                $list_images = json_decode($deposit->images);
                                            } else{
                                                $list_images[] = $deposit->images;
                                            }
                                            ?>
                                            <?php if ($list_images): ?>
                                                <div class="row row-eq-height mt30">
                                                    <div class="col-sm-36 col-xs-36 ">
                                                        <div class="combo-slider">
                                                            <div class="box-image">
                                                                <div class="">
                                                                    <div id="customer-pay-photo" class="lightgallery2">
                                                                        <?php
                                                                        $other = count($list_images) - 3;
                                                                        ?>
                                                                        <?php if ($list_images): ?>
                                                                            <?php foreach ($list_images as $key => $image): ?>
                                                                                <?php
                                                                                $class = '';

                                                                                if ($key <= 2) {
                                                                                    $class = 'img item_' . $key;
                                                                                    $class .= ' medium-small';
                                                                                        if ($key == 2) {
                                                                                        $class .= ' end';
                                                                                    }
                                                                                } else {
                                                                                    $class = 'hide';
                                                                                }
                                                                                ?>
                                                                                <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>" >
                                                                                    <?php if ($key == 0): ?>
                                                                                    <button>Show</button>
                                                                                    <?php endif; ?>
                                                                                    <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>" style="display: none">
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($deposit->status == 2) {
                                                echo '<button type="button" class="btn btn-xs btn-warning" href="#" data-toggle="modal" data-target="#actionDebosit-'.$deposit->id.'">Duyệt</button>';
                                            }
                                            ?>
                                            <div class="modal" id="actionDebosit-<?= $deposit->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div>
                                                                <h4>Xác nhận nạp số tiền <?= number_format($deposit->amount) ?> VNĐ</h4>
                                                                <?php if (!$deposit->images) {
                                                                    echo '<img src="' . $deposit->images . '" alt="">';
                                                                }
                                                                ?>
                                                            </div>
                                                            <button type="button" class="btn btn-warning" href="#" data-dismiss="modal" onclick="browseDeposit(<?= $deposit->id ?>)">Duyệt</button>
                                                            <button type="button" class="btn btn-danger" href="#" data-dismiss="modal" onclick="deleteDeposit(<?= $deposit->id ?>)">Hủy</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <?= $this->element('Backend/admin_paging') ?>
                </div>
            </div>
        </div>
    </div>
