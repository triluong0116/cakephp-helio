<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question $question
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tên Đại lý') ?></th>
            <td><?= $withdraw->user->screen_name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số tiền cần rút') ?></th>
            <td><?= number_format($withdraw->amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Chủ tài khoản') ?></th>
            <td><?= $withdraw->user->bank_master ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số tài khoản') ?></th>
            <td><?= $withdraw->user->bank_code ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tên ngân hàng') ?></th>
            <td><?= $withdraw->user->bank_name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Chi nhánh') ?></th>
            <td><?= $withdraw->user->bank ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= $withdraw->email ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zalo') ?></th>
            <td><?= $withdraw->zalo ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Trạng thái') ?></th>
            <td><?= $withdraw->status == 0 ? 'Đang chờ xử lý' : 'Đã thanh toán' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày yêu cầu') ?></th>
            <td><?= date_format($withdraw->created, 'd-m-Y H:i:s') ?></td>
        </tr>
    </table>
    <?php
    echo $this->Form->postLink(__('Hoàn tất'), ['action' => 'delete', $withdraw->id], ['confirm' => __('Bạn có chắc muốn hoàn tất?'), 'class' => 'btn btn-xs btn-danger']);
    ?>
</div>
