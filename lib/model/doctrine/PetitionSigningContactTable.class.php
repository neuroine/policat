<?php

/**
 * PetitionSigningContactTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PetitionSigningContactTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return PetitionSigningContactTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PetitionSigningContact');
    }

    /** @deprecated do not use! it is slow */
    public function countSentMails(Petition $petition = null) {
      $query = self::getInstance()->createQuery('sc')
        ->leftJoin('sc.PetitionSigning s');
      if ($petition)
        $query->where('s.petition_id = ?', $petition->getId());
      $query->leftJoin('s.PetitionSigningWave w')
        ->andWhere('sc.wave = w.wave and w.status = ?', array(PetitionSigning::STATUS_SENT));

      return $query->count();
    }

    /** @deprecated do not use! it is slow */
    public function countOutgoingMails(Petition $petition = null) {
      $query = self::getInstance()->createQuery('sc')
        ->leftJoin('sc.PetitionSigning s');
      if ($petition)
        $query->where('s.petition_id = ?', $petition->getId());
      $query->leftJoin('s.PetitionSigningWave w')
        ->andWhere('sc.wave = w.wave and w.status = ?', PetitionSigning::STATUS_VERIFIED);

      return $query->count();
    }

    /** @deprecated do not use! it is slow */
    public function countPendingMails(Petition $petition = null) {
      $query = self::getInstance()->createQuery('sc')
        ->leftJoin('sc.PetitionSigning s');
      if ($petition)
        $query->where('s.petition_id = ?', $petition->getId());
      $query->leftJoin('s.PetitionSigningWave w')
        ->andWhere('sc.wave = w.wave and w.status = ?', PetitionSigning::STATUS_PENDING);

      return $query->count();
    }
}