<?php

use yii\db\Migration;

/**
 * Handles the creation of table `castings`.
 */
class m180217_101045_create_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            '[[file_id]]' => $this->primaryKey(),
            '[[title]]' => $this->string(500)->comment('Название'),
            '[[description]]' => $this->text()->comment('Описание'),
            '[[type]]' => $this->string(255)->comment('Тип'),
            '[[file]]' => $this->text()->comment('Файл'),
            '[[params]]' => $this->string(255)->comment('Параметры'),
            '[[date_create]]' => $this->integer(255)->notNull()->comment('Дата добавление'),
            '[[converted]]' => $this->integer(255)->notNull()->defaultValue(0)->comment('Конвертировано'),
            '[[user_id]]' => $this->integer(255)->comment('Пользователь')
        ]);

        /*
        * Создание индекса для создание отношение:
         * Пользаватель - user_id
        */
        $this->createIndex(
            'idx-files-users-user-id',
            '{{%files}}',
            '[[user_id]]'
        );
        //Создание отношение
        $this->addForeignKey(
            'fk-files-users-user-id',
            '{{%files}}',
            '[[user_id]]',
            '{{%users}}',
            '[[user_id]]',
            'CASCADE'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        /*
        * Удаление связи:
        * Пользаватель - user_id
        */
        $this->dropForeignKey(
            'fk-files-users-user-id',
            '{{%files}}'
        );

        $this->dropIndex(
            'idx-files-users-user-id',
            '{{%files}}'
        );


        $this->dropTable('{{%files}}');
    }
}
