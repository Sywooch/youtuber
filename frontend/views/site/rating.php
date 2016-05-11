<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.05.16
 * Time: 15:43
 */

$js = <<<'JS'
$("body").on("click", '#updateBtn', function(){
    $.pjax.reload({container: '#rating-grid'});
});

setInterval(function(){$.pjax.reload({container: '#rating-grid'});}, 1500);
JS;

$this->registerJs($js);

$this->title = 'Таблица рейтинга';

echo \yii\bootstrap\Html::tag('blockquote',
    \yii\bootstrap\Html::tag('h1', $this->title).
    \yii\bootstrap\Html::tag('p', 'Здесь собраны в виде таблицы все видео прошедшие через наш сервис')
);

\yii\widgets\Pjax::begin([
    'id'    =>  'rating-grid'
]);
echo \yii\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'columns'       =>  [
        [
            'class' =>  \yii\grid\SerialColumn::className()
        ],
        [
            'format'    =>  'raw',
            'attribute' =>  'name',
            'value'     =>  function($model){
                if(!empty($model->link_hash)){
                    return \yii\bootstrap\Html::a($model->name, '/search-video/'.$model->link_hash);
                }
                return $model->name;
            }
        ],
        [
            'attribute' =>  'views',
            'value'     =>  function($model){
                return number_format($model->views, 0, '.', ' ');
            }
        ],
        [
            'attribute' =>  'likes',
            'value'     =>  function($model){
                return number_format($model->likes, 0, '.', ' ');
            }
        ],
        [
            'attribute' =>  'dislikes',
            'value'     =>  function($model){
                return number_format($model->dislikes, 0, '.', ' ');
            }
        ],
        'uploaded',
        [
            'attribute' =>  'checked',
            'value'     =>  function($model){
                //return $model->checked;
                return \Yii::$app->formatter->asRelativeTime(strtotime($model->checked) - (60 * 60 * 3));
            }
        ]
    ]
]);
\yii\widgets\Pjax::end();