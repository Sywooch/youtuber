<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\JsExpression;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '57x57', 'href' => '/apple-icon-57x57.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '60x60', 'href' => '/apple-icon-60x60.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '72x72', 'href' => '/apple-icon-72x72.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '76x76', 'href' => '/apple-icon-76x76.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '114x114', 'href' => '/apple-icon-114x114.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '120x120', 'href' => '/apple-icon-120x120.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '144x144', 'href' => '/apple-icon-144x144.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '152x152', 'href' => '/apple-icon-152x152.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => '/apple-icon-180x180.png']);
$this->registerLinkTag(['rel' => 'icon', 'sizes' => '192x192', 'href' => '/android-icon-192x192.png']);
$this->registerLinkTag(['rel' => 'icon', 'sizes' => '32x32', 'href' => '/favicon-32x32.png']);
$this->registerLinkTag(['rel' => 'icon', 'sizes' => '96x96', 'href' => '/favicon-96x96.png']);
$this->registerLinkTag(['rel' => 'icon', 'sizes' => '16x16', 'href' => '/favicon-16x16.png']);
$this->registerLinkTag(['rel' => 'manifest', 'href' => '/manifest.json']);
$this->registerMetaTag(['name' => 'msapplication-TileColor', 'content'  =>  '#0c84e4']);
$this->registerMetaTag(['name' => 'msapplication-TileImage', 'content'  =>  '/ms-icon-144x144.png']);
$this->registerMetaTag(['name' => 'theme-color', 'content'  =>  '#0c84e4']);

\rmrevin\yii\fontawesome\cdn\AssetBundle::register($this);

$analyticsJs = <<<'JS'
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-77677693-1', 'auto');
ga('require', 'linkid');
ga('send', 'pageview');

$("#searchWidget").bind("typeahead:asyncreceive", function(event, query, dataset){
    ga('send', 'pageview', '/search/?string=' + $(this).val());
});
JS;

$css = <<<'CSS'
.twitter-typeahead{
    width: 300px;
}

.tt-input.loading {
    background: transparent url('../img/loading.gif') no-repeat scroll right center content-box !important;
}

.tt-input{
    width: 100% !important;
}

.tt-menu, .tt-dataset{
    width: 500px;
}

.tt-dataset a{
    //width: 480px;
    overflow: hidden;
}
CSS;

$this->registerCss($css);

$this->registerJs($analyticsJs);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Youtuber',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label'    =>  'Главная', 'url' => ['/site/index'], ['options' => ['data-pjax' => 0]]],
        ['label'    =>  'Поиск', 'url' => ['/site/search'], ['options' => ['data-pjax' => 0]]],
        ['label'    =>  'Таблица рейтинга', 'url' => ['/site/rating'], ['options' => ['data-pjax' => 0]]],
        //['label'    =>  'О проекте', 'url' => ['/site/contact']],
    ];
    /*if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }*/

    $typeaheadTemplate = Html::a('{{name}}', '/video/{{youtubeID}}', ['data-pjax' => 0]);

    echo Nav::widget([
        'options'   =>  ['class' => 'navbar-form navbar-left', 'role'   =>  'search'],
        'items'     =>  [\kartik\typeahead\Typeahead::widget([
            'name'      =>  'search',
            'id'        =>  'searchWidget',
            'options'   =>  [
                'placeholder'   =>  'поиск по видео...'
            ],
            'dataset'   =>  [
                [
                    'remote'    =>  [
                        'rateLimitBy'   =>  'throttle',
                        'url'       =>  '/search?string=QUERY',
                        'wildcard'  =>  'QUERY'
                    ],
                    'display'   =>  'name',
                    'limit'     => 10,
                    'templates' => [
                        'empty' => Html::tag('div', 'Ничего не найдено', ['class' => 'text-error']),
                        'suggestion' => new JsExpression("Handlebars.compile('{$typeaheadTemplate}')"),
                    ]
                ]
            ]
        ])]
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy;Youtuber <?= date('Y') ?></p>

        <p class="pull-right"><a href="https://telegram.me/SomeWho">Telegram: @SomeWho</a></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
