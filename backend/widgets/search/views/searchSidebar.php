<form action="" method="get" class="sidebar-form">
    <div class="input-group">
        <label for="q-input"></label>
        <input id="q-input" type="text" name="q" class="form-control"
               placeholder="<?= Yii::t('app', 'Search') . '...' ?>">

        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                <i class="fa fa-search"></i>
            </button>
        </span>
        <span class="input-group-btn">
            <a href="#">
                <button type="button" title="Información sobre lo que se puede buscar aqui." name="info-search" id="info-search-btn" class="btn btn-flat">
                    <i class="fa fa-info"></i>
                </button>
            </a>
        </span>
    </div>
</form>

