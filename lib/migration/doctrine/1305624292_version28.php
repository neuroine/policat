<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version28 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('default_text', 'subject', 'string', '255', array(
             'notnull' => '1',
             'default' => '',
             ));
    }

    public function down()
    {
        $this->removeColumn('default_text', 'subject');
    }
}