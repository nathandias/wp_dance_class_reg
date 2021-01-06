<div style="background-color:#CCC; padding:2px;"><img src="/regman/images/logo.gif" style="float:left; height:14px; margin-right:5px;"/>
<span style="font-size:12px;"><strong><?= $REG_SETTINGS[venue_name] ?> | 
<?= $REG_SETTINGS[venue_address] ?> | <?= $REG_SETTINGS[venue_url] ?></strong></span>
</div>

<div style="clear:both"></div>

<?php if (!$printable) { ?>
<ul class="navigation">
<li><a href="/regman/index.php">Class Rosters</a></li>
<li><a href="/regman/classes.php">Classes</a></li>
<li><a href="/regman/registrations.php">Registrations</a></li>
</ul>

<?php } ?>
