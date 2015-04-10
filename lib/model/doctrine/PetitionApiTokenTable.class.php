<?php

/**
 * PetitionApiTokenTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PetitionApiTokenTable extends Doctrine_Table {

  const STATUS_BANNED = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_BLOCKED = 2;

  static public $STATUS = array(
      self::STATUS_ACTIVE => 'active',
      self::STATUS_BLOCKED => 'blocked (but count)',
      self::STATUS_BANNED => 'banned (do not count)'
  );

  /**
   * Returns an instance of this class.
   *
   * @return PetitionApiTokenTable The table instance
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('PetitionApiToken');
  }

  public function queryByPetition(Petition $petition) {
    return $this->createQuery('t')->where('t.petition_id = ?', $petition->getId())->orderBy('t.name ASC');
  }

  public function fetchByPetitionAndToken(Petition $petition, $token, $status = self::STATUS_ACTIVE) {
    return $this->queryByPetition($petition)->andWhere('t.token = ?', $token)->andWhere('t.status = ?', $status)->fetchOne();
  }

  public function sumOffsetsCountry($petition, $timeToLive = 600, $refresh = false) {
    $petition_id = $petition instanceof Petition ? $petition->getId() : $petition;

    $query = $this->createQuery('t')
      ->where('t.petition_id = ?', $petition_id)
      ->andWhereIn('t.status', array(self::STATUS_ACTIVE, self::STATUS_BLOCKED))
      ->leftJoin('t.Offsets o')
      ->select('t.id, o.country, sum(o.addnum) as addnum')
      ->groupBy('o.country');

    if ($refresh)
      $query->expireResultCache();

    if ($timeToLive)
      $query->useResultCache(true, $timeToLive);

    $ret = array();

    foreach ($query->execute(array(), Doctrine_Core::HYDRATE_ARRAY_SHALLOW) as $row) {
      $ret[$row['country']] = (int) $row['addnum'];
    }

    return $ret;
  }

  public function sumOffsets($petition, $timeToLive = 600, $refresh = false) {
    $petition_id = $petition instanceof Petition ? $petition->getId() : $petition;

    $query = $this->createQuery('t')
      ->where('t.petition_id = ?', $petition_id)
      ->andWhereIn('t.status', array(self::STATUS_ACTIVE, self::STATUS_BLOCKED))
      ->leftJoin('t.Offsets o')
      ->select('sum(o.addnum) as addnum');

    if ($refresh)
      $query->expireResultCache();

    if ($timeToLive)
      $query->useResultCache(true, $timeToLive);

    return (int) $query->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }

}
