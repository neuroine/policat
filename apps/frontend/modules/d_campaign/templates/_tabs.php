<?php
$list = array(
    'overview' => array('title' => 'Overview', 'route' => 'campaign_edit_'),
    'targets' => array('title' => 'Target-lists', 'route' => 'target_index')
);
$user = $sf_user->getGuardUser();
/* @var $user sfGuardUser */
/* @var $campaign Campaign */
if ($user->isCampaignAdmin($campaign->getRawValue()))
  $list['privacy'] = array('title' => 'Default Privacy Policy', 'route' => 'campaign_privacy_list');
if ($user->isCampaignAdmin($campaign->getRawValue()))
  $list['data'] = array('title' => 'Signings', 'route' => 'campaign_data');
if (!isset($active))
  $active = '';
?>
<div class="page-header">
  <h1>Campaign: <?php echo $campaign->getName() ?></h1>
</div>
<?php if (count($list) > 1): ?>
<ul class="nav nav-tabs">
  <?php foreach ($list as $key => $entry): ?>
    <li class="<?php if ($active == $key) echo 'active' ?>">
      <a href="<?php echo url_for($entry['route'], array('id' => $campaign->getId())) ?>"><?php echo $entry['title'] ?></a>
    </li>
  <?php endforeach ?>
</ul>
<?php endif ?>