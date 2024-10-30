(function() {

if (document.currentScript.getAttribute('data-sw-url')){
    
console.log('service worker exists' + document.currentScript.getAttribute('data-sw-url'));

var service_worker_url = document.currentScript.getAttribute('data-sw-url');

if (navigator.serviceWorker) {
    window.addEventListener('load', function() {
        
        navigator.serviceWorker.register(service_worker_url, {
            scope: '/'
        });
        
    // in the page being controlled
    navigator.serviceWorker.addEventListener('message', (message) => {
    console.log(message);
});

    });
}

}




window.addEventListener('load', function() {
    


function lh_web_application_handleConnectionChange(event){
    if(event.type == "offline"){
        
        document.body.classList.remove("online");
        document.body.classList.add("offline");
        console.log("You lost connection.");
        
    }

    if(event.type == "online"){
        
        document.body.classList.remove("offline");
        document.body.classList.add("online");
        console.log("You are now back online.");
        

    }
    
    console.log(new Date(event.timeStamp));
}

        var connDiv = document.createElement('div');
        connDiv.setAttribute("id", "lh_web_application-connection_status");
        document.body.appendChild(connDiv);

window.addEventListener('online', lh_web_application_handleConnectionChange);
window.addEventListener('offline', lh_web_application_handleConnectionChange);


});

function getParams(url) {
	var params = {};
	var parser = document.createElement('a');
	parser.href = url;
	var query = parser.search.substring(1);
	var vars = query.split('&');
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split('=');
		params[pair[0]] = decodeURIComponent(pair[1]);
	}
	return params;
}
    

function lh_web_application_isExternal(url) {

    
var params = getParams(url);

if (params.redirect_to){

    var redirectLink = document.createElement('a');
    redirectLink.href = params.redirect_to;

    
    return redirectLink.hostname != window.location.hostname;

    
} else {
    
    var tempLink = document.createElement('a');
    tempLink.href = url;

 return tempLink.hostname != window.location.hostname;
 
}

    
}








if ( window.matchMedia( '(display-mode: standalone)' ).matches && (window.navigator.standalone !== true)) {
    
    document.body.classList.add("standalone");
    
    

  window.addEventListener( 'click', function(event) {
      
      
    if (event.target.tagName === 'A' && lh_web_application_isExternal(event.target.href)){
        
        window.open( event.target.href );
      event.preventDefault();
    } 
  } );
}

window.addEventListener('beforeinstallprompt', (e) => {
    
    // Prevent Chrome 76 and later from showing the mini-infobar
    e.preventDefault();
  
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
  
    //change the body class so you can selectively prompt
    document.body.classList.add("lh_web_application-prompt_stashed");
    
if (document.querySelector('.lh_web_application-stashed_prompt') !== null) {
    

var a2hsBtn = document.querySelector('.lh_web_application-stashed_prompt');


  a2hsBtn.addEventListener("click", addToHomeScreen);
  
}
  
});


function addToHomeScreen() {  
    
    
    var a2hsBtn = document.querySelector('.lh_web_application-stashed_prompt');  // hide our user interface that shows our A2HS button
  a2hsBtn.style.display = 'none';  
  // Show the prompt
  deferredPrompt.prompt();  
  // Wait for the user to respond to the prompt
  deferredPrompt.userChoice
    .then(function(choiceResult){

  if (choiceResult.outcome === 'accepted') {
    console.log('User accepted the A2HS prompt');
  } else {
    console.log('User dismissed the A2HS prompt');
  }

  deferredPrompt = null;

});}


})();