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


function stashInCache(cacheName, request, response) {

if (response.status === 200){

  caches.open(cacheName).then(cache => cache.put(request, response));
  
  }
}

function isStaticUrl(url){
    
  var static_extensions = ["js", "css", "jpg", "png", "gif", "woff", "svg", "ico"]; 
  
  var current_file_ext = getFileExt(url);
  
  if( static_extensions.indexOf(current_file_ext) === -1) {
  
      return false;
      
  } else {
      
      return true;
      
  }
    
    
}

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
    
    
  if (isStaticUrl(event.request.url)) {
  
  console.log('static request for ' + event.request.url);
  
  return handle_possible_static_request(event);
  
  } else {
  
  console.log('dynamic request for ' + event.request.url);
  
  return handle_possible_dynamic_request(event);
  
  }
    
    
    
    
});


function handle_possible_dynamic_request(event){
    
 console.log('try dynamic request handling for ' + event.request.url);
    
      event.respondWith(
  
fetch(event.request).then(function(response) { 
    
    console.log('we have a response for ' + event.request.url);
    
            let responseCopy = response.clone();
        let contentType = response.headers.get('content-type');
    //this is shit, we need better content type sniffing
    stashInCache(PAGES_CACHENAME, event.request, responseCopy);
      return response;
      
}).catch(function(error) {
 

return caches.match(event.request).then(function(cacheresponse) {
    
    console.log('the cache response is ');
    
    console.log(cacheresponse);
    
    
return response || new Response('try fall');
        
    }).catch(function(error) {
        

return caches.match(HTML_FALLBACK_URL).then(function(htmlfallbackresponse) {
        
console.log('cache match promise resolved for ' + HTML_FALLBACK_URL );

console.log(htmlfallbackresponse );

    return htmlfallbackresponse;
        
    }).catch(function(error) {
        
        console.log('No fallback at all for ' + event.request.url);
        
return new Response('no fallback at all');
    
});

    
});
    
})
    
  );
    
}






function handle_possible_static_request(event){
    
event.respondWith(
    caches.match(event.request).then(function(cached_response) {
      return cached_response || fetch(event.request).then(function(network_response) {
           // cache the resource and serve it
      let responseCopy = network_response.clone();
      stashInCache(STATIC_CACHENAME, event.request, responseCopy);
      return network_response;
    }).catch(function(error) {
        
        
        
            console.log(event.request.url + 'returned not found try svg');
    
                if (event.request.url.match(/\.(jpe?g|png|gif|svg|mapbox)/)) {
                    return new Response('<svg role="img" aria-labelledby="offline-title" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Helvetica Neue,Arial,Helvetica,sans-serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>', {headers: {'Content-Type': 'image/svg+xml', 'Cache-Control': 'no-store'}});
                }

    });
          
          
    }).catch(function(error) {
        
        return new Response('there was an error');
        
    
    })
 
  );

    
}