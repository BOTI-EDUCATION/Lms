function supportNotificationApi() {
    return new Promise((reslove, reject) => {
        if ("Notification" in window) {
            reslove(true);
        } else {
            reject(false);
        }
    });
}

function initFcm() {
    return new Promise((reslove, reject) => {
        supportNotificationApi()
            .then(() => {
                console.log("Push notification  started");
                const firebaseConfig = {
                    apiKey: "AIzaSyAj9EBXZkLxNv3LNocUNYYYTgED7N9TTIY",
                    authDomain: "boti-school-10e14.firebaseapp.com",
                    projectId: "boti-school-10e14",
                    storageBucket: "boti-school-10e14.appspot.com",
                    messagingSenderId: "21317928207",
                    appId: "1:21317928207:web:5595984c8c61d2238fea7a",
                    measurementId: "G-7BLKBDZ59V"
                };
                window.firebase.initializeApp(firebaseConfig);
                window.firebase.analytics();
                const messaging = window.firebase.messaging();
                messaging.usePublicVapidKey(
                    "BIiqmyMGVqmmXja4-awJJ4xGjhCjygoCOOsXCgkprOwCC9C1MxP8nBesC3ytlzJh19eiy57SzmSMsvlyKD7nCNY"
                );
                if ('serviceWorker' in navigator) {
                    window.addEventListener('load', function () {
                        navigator.serviceWorker.register("/firebase-messaging-sw.js")
                            .then(function (registration) {
                                messaging.useServiceWorker(registration);
                                reslove(messaging);
                                console.log("firebase-messaging-sw.js registered");
                            });
                    });
                }
            })
            .catch(() => {
                console.log("Push notification will not work not supported by the browser");
            });

    });
}

initFcm().then(function (messaging) {
    messaging.getToken().then(function (newtoken) {
        if (newtoken) {
            console.log(newtoken);
            formData = new FormData();
            formData.append('token', newtoken);
            formData.append('op', 'token');
            $.ajax({
                method: 'POST',
                url: app.url.base + '/admin/users',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
            }).done(function (res) {
                console.log(res);
            });

        } else {
            console.log("No Instance ID token avalible. Request Permission to generate one.");
        }
    }).catch(function (err) {
        console.log(err);
    })
});