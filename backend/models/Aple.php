<?php 

namespace backend\models;

use Yii;

class Aple extends \yii\base\Model
{
    const COLORS = [ 'y' => 'желтое', 'r' => 'красное', 'g' => 'зелёное'];
    const STATUSES = ['n' => 'На дереве', 'd' => 'на земле', 'b' => 'гнилое яблоко'];
    /**
     * Идентификатор яблока ... 
     * @var integer
     */
    public $id;

    /**
     * цвет яблока  .. go = желтый, re = красный, gr = зелёный
     * @var [type]
     */
    public $color;

    /**
     * Дата создания яблока... 
     * @var integer
     */
    public $dcreated;

    /**
     * дата падения яблока ... 
     * @var integer
     */
    public $ddown;

    /**
     * процент скушанного яблока ..(какой процент от яблока слопали) 
     * @var float
     */
    public $toit;

    /**
     * статус яблока ... n = новое, d = упало  b = сгнило
     * @var string
     */
    //public $status;

    public function init()
    {
        parent::init();
        $colors = array_keys(self::COLORS);
        // определяем цвет если не определён 
        $this->color = $this->color ?? $colors[rand(0, count($colors) - 1)];
        $t = time();
        // определяем время создания .. если не определно ...
        $this->dcreated = $this->dcreated ?? rand($t - 10 * 3600, $t);
    }

    public function rules()
    {
        return [
            [['color', 'dcreated'], 'required'],
            ['color', 'in', 'range' => array_keys(self::COLORS)],
            ['toit', 'double', 'min' => 0, 'max' => 100],
            [['id', 'dcreated', 'ddown'], 'integer'],
        ];
    }

    /**
     * создаём / сохраняем яблоки ...
     * @return [type] [description]
     */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }
        $attrs = $this->attributes;
        foreach($attrs as $x => $y) {
            if (!isset($y)) {
                unset($attrs[$x]);
            }
        }
        Yii::$app->db->createCommand()->insert('{{aples}}', $attrs)->execute();
        return true;
    }

    /**
     * забрать одно яблоко ... по id
     * @param int  $id Id яблока 
     */
    public static function getById($id)
    {
        $res = Yii::$app->db->createCommand('select * from {{aples}} where id=:id', [':id' => $id])->queryOne();
        return $res ? new static($res) : null; 
    }
    /**
     * запрос всех яблок 
     */
    public static function all()
    {
        $res = Yii::$app->db->createCommand('select * from {{aples}}')->queryAll();
        for($i = 0; $i < count($res); $i++) {
            $res[$i]['toit'] = floatval($res[$i]['toit']);
            $res[$i] = new static($res[$i]);
        }
        return $res;
    }

    /**
     * определить время через которое сгниёт яюдлко 
     * @return int Интервал в секундах 
     */
    public function getGnilTime()
    {
        $dt = 5 * 3600;
        // яблоко надкушенное гниёт быстрее .. 
        if ($this->toit) {
            $dt -= exp($this->toit / 100) * 3600;
        }
        return $dt;
    }
    /**
     * определить статус яблока .. 
     * @return [type] [description]
     */
    public function getStatus()
    {
        // яблоко висит на дереве .. 
        if (!isset($this->ddown)) {
            return 'n';
        }
        $ct = time();
        // яблоко сгнило ... 
        if ($ct - $this->ddown > $this->gnilTime) {
            return 'b';
        }
        // просто лежит ..
        return 'd';
    }

    /**
     * уронить яблоко ... 
     * @return bool Результат роняния .. 
     */
    public function down()
    {
        if (!empty($this->ddown)) {
            return false;
        }
        Yii::$app->db->createCommand("update {{aples}} set [[ddown]]=strftime('%s', 'now') where [[id]]=:id", [':id' => $this->id])->execute();

        return true;
    }

    /**
     * слопать яблоко ... 
     * @param float $size Объём в процентах ... от целого яблака
     */
    public function it($size)
    {
        // яблоко ещё не упало ...  или уже сгнило
        if ($this->status != 'd') {
            return false;
        } 
        // захотели слопать больше чем надо или  вообще не захотели ...
        if ($size <= 0 || $size > 100 - $this->toit) {
            return false;
        }

        // откусываем )) 
        $this->toit += $size;
        // слопали яблоко полностью 
        if ($this->toit >= 100) {
            Yii::$app->db->createCommand('delete from {{aples}} where [[id]]=:id', [':id' => $this->id])->execute();
        } else {
            Yii::$app->db->createCommand('update {{aples}} set [[toit]]=:toit where [[id]]=:id', [':id' => $this->id, ':toit' => $this->toit])->execute();
        }
        return true;
    }
}