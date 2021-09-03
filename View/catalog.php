<div class="row">
<?foreach($params['items'] as $item):?>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <a href="<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0] . "&delete=" . $item['id']?>" class="button">Удалить</a>
            <form action="/cabinet/catalog&id=<?=$item['id'];?>" enctype="multipart/form-data" method="POST">    
                <div class="card-body d-flex flex-column">
                    <input name = "element[name]" placeholder="Наименование" value="<?=$item["name"]??''?>">
                    <input name = "element[price]" placeholder="Цена" value="<?=$item["price"]??''?>">
                    Фото: <input type="file" name="filename" size="10" />
                    <?if($item["photo"]):?>
                        Удалить фото<input name = "deletePhoto" type="checkbox">
                    <?endif;?>
                </div>
                <div class="card-footer">
                    <button type="submit">Сохранить элемент</button>
                </div>
            </form>
        </div>
        <br>
    </div>      
<?endforeach;?>
</div>
<br>
<div>
    <a href="<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0]?>&add=true" class="button">Добавить элемент</a>
</div>
<?if(isset($_GET['page'])):?>
    <?if(intval($_GET['page']) > 1):?>
        <a href = "<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0].'&page='.(intval($_GET['page']) - 1)?>">
            Назад  
        </a>
    <?endif;?>
    <a href = "<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0].'&page='.((intval($_GET['page']) > 1) ? intval($_GET['page']) + 1 : 2)?>">
        Вперед
    </a>
<?else:?>
    <a href = "<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0].'&page=2'?>">
        Вперед
    </a>
<?endif;?>