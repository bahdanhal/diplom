<?if(!isset($params['empty'])):?>
    <?foreach($params['items'] as $item):?>

            <?=$item['name']?>: <?=$item['quantity']?> - <?=$item['price'] * $item['quantity']?>р. <br>

    <?endforeach;?>
    <br><br>
    <h1>Итого: <?=$params['sum']?>р.</h1>
    <form action="<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0]?>&confirmOrder=Y" method="POST">
        <label for="description">Опишите возможные детали и то, как с вами связаться</label><br>
        <textarea id="description" name="description" rows="10" cols="70"></textarea><br>
        <button type="submit">Оформить заказ</button>
    </form>
<?else:?>
    <br>
    <br>
    <?if(isset($params['created'])):?>
        <h1>Заказ успешно оформлен</h1>
    <?else:?>    
        <h1>Корзина пуста</h1>
    <?endif;?>
<?endif;?>