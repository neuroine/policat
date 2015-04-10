<?php

class ResignDataOfficerForm extends BaseForm {
  const OPTION_CAMPAIGN = 'campaign';

  public function configure() {
    $this->widgetSchema->setFormFormatterName('bootstrapInline');
    $this->widgetSchema->setNameFormat('resign_officer[%s]');

    $campaign = $this->getOption(self::OPTION_CAMPAIGN);

    $query = sfGuardUserTable::getInstance()->queryAdminsByCampaign($campaign);

    $this->setWidget('new', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'query' => $query,
        'method' => 'getFullName',
        'label' => 'please select new one'
    )));
    
    $this->setValidator('new', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'query' => $query,
    )));
    
  }

}