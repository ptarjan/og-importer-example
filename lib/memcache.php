<?php

require_once('PHPMemcacheSASL/MemcacheSASL.php');

function get_memcache() {
  $m = new MemcacheSASL;
  $m->addServer($_ENV['MEMCACHE_SERVERS'], '11211');
  $m->setSaslAuthData($_ENV['MEMCACHE_USERNAME'], $_ENV['MEMCACHE_PASSWORD']);
  return $m;
}
