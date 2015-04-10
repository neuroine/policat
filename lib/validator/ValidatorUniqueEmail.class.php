<?php

class ValidatorUniqueEmail extends ValidatorEmail {

  const OPTION_IS_GEO = 'is_geo';
  const OPTION_IGNORE_PENDING = 'ignore_pending';

  protected function configure($options = array(), $messages = array()) {
    parent::configure($options, $messages);

    $this->addMessage('old', "Attention: You've already taken part in this action (maybe on another website).");
    $this->addOption(self::OPTION_IS_GEO, false);
    $this->addRequiredOption('petition_id');
    $this->addOption(self::OPTION_IGNORE_PENDING, false);
  }

  protected function doClean($value) {
    $clean = parent::doClean($value);
    $existing_signing = Doctrine_Core::getTable('PetitionSigning')
      ->createQuery('s')
      ->where('s.petition_id = ?', $this->getOption('petition_id'))
      ->andWhere('LOWER(s.email) = LOWER(?)', $clean)
      ->limit(1)
      ->fetchOne();
    if ($existing_signing && !$this->getOption(self::OPTION_IS_GEO)) {
      /* @var $existing_signing PetitionSigning */

      if ($this->getOption(self::OPTION_IGNORE_PENDING) && ($existing_signing->getStatus() == PetitionSigning::STATUS_PENDING)) {

        return $clean;
      }

      throw new sfValidatorError($this, 'old');
      ;
    }

    return $clean;
  }

}
