<?php 

namespace backend\controllers;

use Yii;
use backend\models\Aple;

class ApleController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            [
                 'class' => \yii\filters\AccessControl::className(),
                 'rules' => [
                    ['allow' => true, 'roles' => ['@']]
                 ],
            ],
        ];
    }

    /**
     * просмотр страницы с яблоками  .... 
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            
            $ret = ['ok' => false];
            switch($post['action'] ?? '') {
                // роняем яблоко ...
                case 'down':
                    $apl = Aple::getById($post['id'] ?? 0);
                    if (!$apl) {
                        break;
                    }
                    if ($apl->down()) {
                        $ret['ok'] = true;
                    }
                    break;
                // скушать часть яблока .. 
                case 'it':
                    $apl = Aple::getById($post['id'] ?? 0);
                    if (!$apl) {
                        break;
                    }
                    if ($apl->it($post['size'] ?? 0)) {
                        $ret['ok'] = true;
                    }
                    break;
                // список яблок 
                case 'list':
                    $list = Aple::all();

                    foreach($list as $i => $a) {
                        $list[$i] = $a->attributes;
                        $list[$i]['color'] = Aple::COLORS[$list[$i]['color']];
                        $list[$i]['dcreated'] = date('Y-m-d H:i:s', $list[$i]['dcreated']);
                        $list[$i]['status'] = $a->status;
                        $list[$i]['st'] = Aple::STATUSES[$list[$i]['status']];
                        if ($list[$i]['status'] != 'b' && $list[$i]['ddown']) {
                            $dt = time() - $list[$i]['ddown'];
                            $list[$i]['st'] .= sprintf(' (лежит: %s, осталось до сгнивания: %s)', Yii::$app->formatter->asDuration($dt), Yii::$app->formatter->asDuration(round($a->gnilTime) - $dt));
                        }
                    }
                    $ret['ok'] = true;
                    $ret['list'] = $list;
                    break;
                // добавляем новые яблоки ... 
                case 'add-aples':
                    $co = intval($post['count'] ?? 0);
                    $added = 0;
                    for($i = 0; $i < $co; $i++) {
                        $aple = new Aple();
                        if ($aple->create()) {
                            $added++;
                        }
                    }

                    $ret['ok'] = true;
                    $ret['message'] = 'Добавлено яблок:' . $added;
                    break;
            }
            return $ret;
        }
        return $this->render('index');
    }
}