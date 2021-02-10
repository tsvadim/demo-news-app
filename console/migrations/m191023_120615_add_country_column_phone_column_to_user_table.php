<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191023_120615_add_country_column_phone_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'country', $this->string());
        $this->addColumn('{{%user}}', 'phone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'country');
        $this->dropColumn('{{%user}}', 'phone');
    }
}
