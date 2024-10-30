<?php

$pages_cache = array();

$pages_cache[] = get_permalink($this->options[ $this->offline_page_field_name ]);

$pages_cache[] = $this->return_start_url();

$buster = wp_hash("fundamentals_".wp_json_encode($pages_cache));

?>
'use strict';

const pageUrlsToCache = <?php echo wp_json_encode($pages_cache); ?>

const staticCacheName = '<?php echo self::return_plugin_namespace(); ?>-static';
const pagesCacheName = '<?php echo self::return_plugin_namespace().'-pages-'.$buster; ?>'

const STATIC_FILE_EXTS = ["js", "css", "jpg", "png", "gif", "woff", "svg", "ico"];

function getFileExt(url) {
//this gets the full url

//this removes the anchor at the end, if there is one
url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
//this removes the query after the file name, if there is one
url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
//this removes everything before the last slash in the path
url = url.substring(url.lastIndexOf("/") + 1, url.length);

var a;
a = url.split(".");
if( a.length === 1 || ( a[0] === "" && a.length === 2 ) ) {
    url = "";
} else {

url = a.pop().toLowerCase(); 

}

//return
return url;


}

function stashInRegCache(cacheName, request, response) {

if (response.status === 200){

  caches.open(cacheName).then(cache => cache.put(request, response));
  
  }
}


function addPageCaches() {
    return caches.open(pagesCacheName)
    .then( pagesCache => {


        // These items must be cached for the Service Worker to complete installation
                // These items won't block the installation of the Service Worker
        //pagesCache.addAll([
        //'<?php echo $this->return_start_url(); ?>'
        //]);
        
        
        
        
        return pagesCache.addAll(pageUrlsToCache);
    });
}


function addStaticCaches() {
    return caches.open(staticCacheName)
    .then( staticCache => {
        
    return staticCache.addAll([
            '<?php echo self::return_child_style_url(); ?>',
        ]);
    });
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
        caches.open(pagesCacheName)
        .then( pagesCache => {
            return pagesCache.addAll(pages);
        });
    })
}

async function messageClient(clientId) {
    const client = await clients.get(clientId);
    client.postMessage('Hi client!');
}



self.addEventListener('install', event => {
    event.waitUntil(
        addPageCaches()
        .then( () => {
            cacheClients()
        })
        .then( () => {
          return self.skipWaiting();
        })
    );
});


self.addEventListener('fetch', function(event) {



    // Ignore requests to some directories
    if (event.request.url.includes('/wp-admin')) {
    console.log('cms request ' + event.request.url);
        return;
    }
    
      if (event.request.method !== 'GET') {
        console.log('cms request non get ' + event.request.url);
        return;
    }


    
console.log('general client of ' + event.request.url);

var current_file_ext;
    
current_file_ext = getFileExt(event.request.url);

console.log('current file extenson is ' + current_file_ext + ' ' + event.request.url);

  if( STATIC_FILE_EXTS.indexOf(current_file_ext) === -1) {
  
console.log('page client of ' + event.request.url);


//console.log('client of ' + event.request.url);
//console.log('the clients id is ' + event.resultingClientId);
//console.log('the clients id is ' + event.clientId);
 
//foobar

  
  event.respondWith(
  
fetch(event.request).then(response => {

 console.log(event.request.url + ' trying network');
 
 
      // cache the resource and serve it
      let responseCopy = response.clone();
      stashInRegCache(pagesCacheName, event.request, responseCopy);
      return response;
    }).catch(() => {
    
    console.log(event.request.url + 'returned from cache');
    
    console.log('the client id is ' + event.clientId);
    
    messageClient(event.clientId);
    
      // the resource could not be fetched from network
      // so let's pull it out from cache, otherwise serve the "offline" page
      return caches.match(event.request.url).then(
      
  
     
      response => response || caches.match('<?php echo get_permalink($this->options[ $this->offline_page_field_name ]); ?>'));

    })
    
    );

} else {

console.log('asset client of ' + event.request.url);

event.respondWith(
  
  caches.match(event.request.url).then(function(response) { 
  
  return response ||     fetch(event.request).then(response => {
      // cache the resource and serve it
      let responseCopy = response.clone();
      stashInRegCache(staticCacheName, event.request, responseCopy);
      return response;
    }).catch(() => {
    
    console.log(event.request.url + ' not found try svg');
    
                if (event.request.url.match(/\.(jpe?g|png|gif|svg|mapbox|ico)/)) {
                    return new Response('<svg role="img" aria-labelledby="offline-title" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Helvetica Neue,Arial,Helvetica,sans-serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>', {headers: {'Content-Type': 'image/svg+xml', 'Cache-Control': 'no-store'}});
                }

    })
  
  })
  
  
   );

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
          if ((cache !== staticCacheName)) {
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

self.addEventListener('push', function(event) {
  console.log('[Service Worker] Push Received.');
  
  const myObject = event.data.json();
  
  console.debug(event.data);

  const title = 'Push Codelab';
  


const options = {
    body: myObject.message
     };

if (myObject.data.url){

console.log('data url exists');
     
    options.data = {
        url: myObject.data.url
      };
      
}   

  if (myObject.icon){
  
  options.icon = myObject.icon;
  
  }
  
   if (myObject.badge){
  
  options.badge = myObject.badge;
  
  console.log('the badge is ' + myObject.badge);
  
  }

  event.waitUntil(self.registration.showNotification(myObject.title, options));
});

self.addEventListener("notificationclick", function(event) {
  event.waitUntil(clients.openWindow(event.notification.data.url));    
});