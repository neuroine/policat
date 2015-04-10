<?php

/**
 * BaseMailingListMeta
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $mailing_list_id
 * @property integer $kind
 * @property string $name
 * @property string $subst
 * @property clob $data_json
 * @property MailingList $MailingList
 * @property Doctrine_Collection $MailingListMetaChoice
 * @property Doctrine_Collection $ContactMeta
 * 
 * @method integer             getId()                    Returns the current record's "id" value
 * @method integer             getMailingListId()         Returns the current record's "mailing_list_id" value
 * @method integer             getKind()                  Returns the current record's "kind" value
 * @method string              getName()                  Returns the current record's "name" value
 * @method string              getSubst()                 Returns the current record's "subst" value
 * @method clob                getDataJson()              Returns the current record's "data_json" value
 * @method MailingList         getMailingList()           Returns the current record's "MailingList" value
 * @method Doctrine_Collection getMailingListMetaChoice() Returns the current record's "MailingListMetaChoice" collection
 * @method Doctrine_Collection getContactMeta()           Returns the current record's "ContactMeta" collection
 * @method MailingListMeta     setId()                    Sets the current record's "id" value
 * @method MailingListMeta     setMailingListId()         Sets the current record's "mailing_list_id" value
 * @method MailingListMeta     setKind()                  Sets the current record's "kind" value
 * @method MailingListMeta     setName()                  Sets the current record's "name" value
 * @method MailingListMeta     setSubst()                 Sets the current record's "subst" value
 * @method MailingListMeta     setDataJson()              Sets the current record's "data_json" value
 * @method MailingListMeta     setMailingList()           Sets the current record's "MailingList" value
 * @method MailingListMeta     setMailingListMetaChoice() Sets the current record's "MailingListMetaChoice" collection
 * @method MailingListMeta     setContactMeta()           Sets the current record's "ContactMeta" collection
 * 
 * @package    policat
 * @subpackage model
 * @author     Martin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMailingListMeta extends myDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('mailing_list_meta');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('mailing_list_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('kind', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('subst', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('data_json', 'clob', null, array(
             'type' => 'clob',
             'notnull' => false,
             ));

        $this->option('symfony', array(
             'form' => true,
             'filter' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('MailingList', array(
             'local' => 'mailing_list_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('MailingListMetaChoice', array(
             'local' => 'id',
             'foreign' => 'mailing_list_meta_id'));

        $this->hasMany('ContactMeta', array(
             'local' => 'id',
             'foreign' => 'mailing_list_meta_id'));
    }
}