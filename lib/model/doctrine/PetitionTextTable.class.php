<?php

class PetitionTextTable extends Doctrine_Table {

  /**
   *
   * @return PetitionTextTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('PetitionText');
  }

  // only for admin generator
  function adminList(Doctrine_Query $query) {
    $root = $query->getRootAlias();
    $query
      ->leftJoin("$root.Petition p")
      ->leftJoin("$root.Language l")
      ->addSelect("$root.*, p.*, l.*");
    return $query;
  }

  /**
   *
   * @param Petition $petition
   * @param bool $fetch_lang
   * @return Doctrine_Query
   */
  public function queryByPetition(Petition $petition, $fetch_lang = true, $status = null, $language_id = null) {
    $query = $this->createQuery('pt')->where('pt.petition_id = ?', $petition->getId())->orderBy('pt.id');
    if ($fetch_lang) {
      $query->leftJoin('pt.Language l')->select('pt.*, l.*');
    }

    if ($status !== null)
      $query->andWhere('pt.status = ?', $status);

    if ($language_id !== null)
      $query->andWhere('pt.language_id = ?', $language_id);

    return $query;
  }

  /**
   *
   * @param Petition $petition
   * @param bool $fetch_lang
   * @return Doctrine_Query
   */
  public function queryByPetitionAndActive(Petition $petition, $fetch_lang = true) {
    return $this->queryByPetition($petition, $fetch_lang)->andWhere('pt.status = ?', PetitionText::STATUS_ACTIVE);
  }

  public function fetchByPetitionAndPrefLang($petition, $pref_lang = null, $hydrationMode = null) {
    $pref_lang = $pref_lang ? : 'en';

    $petition_text = $this->queryByPetition($petition, null, PetitionText::STATUS_ACTIVE, $pref_lang)->fetchOne(array(), $hydrationMode);
    if (!$petition_text && $pref_lang !== 'en') {
      $petition_text = $this->queryByPetition($petition, null, PetitionText::STATUS_ACTIVE, 'en')->fetchOne(array(), $hydrationMode);
    }
    if (!$petition_text) {
      $petition_text = $this->queryByPetition($petition, false, PetitionText::STATUS_ACTIVE)->fetchOne(array(), $hydrationMode);
    }

    return $petition_text;
  }

  /**
   *
   * @param int $id
   * @return PetitionText
   */
  public function findByIdCachedActive($id, $timeToLive = 600) {
    if (!is_numeric($id)) {
      return false;
    }

    $query = $this->createQuery('pt')
      ->where('pt.id = ?', $id)
      ->andWhere('pt.status = ?', PetitionText::STATUS_ACTIVE);

    if ($timeToLive !== false) {
      $query->useResultCache(true, $timeToLive);
    }

    $res = $query->fetchOne(); /* @var $res PetitionText */
    $query->free();

    return $res;
  }

  public function fetchTopLanguage() {
    $result = array('en'); // english is always top language
    $data = (array) $this->getConnection()
      ->query('SELECT language_id FROM PetitionText WHERE language_id != "en" GROUP BY language_id ORDER BY count(id) DESC LIMIT 10', array(), Doctrine_Core::HYDRATE_ARRAY);

    foreach ($data as $row) {
      if (is_array($row) && array_key_exists('language_id', $row)) {
        $result[] = $row['language_id'];
      }
    }

    return $result;
  }

}
