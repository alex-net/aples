<?php

use yii\db\Migration;

/**
 * Class m191210_130637_aples
 */
class m191210_130637_aples extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('aples', [
            'id' => $this->primaryKey(),//->comment('Ключик'),
            'color' => $this->char(),//->comment('Цвет яблока. y,r,g'),
            'dcreated' => $this->integer()->notNull()->defaultExpression("(strftime('%s', 'now'))"),//->comment('Время создания'),
            'ddown' => $this->integer(),//->comment('Время падения'),
            'toit' => $this->decimal(5, 2)->defaultValue(0)->notNull(),//->comment('Скушали'),
        ]);
    }

    public function down()
    {
        $this->dropTable('aples');
        return true;
    }
}
