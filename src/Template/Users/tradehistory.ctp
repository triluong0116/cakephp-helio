<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div>
                <center>
                    <div class="pt25 pb50">
                        <h2><span class="box-underline-center pb20">Lịch sử giao dịch</span></h2>
                    </div>
                </center>
            </div>
            <center>
                <div class="box-white mb50 mt20" style="width :500px">
                    <h4 class="pt35 fs20 text-center">Số dư tài khoản: <?= number_format($user->revenue) ?><sup>đ</sup></h4>
                    <center class="pt20 pb40">
                        <a class="btn bg-blue text-white fs15 pl30 pr30 pt5 pb5" onclick="Frontend.withdraw()">Rút </a>
                    </center>
                </div>
                <div class="mt30 mb20">
                    <div class="box-white mb30">
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Ngày giao dịch</th>
                                <th class="text-center">Số tài khoản</th>
                                <th class="text-center">Chủ tài khoản</th>
                                <th class="text-center">Ngân hàng</th>
                                <th class="text-center">Số tiền rút</th>
                                <th class="text-center">Tình trạng giao dịch</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($withdraws as $key => $withdraw): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= date_format($withdraw->created, "d/m/Y H:m:s") ?></td>
                                    <td><?= $withdraw->user->bank_code ?></td>
                                    <td><?= $withdraw->user->bank_master ?></td>
                                    <td><?= $withdraw->user->bank_name ?></td>
                                    <td><?= number_format($withdraw->amount) ?>đ</td>
                                    <td><?= ($withdraw->status == 0)? 'Đang giao dịch' : 'Hoàn thành' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </center>
        </div>
    </div>
</div>
<?= $this->element('Front/Popup/withdraw') ?>