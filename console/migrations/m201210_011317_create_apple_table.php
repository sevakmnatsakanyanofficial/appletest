<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m201210_011317_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'color' => $this->string()->notNull(),
            'status' => $this->smallInteger(1)->notNull(),
            'eat_percent' => $this->decimal(5, 2)->defaultValue(0),

            'fell_at' => $this->integer()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-apple-user_id',
            'apple',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-apple-user_id',
            'apple',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
