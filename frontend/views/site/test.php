<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Список карт';
$this->params['breadcrumbs'][] = $this->title;

$card = $params['card'] ?? '';
?>
<div class="site-test">

    <form method="get">
        <input id="card" type="text" name="card" placeholder="Номер карты" title="Очистите это поле чтобы показать информацию по всем картам" value="<?= $card?>">
        <input type="submit" value="Выбрать">
    </form>

    <div class="col-sm-3">
        <?php
            foreach ($tree as $row) {
                echo '<div>';
                echo Html::a($row['name'] . ' : ' . '<span class="badge">' . $row['count'] . '</span>'  , [
                        '',
                        'year' => $row["year"],
                        'month' => $row["month"],
                        'card' => $card
                    ],
                    ['class' => 'btn btn-link']
                );
                echo '</div>';
            }
        ?>
    </div>

    <div class="col-sm-9">
        <table class="table table-striped table-bordered">
            <tr>
                <th>Дата транзакции</th>
                <th>Номер карты</th>
                <th>Количество</th>
                <th>Сервис</th>
            </tr>
        <?= ListView::widget( [
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
            ]);
        ?>
        </table>
    </div>
</div>
