<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Apples');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Random Apples'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'color',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->realStatusLabel();
                }
            ],
            'eat_percent',
            [
                'attribute' => 'fell_at',
                'value' => function ($model) {
                    return $model->fell_at === 0 ? '-' : (new DateTime())->setTimestamp($model->fell_at)->format('M d, Y H:i:s A');
                }
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fall} {eat}',
                'buttons' => [
                    'fall' => function ($url, $model, $key) {
                        return $model->canFall() ? Html::a('Fall', $url) : '';
                    },
                    'eat' => function ($url, $model, $key) {
                        return $model->canEat() ? Html::beginForm($url, 'get')
                            . Html::input('string', 'percent')
                            . Html::submitButton('Eat')
                            . Html::endForm() : '';
                    }
                ]
            ],
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>
