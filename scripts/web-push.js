(function (window) {
  'use strict';
  
  if ( document.currentScript.getAttribute('data-applicationServerPublicKey')){
  
  console.log('public key is ' + document.currentScript.getAttribute('data-applicationServerPublicKey'));
  
  const applicationServerPublicKey = document.currentScript.getAttribute('data-applicationServerPublicKey');
  
  }
  
  
  function urlB64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

  
  //To subscribe `push notification`
  function subscribePush() {
      const applicationServerPublicKey = document.getElementById('lh_web_application-web_push-script').getAttribute('data-applicationServerPublicKey');
      
      
      const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    navigator.serviceWorker.ready.then(function(registration) {
      if (!registration.pushManager) {
        alert('Your browser doesn\'t support push notification.');
        return false;
      }

      //To subscribe `push notification` from push manager
      registration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: applicationServerKey
    })
      .then(function (subscription) {
          
          console.log(JSON.stringify(subscription));
        //alert(JSON.stringify(subscription));
        console.info('Push notification subscribed.');
        //changePushStatus(true);
        //sendPushNotification();
      })
      .catch(function (error) {
        //changePushStatus(false);
        console.error('Push notification subscription error: ', error);
      });
    })
  }
  
  //To check `push notification` is supported or not
  function isPushSupported() {
    //To check `push notification` permission is denied by user
    if (Notification.permission === 'denied') {
      console.warn('User has blocked push notification.');
      return;
    }

    //Check `push notification` is supported or not
    if (!('PushManager' in window)) {
      console.error('Push notification isn\'t supported in your browser.');
      return;
    }

    //Get `push notification` subscription
    //If `serviceWorker` is registered and ready
    navigator.serviceWorker.ready
      .then(function (registration) {
        registration.pushManager.getSubscription()
        .then(function (subscription) {
          //If already access granted, enable push button status
          if (subscription) {
              console.log(JSON.stringify(subscription));
            //alert(JSON.stringify(subscription));
          
              
          } else {
              
              subscribePush();
           console.log('try subscribing.');
           
          }
        })
        .catch(function (error) {
          console.error('Error occurred while enabling push ', error);
        });
      });
  }
  
   isPushSupported(); //Check for push notification support
})(window);