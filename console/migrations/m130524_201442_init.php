<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('Ключик'),
            'username' => $this->string()->notNull()->unique()->comment('Имя пользователя'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull()->defaultExpression('unix_timestamp()'),
            'updated_at' => $this->integer()->notNull()->defaultExpression('unix_timestamp()'),
        ]);

        // создаём юзеря .
        $u = new \common\models\User([
            'username' => 'admin',
            'email' => 'addmin@amin.org',
            'status' => \common\models\User::STATUS_ACTIVE,
            'auth_key' => Yii::$app->security->generateRandomString(18),
        ]);
        $u->setPassword('admin');
        $u->save();
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
