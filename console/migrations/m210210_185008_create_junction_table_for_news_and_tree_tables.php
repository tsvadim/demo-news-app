<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_tree}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%news}}`
 * - `{{%tree}}`
 */
class m210210_185008_create_junction_table_for_news_and_tree_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_tree}}', [
            'news_id' => $this->integer(),
            'tree_id' => $this->bigInteger(),
            'created_at' => $this->dateTime(3),
            'PRIMARY KEY(news_id, tree_id)',
        ],$tableOptions);

        // creates index for column `news_id`
        $this->createIndex(
            '{{%idx-news_tree-news_id}}',
            '{{%news_tree}}',
            'news_id'
        );

        // add foreign key for table `{{%news}}`
        $this->addForeignKey(
            '{{%fk-news_tree-news_id}}',
            '{{%news_tree}}',
            'news_id',
            '{{%news}}',
            'id',
            'CASCADE'
        );

        // creates index for column `tree_id`
        $this->createIndex(
            '{{%idx-news_tree-tree_id}}',
            '{{%news_tree}}',
            'tree_id'
        );

        // add foreign key for table `{{%tree}}`
        $this->addForeignKey(
            '{{%fk-news_tree-tree_id}}',
            '{{%news_tree}}',
            'tree_id',
            '{{%tree}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%news}}`
        $this->dropForeignKey(
            '{{%fk-news_tree-news_id}}',
            '{{%news_tree}}'
        );

        // drops index for column `news_id`
        $this->dropIndex(
            '{{%idx-news_tree-news_id}}',
            '{{%news_tree}}'
        );

        // drops foreign key for table `{{%tree}}`
        $this->dropForeignKey(
            '{{%fk-news_tree-tree_id}}',
            '{{%news_tree}}'
        );

        // drops index for column `tree_id`
        $this->dropIndex(
            '{{%idx-news_tree-tree_id}}',
            '{{%news_tree}}'
        );

        $this->dropTable('{{%news_tree}}');
    }
}
