<h1>Итого: <?=$params['order']['sum'];?>р.</h1>
<?if($params['order']['user_id']):?>
    <h2>ID пользователя, который сделал заказ: <?=$params['order']['user_id'];?></h2>
<?endif;?>
<?if($params['order']['description']):?>
    <h3>Детали заказа: <?=$params['order']['description'];?></h3>
<?endif;?>
<br><br>
<h3>Товары:</h3>
<?foreach($params['orderItems'] as $item):?>
<h4>
    <?=$item['name']?>: <?=$item['quantity']?> - <?=$item['price'] * $item['quantity']?>р. <br>
</h4>
<?endforeach;?>