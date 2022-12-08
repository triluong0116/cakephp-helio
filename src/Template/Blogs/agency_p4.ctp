<!-- Header Combo -->
<!-- End Header Combo -->
<!-- Start content -->
<div class="blog-detail bg-grey">
    <div class="container pl14">
        <div class="row">
            <div class="col-sm-36">
                <center>
                    <div class="pb50">
                        <div class="mt25 pb15 box-underline-center">
                            <h3>ĐÀO TẠO CỘNG TÁC VIÊN</h3>
                        </div>
                    </div>
                </center>
                <div class="box-shadow bg-white">
                    <div class="fs14 pt25 pb15">
                        <form id="agency-quiz">
                            <ol>
                                <?php foreach ($questions as $question): ?>
                                    <li class="mb50 fs16">
                                        <?= $question->content ?>
                                        <div class="row pt10">
                                            <ul>
                                                <?php $answers = json_decode($question->answer, true); ?>
                                                <?php foreach ($answers as $answer): ?>
                                                    <div class="col-sm-9">
                                                        <li><input type="radio" class="iCheck" name="<?= $question->id ?>" value="<?= $answer['content'] ?>"> <?= $answer['content'] ?></li>
                                                    </div>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </form>
                    </div>
                </div>
                <div class="pt30 pb30">
                    <center>
                        <a class="btn bg-blue text-white fs18 mb20" data-toggle="modal" data-target="#myModal" onclick="Frontend.submitAnswers();return false;">GỬI</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End content -->
<?= $this->element('Front/Popup/agency_result') ?>   