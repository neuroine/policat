<?php

/**
 * TicketTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TicketTable extends Doctrine_Table {

  const CREATE_AUTO_FROM = 'auto_from';
  const CREATE_TO = 'to';
  const CREATE_CAMPAIGN = 'campaign';
  const CREATE_PETITION = 'petition';
  const CREATE_WIDGET = 'widget';
  const CREATE_TARGET_LIST = 'target_list';
  const CREATE_KIND = 'kind';
  const CREATE_TEXT = 'text';
  const CREATE_CHECK_DUPLICATE = 'check_duplicate';

  static $CREATE_DEFAULTS = array(
      self::CREATE_AUTO_FROM => false,
      self::CREATE_TO => null,
      self::CREATE_CAMPAIGN => null,
      self::CREATE_PETITION => null,
      self::CREATE_WIDGET => null,
      self::CREATE_TARGET_LIST => null,
      self::CREATE_KIND => false,
      self::CREATE_CHECK_DUPLICATE => false,
      self::CREATE_TEXT => null,
  );

  const KIND_DEFAULT = 1;
  const KIND_JOIN_CAMPAIGN = 2;
  const KIND_JOIN_PETITION = 3;
  const KIND_JOIN_PETITION_ADMIN = 4;
  const KIND_WIDGET_DATA_OWNER = 5;
  const KIND_TARGET_LIST_MEMBER = 6;
  const KIND_TARGET_LIST_ACTIVATE = 7;
  const KIND_USER_UNBLOCK = 8;
  const KIND_PRIVACY_POLICY_CHANGED = 9;
  const KIND_RESIGN_DATA_OFFICER = 10;
  const KIND_CALL_DATA_OFFICER = 11;

  static $KIND_NAME = array(
      self::KIND_DEFAULT => 'Default',
      self::KIND_JOIN_CAMPAIGN => 'Join Campaign',
      self::KIND_JOIN_PETITION => 'Join Action',
      self::KIND_JOIN_PETITION_ADMIN => 'Become Member-manager',
      self::KIND_WIDGET_DATA_OWNER => 'Become Data-owner for Widget',
      self::KIND_TARGET_LIST_MEMBER => 'Become Target-list member',
      self::KIND_TARGET_LIST_ACTIVATE => 'Activate Target-list',
      self::KIND_USER_UNBLOCK => 'User unblock-request',
      self::KIND_PRIVACY_POLICY_CHANGED => 'Privacy policy changed',
      self::KIND_RESIGN_DATA_OFFICER => 'Resign of data officer',
      self::KIND_CALL_DATA_OFFICER => 'Call of data officer'
  );
  static $KIND_HANDLER = array(
      self::KIND_DEFAULT => 'default',
      self::KIND_JOIN_CAMPAIGN => 'joinCampaign',
      self::KIND_JOIN_PETITION => 'joinPetition',
      self::KIND_JOIN_PETITION_ADMIN => 'joinPetitionAdmin',
      self::KIND_WIDGET_DATA_OWNER => 'widgetDataOwner',
      self::KIND_TARGET_LIST_MEMBER => 'targetListMember',
      self::KIND_TARGET_LIST_ACTIVATE => 'targetListActivate',
      self::KIND_USER_UNBLOCK => 'userUnblock',
      self::KIND_PRIVACY_POLICY_CHANGED => 'privacyPolicyChanged',
      self::KIND_RESIGN_DATA_OFFICER => 'resignDataOfficer',
      self::KIND_CALL_DATA_OFFICER => 'callDataOfficer'
  );

  const STATUS_NEW = 1;
  const STATUS_READ = 2;
  const STATUS_WAIT = 3;
  const STATUS_APPROVED = 10;
  const STATUS_DENIED = 20;

  static $STATUS_NAME = array(
      self::STATUS_NEW => 'new',
  );

  /**
   * @return TicketTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Ticket');
  }

  /**
   *
   * @param array $options 
   * @return Ticket
   */
  public function generate($options = array(), $ticket = null) {
    $options = array_merge(self::$CREATE_DEFAULTS, $options);

    if (!$ticket)
      $ticket = new Ticket();

    if ($options[self::CREATE_KIND]) {
      $ticket->setKind($options[self::CREATE_KIND]);
    }

    if ($options[self::CREATE_AUTO_FROM]) {
      $user = sfContext::getInstance()->getUser();
      /* @var $user myUser */

      if ($user && $user->getGuardUser())
        $ticket->setFrom($user->getGuardUser());
    }

    if ($options[self::CREATE_TO]) {
      $ticket->setTo($options[self::CREATE_TO]);
    }

    if ($options[self::CREATE_TARGET_LIST]) {
      $target_list = $options[self::CREATE_TARGET_LIST];
      /* @var $target_list MailingList */
      $ticket->setTargetList($target_list);
      $ticket->setCampaign($target_list->getCampaign());
    }

    if ($options[self::CREATE_WIDGET]) {
      $widget = $options[self::CREATE_WIDGET];
      /* @var $widget Widget */
      $ticket->setWidget($widget);
      $ticket->setPetition($widget->getPetition());
      $ticket->setCampaign($widget->getCampaign());
    }

    if ($options[self::CREATE_PETITION]) {
      $petition = $options[self::CREATE_PETITION];
      /* @var $petition Petition */
      $ticket->setPetition($petition);
      $ticket->setCampaign($petition->getCampaign());
    }

    if ($options[self::CREATE_CAMPAIGN])
      $ticket->setCampaign($options[self::CREATE_CAMPAIGN]);

    if ($options[self::CREATE_TEXT])
      $ticket->setText($options[self::CREATE_TEXT]);

    if ($options[self::CREATE_CHECK_DUPLICATE]) {
      $query = $this->createQuery('t');
      if ($ticket->getToId())
        $query->where('t.to_id = ?', $ticket->getToId());
      if ($ticket->getCampaignId())
        $query->andWhere('t.campaign_id = ?', $ticket->getCampaignId());
      if ($ticket->getPetitionId())
        $query->andWhere('t.petition_id = ?', $ticket->getPetitionId());
      if ($ticket->getWidgetId())
        $query->andWhere('t.widget_id = ?', $ticket->getWidgetId());
      if ($ticket->getFromId())
        $query->andWhere('t.from_id = ?', $ticket->getFromId());
      if ($ticket->getTargetListId())
        $query->andWhere('t.target_list_id = ?', $ticket->getTargetListId());
      $query->andWhere('t.kind = ?', $ticket->getKind());
      $query->andWhereIn('t.status', array(TicketTable::STATUS_NEW, TicketTable::STATUS_READ, TicketTable::STATUS_WAIT));

      if ($query->count())
        return null;
    }

    return $ticket;
  }

  /**
   *
   * @return Doctrine_Query
   */
  public function queryAll() {
    return $this->createQuery('t')->orderBy('t.id');
  }

  public function queryForUser(sfGuardUser $user, $stati = null) {
    $query = $this->queryAll();

    if (!$user->hasPermission(myUser::CREDENTIAL_ADMIN)) {
      $campaign_admin_ids = $user->getCampaignAdminIds();
      $petition_admin_ids = $user->getPetitionAdminIds();

      $ors = array('t.to_id = ?');
      $params = array($user->getId());

      if ($campaign_admin_ids) {
        $ors[] = 't.campaign_id IN ?';
        $params[] = $campaign_admin_ids;
      }

      if ($petition_admin_ids) {
        $ors[] = 't.petition_id IN ?';
        $params[] = $petition_admin_ids;
      }

      $query->where(implode(' OR ', $ors), $params);
    }

    $query->andWhere('t.to_id IS NULL OR t.to_id = ?', $user->getId());

    if ($stati)
      $query->andWhereIn('t.status', $stati);
    return $query;
  }

  /**
   *
   * @param array $ids
   * @return Doctrine_Query
   */
  public function queryIds($ids) {
    return self::getInstance()->queryAll()
        ->whereIn('t.id', $ids);
  }

  /**
   *
   * @param sfGuardUser $user
   * @return bool
   */
  public function checkOpenUnblockTicketForUser(sfGuardUser $user) {
    return $this->createQuery('t')
        ->where('t.from_id = ?', $user->getId())
        ->andWhereIn('t.status', array(TicketTable::STATUS_NEW, TicketTable::STATUS_READ, TicketTable::STATUS_WAIT))
        ->andWhere('t.kind = ?', self::KIND_USER_UNBLOCK)
        ->count() ? true : false;
  }

  public function queryResignTicketForCampaign(Campaign $campaign, $not_id = null) {
    $query = $this->queryAll()
      ->where('t.campaign_id = ?', $campaign->getId())
      ->andWhereIn('t.status', array(TicketTable::STATUS_NEW, TicketTable::STATUS_READ, TicketTable::STATUS_WAIT))
      ->andWhere('t.kind = ?', TicketTable::KIND_RESIGN_DATA_OFFICER);

    if ($not_id)
      $query->andWhere('t.id != ?', $not_id);

    return $query;
  }

  public function queryCallTicketForCampaign(Campaign $campaign, $not_id = null) {
    $query = $this->queryAll()
      ->where('t.campaign_id = ?', $campaign->getId())
      ->andWhereIn('t.status', array(TicketTable::STATUS_NEW, TicketTable::STATUS_READ, TicketTable::STATUS_WAIT))
      ->andWhere('t.kind = ?', TicketTable::KIND_CALL_DATA_OFFICER);

    if ($not_id)
      $query->andWhere('t.id != ?', $not_id);

    return $query;
  }

}