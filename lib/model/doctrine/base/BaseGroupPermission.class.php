<?php

/**
 * BaseGroupPermission
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $group_id
 * @property integer $permission_id
 * @property Group $Group
 * @property Permission $Permission
 * 
 * @method integer         getGroupId()       Returns the current record's "group_id" value
 * @method integer         getPermissionId()  Returns the current record's "permission_id" value
 * @method Group           getGroup()         Returns the current record's "Group" value
 * @method Permission      getPermission()    Returns the current record's "Permission" value
 * @method GroupPermission setGroupId()       Sets the current record's "group_id" value
 * @method GroupPermission setPermissionId()  Sets the current record's "permission_id" value
 * @method GroupPermission setGroup()         Sets the current record's "Group" value
 * @method GroupPermission setPermission()    Sets the current record's "Permission" value
 * 
 * @package    policat
 * @subpackage model
 * @author     Martin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGroupPermission extends myDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('group_permission');
        $this->hasColumn('group_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('permission_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));

        $this->option('symfony', array(
             'form' => false,
             'filter' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Group', array(
             'local' => 'group_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Permission', array(
             'local' => 'permission_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}