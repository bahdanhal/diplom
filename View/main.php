<div class="row">

<?foreach($params['items'] as $item):?>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <img class="card-img-top" alt="Нет фото" src="<?=$item["photo"]?$item["photo"]:'https://brilliant24.ru/files/cat/template_01.png'?>" style="height: 180px; width: 100%; display: block;">
            <div class="card-body d-flex flex-column" style="background-color: yellow;">
                <h3><?=$item["name"]??''?></h3>
                <span class="price"><?=$item["price"]??'';?>р.</span>
            </div>
            <div class="card-footer" style="background-color: yellow;">
                <?if(isset($params['nonAuth'])):?>
                    <form action="<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0]?>&nonAuthBuy=<?=$item['id']?>" method="POST" class="">
                        <div class="input-group">
                            <input id="description" name="description" placeholder="Ваш телефон" type="tel" required>
                            <button type="submit" class="btn btn-dark">Заказать</button>
                        </div>
                    </form>
                <?else:?>
                    <form action="<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0]?>&addToBasket=<?=$item['id']?>" method="POST" class="">
                        <input id="num" name="quantity" type="number" value="0" min="1">
                        <button type="submit">В корзину</button>
                    </form>
                <?endif;?>
            </div>

        </div>
    </div>      
<?endforeach;?>
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