<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version36 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('owner', 'password', 'string', '81', array(
             'notnull' => '1',
             'default' => '',
             ));
    }

    public function down()
    {

    }
}