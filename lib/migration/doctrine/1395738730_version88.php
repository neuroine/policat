<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version88 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('petition', 'pledge_header_visual', 'string', '60', array(
             'notnull' => '',
             ));
        $this->addColumn('petition', 'pledge_key_visual', 'string', '60', array(
             'notnull' => '',
             ));
        $this->addColumn('petition_contact', 'password_reset', 'string', '255', array(
             'notnull' => '',
             ));
        $this->addColumn('petition_contact', 'password_reset_until', 'date', '25', array(
             'notnull' => '',
             ));
        $this->addColumn('petition_text', 'pledge_title', 'string', '', array(
             'notnull' => '',
             ));
        $this->addColumn('petition_text', 'pledge_comment', 'clob', '', array(
             'notnull' => '',
             ));
        $this->addColumn('petition_text', 'pledge_explantory', 'clob', '', array(
             'notnull' => '',
             'alias' => 'pledge_explantory_annotation',
             ));
    }

    public function down()
    {
        $this->removeColumn('petition', 'pledge_header_visual');
        $this->removeColumn('petition', 'pledge_key_visual');
        $this->removeColumn('petition_contact', 'password_reset');
        $this->removeColumn('petition_contact', 'password_reset_until');
        $this->removeColumn('petition_text', 'pledge_title');
        $this->removeColumn('petition_text', 'pledge_comment');
        $this->removeColumn('petition_text', 'pledge_explantory');
    }
}