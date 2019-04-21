<?php

use yii\db\Migration;

/**
 * Class m190420_112552_init
 */
class m190420_112552_init extends Migration
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

        $this->createTable('driver', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'birth_date' => $this->date()->notNull(),
        ], $tableOptions);

        $this->createTable('bus', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'avg_speed' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('driver_bus', [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull(),
            'bus_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_driver_bus_driver', 'driver_bus', 'driver_id', 'driver', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_driver_bus_bus', 'driver_bus', 'bus_id', 'bus', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_driver_bus_driver', 'driver_bus');
        $this->dropForeignKey('fk_driver_bus_bus', 'driver_bus');

        $this->dropTable('driver_bus');
        $this->dropTable('driver');
        $this->dropTable('bus');
    }
}
