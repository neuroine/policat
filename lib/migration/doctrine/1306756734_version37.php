<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version37 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('campaign', 'address', 'clob', '', array(
             'notnull' => '1',
             'default' => '',
             ));
    }

    public function down()
    {
        $this->removeColumn('campaign', 'address');
    }
}