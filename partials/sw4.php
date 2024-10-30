<?php

$precache_page_urls= array();
if (!empty($this->options[ self::return_offline_page_field_name() ]) && !empty(get_permalink($this->options[ self::return_offline_page_field_name() ]))){ $precache_page_urls[] = get_permalink($this->options[ self::return_offline_page_field_name() ]); }
$precache_page_urls[] = self::return_start_url();

$buster = wp_hash(self::return_plugin_namespace().wp_json_encode($precache_page_urls));

$precache_static_urls= self::return_precache_static_urls_as_array();


do_action( self::return_plugin_namespace().'_open_service_worker' );

?>
'use strict';

const PAGES_CACHENAME = 'lh_web_application-pages-<?php echo $buster; ?>';
const PRECACHE_PAGE_URLS = <?php echo wp_json_encode($precache_page_urls); ?>;



const STATIC_CACHENAME = 'lh_web_application-static';
const PRECACHE_STATIC_URLS = <?php echo wp_json_encode($precache_static_urls); ?>;

<?php if (!empty($this->options[ self::return_offline_page_field_name() ]) && !empty(get_permalink($this->options[ self::return_offline_page_field_name() ]))){ ?>

const HTML_FALLBACK_URL = '<?php echo get_permalink($this->options[ self::return_offline_page_field_name() ]); ?>';

<?php } ?>

self.addEventListener('install', function(event) {
  event.waitUntil(
    
    caches.open(PAGES_CACHENAME).then(function(dynamic_cache) {
      return dynamic_cache.addAll(PRECACHE_PAGE_URLS);
    }).then(caches.open(STATIC_CACHENAME).then(function(static_cache) {
      return static_cache.addAll(PRECACHE_STATIC_URLS);
    })).then(self.skipWaiting())
  
  
  
  );
});







// The activate handler takes care of cleaning up old caches.
self.addEventListener('activate', event => {
  const currentCaches = [PAGES_CACHENAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
    }).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
  );
});



<?php


$scripts_array = apply_filters(self::return_plugin_namespace().'_import_scripts_src_filter', self::get_import_scripts_array());

foreach ( $scripts_array as $script_src ) {
    
echo "
importScripts('".apply_filters('script_loader_src',$script_src, '')."');";

}

?>

//</html>