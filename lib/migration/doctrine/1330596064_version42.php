<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version42 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('sf_guard_user', 'sf_guard_user_language_id_language_id', array(
             'name' => 'sf_guard_user_language_id_language_id',
             'local' => 'language_id',
             'foreign' => 'id',
             'foreignTable' => 'language',
             ));
        $this->addIndex('sf_guard_user', 'sf_guard_user_language_id', array(
             'fields' => 
             array(
              0 => 'language_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('sf_guard_user', 'sf_guard_user_language_id_language_id');
        $this->removeIndex('sf_guard_user', 'sf_guard_user_language_id', array(
             'fields' => 
             array(
              0 => 'language_id',
             ),
             ));
    }
}