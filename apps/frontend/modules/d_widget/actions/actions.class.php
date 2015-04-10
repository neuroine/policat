<?php

/**
 * dashboard widget actions.
 *
 * @package    policat
 * @subpackage d_widget
 * @author     Martin
 */
class d_widgetActions extends policatActions {

  public function executeIndex(sfWebRequest $request) {
    $this->includeChosen();
  }

  public function executePager(sfWebRequest $request) {
    $page = $request->getParameter('page', 1);
    if ($request->hasParameter('id')) {
      $petition = PetitionTable::getInstance()->findById($request->getParameter('id'), $this->userIsAdmin());
      /* @var $petition Petition */
      if (!$petition)
        return $this->notFound();

      if (!$this->getGuardUser()->isPetitionMember($petition, true))
        return $this->noAccess();

      return $this->ajax()->replaceWithComponent('#widget_list', 'd_widget', 'list', array('page' => $page, 'petition' => $petition, 'no_filter' => true))->render();
    }
    return $this->ajax()->replaceWithComponent('#widget_list', 'd_widget', 'list', array('page' => $page, 'no_filter' => true))->render();
  }

  public function executePetition(sfWebRequest $request) {
    $petition = PetitionTable::getInstance()->findById($request->getParameter('id'), $this->userIsAdmin());
    /* @var $petition Petition */
    if (!$petition)
      return $this->notFound();

    if (!$this->getGuardUser()->isPetitionMember($petition, true))
      return $this->noAccess();

    $this->petition = $petition;
    $this->form = new NewWidgetLanguageForm(array(), array(NewWidgetLanguageForm::OPTION_PETITION => $petition));
    
    $this->includeChosen();
  }

  public function executeEdit(sfWebRequest $request) {
    $route_params = $this->getRoute()->getParameters();
    if (isset($route_params['new'])) { // CREATE FORM OR REDIRECT TO CREATE FORM
      if (!$request->isMethod('post'))
        return $this->redirect($this->generateUrl('dashboard'));

      $petition = PetitionTable::getInstance()->findById($request->getParameter('id', $this->userIsAdmin()));
      /* @var $petition Petition */
      if (!$petition)
        return $this->notFound();

      if (!$this->getGuardUser()->isPetitionMember($petition, true))
        return $this->noAccess();

      $widget = new Widget();
      $widget->setPetition($petition);
      $widget->setCampaignId($petition->getCampaignId());
      $widget->setUser($this->getGuardUser());

      $this->ajax()->setAlertTarget('#new_widget', 'after');

      $lang = $request->getPostParameter('lang');
      if (!$lang || !is_numeric($lang))
        return $this->ajax()->alert('Please select a language.')->render();

      $pt = PetitionTextTable::getInstance()->find($lang);
      /* @var $pt PetitionText */

      if (!$pt || $pt->getPetitionId() != $petition->getId())
        return $this->notFound();

      if ($pt->getStatus() != PetitionText::STATUS_ACTIVE)
        return $this->ajax()->alert('Translation not active', 'Error')->render();

      $widget->setPetitionText($pt);
      $this->form = new EditWidgetForm($widget);
      $this->lang = $pt->getId();

      if (!$request->getPostParameter($this->form->getName()) && !$request->getPostParameter('page'))
        return $this->ajax()->redirectPostRoute('widget_create', array('id' => $petition->getId()), array('page' => 1, 'lang' => $pt->getId()))->render();
    }
    else { // EDIT FORM
      $widget = WidgetTable::getInstance()->find($request->getParameter('id'));
      /* @var $widget Widget */
      if (!$widget || !($this->getGuardUser()->isPetitionMember($widget->getPetition(), true) || $widget->getUserId() == $this->getGuardUser()->getId()))
        return $this->noAccess();

      if (!$this->userIsAdmin()
        && ($widget->getPetition()->getStatus() == Petition::STATUS_DELETED || $widget->getCampaign()->getStatus() == CampaignTable::STATUS_DELETED ))
        return $this->notFound();

      $this->form = new EditWidgetForm($widget);
    }

    $this->petition = $widget->getPetition();

    if ($request->isMethod('post') && !$request->getPostParameter('page')) {
      $this->form->bind($request->getPostParameter($this->form->getName()));

      if ($this->form->isValid()) {
        $this->form->save();
        if ($widget->getStatus() == Widget::STATUS_ACTIVE && !$widget->getPetitionText()->getWidgetId()) {
          $widget->getPetitionText()->setDefaultWidget($widget);
          $widget->getPetitionText()->save();
        }
        if ($this->getGuardUser()->isPetitionMember($widget->getPetition(), true))
          return $this->ajax()->redirectRotue('petition_widgets', array('id' => $this->petition->getId()))->render();
        else
          return $this->ajax()->redirectRotue('widget_index')->render();
      } else {
        return $this->ajax()->form($this->form)->render();
      }
    }

    $this->includeMarkdown();
    $this->includeHighlight();
  }

  public function executeView(sfWebRequest $request) {
    $widget = WidgetTable::getInstance()->find($request->getParameter('id'));
    /* @var $widget Widget */
    if (!$widget || $widget->getStatus() != Widget::STATUS_ACTIVE)
      return $this->notFound();

    return $this->ajax()->appendPartial('body', 'view', array('id' => $widget->getId()))->modal('#widget_view')->render();
  }

  public function executeDataOwner(sfWebRequest $request) {
    $this->ajax()->setAlertTarget('#widget_list table', 'after');

    if ($request->getPostParameter('csrf_token') !== UtilCSRF::gen('widget_data_owner'))
      return $this->ajax()->alert('CSRF Attack detected, please relogin.', 'Error')->render();

    $id = $request->getPostParameter('id');
    if (!is_numeric($id))
      return $this->ajax()->alert('invalid data', 'Error')->render();

    $widget = WidgetTable::getInstance()->find($id);
    /* @var $widget Widget */
    if (!$widget)
      return $this->ajax()->alert('Widget not found', 'Error')->render();

    if (!$this->userIsAdmin() && ($widget->getCampaign()->getStatus() == CampaignTable::STATUS_DELETED
      || $widget->getPetition()->getStatus() == Petition::STATUS_DELETED))
      return $this->ajax()->alert('Widget not found', 'Error')->render();

    if (!$widget->getCampaign()->getOwnerRegister())
      return $this->ajax()->alert('Disabled function', 'Error')->render();

    if (!$widget->getUserId() || $widget->getUserId() != $this->getGuardUser()->getId())
      return $this->ajax()->alert('You are not owner of this widget', '')->render();

    if ($widget->getDataOwner() == WidgetTable::DATA_OWNER_YES)
      return $this->ajax()->alert('You are already Data-owner of this widget', '')->render();

    $ticket = TicketTable::getInstance()->generate(array(
        TicketTable::CREATE_AUTO_FROM => true,
        TicketTable::CREATE_WIDGET => $widget,
        TicketTable::CREATE_KIND => TicketTable::KIND_WIDGET_DATA_OWNER,
        TicketTable::CREATE_CHECK_DUPLICATE => true
      ));
    if ($ticket) {
      $ticket->save();
      $ticket->notifyAdmin();
    }
    else
      return $this->ajax()->alert('Application already pending', '')->render();

    return $this->ajax()->alert('Application has been sent to Campaign admin', '')->render();
  }

  // this is for widget owners only
  public function executeData(sfWebRequest $request) {
    $widget = WidgetTable::getInstance()->find($request->getParameter('id'));
    /* @var $widget Widget */
    if (!$widget)
      return $this->notFound();

    if (!$widget->isDataOwner($this->getGuardUser()))
      return $this->noAccess('You are not Data-owner of this widget.');

    $this->widget = $widget;
    $this->petition = $widget->getPetition();
  }

  public function executeRevokeData(sfWebRequest $request) {
    $this->ajax()->setAlertTarget('#widget_list table', 'after');

    if ($request->getPostParameter('csrf_token') !== UtilCSRF::gen('widget_revoke_data_owner'))
      return $this->ajax()->alert('CSRF Attack detected, please relogin.', 'Error')->render();

    $id = $request->getPostParameter('id');
    if (!is_numeric($id))
      return $this->ajax()->alert('invalid data', 'Error')->render();

    $widget = WidgetTable::getInstance()->find($id);
    /* @var $widget Widget */
    if (!$widget)
      return $this->ajax()->alert('Widget not found', 'Error')->render();

    if (!$this->userIsAdmin() && ($widget->getCampaign()->getStatus() == CampaignTable::STATUS_DELETED
      || $widget->getPetition()->getStatus() == Petition::STATUS_DELETED))
      return $this->ajax()->alert('Widget not found', 'Error')->render();

    if (!$this->getGuardUser()->isDataOwnerOfCampaign($widget->getPetition()->getCampaign()))
      return $this->ajax()->alert('You are not Data manager', '')->render();

    if ($widget->getDataOwner() != WidgetTable::DATA_OWNER_YES)
      return $this->ajax()->alert('This user is not data-owner.', '')->render();

    $widget->setDataOwner(WidgetTable::DATA_OWNER_NO);
    $widget->save();

    return $this->ajax()->alert('Data-owner revoked', '')->render();
  }

  public function executeWidgetval(sfWebRequest $request) {
    if ($request->hasParameter('code')) {
      $idcode = $request->getParameter('code');
      if (is_string($idcode))
        $idcode = explode('-', trim($idcode));
      if (is_array($idcode) && count($idcode) === 2) {
        list($id, $code) = $idcode;
        $id = ltrim($id, '0 ');
        $widget = Doctrine_Core::getTable('Widget')
          ->createQuery('w')
          ->where('w.id = ?', $id)
          ->leftJoin('w.PetitionText pt')
          ->select('w.*, pt.id, pt.language_id')
          ->fetchOne();  /* @var $widget Widget */
        if (!empty($widget)) {
//          $this->lang = $widget->getPetitionText()->getLanguageId();
//          $this->getContext()->getI18N()->setCulture($this->lang);
//          $this->getUser()->setCulture($this->lang);

          if ($code === $widget->getValidationData()) {
            $this->idcode = $id . '-' . $code;
            $this->id = $widget->getId();

            if ($widget->getValidationStatus() == Widget::VALIDATION_STATUS_PENDING) {
              $widget->setValidationStatus(Widget::VALIDATION_STATUS_VERIFIED);
              $widget->save();
            }

            if ($this->getUser()->isAuthenticated()) {
              if ($widget->getValidationStatus() != Widget::VALIDATION_STATUS_OWNER) {

                $this->csrf_token = UtilCSRF::gen('widgetval');

                if ($request->isMethod('post')) {
                  if ($request->getPostParameter('csrf_token') != $this->csrf_token)
                    return $this->ajax()->alert('CSRF Attack detected, please relogin.', 'Error')->render();

                  $widget->setUser($this->getGuardUser());
                  $widget->setValidationStatus(Widget::VALIDATION_STATUS_OWNER);
                  $widget->save();

                  return $this->ajax()
                      ->addClass('#connect a', 'disabled')
                      ->afterPartial('#connect', 'widget_link', array('id' => $widget->getId()))
                      ->alert('Successfully connected.', '', '#connect', 'after', false, 'success')
                      ->render();
                }
              }
            }
            else {
              $storage = sfContext::getInstance()->getStorage();
              if ($storage instanceof policatSessionStorage) {
                $storage->needSession();
              }
              $this->getUser()->setAttribute(myUser::SESSION_WIDGETVAL_IDCODE, $this->idcode);
              $this->getUser()->setAttribute(myUser::SESSION_WIDGETVAL_ON, 0);
            }
          }
        }
      }
    }
  }

}