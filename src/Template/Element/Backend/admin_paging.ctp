<?php if ($this->Paginator->param('pageCount') > 1) { ?>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <ul class="pagination">
            <?= $this->Paginator->first('First') ?>
            <?= $this->Paginator->prev('Prev') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('Next') ?>
            <?= $this->Paginator->last('Last') ?>
        </ul>
    </div>
<?php } ?>