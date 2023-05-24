//firebase-messaging-sw.js
importScripts("https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js");

self.addEventListener("install", function(event) {
    self.skipWaiting();
});

self.onnotificationclick = function(event) {
    console.log("On notification click: ", event.notification.tag);
    console.log(event);
    event.notification.close();

    // This looks to see if the current is already open and
    // focuses if it is
    event.waitUntil(
        clients
        .matchAll({
            type: "window",
        })
        .then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];
                if (client.url == "/" && "focus" in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow("/");
        })
    );
};

const firebaseConfig = {
    apiKey: "AIzaSyAj9EBXZkLxNv3LNocUNYYYTgED7N9TTIY",
    authDomain: "boti-school-10e14.firebaseapp.com",
    projectId: "boti-school-10e14",
    storageBucket: "boti-school-10e14.appspot.com",
    messagingSenderId: "21317928207",
    appId: "1:21317928207:web:5595984c8c61d2238fea7a",
    measurementId: "G-7BLKBDZ59V"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.getToken().then((token) => {
    console.log(token);
});