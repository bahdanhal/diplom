<div>
Привет, <?=  $params['user']['name'] ?>!	
</div>
<div>
<?if($params['user']['status'] == 'admin'):?>
    <a href="/cabinet/catalog">Управление каталогом</a>
    <a href="/cabinet/orders">Заказы</a>
<?endif;?>
    <a href="/cabinet/basket">Корзина</a>
</div>
<a href="/exit">Выход</a>
