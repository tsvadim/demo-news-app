<?php

use \yii\db\Migration;

class m141204_121820_add_verification_token_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'verification_token', $this->string()->defaultValue(null));
        $this->addColumn('{{%user}}', 'api_token', $this->string()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'verification_token');
        $this->dropColumn('{{%user}}', 'api_token');
    }
}
