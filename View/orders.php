<?if(!empty($params['orders'])):?>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Ссылка на заказ</th>
            <th scope="col">Детали</th>
            <th scope="col">Сумма заказа</th>
            </tr>
        </thead>
        <tbody>
            <?foreach($params['orders'] as $order):?>
                <tr>
                    <td><a href=<?=explode('&', $_SERVER['REQUEST_URI'], 2)[0] ."&order=". $order['id'];?>><?=$order['id']?></a></td>
                    <td><?=$order['description'];?></td>
                    <td><?=$order['sum'];?></td>
                </tr>
            <?endforeach;?>
        </tbody>
    </table>

<?else:?>
    <br>
    <br>
    <h1>Заказов ещё не поступало</h1>
<?endif;?>