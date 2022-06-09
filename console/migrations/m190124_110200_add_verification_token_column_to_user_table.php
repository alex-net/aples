<?php

use \yii\db\Migration;

class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'verification_token', $this->string()->defaultValue(null));
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
        $this->dropColumn('{{%user}}', 'verification_token');
    }
}
