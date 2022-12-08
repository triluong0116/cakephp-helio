

<div id = "resultAgency" class = "modal fade modal-fb" role = "dialog">
    <div class = "modal-dialog">
        <!--Modal content-->
        <div class = "modal-content modal-facebook border-blue">
            <div class = "modal-header">
                <button type = "button" class = "modal-close" data-dismiss = "modal"><i class="fas fa-times"></i></button>
            </div>
            <div class = "modal-body mt120 mb120 text-center">
                <p class = "bold fs18">Chúc mừng bạn đã hoàn thành bài kiểm tra</p>
                <p class = "bold fs18">Kết quả của bạn là <span class = "text-light-blue"><span id="result"></span>/<span id="total"></span></span></p>
                <a class="btn bg-blue text-white " href="<?= $this->Url->build('/') ?>">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>
