<?php

$last_modified = self::get_last_modified();

    echo "//<html>
    "; 

$maybe_add_to_cache = array();

if ($page = get_page($this->options[ $this->offline_page_field_name ])){
    
$maybe_add_to_cache[] = get_permalink($this->options[ $this->offline_page_field_name ]);   

//$maybe_add_to_cache[] = $this->return_start_url();
    
}



$maybe_add_to_cache = array_filter($maybe_add_to_cache);

$add_to_cache = array();

foreach( $maybe_add_to_cache as $cache) {
    
if (self::isValidURL($cache)){
    
array_push($add_to_cache,$cache);
    
}
    
}

$add_to_cache = array_filter($add_to_cache);

echo "var CACHED_URLS = ".wp_json_encode($add_to_cache).";";

echo "
";

$href_72 = $this->check_image_size($this->return_manifest_icon_id(),$this->web_application_icon_image, '72', '72');

if (isset($href_72) and !empty($href_72)){
    
echo "var ICON_72_URL = '".$href_72."';";

echo "
";
    
}

$href_192 = $this->check_image_size($this->return_manifest_icon_id(), $this->web_application_icon_image, '192','192');

if (isset($href_192) and !empty($href_192)){
    
echo "var ICON_192_URL = '".$href_192."';";

echo "
";
    
}


$buster = wp_hash("fundamentals_".wp_json_encode($add_to_cache).$last_modified);

?>

importScripts('<?php echo plugins_url().'/lh-web-application/scripts/localforage.min.js' ?>');


var DYNAMIC_CACHE_NAME = '<?php echo self::return_plugin_namespace().'-'.$buster; ?>';
var STATIC_CACHE_NAME = '<?php echo self::return_plugin_namespace().'-static_cache'; ?>';
var STATIC_FILE_EXTS = ["js", "css", "jpg", "png", "gif", "woff", "svg"];




function getFileExt(url) {
//this gets the full url

//this removes the anchor at the end, if there is one
url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
//this removes the query after the file name, if there is one
url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
//this removes everything before the last slash in the path
url = url.substring(url.lastIndexOf("/") + 1, url.length);


a = url.split(".");
if( a.length === 1 || ( a[0] === "" && a.length === 2 ) ) {
    url = "";
} else {

url = a.pop().toLowerCase(); 

}

//return
return url;


}


// Cache the page(s) that initiate the service worker
function cacheClients() {
    const pages = [];
    return clients.matchAll({
        includeUncontrolled: true
    })
    .then( allClients => {
        for (const client of allClients) {
            pages.push(client.url);
        }
    })
    .then ( () => {
        caches.open(DYNAMIC_CACHE_NAME)
        .then( pagesCache => {
            return pagesCache.addAll(pages);
        });
    })
}




function stashInRegCache(cacheName, request, response) {

if (response.status === 200){

  caches.open(cacheName).then(cache => cache.put(request, response));
  
  }
}



self.addEventListener('install', function(event) {

    event.waitUntil(
        caches.open(DYNAMIC_CACHE_NAME).then(function(cache) {
            return cache.addAll(CACHED_URLS);
        }).then(function() {   
        
        return cacheClients();
        
        }).then(function() {
        
        
        
        
      // `skipWaiting()` forces the waiting ServiceWorker to become the
      // active ServiceWorker, triggering the `onactivate` event.
      // Together with `Clients.claim()` this allows a worker to take effect
      // immediately in the client(s).
      return self.skipWaiting();
    })
    );

});

self.addEventListener('fetch', function(event) {

console.log('general client of ' + event.request.url);

if (event.request.method === 'GET' && event.request.url.indexOf('wp-admin') === -1 ) {

 var type = event.request.headers.get('Accept');
 
 
ext = getFileExt(event.request.url);



  if( STATIC_FILE_EXTS.indexOf(ext) === -1) {
  

//console.log('client of ' + event.request.url);
//console.log('the clients id is ' + event.resultingClientId);
//console.log('the clients id is ' + event.clientId);
 
//foobar

  
  event.respondWith(
  
fetch(event.request).then(response => {

 console.log(event.request.url + 'returned from network');
 
 
      // cache the resource and serve it
      let responseCopy = response.clone();
      stashInRegCache(DYNAMIC_CACHE_NAME, event.request, responseCopy);
      return response;
    }).catch(() => {
    
    console.log(event.request.url + 'returned from cache');
    
      // the resource could not be fetched from network
      // so let's pull it out from cache, otherwise serve the "offline" page
      return caches.match(event.request).then(
      
      
     
      response => response || caches.match('<?php echo get_permalink($this->options[ $this->offline_page_field_name ]); ?>'));

    })
    
    );

} else {

  console.log( 'the static request is ' + event.request.url);

event.respondWith(
  
  caches.match(event.request).then(function(response) { 
  
  return response ||     fetch(event.request).then(response => {
      // cache the resource and serve it
      let responseCopy = response.clone();
      stashInRegCache(STATIC_CACHE_NAME, event.request, responseCopy);
      return response;
    }).catch(() => {
    
    console.log(event.request.url + 'returned not found try svg');
    
                if (event.request.url.match(/\.(jpe?g|png|gif|svg|mapbox)/)) {
                    return new Response('<svg role="img" aria-labelledby="offline-title" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Helvetica Neue,Arial,Helvetica,sans-serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>', {headers: {'Content-Type': 'image/svg+xml', 'Cache-Control': 'no-store'}});
                }

    })
  
  })
  
  
   );

}

}
});

/*
  ACTIVATE EVENT: triggered once after registering, also used to clean up caches.
*/

//Adding `activate` event listener
self.addEventListener('activate', (event) => {
  console.info('Event: Activate');

  //Navigation preload is help us make parallel request while service worker is booting up.
  //Enable - chrome://flags/#enable-service-worker-navigation-preload
  //Support - Chrome 57 beta (behing the flag)
  //More info - https://developers.google.com/web/updates/2017/02/navigation-preload#the-problem

  // Check if navigationPreload is supported or not
  // if (self.registration.navigationPreload) { 
  //   self.registration.navigationPreload.enable();
  // }
  // else if (!self.registration.navigationPreload) { 
  //   console.info('Your browser does not support navigation preload.');
  // }

  //Remove old and unwanted caches
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if ((cache !== DYNAMIC_CACHE_NAME) && (cache !== STATIC_CACHE_NAME)) {
          console.log(cache + 'deleted');
            return caches.delete(cache); //Deleting the old cache (cache v1)
          }
        })
      );
    })
    .then(function () {
      console.info("Old caches are cleared!");
      // To tell the service worker to activate current one 
      // instead of waiting for the old one to finish.
      return self.clients.claim(); 
    }) 
  );
});


/*
  PUSH EVENT: triggered everytime, when a push notification is received.
*/

//Adding `push` event listener
self.addEventListener('push', function (event) {
  console.log('Received push');
  var notificationTitle = 'Hello';
  var notificationOptions = {
    body: 'Thanks for sending this push msg.',
    icon: ICON_192_URL,
    badge: ICON_72_URL,
    tag: 'simple-push-demo-notification',
    data: {
      url: 'https://developers.google.com/web/fundamentals/getting-started/push-notifications/'
    }
  };
  

  if (event.data) {
    var dataText = event.data.text();
    notificationTitle = 'Received Payload';
    notificationOptions.body = 'Push data: \'' + dataText + '\'';
  }

  event.waitUntil(self.registration.showNotification(notificationTitle, notificationOptions));
});


self.addEventListener('notificationclick', function(event) {
  var doge = event.notification.data.url;
  console.log(doge);
});



self.addEventListener('notificationclick', function(event) {
  console.log('[Service Worker] Notification click Received.');

  event.notification.close();
  
  var doge = event.notification.data.url;

  event.waitUntil( clients.openWindow(doge) );
});


// for wp super cache </html>