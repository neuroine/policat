<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version81 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('petition_contact', 'petition_contact_petition_id_petition_id', array(
             'name' => 'petition_contact_petition_id_petition_id',
             'local' => 'petition_id',
             'foreign' => 'id',
             'foreignTable' => 'petition',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('petition_contact', 'petition_contact_contact_id_contact_id', array(
             'name' => 'petition_contact_contact_id_contact_id',
             'local' => 'contact_id',
             'foreign' => 'id',
             'foreignTable' => 'contact',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('pledge_item', 'pledge_item_petition_id_petition_id', array(
             'name' => 'pledge_item_petition_id_petition_id',
             'local' => 'petition_id',
             'foreign' => 'id',
             'foreignTable' => 'petition',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('pledge_text', 'pledge_text_pledge_item_id_pledge_item_id', array(
             'name' => 'pledge_text_pledge_item_id_pledge_item_id',
             'local' => 'pledge_item_id',
             'foreign' => 'id',
             'foreignTable' => 'pledge_item',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('pledge_text', 'pledge_text_petition_text_id_petition_text_id', array(
             'name' => 'pledge_text_petition_text_id_petition_text_id',
             'local' => 'petition_text_id',
             'foreign' => 'id',
             'foreignTable' => 'petition_text',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('plegde', 'plegde_pledge_item_id_pledge_item_id', array(
             'name' => 'plegde_pledge_item_id_pledge_item_id',
             'local' => 'pledge_item_id',
             'foreign' => 'id',
             'foreignTable' => 'pledge_item',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('plegde', 'plegde_contact_id_contact_id', array(
             'name' => 'plegde_contact_id_contact_id',
             'local' => 'contact_id',
             'foreign' => 'id',
             'foreignTable' => 'contact',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('petition_contact', 'petition_contact_petition_id', array(
             'fields' => 
             array(
              0 => 'petition_id',
             ),
             ));
        $this->addIndex('petition_contact', 'petition_contact_contact_id', array(
             'fields' => 
             array(
              0 => 'contact_id',
             ),
             ));
        $this->addIndex('pledge_item', 'pledge_item_petition_id', array(
             'fields' => 
             array(
              0 => 'petition_id',
             ),
             ));
        $this->addIndex('pledge_text', 'pledge_text_pledge_item_id', array(
             'fields' => 
             array(
              0 => 'pledge_item_id',
             ),
             ));
        $this->addIndex('pledge_text', 'pledge_text_petition_text_id', array(
             'fields' => 
             array(
              0 => 'petition_text_id',
             ),
             ));
        $this->addIndex('plegde', 'plegde_pledge_item_id', array(
             'fields' => 
             array(
              0 => 'pledge_item_id',
             ),
             ));
        $this->addIndex('plegde', 'plegde_contact_id', array(
             'fields' => 
             array(
              0 => 'contact_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('petition_contact', 'petition_contact_petition_id_petition_id');
        $this->dropForeignKey('petition_contact', 'petition_contact_contact_id_contact_id');
        $this->dropForeignKey('pledge_item', 'pledge_item_petition_id_petition_id');
        $this->dropForeignKey('pledge_text', 'pledge_text_pledge_item_id_pledge_item_id');
        $this->dropForeignKey('pledge_text', 'pledge_text_petition_text_id_petition_text_id');
        $this->dropForeignKey('plegde', 'plegde_pledge_item_id_pledge_item_id');
        $this->dropForeignKey('plegde', 'plegde_contact_id_contact_id');
        $this->removeIndex('petition_contact', 'petition_contact_petition_id', array(
             'fields' => 
             array(
              0 => 'petition_id',
             ),
             ));
        $this->removeIndex('petition_contact', 'petition_contact_contact_id', array(
             'fields' => 
             array(
              0 => 'contact_id',
             ),
             ));
        $this->removeIndex('pledge_item', 'pledge_item_petition_id', array(
             'fields' => 
             array(
              0 => 'petition_id',
             ),
             ));
        $this->removeIndex('pledge_text', 'pledge_text_pledge_item_id', array(
             'fields' => 
             array(
              0 => 'pledge_item_id',
             ),
             ));
        $this->removeIndex('pledge_text', 'pledge_text_petition_text_id', array(
             'fields' => 
             array(
              0 => 'petition_text_id',
             ),
             ));
        $this->removeIndex('plegde', 'plegde_pledge_item_id', array(
             'fields' => 
             array(
              0 => 'pledge_item_id',
             ),
             ));
        $this->removeIndex('plegde', 'plegde_contact_id', array(
             'fields' => 
             array(
              0 => 'contact_id',
             ),
             ));
    }
}