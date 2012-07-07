<?php if($protected && !$class->protectedMethodCount || !$protected && !$class->publicMethodCount) return; ?>

<div class="summary">
<h2><?php echo $protected ? 'Protected Methods' : 'Public Methods'; ?></h2>
<p>
<a href="#" class="toggle">Hide inherited methods</a>
</p>
<table class="summaryTable">
<tr>
  <th>Method</th><th>Description</th><th>Defined By</th>
</tr>
<?php foreach($class->methods as $method): ?>
<?php if($protected && $method->isProtected || !$protected && !$method->isProtected): ?>
<tr<?php echo $method->isInherited?' class="inherited"':''; ?> id="<?php echo $method->name; ?>">
  <td><?php echo $this->renderSubjectUrl($method->definedBy,$method->name,$method->name.'()'); ?></td>
  <td><?php echo $method->introduction; ?></td>
  <td><?php echo $this->renderTypeUrl($method->definedBy); ?></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
</div>