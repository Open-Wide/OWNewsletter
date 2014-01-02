<?php /* #?ini charset="utf-8"?

/*
# php runcronjobs.php newsletter
# php runcronjobs.php -s siteaccess newsletter_mailqueue_create
# php runcronjobs.php -s siteaccess newsletter_mailqueue_process
# php runcronjobs.php -s siteaccess newsletter_users_clean_pending

[CronjobSettings]
ExtensionDirectories[]=ownewsletter

# CronjobPart for Testing
[CronjobPart-newsletter]
Scripts[]=newsletter_mailqueue_create.php
Scripts[]=newsletter_mailqueue_process.php
Scripts[]=newsletter_users_clean_pending.php

[CronjobPart-newsletter_mailqueue_create]
Scripts[]=newsletter_mailqueue_create.php

[CronjobPart-newsletter_mailqueue_process]
Scripts[]=newsletter_mailqueue_process.php

[CronjobPart-newsletter_users_clean_pending]
Scripts[]=newsletter_users_clean_pending.php

*/ ?>
