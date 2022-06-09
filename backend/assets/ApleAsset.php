<?php 

namespace backend\assets;

class ApleAsset extends \yii\web\AssetBundle
{
    public $baseUrl = '@web';
    public $basePath = '@webroot';

    public $js = ['js/apple.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}