<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version94 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('petition', 'pledge_with_comments', 'integer', '1', array(
             'notnull' => '1',
             'default' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('petition', 'pledge_with_comments');
    }
}