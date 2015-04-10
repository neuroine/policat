<?php

/**
 * PetitionApiToken
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    policat
 * @subpackage model
 * @author     Martin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PetitionApiToken extends BasePetitionApiToken {

  public function getStatusName() {
    return PetitionApiTokenTable::$STATUS[$this->getStatus()];
  }

  public function getOffsetSum($timeToLive = 600, $refresh = false) {
    return ApiTokenOffsetTable::getInstance()->sumOffsetsBYToken($this);
  }
}
