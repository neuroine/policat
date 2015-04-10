<?php

/**
 * PetitionText
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    policat
 * @subpackage model
 * @author     Martin
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class PetitionText extends BasePetitionText {

  const STATUS_DRAFT = 1;
  const STATUS_WAITING = 2; //
  const STATUS_APPROVED = 3; //
  const STATUS_ACTIVE = 4;
  const STATUS_BLOCKED = 5; //
  const STATUS_DENIED = 6; //

  static $STATUS_SHOW = array(
      self::STATUS_DRAFT => 'draft',
//    self::STATUS_WAITING  => 'waiting',
//    self::STATUS_APPROVED => 'approved',
      self::STATUS_ACTIVE => 'active',
//    self::STATUS_BLOCKED  => 'blocked',
//    self::STATUS_DENIED   => 'denied'
  );
  static $STATUS_RIGHTS_MATRIX = array(
      null => array
          (
          self::STATUS_DRAFT => array(Permission::NAME_PETITION_TEXT_CREATE)
      ),
      self::STATUS_DRAFT => array
          (
//        self::STATUS_WAITING => array(Permission::NAME_PETITION_TEXT_CREATE),
          self::STATUS_ACTIVE => array(Permission::NAME_PETITION_TEXT_ACTIVATE),
      ),
//    self::STATUS_WAITING => array
//    (
//        self::STATUS_APPROVED => array(Permission::NAME_PETITION_TEXT_APPROVAL),
//        self::STATUS_DENIED   => array(Permission::NAME_PETITION_TEXT_APPROVAL),
//        self::STATUS_ACTIVE => array(Permission::NAME_PETITION_TEXT_ACTIVATE),
//    ),
//    self::STATUS_APPROVED => array
//    (
//        self::STATUS_ACTIVE => array(Permission::NAME_PETITION_TEXT_ACTIVATE),
//        self::STATUS_DRAFT  => array(Permission::NAME_PETITION_TEXT_REDRAFT)
//    ),
      self::STATUS_ACTIVE => array
      (
//        self::STATUS_BLOCKED => array(Permission::NAME_PETITION_TEXT_BLOCK)
      ),
//    self::STATUS_BLOCKED => array
//    (
//        self::STATUS_ACTIVE => array(Permission::NAME_PETITION_TEXT_BLOCK)
//    ),
//    self::STATUS_DENIED => array
//    (
//        self::STATUS_DRAFT => array(Permission::NAME_PETITION_TEXT_CREATE)
//    )
  );
  static $EDIT_RIGHTS = array(
      self::STATUS_DRAFT => array(Permission::NAME_PETITION_TEXT_EDIT_ALL, Permission::NAME_PETITION_TEXT_EDIT_DRAFT),
//    self::STATUS_WAITING  => array(Permission::NAME_PETITION_TEXT_EDIT_ALL),
//    self::STATUS_APPROVED => array(Permission::NAME_PETITION_TEXT_EDIT_ALL),
      self::STATUS_ACTIVE => array(Permission::NAME_PETITION_TEXT_EDIT_ALL, Permission::NAME_PETITION_TEXT_EDIT_ACTIVE),
//    self::STATUS_BLOCKED  => array(Permission::NAME_PETITION_TEXT_EDIT_ALL, Permission::NAME_PETITION_TEXT_EDIT_ACTIVE),
//    self::STATUS_DENIED   => array(Permission::NAME_PETITION_TEXT_EDIT_ALL)
  );

  public function calcPossibleStatusByPermissions($permissions) {
    return $this->utilCalcPossibleStatusByPermissions(self::$STATUS_RIGHTS_MATRIX, $permissions);
  }

  public function calcIsEditableByPermission($permissions) {
    return $this->utilCalcIsEditableByPermission(self::$EDIT_RIGHTS, $permissions);
  }

  public static function calcStatusShow($statuses) {
    $ret = array();
    foreach ($statuses as $status)
      if (isset(self::$STATUS_SHOW[$status]))
        $ret[$status] = self::$STATUS_SHOW[$status];
    return $ret;
  }

  public function getStatusName() {
    return isset(self::$STATUS_SHOW[$this->getStatus()]) ? self::$STATUS_SHOW[$this->getStatus()] : 'unknown';
  }

  public static function getSecretHash() {
    return sfConfig::get('app_hashes_petition_text');
  }

  public function getIdHash() {
    return UtilIdHash::getInstance(self::getSecretHash())->getHashById($this->getId());
  }

  public static function getHashForId($id) {
    return UtilIdHash::getInstance(self::getSecretHash())->getHashById($id);
  }

  public static function getIdByHash($id_hash) {
    return UtilIdHash::getInstance(self::getSecretHash())->getIdByHash($id_hash);
  }

  private $_utilCultureInfo = null;

  /**
   * @return sfCultureInfo
   */
  public function utilCultureInfo() {
    if ($this->_utilCultureInfo)
      return $this->_utilCultureInfo;

    $culture = $this->getLanguageId();
    try {
      $culture_info = sfCultureInfo::getInstance($culture);
    } catch (sfException $e) {
      if (strlen($culture) > 2) {
        $culture = substr($culture, 0, 2);
        try {
          $culture_info = sfCultureInfo::getInstance($culture);
        } catch (sfException $e) {
          $culture = 'en';
          $culture_info = sfCultureInfo::getInstance($culture);
        }
      } else {
        $culture = 'en';
        $culture_info = sfCultureInfo::getInstance($culture);
      }
    }

    return $this->_utilCultureInfo = $culture_info;
  }

  public function utilCountries() {
    $countries_false = array_keys($this->utilCultureInfo()->getCountries());
    $countries = array();
    foreach ($countries_false as $country)
      if (!is_numeric($country))
        $countries[] = $country;
    return array_diff($countries, array('QU', 'ZZ'));
  }

  public function getPledgeTextByPledgeItem(PledgeItem $pledge_item) {
    foreach ($this->getPledgeTexts() as $pledge_text) {
      /* @var $pledge_text PledgeText */

      if ($pledge_text->getPledgeItemId() == $pledge_item->getId()) {
        $text = trim($pledge_text->getText());
        if ($text) {
          return $text;
        } else {
          return $pledge_item->getName();
        }
      }
    }
    return $pledge_item->getName();
  }

}
