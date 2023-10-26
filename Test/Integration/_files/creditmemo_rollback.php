<?php
$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$resolver->requireDataFixture('MageSuite_ServerSideGoogleAnalytics::Test/Integration/_files/order_rollback.php');
