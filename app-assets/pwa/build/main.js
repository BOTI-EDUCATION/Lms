webpackJsonp([0],{

/***/ 111:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MyApp; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_native_status_bar__ = __webpack_require__(591);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__ = __webpack_require__(592);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_storage__ = __webpack_require__(58);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_app_version__ = __webpack_require__(584);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_network__ = __webpack_require__(585);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7_rxjs_operators__ = __webpack_require__(70);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7_rxjs_operators___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_7_rxjs_operators__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__pages_home_home__ = __webpack_require__(112);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__pages_connexion_connexion__ = __webpack_require__(202);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__ = __webpack_require__(205);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__pages_messages_messages__ = __webpack_require__(207);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__pages_compte_compte__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__pages_demandes_demandes__ = __webpack_require__(209);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_14__pages_ressources_ressources__ = __webpack_require__(614);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_15__pages_devoirs_devoirs__ = __webpack_require__(615);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_17__pages_contact_contact__ = __webpack_require__(616);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_18__pages_mise_a_jour_mise_a_jour__ = __webpack_require__(617);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__ = __webpack_require__(618);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_20__pages_etat_paiement_etat_paiement__ = __webpack_require__(619);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_22__providers_fcm_fcm__ = __webpack_require__(113);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_23__pages_absences_absences__ = __webpack_require__(206);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_24__pages_prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};

























var MyApp = /** @class */ (function () {
    function MyApp(platform, statusBar, fcm, splashScreen, events, storage, network, appVersion, apiSerivce, menu, alertCtrl) {
        var _this = this;
        this.platform = platform;
        this.statusBar = statusBar;
        this.fcm = fcm;
        this.splashScreen = splashScreen;
        this.events = events;
        this.storage = storage;
        this.network = network;
        this.appVersion = appVersion;
        this.apiSerivce = apiSerivce;
        this.menu = menu;
        this.alertCtrl = alertCtrl;
        this.onDev = true;
        this.versionAr = __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].versionAr;
        this.initializeApp();
        //Event pour stocket Token FCM et init Firebase event notification
        events.subscribe('user:fcm', function () {
            if (!_this.onDev) {
                if (_this.platform.is('cordova')) {
                    _this.initFcm();
                }
            }
        });
        //Event pour changer le role to parent
        events.subscribe('user:changetoparent', function () {
            _this.menu.swipeEnable(true);
            _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */]);
        });
        events.subscribe('user:changetoprof', function () {
            _this.menu.swipeEnable(false);
            _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_24__pages_prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
        });
        //Event pour detecter le type de connexion
        events.subscribe('network:type', function () {
            if (!_this.onDev) {
                var type = _this.network.type;
                if (type == "unknown" || type == "none" || type == undefined) {
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__["a" /* NoNetworkPage */]);
                }
            }
        });
        // Compte CHECK :)
        events.subscribe('compte:check', function () {
            _this.apiSerivce.getData({
                user_id: __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].userId,
                key: __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].keyToken,
            }, 'compte_check')
                .subscribe(function (result) {
                _this._resultCompte = result;
                if (_this._resultCompte.disconnect) {
                    __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].activeEleve = null;
                    __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId = null;
                    _this.storage.remove('boti_auth');
                    _this.storage.remove('boti_userId');
                    _this.storage.remove('boti_eleveId');
                    _this.storage.remove('boti_parentId');
                    _this.storage.remove('boti_enseignantId');
                    _this.storage.remove('boti_keyToken');
                    _this.storage.remove('boti_activeEleve');
                    _this.menu.swipeEnable(false);
                    _this.rootPage = __WEBPACK_IMPORTED_MODULE_9__pages_connexion_connexion__["a" /* ConnexionPage */];
                    return;
                }
                _this.events.publish('user:fcm');
                _this.events.publish('storage:infos', _this._resultCompte);
                if (_this.platform.is('cordova')) {
                    _this.appVersion.getVersionNumber().then(function (versionNumber) {
                        if (_this._resultCompte.version.version_number != versionNumber)
                            _this.rootPage = __WEBPACK_IMPORTED_MODULE_18__pages_mise_a_jour_mise_a_jour__["a" /* MiseAJourPage */];
                        else
                            _this.rootPage = _this.rootPageComponent(_this._resultCompte._acces);
                    });
                }
                else {
                    _this.rootPage = _this.rootPageComponent(_this._resultCompte._acces);
                }
            }, function (error) {
                _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__["a" /* NoNetworkPage */], {
                    error: true
                });
            });
        });
        //Event pour Stocker les infos en storage
        events.subscribe('storage:infos', function (_result) {
            if (_result._acces == 'parent') {
                _this.menu.swipeEnable(true);
            }
            else if (_result._acces == 'enseignant') {
                _this.menu.swipeEnable(false);
            }
            if (_result.parentId) {
                __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].parentId = _result.parentId;
                MyApp_1.parent = _result.parent;
                MyApp_1.eleves = _result.eleves;
            }
            if (_result.enseignantId) {
                __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].enseignantId = _result.enseignantId;
                MyApp_1.enseignant = _result.enseignant;
            }
            if (_result.parentId && _result.enseignantId)
                MyApp_1.doubleAccount = true;
            __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].keyToken = _result.keyToken;
            __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].userId = _result.userId;
            if (!_result.comOnly)
                MyApp_1.pages[6].visible = true;
            _this.storage.set('boti_auth', "boti_connected");
            _this.storage.set('boti_parentId', _result.parentId);
            _this.storage.set('boti_keyToken', _result.keyToken);
            _this.storage.set('boti_userId', _result.userId);
            if (__WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId) {
                var currentEleve = MyApp_1.eleves.find(function (obj) { return obj.id === __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId; });
                __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].activeEleve = currentEleve;
                MyApp_1.eleve = currentEleve;
                _this.storage.set('boti_activeEleve', currentEleve);
            }
            else if (__WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].parentId) {
                __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId = _result.eleveId;
                __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].activeEleve = _result.eleves[0];
                MyApp_1.eleve = _result.eleves[0];
                _this.storage.set('boti_eleveId', _result.eleveId);
                _this.storage.set('boti_activeEleve', _result.eleves[0]);
            }
        });
        //Event pour detecter si le user est connecté ou non
        this.storage.get('boti_auth').then(function (auth) {
            if (auth == "boti_connected") {
                _this.storage.get('boti_userId').then(function (valeur) {
                    __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].userId = valeur;
                    _this.storage.get('boti_keyToken').then(function (valeur) {
                        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].keyToken = valeur;
                        _this.events.publish('compte:check');
                    });
                });
            }
            else {
                _this.rootPage = __WEBPACK_IMPORTED_MODULE_8__pages_home_home__["a" /* HomePage */];
                _this.menu.swipeEnable(false);
            }
            _this.splashScreen.hide();
        });
        if (__WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].versionAr) {
            // used for an example of ngFor and navigation
            MyApp_1.pages = [
                { title: ' المستجدات', visible: true, component: __WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */], icone: 'ios-globe-outline' },
                { title: 'التمارين المنزلية', visible: true, component: __WEBPACK_IMPORTED_MODULE_15__pages_devoirs_devoirs__["a" /* DevoirsPage */], icone: 'ios-paper-outline' },
                { title: 'التتبع الدراسي', visible: true, component: __WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], icone: 'ios-school-outline' },
                { title: 'الموارد المدرسية', visible: true, component: __WEBPACK_IMPORTED_MODULE_14__pages_ressources_ressources__["a" /* RessourcesPage */], icone: 'ios-send-outline' },
                { title: 'الرسائل', visible: true, component: __WEBPACK_IMPORTED_MODULE_11__pages_messages_messages__["a" /* MessagesPage */], icone: 'ios-mail-outline' },
                { title: 'الطلبات', visible: true, component: __WEBPACK_IMPORTED_MODULE_13__pages_demandes_demandes__["a" /* DemandesPage */], icone: 'ios-create-outline' },
                { title: 'الأداء المالي', visible: false, component: __WEBPACK_IMPORTED_MODULE_20__pages_etat_paiement_etat_paiement__["a" /* EtatPaiementPage */], icone: 'ios-card' },
                { title: 'الحساب الشخصي', visible: true, component: __WEBPACK_IMPORTED_MODULE_12__pages_compte_compte__["a" /* ComptePage */], icone: 'ios-person-outline' },
                { title: 'التواصل', visible: true, component: __WEBPACK_IMPORTED_MODULE_17__pages_contact_contact__["a" /* ContactPage */], icone: 'ios-information-circle-outline' },
            ];
        }
        else {
            // used for an example of ngFor and navigation
            MyApp_1.pages = [
                { title: 'Nouveautés', visible: true, component: __WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */], icone: 'ios-globe-outline' },
                { title: 'Devoirs', visible: true, component: __WEBPACK_IMPORTED_MODULE_15__pages_devoirs_devoirs__["a" /* DevoirsPage */], icone: 'ios-paper-outline' },
                { title: 'Suivi pédagogique', visible: true, component: __WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], icone: 'ios-school-outline' },
                { title: 'Ressources', visible: true, component: __WEBPACK_IMPORTED_MODULE_14__pages_ressources_ressources__["a" /* RessourcesPage */], icone: 'ios-send-outline' },
                { title: 'Messages', visible: true, component: __WEBPACK_IMPORTED_MODULE_11__pages_messages_messages__["a" /* MessagesPage */], icone: 'ios-mail-outline' },
                { title: 'Demandes', visible: true, component: __WEBPACK_IMPORTED_MODULE_13__pages_demandes_demandes__["a" /* DemandesPage */], icone: 'ios-create-outline' },
                { title: 'Etat de paiement', visible: false, component: __WEBPACK_IMPORTED_MODULE_20__pages_etat_paiement_etat_paiement__["a" /* EtatPaiementPage */], icone: 'ios-card' },
                { title: 'Mon compte', visible: true, component: __WEBPACK_IMPORTED_MODULE_12__pages_compte_compte__["a" /* ComptePage */], icone: 'ios-person-outline' },
                { title: 'Contact', visible: true, component: __WEBPACK_IMPORTED_MODULE_17__pages_contact_contact__["a" /* ContactPage */], icone: 'ios-information-circle-outline' },
            ];
        }
    }
    MyApp_1 = MyApp;
    Object.defineProperty(MyApp.prototype, "eleve", {
        get: function () {
            return MyApp_1.eleve;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MyApp.prototype, "doubleAccount", {
        get: function () {
            return MyApp_1.doubleAccount;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MyApp.prototype, "eleves", {
        get: function () {
            return MyApp_1.eleves;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MyApp.prototype, "pages", {
        get: function () {
            return MyApp_1.pages;
        },
        enumerable: true,
        configurable: true
    });
    MyApp.prototype.rootPageComponent = function (_acces) {
        if (_acces == 'parent') {
            return __WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */];
        }
        else if (_acces == 'enseignant') {
            return __WEBPACK_IMPORTED_MODULE_24__pages_prof_planning_prof_planning__["a" /* ProfPlanningPage */];
        }
    };
    MyApp.prototype.switchToProf = function () {
        this.events.publish('user:changetoprof');
    };
    MyApp.prototype.initFcm = function () {
        var _this = this;
        // Get a FCM token
        this.fcm.getToken();
        this.fcm.listenToNotifications().pipe(Object(__WEBPACK_IMPORTED_MODULE_7_rxjs_operators__["tap"])(function (msg) {
            __WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */].newNotif = true;
            if (msg.page) {
                var pageTo = msg.page;
                if (pageTo == 'eleve_absences')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_23__pages_absences_absences__["a" /* AbsencesPage */]);
                else if (pageTo == 'eleve_nouveautes')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */]);
                else if (pageTo == 'eleve_paiements')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_20__pages_etat_paiement_etat_paiement__["a" /* EtatPaiementPage */]);
                else if (pageTo == 'eleve_devoirs')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_15__pages_devoirs_devoirs__["a" /* DevoirsPage */]);
                else if (pageTo == 'eleve_demandes')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_13__pages_demandes_demandes__["a" /* DemandesPage */]);
                else if (pageTo == 'eleve_messages')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_11__pages_messages_messages__["a" /* MessagesPage */]);
                else if (pageTo == 'eleve_emploi')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], {
                        tabIndex: 0
                    });
                else if (pageTo == 'eleve_absences')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], {
                        tabIndex: 1
                    });
                else if (pageTo == 'eleve_examens')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], {
                        tabIndex: 2
                    });
                else if (pageTo == 'eleve_discipline')
                    _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_10__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], {
                        tabIndex: 3
                    });
            }
        })).subscribe();
    };
    MyApp.prototype.initializeApp = function () {
        var _this = this;
        this.platform.ready().then(function () {
            // Okay, so the platform is ready and our plugins are available.
            // Here you can do any higher level native things you might need.
            _this.statusBar.styleDefault();
            //this.statusBar.backgroundColorByHexString('#ffb606');
            _this.listenConnection();
        });
    };
    MyApp.prototype.listenConnection = function () {
        var _this = this;
        if (!this.onDev) {
            var type = this.network.type;
            if (type == "unknown" || type == "none" || type == undefined) {
                this.rootPage = __WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__["a" /* NoNetworkPage */];
                this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__["a" /* NoNetworkPage */]);
            }
            this.network.onDisconnect()
                .subscribe(function () {
                _this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_19__pages_no_network_no_network__["a" /* NoNetworkPage */]);
            });
        }
    };
    MyApp.prototype.changeprofil = function (eleve) {
        this.activePage = null;
        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].activeEleve = eleve;
        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId = eleve.id;
        this.storage.set('activeleve', eleve);
        this.storage.set('boti_eleveId', eleve.id);
        this.storage.set('boti_activeEleve', eleve);
        MyApp_1.eleve = eleve;
        this.menu.close();
        this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_16__pages_nouveautes_nouveautes__["a" /* NouveautesPage */]);
    };
    MyApp.prototype.logout = function () {
        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].activeEleve = null;
        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].eleveId = null;
        __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */].enseignantId = null;
        this.storage.remove('boti_auth');
        this.storage.remove('boti_userId');
        this.storage.remove('boti_eleveId');
        this.storage.remove('boti_parentId');
        this.storage.remove('boti_enseignantId');
        this.storage.remove('boti_keyToken');
        this.storage.remove('boti_activeEleve');
        this.menu.swipeEnable(false);
        this.nav.setRoot(__WEBPACK_IMPORTED_MODULE_8__pages_home_home__["a" /* HomePage */]);
    };
    MyApp.prototype.checkActive = function (page) {
        return page == this.activePage;
    };
    MyApp.prototype.openPage = function (page) {
        // Reset the content nav to have just this page
        // we wouldn't want the back button to show in this scenario
        this.activePage = page;
        this.nav.setRoot(page.component);
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["ViewChild"])(__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Nav"]),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Nav"])
    ], MyApp.prototype, "nav", void 0);
    MyApp = MyApp_1 = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/app/app.html"*/'<ion-menu [content]="content">\n\n  <ion-content class="content-menu">\n    <div class="eleves_menu">\n      <div *ngIf="eleve" class="active_eleve">\n          <div class="img_container">\n            <div class="statut-eleve"></div>\n            <img class="img-responsive" src="{{eleve.img}}" />\n          </div>\n          <h2>{{eleve.nomcomplet}}</h2>\n          <h3>{{eleve.niveau}}</h3>\n      </div>\n      <div *ngIf="eleves" class="autres_eleves">\n        <div  *ngFor="let item of eleves" class="eleve_item" (click)="changeprofil(item)">\n          <div  *ngIf="item.id != eleve.id" >\n              <div class="img_container">\n                <div class="statut-eleve"></div>\n                <img class="img-responsive" src="{{item.img}}" />\n              </div>\n              <h3>{{item.prenom}}</h3>\n          </div>\n        </div>\n      </div>\n    </div>\n    <ul class="ulmenu">\n      <li menuClose class="menuapp" [class.activemenu]="checkActive(p)" *ngFor="let p of pages" (click)="openPage(p)" [ngClass]="{\'hidden\': !p.visible}">\n        <ion-icon name="{{p.icone}}"></ion-icon> <span>{{p.title}}</span>\n      </li>\n      \n      <li *ngIf="doubleAccount"  menuClose class="menuapp"   (click)="switchToProf()">\n          <ion-icon name="repeat"></ion-icon> <span  *ngIf="!versionAr">Switcher vers accés enseignant</span>\n          <span *ngIf="versionAr">فضاء الاساتذة</span>\n       </li>\n\n      <li  menuClose class="menuapp"   (click)="logout()">\n          <ion-icon  name="ios-log-in-outline"></ion-icon> <span *ngIf="!versionAr">Se Déconnecter</span>\n          <span *ngIf="versionAr">الخروج من التطبيق</span>\n       </li>\n\n    </ul>\n  </ion-content>\n\n</ion-menu>\n\n<!-- Disable swipe-to-go-back because it\'s poor UX to combine STGB with side menus -->\n<ion-nav [root]="rootPage" #content swipeBackEnabled="false"></ion-nav>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/app/app.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_2__ionic_native_status_bar__["a" /* StatusBar */],
            __WEBPACK_IMPORTED_MODULE_22__providers_fcm_fcm__["a" /* FcmProvider */],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__["a" /* SplashScreen */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_4__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_network__["a" /* Network */],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_app_version__["a" /* AppVersion */],
            __WEBPACK_IMPORTED_MODULE_21__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["MenuController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"]])
    ], MyApp);
    return MyApp;
    var MyApp_1;
}());

//# sourceMappingURL=app.component.js.map

/***/ }),

/***/ 112:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return HomePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__connexion_connexion__ = __webpack_require__(202);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var HomePage = /** @class */ (function () {
    function HomePage(navCtrl, apiSerivce, toast, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.apiSerivce = apiSerivce;
        this.toast = toast;
        this.loadingCtrl = loadingCtrl;
        this.ConnexionPage = __WEBPACK_IMPORTED_MODULE_3__connexion_connexion__["a" /* ConnexionPage */];
    }
    HomePage.prototype.ionViewDidLoad = function () {
        //this.presentLoadingCustom();
    };
    HomePage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: "\n        <div class=\"custom-spinner-container\">\n          <div class=\"custom-spinner-box\">\n          <img src=\"assets/imgs/loading/animation-loading\" />\n          <p>Veuillez patienter...</p>\n          </div>\n        </div>",
            duration: 10000
        });
        this.loading.present();
    };
    HomePage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({}, 'slides')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) {
            console.log("Error :: " + error);
        });
    };
    HomePage.prototype.ngOnInit = function () {
        this.getData();
    };
    HomePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-home',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/home/home.html"*/'<ion-content [ngClass]="{\'loadingblur\': loadingBlur}">\n\n  <div class="illustration_page" *ngIf="_result?.illustration">\n      <img class="logo_boti" src="{{_result.illustration.logo}}" />\n      <h1>{{_result.illustration.title}}</h1>\n      <p>{{_result.illustration.body}}</p>\n      <button class="btn-main"  [navPush]="ConnexionPage" >{{_result.translation.login}} sdfgdsfhgfghjfgjgf</button>\n      <div class="illustration_img">\n        <img src="{{_result.illustration.img}}" />\n      </div>\n      <div class="illustration_pattern">\n        <img src="{{_result.illustration.pattern}}" />\n      </div>\n  </div>\n  <ion-slides pager="true" loop="true" parallax="true" *ngIf="_result?.data?.length > 0">\n    <ion-slide *ngFor="let result of _result.data" [ngStyle]="{\'background-image\': \'url(\' + result.img + \')\'}">\n        <div class="slider-item text-center" >\n          <h3>{{result.text}}</h3>\n          <p></p>\n        </div>\n    </ion-slide>\n  </ion-slides>\n  <!-- END SLIDER -->\n\n  <div class="btn-actions text-center"  *ngIf="_result?.data?.length > 0">\n    <ul class="list-unstyled">\n      <li>\n          <button class="btn-main"  [navPush]="ConnexionPage" >{{_result.translation.login}}</button>\n      </li>\n    </ul>\n  </div>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/home/home.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], HomePage);
    return HomePage;
}());

//# sourceMappingURL=home.js.map

/***/ }),

/***/ 113:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return FcmProvider; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ionic_native_firebase__ = __webpack_require__(593);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};




var FcmProvider = /** @class */ (function () {
    function FcmProvider(firebaseNative, apiSerivce, platform) {
        this.firebaseNative = firebaseNative;
        this.apiSerivce = apiSerivce;
        this.platform = platform;
    }
    FcmProvider.prototype.getToken = function () {
        return __awaiter(this, void 0, void 0, function () {
            var token, perm;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        if (!this.platform.is('cordova'))
                            return [2 /*return*/];
                        if (!this.platform.is('android')) return [3 /*break*/, 2];
                        return [4 /*yield*/, this.firebaseNative.getToken()];
                    case 1:
                        token = _a.sent();
                        _a.label = 2;
                    case 2:
                        if (!this.platform.is('ios')) return [3 /*break*/, 5];
                        return [4 /*yield*/, this.firebaseNative.getToken()];
                    case 3:
                        token = _a.sent();
                        return [4 /*yield*/, this.firebaseNative.grantPermission()];
                    case 4:
                        perm = _a.sent();
                        _a.label = 5;
                    case 5:
                        // Is not cordova == web PWA
                        if (!this.platform.is('cordova')) {
                            // TODO add PWA support with angularfire2
                        }
                        return [2 /*return*/, this.saveTokenToFirestore(token)];
                }
            });
        });
    };
    FcmProvider.prototype.saveTokenToFirestore = function (token) {
        if (!token) {
            return;
        }
        this.apiSerivce.postData("token=" + token + '&parent_id=' + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId + '&enseignant_id=' + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].enseignantId + '&key=' + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken, 'token').subscribe();
    };
    FcmProvider.prototype.listenToNotifications = function () {
        return this.firebaseNative.onNotificationOpen();
    };
    FcmProvider = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Injectable"])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__ionic_native_firebase__["a" /* Firebase */],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_2_ionic_angular__["Platform"]])
    ], FcmProvider);
    return FcmProvider;
}());

//# sourceMappingURL=fcm.js.map

/***/ }),

/***/ 114:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ComptePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__app_app_component__ = __webpack_require__(111);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__compte_eleve_compte_eleve__ = __webpack_require__(610);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__compte_parent_compte_parent__ = __webpack_require__(611);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__change_password_change_password__ = __webpack_require__(612);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_storage__ = __webpack_require__(58);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};









var ComptePage = /** @class */ (function () {
    function ComptePage(navCtrl, apiSerivce, events, storage, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.apiSerivce = apiSerivce;
        this.events = events;
        this.storage = storage;
        this.loadingCtrl = loadingCtrl;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    ComptePage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ComptePage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ComptePage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'compte')
            .subscribe(function (result) {
            _this._result = result;
            console.log(_this._result, 'yes');
            _this.events.publish('storage:infos', _this._result);
            _this.loading.dismiss();
            _this.loadingBlur = null;
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    ComptePage.prototype.changeprofil = function (eleve) {
        __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve = eleve;
        __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId = eleve.id;
        this.storage.set('activeleve', eleve);
        this.storage.set('boti_eleveId', eleve.id);
        this.storage.set('boti_activeEleve', eleve);
        __WEBPACK_IMPORTED_MODULE_3__app_app_component__["a" /* MyApp */].eleve = eleve;
        this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__nouveautes_nouveautes__["a" /* NouveautesPage */]);
    };
    ComptePage.prototype.ngOnInit = function () {
        this.getData();
    };
    ComptePage.prototype.compteEleve = function (eleve) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_5__compte_eleve_compte_eleve__["a" /* CompteElevePage */], {
            eleve: eleve
        });
    };
    ComptePage.prototype.changePassword = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_7__change_password_change_password__["a" /* ChangePasswordPage */]);
    };
    ComptePage.prototype.compteParent = function (parent) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_6__compte_parent_compte_parent__["a" /* CompteParentPage */], {
            parent: parent
        });
    };
    ComptePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-compte',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte/compte.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n    <button ion-button menuToggle>\n    <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="_result?.translation" >{{_result.translation.title}}</ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content  [ngClass]="{\'loadingblur\': loadingBlur}" >\n\n        <div class="parent-picture" *ngIf="_result" >\n            <div>\n                <img src="{{_result.image}}" alt="">\n            </div>\n            <div>\n                <h1 (click)="compteParent(_result.parent)"> {{_result.nomcomplet}}\n                    <ion-icon class="edit_compte" name="md-create"></ion-icon>\n                </h1>\n                <p class="contact"><ion-icon name="ios-call-outline"></ion-icon> <span>{{_result.tel}}</span></p>\n                <p class="contact"><ion-icon name="ios-mail-outline"></ion-icon> <span>{{_result.email}}</span> </p>\n                <a href="#" (click)="changePassword()">{{_result.translation.changer_password}}</a>\n            </div>\n        </div>\n \n  <div class="eleves" *ngIf="_result">\n       <h3>{{_result.translation.enfants}}</h3>\n        <div class="eleve-list" *ngFor="let item of _result.eleves" >\n            \n            <div class="eleve-picture" (click)="compteEleve(item)" >\n                <div>\n                    <div *ngIf="item.id == eleve.id" class="statut-eleve"></div>\n                    <img src="{{item.img}}" alt="">\n                </div>\n                <div>\n                    <h1> {{item.nomcomplet}}\n                        <ion-icon class="edit_compte" name="md-create" ></ion-icon>\n                    </h1>\n                    <span>{{item.niveau}}</span>\n                </div>\n            </div>\n            <ion-icon class="connect_with" *ngIf="item.id != eleve.id" name="ios-key-outline" (click)="changeprofil(item)"></ion-icon>\n        </div>\n  </div>\n  \n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte/compte.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_8__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ComptePage);
    return ComptePage;
}());

//# sourceMappingURL=compte.js.map

/***/ }),

/***/ 202:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ConnexionPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_storage__ = __webpack_require__(58);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_sim__ = __webpack_require__(581);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__providers_fcm_fcm__ = __webpack_require__(113);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__forgot_password_forgot_password__ = __webpack_require__(595);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__connexion_phonenumber_connexion_phonenumber__ = __webpack_require__(596);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};











var ConnexionPage = /** @class */ (function () {
    function ConnexionPage(navCtrl, navParams, modalCtrl, sim, loadingCtrl, events, fcm, renderer, apiSerivce, storage, formBuilder, toastCtrl, menu) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.modalCtrl = modalCtrl;
        this.sim = sim;
        this.loadingCtrl = loadingCtrl;
        this.events = events;
        this.fcm = fcm;
        this.renderer = renderer;
        this.apiSerivce = apiSerivce;
        this.storage = storage;
        this.formBuilder = formBuilder;
        this.toastCtrl = toastCtrl;
        this.menu = menu;
        this.showPwd = false;
        this.pwdType = "password";
        this.pwdIcon = "md-eye-off";
        this.formGroup = this.formBuilder.group({
            email: ['', __WEBPACK_IMPORTED_MODULE_3__angular_forms__["Validators"].required],
            password: ['', __WEBPACK_IMPORTED_MODULE_3__angular_forms__["Validators"].required]
        });
    }
    ConnexionPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({}, 'login-page')
            .subscribe(function (result) {
            _this._resultPage = result;
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ConnexionPage.prototype.ngOnInit = function () {
        this.getData();
        //this.getSimData();
        this.events.publish('network:type');
    };
    ConnexionPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad ConnexionPage');
    };
    ConnexionPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ConnexionPage.prototype.getSimData = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _this = this;
            var simPermission, simData, error_1;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        _a.trys.push([0, 4, , 5]);
                        return [4 /*yield*/, this.sim.requestReadPermission()];
                    case 1:
                        simPermission = _a.sent();
                        if (!(simPermission == "OK")) return [3 /*break*/, 3];
                        return [4 /*yield*/, this.sim.getSimInfo()];
                    case 2:
                        simData = _a.sent();
                        this.simInfo = simData;
                        this.cards = simData.cards;
                        //alert(JSON.stringify(simData));
                        //alert(JSON.stringify(this.simInfo));
                        if (this.simInfo.phoneNumber) {
                            this.apiSerivce.postData("phone=" + this.simInfo.phoneNumber, 'phone_auth').subscribe(function (result) {
                                _this._result = result;
                                if (_this._result.error === false) {
                                    _this.sendNotification(_this._result.msg, 'toast-success');
                                    _this.events.publish('storage:infos', _this._result);
                                    _this.events.publish('user:fcm');
                                    if (_this._result._acces == 'parent') {
                                        _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_7__nouveautes_nouveautes__["a" /* NouveautesPage */]);
                                    }
                                    else if (_this._result._acces == 'enseignant') {
                                        _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_10__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
                                    }
                                }
                                else {
                                    _this.sendNotification(_this._result.msg, 'toast-error');
                                }
                            }, function (error) {
                                console.log("Error :: " + error);
                            });
                        }
                        _a.label = 3;
                    case 3: return [3 /*break*/, 5];
                    case 4:
                        error_1 = _a.sent();
                        return [3 /*break*/, 5];
                    case 5: return [2 /*return*/];
                }
            });
        });
    };
    ConnexionPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    ConnexionPage.prototype.showHidePassword = function () {
        this.showPwd = !this.showPwd;
        if (this.showPwd) {
            this.pwdType = 'text';
            this.pwdIcon = 'md-eye-off';
        }
        else {
            this.pwdType = 'password';
            this.pwdIcon = 'md-eye';
        }
    };
    ConnexionPage.prototype.login = function () {
        var _this = this;
        this.apiSerivce.postData("login=" + this.loginI + "&password=" + this.passwordI, 'login').subscribe(function (result) {
            _this._result = result;
            if (_this._result.error === false) {
                _this.events.publish('storage:infos', _this._result);
                _this.events.publish('user:fcm');
                if (_this._result._acces == 'parent') {
                    _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_7__nouveautes_nouveautes__["a" /* NouveautesPage */]);
                }
                else if (_this._result._acces == 'enseignant') {
                    _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_10__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
                }
            }
            else {
                _this.sendNotification(_this._result.msg, 'toast-error');
            }
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    ConnexionPage.prototype.forgotPasswordModal = function () {
        var passwordModal = this.modalCtrl.create(__WEBPACK_IMPORTED_MODULE_8__forgot_password_forgot_password__["a" /* ForgotPasswordPage */]);
        passwordModal.present();
    };
    ConnexionPage.prototype.connexionPhonenumberModal = function () {
        var passwordModal = this.modalCtrl.create(__WEBPACK_IMPORTED_MODULE_9__connexion_phonenumber_connexion_phonenumber__["a" /* ConnexionPhoneNumberPage */]);
        passwordModal.present();
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["ViewChild"])('passwordInput'),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_0__angular_core__["ElementRef"])
    ], ConnexionPage.prototype, "passwordInputElement", void 0);
    ConnexionPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-connexion',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/connexion/connexion.html"*/'<!--\n  Generated template for the ConnexionPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n\n\n<ion-content >\n    <div class="square"></div>\n    <div class="square-two"></div>\n    <div class="square-bottom"></div>\n    <div class="square-two-bottom"></div>\n  <div class="vertical-center t-40" *ngIf="_resultPage">\n      <div class="container">\n          <div class="banner text-center">\n              <img *ngIf="_resultPage?.data?.logo" src="{{_resultPage.data.logo}}" alt="">\n              <h1>{{_resultPage.translation.title}}</h1>\n              <p *ngIf="_resultPage?.data?.ecole">{{_resultPage.data.ecole}}</p>\n              <form (ngSubmit)="login()" [formGroup]="formGroup" >\n                <div class="form-group">\n                  <input type="email" name="email" formControlName="email" [(ngModel)]="loginI" required placeholder="{{_resultPage.translation.login}}">\n                </div>\n                <div class="form-group">\n                    <div class="input-group">\n                        <input type="{{pwdType}}"  name="password" id="inputID"   formControlName="password" [(ngModel)]="passwordI"  placeholder="{{_resultPage.translation.password}}">\n                        <span class="input-group-btn">\n                            <label for="inputID"  ion-button="" icon-only="" clear="" color="dark" item-right="" (click)="showHidePassword()"> \n                            <ion-icon [name]="pwdIcon"></ion-icon>\n                            </label>\n                        </span>\n                      </div><!-- /input-group -->\n                </div>\n                <button type="submit" class="btn-main btn-block text-uppercase" [disabled]="!formGroup.valid" >{{_resultPage.translation.btn_login}}</button>\n              </form>\n              <div class="row row-actions">\n                <div class="col-xs-6 text-left" hidden>\n				        	<a href="#" (click)="connexionPhonenumberModal()" >Se connecter avec numéro de téléphone</a>\n                </div>\n                <div class="col-xs-12 text-right">\n					        <a href="#" (click)="forgotPasswordModal()" >{{_resultPage.translation.forgot_password}}</a>\n                </div>\n              </div>\n              <div class="bottom-cgu text-center"  *ngIf="_resultPage && _resultPage.data.cgu">\n                <p>En continuant, Vous acceptez nos <a href="{{_resultPage.data.cgu}}" class=""> CGU et nos conditions de confidentialité</a></p>\n              </div>\n          </div>\n      </div>\n  </div>\n  <!--\n    <div class="btn-actions">\n      <button class="btn-main btn-block btn-facebook text-uppercase">\n        <i class="fa fa-facebook fw"></i>\n        Se connecter avec Facebook\n      </button>\n    </div> \n  -->\n  \n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/connexion/connexion.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ModalController"],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_sim__["a" /* Sim */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_6__providers_fcm_fcm__["a" /* FcmProvider */],
            __WEBPACK_IMPORTED_MODULE_0__angular_core__["Renderer"],
            __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_2__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_3__angular_forms__["FormBuilder"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["MenuController"]])
    ], ConnexionPage);
    return ConnexionPage;
}());

//# sourceMappingURL=connexion.js.map

/***/ }),

/***/ 203:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return PostPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_moment__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_moment___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_moment__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__angular_common__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__ = __webpack_require__(108);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__angular_forms__ = __webpack_require__(8);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};










var PostPage = /** @class */ (function () {
    function PostPage(navCtrl, navParams, iab, formBuilder, datePipe, alertCtrl, apiSerivce, sanitizer, file, transfer, platform) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.iab = iab;
        this.formBuilder = formBuilder;
        this.datePipe = datePipe;
        this.alertCtrl = alertCtrl;
        this.apiSerivce = apiSerivce;
        this.sanitizer = sanitizer;
        this.file = file;
        this.transfer = transfer;
        this.platform = platform;
        this.fileTransfer = this.transfer.create();
        __WEBPACK_IMPORTED_MODULE_3_moment__["locale"]('fr');
        this.eleve = __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].activeEleve;
        this.result = navParams.get('result');
        this.description = this.getInnerHTMLValue();
        this.formGroup = this.formBuilder.group({
            commentaire: ['', __WEBPACK_IMPORTED_MODULE_9__angular_forms__["Validators"].required],
        });
    }
    PostPage_1 = PostPage;
    PostPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].keyToken,
            post: this.result.id
        }, 'post_view')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    PostPage.prototype.ngOnInit = function () {
        this.getData();
    };
    PostPage.prototype.getInnerHTMLValue = function () {
        return this.sanitizer.bypassSecurityTrustHtml(this.result.description);
    };
    PostPage.prototype.ionViewDidLoad = function () {
    };
    PostPage.prototype.downloadFile = function (file) {
        var browser = this.iab.create(file.link);
    };
    PostPage.prototype.send = function () {
        var _this = this;
        var query = "commentaire=" + this.commentaire +
            "&eleve_id=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].eleveId +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].keyToken;
        query += "&post=" + this.result.id;
        if (!__WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].urlLoadingPost)
            this.apiSerivce.postData(query, 'nouveautes').subscribe(function (result) {
                _this.navCtrl.setRoot(PostPage_1, { result: _this.result });
            }, function (error) {
                //alert(error);
                console.log("Error :: " + error);
                //this.loading.dismiss().catch();
            });
    };
    PostPage.prototype.send_quiz = function (reponse) {
        var _this = this;
        var query = "quiz=" + reponse +
            "&eleve_id=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].eleveId +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].keyToken;
        query += "&post=" + this.result.id;
        if (!__WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].urlLoadingPost)
            this.apiSerivce.postData(query, 'nouveautes').subscribe(function (result) {
                _this._resultQuiz = result;
                _this.result.quizDone = _this._resultQuiz.quizDone;
                _this.result.permitQuiz = false;
                var alertSuccess = _this.alertCtrl.create({
                    title: 'Merci !',
                    message: _this._resultQuiz.message,
                    cssClass: 'success_alert_boti',
                    buttons: ['Ok']
                });
                alertSuccess.present();
            }, function (error) {
                //alert(error);
                console.log("Error :: " + error);
                //this.loading.dismiss().catch();
            });
    };
    PostPage = PostPage_1 = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-post',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/post/post.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title>{{result.categorie}}</ion-title>\n      <div  class="menu_active_eleve">\n          <div class="img_container">\n              <div class="statut-eleve"></div>\n              <img class="img-responsive" src="{{eleve.img}}" />\n          </div>\n      </div>\n    </ion-navbar>\n</ion-header>\n\n\n<ion-content padding>\n\n    <div class="card-post" >\n\n        <div class="card__meta">\n            <span class="categorie">{{result.categorie}}</span>\n            <time>{{ result.date | amTimeAgo }}</time>\n            <h2>{{result.title}}</h2>\n        </div>\n\n        <div *ngIf="result.user" class="card__action">\n            <div class="card__author">\n              <img src="{{result.user.photo}}" alt="user">\n              <div class="card__author-content">\n                Par <a href="#">{{result.user.nom}}</a>\n              </div>\n            </div>\n        </div>\n        \n        <div *ngIf="result.image" class="card__image border-tlr-radius">\n          <img  src="{{result.image}}" alt="" class="border-tlr-radius">\n        </div>\n\n        <article class="card__article">\n            <p [innerHtml]="description"></p>\n        </article>\n\n        <div *ngIf="result.file" class="card__file">\n            <button (click)="downloadFile(result.file)" class="btn btn-main" >{{result.file.text}}</button>\n        </div>\n\n        <div *ngIf="result.permitQuiz" class="quiz_container">\n            <div class="row">\n                <div class="col-xs-6">\n                    <button (click)="send_quiz(\'oui\')" class="btn btn-main btn-quiz-oui" >OUI</button>\n                </div>\n                <div class="col-xs-6">\n                    <button (click)="send_quiz(\'non\')" class="btn btn-main btn-quiz-non" >NON</button>\n                </div>\n            </div>\n        </div>\n\n        <div *ngIf="result.quizDone" class="quiz_container_done">\n            <img class="img-responsive" src="{{result.quizDone.icon}}" />\n            <p>{{result.quizDone.txt}}</p>\n        </div>\n\n        <div *ngIf="result.permitComments" class="comments_container">\n            <h3>{{_result?.data?.commentaires?.length}} commentaires</h3>\n            <div class="chatbox" >\n\n                <div class="chatbox-messages" *ngIf="_result?.data?.commentaires?.length > 0">\n                    <div class="messages clear" *ngFor="let commentaire of _result.data.commentaires">\n                        <span class="avatar" >\n                            <img src="{{commentaire.img}}" alt="{{commentaire.nom}}" />\n                        </span>\n                        <div class="sender">\n                            <div class="message-container">\n                                <div class="message">\n                                    <p><span>{{commentaire.nom}}</span>{{commentaire.commentaire}}</p>\n                                </div>\n                                <span class="delivered">{{ commentaire.date | amTimeAgo }}</span>\n                            </div><!-- /.message-container -->\n\n                        </div><!-- /.sender -->\n                    </div><!-- /.messages -->\n\n                </div><!-- /.chatbox-messages -->\n\n\n                <div class="message-form-container">\n                    \n                    <form (ngSubmit)="send()"  class="message-form" [formGroup]="formGroup" >\n                        <textarea id="commentaire" name="commentaire"  [(ngModel)]="commentaire" formControlName="commentaire" placeholder="Votre commentaire ..."></textarea>\n                        <button class="send-btn" type="submit" [disabled]="!formGroup.valid">\n                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.2 30.1"><path class="st0" d="M2.1 14.6C8.9 12 28.5 4 28.5 4l-3.9 22.6c-0.2 1.1-1.5 1.5-2.3 0.8l-6.1-5.1 -4.3 4 0.7-6.7 13-12.3 -16 10 1 5.7 -3.3-5.3 -5-1.6C1.5 15.8 1.4 14.8 2.1 14.6z"/></svg>\n                        </button>\n                    </form><!-- /.search-form -->\n\n\n                </div><!-- /.message-form-container -->\n\n            </div><!-- /.chatbox -->\n        </div>\n      \n      </div>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/post/post.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__["a" /* InAppBrowser */],
            __WEBPACK_IMPORTED_MODULE_9__angular_forms__["FormBuilder"],
            __WEBPACK_IMPORTED_MODULE_4__angular_common__["DatePipe"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__["DomSanitizer"],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_transfer__["a" /* FileTransfer */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"]])
    ], PostPage);
    return PostPage;
    var PostPage_1;
}());

//# sourceMappingURL=post.js.map

/***/ }),

/***/ 204:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfNouveauMessagePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var ProfNouveauMessagePage = /** @class */ (function () {
    function ProfNouveauMessagePage(navCtrl, loadingCtrl, alertCtrl, toastCtrl, apiSerivce, navParams, formBuilder) {
        this.navCtrl = navCtrl;
        this.loadingCtrl = loadingCtrl;
        this.alertCtrl = alertCtrl;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.navParams = navParams;
        this.formBuilder = formBuilder;
        this.formGroup = this.formBuilder.group({
            sujet: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            message: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
        this.messageRef = navParams.get('messageRef');
        this.sujet = navParams.get('sujet');
        this.translation = navParams.get('translation');
    }
    ProfNouveauMessagePage.prototype.ionViewDidLoad = function () {
    };
    ProfNouveauMessagePage.prototype.send = function () {
        var _this = this;
        var query = "sujet=" + this.sujet +
            "&message=" + this.message +
            "&enseignant_id=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].enseignantId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken;
        if (this.messageRef)
            query += "&ref=" + this.messageRef;
        this.apiSerivce.postData(query, 'prof_messages').subscribe(function (result) {
            var res = result;
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
            var alert = _this.alertCtrl.create({
                cssClass: 'success_alert_boti',
                title: res.title,
                message: res.message,
                buttons: [{
                        text: 'Fermer',
                        role: 'cancel',
                        handler: function () {
                        }
                    }]
            });
            alert.present();
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    ProfNouveauMessagePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-nouveau-message',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-nouveau-message/prof-nouveau-message.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title>{{translation.nouveau_message}}</ion-title>\n    </ion-navbar>\n  </ion-header>\n\n\n<ion-content padding>\n  <div class="">\n      <img src="https://image.flaticon.com/icons/svg/149/149063.svg" />\n      <form (ngSubmit)="send()"  [formGroup]="formGroup" >\n          <ion-list>\n              <ion-item class="pl-0">\n                <ion-input type="text" placeholder="{{translation.nouveau_message}}" name="sujet" class="input-height"  [(ngModel)]="sujet" formControlName="sujet" ></ion-input>\n              </ion-item>\n              <br />\n              <ion-item class="pl-0">\n                <ion-textarea placeholder="{{translation.message}}" name="message"  [(ngModel)]="message" formControlName="message"></ion-textarea>\n              </ion-item>\n              <br />\n              <button  type="submit" class="btn-main btn-block" [disabled]="!formGroup.valid" >{{translation.envoyer}}</button>\n          </ion-list>\n        </form>\n  </div>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-nouveau-message/prof-nouveau-message.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], ProfNouveauMessagePage);
    return ProfNouveauMessagePage;
}());

//# sourceMappingURL=prof-nouveau-message.js.map

/***/ }),

/***/ 205:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SuiviPedagogiqueTabsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__cours_cours__ = __webpack_require__(606);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__absences_absences__ = __webpack_require__(206);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__examens_examens__ = __webpack_require__(607);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__discipline_discipline__ = __webpack_require__(608);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};







var SuiviPedagogiqueTabsPage = /** @class */ (function () {
    function SuiviPedagogiqueTabsPage(navCtrl, navParams, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.tab1Root = __WEBPACK_IMPORTED_MODULE_3__cours_cours__["a" /* CoursPage */];
        this.tab2Root = __WEBPACK_IMPORTED_MODULE_4__absences_absences__["a" /* AbsencesPage */];
        this.tab3Root = __WEBPACK_IMPORTED_MODULE_5__examens_examens__["a" /* ExamensPage */];
        this.tab4Root = __WEBPACK_IMPORTED_MODULE_6__discipline_discipline__["a" /* DisciplinePage */];
        this.tabIndex = 0;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
        if (navParams.get('tabIndex')) {
            this.tabIndex = navParams.get('tabIndex');
        }
        if (!__WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].versionAr) {
            this.translation = {
                'title': 'Suivi pédagogique',
                'tab1': 'Emploi du temps',
                'tab2': 'Absences',
                'tab3': 'Examens & notes',
                'tab4': 'Discipline',
            };
        }
        else {
            this.translation = {
                'title': 'التتبع البيداغوجي',
                'tab1': 'استعمال الزمن',
                'tab2': 'الغياب',
                'tab3': 'الامتحانات و النقط',
                'tab4': 'السلوك',
            };
        }
    }
    SuiviPedagogiqueTabsPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad MenuTabsPage');
    };
    SuiviPedagogiqueTabsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-suivipedagogique-tabs',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/suivipedagogique-tabs/suivipedagogique-tabs.html"*/'<ion-header no-border>\n\n  <ion-navbar transparent>\n\n    <button ion-button menuToggle>\n\n      <ion-icon name="menu"></ion-icon>\n\n    </button>\n\n    <ion-title>{{translation.title}}</ion-title>\n\n    <div  class="menu_active_eleve">\n\n        <div class="img_container">\n\n            <div class="statut-eleve"></div>\n\n            <img class="img-responsive" src="{{eleve.img}}" />\n\n        </div>\n\n    </div>\n\n  </ion-navbar>\n\n</ion-header>\n\n\n\n\n\n<ion-content>\n\n    <ion-tabs selectedIndex="{{tabIndex}}">\n\n      <ion-tab [root]="tab1Root" tabTitle = "{{translation.tab1}}" tabIcon="ios-calendar-outline"></ion-tab>\n\n      <ion-tab [root]="tab2Root" tabTitle = "{{translation.tab2}}" tabIcon="ios-list-box-outline"></ion-tab>\n\n      <ion-tab [root]="tab3Root" tabTitle = "{{translation.tab3}}" tabIcon="ios-bulb-outline"></ion-tab>\n\n      <ion-tab [root]="tab4Root" tabTitle = "{{translation.tab4}}" tabIcon="ios-heart-outline"></ion-tab>\n\n    </ion-tabs>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/suivipedagogique-tabs/suivipedagogique-tabs.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */]])
    ], SuiviPedagogiqueTabsPage);
    return SuiviPedagogiqueTabsPage;
}());

//# sourceMappingURL=suivipedagogique-tabs.js.map

/***/ }),

/***/ 206:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AbsencesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__suivipedagogique_tabs_suivipedagogique_tabs__ = __webpack_require__(205);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_picker_ngx__ = __webpack_require__(79);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_chooser__ = __webpack_require__(80);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_base64__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_native_file_path__ = __webpack_require__(82);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__angular_forms__ = __webpack_require__(8);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};











var AbsencesPage = /** @class */ (function () {
    function AbsencesPage(navCtrl, navParams, alertCtrl, apiSerivce, base64, camera, fileChooser, plt, filePicker, filePath, toastCtrl, loadingCtrl, app, platform, formBuilder) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.alertCtrl = alertCtrl;
        this.apiSerivce = apiSerivce;
        this.base64 = base64;
        this.camera = camera;
        this.fileChooser = fileChooser;
        this.plt = plt;
        this.filePicker = filePicker;
        this.filePath = filePath;
        this.toastCtrl = toastCtrl;
        this.loadingCtrl = loadingCtrl;
        this.app = app;
        this.platform = platform;
        this.formBuilder = formBuilder;
        this.selected = 0;
        this.indicator = null;
        this.mySlideOptions = {};
        this._toggleJustification = false;
        this.platform = platform;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
        this.formGroup = this.formBuilder.group({
            motif: ['', __WEBPACK_IMPORTED_MODULE_9__angular_forms__["Validators"].required],
        });
    }
    AbsencesPage.prototype.ngAfterViewInit = function () {
    };
    AbsencesPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'absences')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    AbsencesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    AbsencesPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    AbsencesPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    AbsencesPage.prototype.toggleJustification = function (absence) {
        if (!this._toggleJustification) {
            this.currentAbsence = absence;
            this.motifAbsence = absence.justificationmotif;
        }
        else {
            this.currentAbsence = null;
        }
        this._toggleJustification = !this._toggleJustification;
    };
    AbsencesPage.prototype.submit = function (absence) {
        var _this = this;
        absence.image = this.imageArr;
        var query = "eleve_id=" + this.eleve.id +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken +
            "&absence=" + JSON.stringify(absence);
        this.apiSerivce.postData(query, 'absences').subscribe(function (result) {
            var res = result;
            _this.app.getRootNav().setRoot(__WEBPACK_IMPORTED_MODULE_3__suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */], {
                tabIndex: 1
            });
            var alert = _this.alertCtrl.create({
                cssClass: 'success_alert_boti',
                title: res.title,
                message: res.message,
                buttons: [{
                        text: 'Fermer',
                        role: 'cancel',
                        handler: function () {
                        }
                    }]
            });
            alert.present();
        }, function (error) {
            _this.loading.dismiss();
        });
    };
    AbsencesPage.prototype.checkJustification = function () {
        this._toggleJustification = false;
        this.currentAbsence.justificationmotif = this.motifAbsence;
        this.submit(this.currentAbsence);
        this.currentAbsence = null;
        this.motifAbsence = null;
    };
    AbsencesPage.prototype.convertToBase64 = function (imageUrl, isImage) {
        var _this = this;
        this.filePath
            .resolveNativePath(imageUrl)
            .then(function (filePath) {
            _this.base64.encodeFile(filePath).then(function (base64Fichier) {
                _this.imageArr = {
                    extention: filePath.split(".").pop(),
                    base64Img: base64Fichier.split(",").pop() //same comment for image follows here.
                };
            }, function (err) {
            });
        })
            .catch(function (err) { return console.log(err); });
    };
    AbsencesPage.prototype.presentToast = function (message) {
        var toast = this.toastCtrl.create({
            message: message,
            duration: 3000,
            position: 'top'
        });
        toast.onDidDismiss(function () {
        });
        toast.present();
    };
    AbsencesPage.prototype.openGallery = function () {
        var _this = this;
        var options = {
            sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
            destinationType: this.camera.DestinationType.FILE_URI
        };
        this.camera
            .getPicture(options)
            .then(function (imageData) {
            _this.presentToast("Image chosen successfully");
            _this.convertToBase64(imageData, true);
        })
            .catch(function (e) {
        });
    };
    AbsencesPage.prototype.select = function (index) {
        this.selected = index;
        this.slider.slideTo(index, 500);
    };
    AbsencesPage.prototype.onSlideChanged = function ($event) {
        if (((($event.touches.startX - $event.touches.currentX) <= 100) || (($event.touches.startX - $event.touches.currentX) > 0)) && (this.slider.isBeginning() || this.slider.isEnd())) {
            //console.log("interdit Direction");
        }
        else {
            //console.log("OK Direction");
            //this.indicator.style.webkitTransform = 'translate3d(' + (-($event.translate) / 4) + 'px,0,0)';
        }
    };
    AbsencesPage.prototype.panEvent = function (e) {
        var currentIndex = this.slider.getActiveIndex();
        console.log('here' + currentIndex);
        if (currentIndex === 1) {
            this.selected = 1;
            //this.indicator.style.webkitTransform = 'translate3d(100%,0,0)';
        }
        if (currentIndex === 0) {
            this.selected = 0;
            //this.indicator.style.webkitTransform = 'translate3d(0%,0,0)';
        }
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["ViewChild"])('mySlider'),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Slides"])
    ], AbsencesPage.prototype, "slider", void 0);
    AbsencesPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-absences',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/absences/absences.html"*/'<ion-content>\n\n  <div *ngIf="_result" class="container_result">\n\n    <div class="user-picture-top-page">\n\n      <div>\n\n        <img src="{{eleve.img}}" alt="{{eleve.nomcomplet}}">\n\n      </div>\n\n      <div>\n\n        <h1> {{_result.translation.title}}</h1>\n\n        <span>{{eleve.nomcomplet}}</span>\n\n      </div>\n\n    </div>\n\n\n\n      <ion-segment color="dark">\n\n        <ion-segment-button style="border:none;" (click)="select(0)" [ngClass]="{SKactiveSegment: selected===0}">\n\n           <span>{{_result.translation.non_justifiees}}</span>\n\n        </ion-segment-button>\n\n        <ion-segment-button style="border:none;" (click)="select(1)" [ngClass]="{SKactiveSegment: selected===1}">\n\n            <span>{{_result.translation.justifiees}}</span>\n\n        </ion-segment-button>\n\n\n\n      </ion-segment>\n\n      <ion-slides  #mySlider >\n\n        \n\n          \n\n          <ion-slide>\n\n              \n\n              <div *ngIf="_result?.non_justifiees?.empty == true" class="vertical-center-absolute no-result ">\n\n                <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n\n                <h3 [innerHtml]="_result.empty_text"></h3>\n\n              </div>\n\n              <section *ngIf="_result?.data?.non_justifiees?.length > 0">\n\n                      <ngb-accordion #acc="ngbAccordion" class="card card-md">\n\n                        <ngb-panel *ngFor="let result of _result.data.non_justifiees">\n\n                          <ng-template ngbPanelTitle>\n\n                            <span class="absence-date">{{result.date}}</span>\n\n                            <span *ngIf="result.nombreseances == 1" class="absence-seances">({{result.nombreseances}} {{_result.translation.seance}})</span>\n\n                            <span *ngIf="result.nombreseances > 1" class="absence-seances">({{result.nombreseances}} {{_result.translation.seances}})</span>\n\n                            <i aria-hidden="true" class="fa fa-angle-right absence-icon"></i>\n\n                          </ng-template>\n\n                          <ng-template ngbPanelContent>\n\n              \n\n                            <div *ngFor="let seance of result.seances">\n\n                              <h6>\n\n                                <span *ngIf="seance.matiere" class="absence-matiere">{{seance.matiere}}</span>\n\n                              </h6>\n\n                              <p>\n\n                                <ion-icon name="person" class="timetable-icon"></ion-icon>\n\n                                <span *ngIf="seance.enseignant" class="absence-enseignant">{{seance.enseignant}}</span>\n\n                                <ion-icon name="time" class="timetable-icon"></ion-icon>\n\n                                <span *ngIf="seance.seance" class="absence-seance">{{seance.seance}}</span>\n\n                              </p>\n\n                              <hr />\n\n                            </div>\n\n                            <div *ngIf="result.button" class="justification_action">\n\n                              <button class="btn-main btn-outline btn-block"  (click)="toggleJustification(result)">{{_result.translation.envoyer_justif}}</button>\n\n                            </div>\n\n                          </ng-template>\n\n                        </ngb-panel>\n\n                      </ngb-accordion>\n\n                    </section>\n\n                  </ion-slide>\n\n                  \n\n          <ion-slide>\n\n              \n\n      <div *ngIf="_result?.justifiees?.empty == true" class="vertical-center-absolute no-result ">\n\n        <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n\n        <h3 [innerHtml]="_result.empty_text"></h3>\n\n      </div>\n\n      <section *ngIf="_result?.data?.justifiees?.length > 0">\n\n              <ngb-accordion #acc="ngbAccordion" class="card card-md">\n\n                <ngb-panel *ngFor="let result of _result.data.justifiees">\n\n                  <ng-template ngbPanelTitle>\n\n                    <span class="absence-date">{{result.date}}</span>\n\n                    <span *ngIf="result.nombreseances == 1" class="absence-seances">({{result.nombreseances}} {{_result.translation.seance}} )</span>\n\n                    <span *ngIf="result.nombreseances > 1" class="absence-seances">({{result.nombreseances}} {{_result.translation.seances}})</span>\n\n                    <i aria-hidden="true" class="fa fa-angle-right absence-icon"></i>\n\n                  </ng-template>\n\n                  <ng-template ngbPanelContent>\n\n      \n\n                    <div *ngFor="let seance of result.seances">\n\n                      <h6>\n\n                        <span *ngIf="seance.matiere" class="absence-matiere">{{seance.matiere}}</span>\n\n                      </h6>\n\n                      <p>\n\n                        <ion-icon name="person" class="timetable-icon"></ion-icon>\n\n                        <span *ngIf="seance.enseignant" class="absence-enseignant">{{seance.enseignant}}</span>\n\n                        <ion-icon name="time" class="timetable-icon"></ion-icon>\n\n                        <span *ngIf="seance.seance" class="absence-seance">{{seance.seance}}</span>\n\n                      </p>\n\n                      <hr />\n\n                    </div>\n\n                  </ng-template>\n\n                </ngb-panel>\n\n              </ngb-accordion>\n\n            </section>\n\n          </ion-slide>\n\n\n\n      </ion-slides>\n\n  </div>\n\n  </ion-content>\n\n  <div *ngIf="_result" class="action-small-modal" [ngClass]="_toggleJustification ? \'show-modal\' : \'\'">\n\n    <div class="bottom-modal">\n\n        <div class="default-modal-header">\n\n            <div class="default-modal-header-text">\n\n                <img src="{{_result.certif_icon}}" alt="">\n\n                <h4>{{_result.translation.justification}}</h4>\n\n                <p *ngIf="currentAbsence" >{{currentAbsence?.date}}</p>\n\n                <span *ngIf="currentAbsence">{{currentAbsence?.nombreseances}} {{_result.translation.seances}}</span>\n\n\n\n            </div>\n\n            <div class="m-close" (click)="toggleJustification()">\n\n                <img src="assets/icon/close.svg" alt="">\n\n            </div>\n\n        </div>\n\n        <div class="d-modal-action">\n\n            <div class="justification-form">\n\n                <form action="" [formGroup]="formGroup">\n\n                    <label for="">{{_result.translation.motif_absence}}</label>\n\n                    <div class="form-group">\n\n                      <textarea name="motif" formControlName="motif" id="" cols="30" rows="10" [(ngModel)]="motifAbsence" class="form-control main-input" placeholder="{{_result.translation.motif_absence}}"></textarea>\n\n                    </div>\n\n                    <ul class="list-unstyled upload-file">\n\n                      <li class="upload-file-container" (tap)="openGallery()" >\n\n                          <img src="assets/icon/photo-upload.svg" alt="">\n\n                          <span>{{_result.translation.ajout_file}}</span>\n\n                      </li>\n\n                  </ul>\n\n                    <div class="btn-main" (click)="checkJustification()" >{{_result.translation.btn_envoyer}}</div>\n\n                </form>\n\n            </div>\n\n        </div>\n\n    </div>\n\n</div>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/absences/absences.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_base64__["a" /* Base64 */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_chooser__["a" /* FileChooser */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_picker_ngx__["a" /* IOSFilePicker */],
            __WEBPACK_IMPORTED_MODULE_8__ionic_native_file_path__["a" /* FilePath */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["App"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_9__angular_forms__["FormBuilder"]])
    ], AbsencesPage);
    return AbsencesPage;
}());

//# sourceMappingURL=absences.js.map

/***/ }),

/***/ 207:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MessagesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__nouveau_message_nouveau_message__ = __webpack_require__(208);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__conversation_conversation__ = __webpack_require__(609);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var MessagesPage = /** @class */ (function () {
    function MessagesPage(navCtrl, navParams, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
    }
    MessagesPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'messages')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) { return console.log("Error :: " + error); });
    };
    MessagesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    MessagesPage.prototype.nouveauMessage = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__nouveau_message_nouveau_message__["a" /* NouveauMessagePage */], { translation: this._result.translation });
    };
    MessagesPage.prototype.detailsMessage = function (result) {
        result.translation = this._result.translation;
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_4__conversation_conversation__["a" /* ConversationPage */], { result: result });
    };
    MessagesPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad Messages & NotesPage');
    };
    MessagesPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-messages',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/messages.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n	<button ion-button menuToggle>\n	<ion-icon name="menu"></ion-icon>\n	</button>\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content>\n	<ion-fab>\n		<button ion-fab class="background-danger fixed-bottom" (click) = "nouveauMessage()">\n			<ion-icon name="create" ></ion-icon>\n		</button>\n	</ion-fab>\n	<div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n        <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n        <h3 [innerHtml]="_result.empty_text"></h3>\n    </div>\n	<section *ngIf="_result?.data?.length > 0" >\n	<ion-grid class="message-item" *ngFor="let result of _result.data" (click) = "detailsMessage(result)">\n		<ion-row class="">\n			<ion-col col-2>\n				<img src="{{result.img}}" class="img-responsive" alt="">\n			</ion-col>\n			<ion-col col-10>\n				<h3 >{{result.at}}</h3>\n				<p><ion-icon [ngClass]="{\'vu\': result.vu_le}" name="ios-done-all"></ion-icon><span>{{result.sujet}}</span></p>\n			</ion-col>\n			<span class="date">{{result.envoye_le}}</span>\n		</ion-row>\n	</ion-grid>\n	</section>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/messages.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */]])
    ], MessagesPage);
    return MessagesPage;
}());

//# sourceMappingURL=messages.js.map

/***/ }),

/***/ 208:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NouveauMessagePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__messages__ = __webpack_require__(207);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_picker_ngx__ = __webpack_require__(79);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_chooser__ = __webpack_require__(80);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_native_base64__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__ionic_native_file_path__ = __webpack_require__(82);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};










var NouveauMessagePage = /** @class */ (function () {
    function NouveauMessagePage(navCtrl, loadingCtrl, apiSerivce, navParams, base64, camera, fileChooser, plt, filePicker, filePath, toastCtrl, formBuilder) {
        this.navCtrl = navCtrl;
        this.loadingCtrl = loadingCtrl;
        this.apiSerivce = apiSerivce;
        this.navParams = navParams;
        this.base64 = base64;
        this.camera = camera;
        this.fileChooser = fileChooser;
        this.plt = plt;
        this.filePicker = filePicker;
        this.filePath = filePath;
        this.toastCtrl = toastCtrl;
        this.formBuilder = formBuilder;
        this.formGroup = this.formBuilder.group({
            sujet: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            message: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
        this.messageRef = navParams.get('messageRef');
        this.sujet = navParams.get('sujet');
        this.translation = navParams.get('translation');
    }
    NouveauMessagePage.prototype.ionViewDidLoad = function () {
        console.log('yes', this.fileMessage);
    };
    NouveauMessagePage.prototype.deleteFile = function () {
        this.fileMessage = null;
    };
    NouveauMessagePage.prototype.send = function () {
        var _this = this;
        var query = "sujet=" + this.sujet +
            "&message=" + this.message +
            "&file=" + JSON.stringify(this.fileMessage) +
            "&eleve_id=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].eleveId +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken;
        if (this.messageRef)
            query += "&ref=" + this.messageRef;
        this.apiSerivce.postData(query, 'nouveau-message').subscribe(function (result) {
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__messages__["a" /* MessagesPage */]);
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    NouveauMessagePage.prototype.chooseFile = function () {
        if (this.plt.is("ios")) {
            this.chooseFileForIos();
        }
        else {
            this.chooseFileForAndroid();
        }
    };
    NouveauMessagePage.prototype.chooseFileForIos = function () {
        var _this = this;
        this.filePicker
            .pickFile()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(uri, false);
        })
            .catch(function (err) { return console.log("Error", err); });
    };
    NouveauMessagePage.prototype.chooseFileForAndroid = function () {
        var _this = this;
        this.fileChooser
            .open()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(uri, false);
        })
            .catch(function (e) {
        });
    };
    NouveauMessagePage.prototype.convertToBase64 = function (imageUrl, isImage) {
        var _this = this;
        this.filePath
            .resolveNativePath(imageUrl)
            .then(function (filePath) {
            _this.base64.encodeFile(filePath).then(function (base64Fichier) {
                var fileName = filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length);
                _this.fileMessage = {
                    name: fileName,
                    extention: filePath.split(".").pop(),
                    base64File: base64Fichier.split(",").pop()
                };
            }, function (err) {
            });
        })
            .catch(function (err) { return console.log(err); });
    };
    NouveauMessagePage.prototype.presentToast = function (message) {
        var toast = this.toastCtrl.create({
            message: message,
            duration: 3000,
            position: 'top'
        });
        toast.onDidDismiss(function () {
        });
        toast.present();
    };
    NouveauMessagePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-nouveau-message',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/nouveau-message/nouveau-message.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title *ngIf="translation">{{translation.nouveau_message}}</ion-title>\n      <ion-title></ion-title>\n    </ion-navbar>\n  </ion-header>\n\n\n<ion-content>\n  <form *ngIf="translation" (ngSubmit)="send()"  [formGroup]="formGroup" >\n    <ion-list>\n        <ion-item class="pl-0">\n          <ion-input type="text" placeholder="{{translation.nouveau_message}}" name="sujet" class="input-height"  [(ngModel)]="sujet" formControlName="sujet" ></ion-input>\n        </ion-item>\n        <ion-item class="pl-0">\n          <ion-textarea placeholder="{{translation.message}}" name="message"  [(ngModel)]="message" formControlName="message"></ion-textarea>\n        </ion-item>\n        <ion-item class="pl-0">\n          <div class="upload-file-container" >\n            <img (tap)="chooseFile()" src="assets/icon/attachment-upload.svg" alt="">\n            <span (tap)="chooseFile()" *ngIf="!fileMessage" >{{translation.joindre}}</span>\n            <span (tap)="chooseFile()" *ngIf="fileMessage" >{{fileMessage.name}}</span>\n            <ion-icon *ngIf="fileMessage" class="delete-btn" (click)="deleteFile()" name="ios-close-circle-outline"></ion-icon>\n          </div>\n        </ion-item>\n        <ion-item>\n            <ion-row>\n                <ion-col class="text-center">\n                    <button  type="submit" class="btn-main btn-block" [disabled]="!formGroup.valid" >{{translation.envoyer}}</button>\n                </ion-col>\n            </ion-row>\n        </ion-item>\n    </ion-list>\n  </form>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/nouveau-message/nouveau-message.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_8__ionic_native_base64__["a" /* Base64 */],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_chooser__["a" /* FileChooser */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_picker_ngx__["a" /* IOSFilePicker */],
            __WEBPACK_IMPORTED_MODULE_9__ionic_native_file_path__["a" /* FilePath */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], NouveauMessagePage);
    return NouveauMessagePage;
}());

//# sourceMappingURL=nouveau-message.js.map

/***/ }),

/***/ 209:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return DemandesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__nouvelle_demande_nouvelle_demande__ = __webpack_require__(210);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__details_demande_details_demande__ = __webpack_require__(613);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var DemandesPage = /** @class */ (function () {
    function DemandesPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.eleve = __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    DemandesPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'demandes')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    DemandesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    DemandesPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    DemandesPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    DemandesPage.prototype.nouvelleDemande = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_2__nouvelle_demande_nouvelle_demande__["a" /* NouvelleDemandePage */]);
    };
    DemandesPage.prototype.detail_demande = function (demande) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__details_demande_details_demande__["a" /* DetailsDemandePage */], { demande: demande });
    };
    DemandesPage.prototype.getColor = function (etat) {
        switch (etat) {
            case 'En Cours': {
                return "en-cours";
            }
            case 'Traitée': {
                return "cloturee";
            }
            case 'Bloquée': {
                return "refusee";
            }
            case 'Nouvelle': {
                return "nouvelle";
            }
            case 'Accepté': {
                return "accepte";
            }
            case 'Livrée': {
                return "livree";
            }
            default: {
                return "en-cours";
            }
        }
    };
    DemandesPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-demandes',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/demandes.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n    <div  class="menu_active_eleve">\n        <div class="img_container">\n            <div class="statut-eleve"></div>\n            <img class="img-responsive" src="{{eleve.img}}" />\n        </div>\n    </div>\n  </ion-navbar>\n</ion-header>\n\n<ion-content padding>\n  <section *ngIf="_result?.data?.length > 0" class="demandes">\n      <div *ngFor="let demande of _result.data" class="demande">\n        <button (click)="detail_demande(demande)">\n            <div class="varow-xs row-pad-sm">\n                  <div class="vacol-xs-4">\n                      <img src="{{demande.icone}}" class="imgicon" alt="">\n                      <h6 class="{{getColor(demande.statut)}}">{{demande.statut}}</h6>\n                  </div>\n                  <div class="vacol-xs-8 text-left">\n                      <div class="border"></div>\n                      <h4>{{demande.type}}</h4>\n                      <span class="date">{{demande.cree}} : {{demande.date}}</span>\n                  </div>\n            </div>\n            <i class="fa fa-angle-right" aria-hidden="true"></i>\n        </button>\n      </div>\n\n  </section>\n   <!-- Real floating action button, fixed. It will not scroll with the content -->\n <ion-fab bottom right >\n    <button ion-fab mini class="add-new" (click) = "nouvelleDemande()"><ion-icon name="md-add"></ion-icon></button> \n  </ion-fab>\n\n  <div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n      <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n      <h3 [innerHtml]="_result.empty_text"></h3>\n  </div>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/demandes.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], DemandesPage);
    return DemandesPage;
}());

//# sourceMappingURL=demandes.js.map

/***/ }),

/***/ 210:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NouvelleDemandePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__demandes__ = __webpack_require__(209);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var NouvelleDemandePage = /** @class */ (function () {
    function NouvelleDemandePage(navCtrl, navParams, apiSerivce, loadingCtrl, formBuilder) {
        var _this = this;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        if (this.navParams.get('nature'))
            this.nature = this.navParams.get('nature');
        if (this.navParams.get('_result'))
            this._result = this.navParams.get('_result');
        if (this.nature) {
            this.formGroup = this.formBuilder.group({
                remarque: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            });
            this.nature.questions.forEach(function (element) {
                var control = new __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormControl"]('question_' + element.id, element.required ? __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required : __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator);
                _this.formGroup.addControl('question_' + element.id, control);
            });
        }
        this.eleve = __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    NouvelleDemandePage_1 = NouvelleDemandePage;
    NouvelleDemandePage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'nouvelle-demande')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    NouvelleDemandePage.prototype.ngOnInit = function () {
        if (!this.nature)
            this.getData();
    };
    NouvelleDemandePage.prototype.ionViewDidLoad = function () {
        if (!this.nature)
            this.presentLoadingCustom();
    };
    NouvelleDemandePage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    NouvelleDemandePage.prototype.post = function () {
    };
    NouvelleDemandePage.prototype.natureDemande = function (nature) {
        this.navCtrl.push(NouvelleDemandePage_1, {
            nature: nature,
            _result: this._result
        });
    };
    NouvelleDemandePage.prototype.send = function () {
        var _this = this;
        var query = "nature=" + JSON.stringify(this.nature) +
            "&eleve_id=" + this.eleve.id +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken;
        this.apiSerivce.postData(query, 'nouvelle-demande').subscribe(function (result) {
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__demandes__["a" /* DemandesPage */]);
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    NouvelleDemandePage = NouvelleDemandePage_1 = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-nouvelle-demande',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/nouvelle-demande/nouvelle-demande.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n    <div  class="menu_active_eleve">\n        <div class="img_container">\n            <div class="statut-eleve"></div>\n            <img class="img-responsive" src="{{eleve.img}}" />\n        </div>\n    </div>\n  </ion-navbar>\n</ion-header>\n\n<ion-content >\n    <div *ngIf="_result" class="user-picture-top-page">\n        <div>\n            <img src="{{eleve.img}}" alt="{{eleve.nomcomplet}}">\n        </div>\n        <div>\n            <h1> {{_result.translation.title}} </h1>\n            <span>{{_result.translation.pour}} {{eleve.nomcomplet}}</span>\n        </div>\n    </div>\n  <section class="demande_types" *ngIf="!nature && _result?.data?.length > 0" >\n      <h3>{{_result.translation.select_type}}</h3>\n    <div class="demande_type" *ngFor="let result of _result.data" (click)="natureDemande(result)">\n      {{result.label}}\n    </div>\n  </section>\n  <section class="form-demande" *ngIf="nature && _result" >\n    <h3>{{_result.translation.repondre_question}}</h3>\n    <form (ngSubmit)="send()" [formGroup]="formGroup" >\n    <div class="form-group" *ngFor="let question of nature.questions" >\n        <label for="">{{question.label}} <b *ngIf="question.required">*</b> </label>\n        <ion-item *ngIf="question.type == \'datepicker\' ">\n            <ion-datetime displayFormat="MM/DD/YYYY" placeholder="{{question.label}}" doneText="Choisir" cancelText="Annuler"  formControlName="question_{{question.id}}" [(ngModel)]="question.reponse"></ion-datetime>\n        </ion-item>\n        <ion-item *ngIf="question.type == \'input\' ">\n            <input type="text" class="form-control" placeholder="{{question.label}}" formControlName="question_{{question.id}}" [(ngModel)]="question.reponse" />\n        </ion-item>\n        <ion-item *ngIf="question.type == \'textarea\' ">\n            <textarea class="form-control" placeholder="{{question.label}}" formControlName="question_{{question.id}}" [(ngModel)]="question.reponse"  ></textarea>\n        </ion-item>\n        <ion-item *ngIf="question.type == \'select\' ">\n            <ion-select okText="Ok" cancelText="Fermer" placeholder="{{question.label}}" formControlName="question_{{question.id}}" [(ngModel)]="question.reponse" >\n                <ion-option *ngFor="let reponse of question.reponses" [value]="reponse.label" >{{reponse.label}}</ion-option>\n            </ion-select>\n        </ion-item>\n    </div>\n    <div class="btn-actions bottom">\n        <button type="submit" class="btn-block btn-main text-uppercase btn-icon" [disabled]="!formGroup.valid" >\n            {{_result.translation.terminer}}\n            <img src="assets/imgs/icon/right-arrow.svg" alt="">\n        </button>\n    </div>\n    </form>\n  </section>\n\n    <div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n        <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n        <h3 [innerHtml]="_result.empty_text"></h3>\n    </div>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/nouvelle-demande/nouvelle-demande.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], NouvelleDemandePage);
    return NouvelleDemandePage;
    var NouvelleDemandePage_1;
}());

//# sourceMappingURL=nouvelle-demande.js.map

/***/ }),

/***/ 221:
/***/ (function(module, exports) {

function webpackEmptyAsyncContext(req) {
	// Here Promise.resolve().then() is used instead of new Promise() to prevent
	// uncatched exception popping up in devtools
	return Promise.resolve().then(function() {
		throw new Error("Cannot find module '" + req + "'.");
	});
}
webpackEmptyAsyncContext.keys = function() { return []; };
webpackEmptyAsyncContext.resolve = webpackEmptyAsyncContext;
module.exports = webpackEmptyAsyncContext;
webpackEmptyAsyncContext.id = 221;

/***/ }),

/***/ 266:
/***/ (function(module, exports) {

function webpackEmptyAsyncContext(req) {
	// Here Promise.resolve().then() is used instead of new Promise() to prevent
	// uncatched exception popping up in devtools
	return Promise.resolve().then(function() {
		throw new Error("Cannot find module '" + req + "'.");
	});
}
webpackEmptyAsyncContext.keys = function() { return []; };
webpackEmptyAsyncContext.resolve = webpackEmptyAsyncContext;
module.exports = webpackEmptyAsyncContext;
webpackEmptyAsyncContext.id = 266;

/***/ }),

/***/ 40:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfPlanningPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__prof_cours_details_prof_cours_details__ = __webpack_require__(597);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__prof_devoirs_prof_devoirs__ = __webpack_require__(598);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__app_app_component__ = __webpack_require__(111);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__compte_prof_compte_prof__ = __webpack_require__(601);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_storage__ = __webpack_require__(58);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__home_home__ = __webpack_require__(112);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__prof_examens_prof_examens__ = __webpack_require__(602);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__prof_messages_prof_messages__ = __webpack_require__(604);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};











var ProfPlanningPage = /** @class */ (function () {
    function ProfPlanningPage(navCtrl, navParams, events, storage, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.events = events;
        this.storage = storage;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.currentJustify = 'justified';
        this.showModal = false;
        this.showModalProfil = false;
        this.doubleAccount = __WEBPACK_IMPORTED_MODULE_5__app_app_component__["a" /* MyApp */].doubleAccount;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    ProfPlanningPage.prototype.ionViewWillEnter = function () {
        this.load();
    };
    ProfPlanningPage.prototype.load = function () {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'prof_cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    ProfPlanningPage.prototype.next = function (next_date) {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
            next_week: next_date
        }, 'prof_cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    ProfPlanningPage.prototype.prev = function (prev_date) {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
            last_week: prev_date
        }, 'prof_cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    ProfPlanningPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ProfPlanningPage.prototype.toggleModal = function () {
        this.showModal = !(this.showModal) ? true : false;
    };
    ProfPlanningPage.prototype.toggleModalProfil = function () {
        this.showModalProfil = !(this.showModalProfil) ? true : false;
    };
    ProfPlanningPage.prototype.coursDetails = function (cours) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__prof_cours_details_prof_cours_details__["a" /* ProfCoursDetailsPage */], {
            cours: cours
        });
    };
    ProfPlanningPage.prototype.devoirsList = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_4__prof_devoirs_prof_devoirs__["a" /* ProfDevoirsPage */], {
            type: 'devoir'
        });
    };
    ProfPlanningPage.prototype.ressourcesList = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_4__prof_devoirs_prof_devoirs__["a" /* ProfDevoirsPage */], {
            type: 'ressource'
        });
    };
    ProfPlanningPage.prototype.moncompte = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_6__compte_prof_compte_prof__["a" /* CompteProfPage */]);
    };
    ProfPlanningPage.prototype.examensList = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_9__prof_examens_prof_examens__["a" /* ProfExamensPage */]);
    };
    ProfPlanningPage.prototype.messagerie = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_10__prof_messages_prof_messages__["a" /* ProfMessagesPage */]);
    };
    ProfPlanningPage.prototype.switchToParent = function () {
        this.events.publish('user:changetoparent');
    };
    ProfPlanningPage.prototype.logout = function () {
        __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve = null;
        __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId = null;
        __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId = null;
        this.storage.remove('boti_auth');
        this.storage.remove('boti_userId');
        this.storage.remove('boti_eleveId');
        this.storage.remove('boti_parentId');
        this.storage.remove('boti_enseignantId');
        this.storage.remove('boti_keyToken');
        this.storage.remove('boti_activeEleve');
        this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_8__home_home__["a" /* HomePage */]);
    };
    ProfPlanningPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-planning',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-planning/prof-planning.html"*/'<ion-content>\n\n        <div *ngIf="results" class="page-container">\n            <div>\n                <div class="main-banner main-bg">\n                    <div class="banner-bottom-left-raduis bg-white text-center">\n                        <h3 *ngIf="results?.translation"><img src="assets/icon/calendar.svg" alt="">{{results.translation.title}}</h3>\n                    </div>\n                    <div class="planning-date">\n                        <div class="date" text-center>\n                            <img src="assets/icon/left-arrow.svg" alt="" class="arrow arrow-left" (click)="prev(results.last_week)">\n                            <img src="assets/icon/right-arrow.svg" alt="" class="arrow arrow-right" (click)="next(results.next_week)">\n                            <h3 class="text-uppercase">{{results.label_periode}}</h3>\n                            <span class="text-uppercase">{{results.label_mois}}</span>\n                        </div>\n                    </div>\n                    <ngb-tabset [justify]="currentJustify">\n                            <ngb-tab  *ngFor="let result of results.seances" title="{{result.label}}">\n                            <ng-template ngbTabContent>\n                                <div class="aucun-cours" *ngIf="result.seances.length == 0">\n                                    <img src="{{results.empty_date.img}}" alt="">\n                                    <h3>{{results.empty_date.label}}</h3>\n                                </div>\n                                <div class="planning-listing">\n                                        <div class="item" *ngFor="let seance of result.seances" (click)="coursDetails(seance)">\n                                            <ion-grid>\n                                                <ion-row align-items-center>\n                                                    <ion-col col-3 text-center>\n                                                        <span>{{seance.start}}</span><br>\n                                                        <span>{{seance.end}}</span>\n                                                    </ion-col>\n                                                    <ion-col col-9>\n                                                        <h5 *ngIf="seance.classe" >{{seance.classe}}</h5>\n                                                        <h6 *ngIf="seance.matiere" >{{seance.matiere}}</h6>\n                                                        <p *ngIf="seance.salle"><img src="assets/icon/salle.svg" alt=""> <span>{{seance.salle}}</span></p>\n                                                        <div *ngIf="seance.examen && results?.translation" class="examen">{{results.translation.examen}}</div>\n                                                    </ion-col>\n                                                </ion-row>\n                                            </ion-grid>\n                                            <img src="assets/icon/arrow-right-gris.svg" alt="" class="show-details">\n                                        </div>\n                                        \n                                    </div>\n                            </ng-template>\n                            </ngb-tab>\n                        </ngb-tabset>\n                </div>\n            </div>\n        </div>\n    </ion-content>\n\n    <div class="action-small-modal" [ngClass]="showModalProfil ? \'show-modal\' : \'\'">\n        <div class="bottom-modal">\n            <div class="default-modal-header">\n                <h4 *ngIf="results?.translation">{{results.translation.action}}</h4>\n                <div class="m-close" (click)="toggleModalProfil()">\n                    <img src="assets/icon/close.svg" alt="">\n                </div>\n            </div>\n            <div class="d-modal-action">\n                <ion-grid>\n                    <ion-row>\n                        <ion-col>\n                            <div class="item text-center" (click)="moncompte()">\n                                <div>\n                                    <img src="assets/icon/edit-profil.svg" alt="">\n                                </div>\n                                <span *ngIf="results?.translation">{{results.translation.profil}}</span>\n                            </div>\n                        </ion-col>\n                        <ion-col *ngIf="doubleAccount">\n                            <div class="item text-center" (click)="switchToParent()">\n                                <div>\n                                    <img src="assets/icon/switch-role.svg" alt="">\n                                </div>\n                                <span *ngIf="results?.translation">{{results.translation.switch}}</span>\n                            </div>\n                        </ion-col>\n                        <ion-col >\n                            <div class="item text-center" (click)="logout()">\n                                <div>\n                                    <img src="assets/icon/log-out.svg" alt="">\n                                </div>\n                                <span *ngIf="results?.translation">{{results.translation.deconnecter}}</span>\n                            </div>\n                        </ion-col>\n                    </ion-row>\n                </ion-grid>\n            </div>\n        </div>\n    </div>\n\n    <div *ngIf="results" class="action-small-modal" [ngClass]="showModal ? \'show-modal\' : \'\'">\n        <div class="bottom-modal">\n            <div class="default-modal-header">\n                <h4>{{results.translation.action_dispo}}</h4>\n                <div class="m-close" (click)="toggleModal()">\n                    <img src="assets/icon/close.svg" alt="">\n                </div>\n            </div>\n            <div class="d-modal-action">\n                <ion-grid>\n                    <ion-row>\n                        <ion-col col-4>\n                            <div class="item text-center" (click)="devoirsList()">\n                                <div>\n                                    <img src="assets/icon/devoir.svg" alt="">\n                                </div>\n                                <span>{{results.translation.devoirs}}</span>\n                            </div>\n                        </ion-col>\n                        <ion-col col-4>\n                            <div class="item text-center" (click)="ressourcesList()">\n                                <div>\n                                    <img src="assets/icon/resources.svg" alt="">\n                                </div>\n                                <span>{{results.translation.ressources}}</span>\n                            </div>\n                        </ion-col>\n                        <ion-col col-4>\n                            <div class="item text-center" (click)="examensList()">\n                                <div>\n                                    <img src="assets/icon/examen.svg" alt="">\n                                </div>\n                                <span>{{results.translation.examens}}</span>\n                            </div>\n                        </ion-col>\n                    </ion-row>\n                </ion-grid>\n            </div>\n        </div>\n    </div>\n    \n    <div *ngIf="results" class="actions">\n        <ul class="list-inline">\n            <li class="list-inline-item">\n                <button class="btn-main btn-circle btn-white" (click)="toggleModalProfil()">\n                    <img src="assets/icon/user.svg" alt="">\n                </button>\n            </li>\n            <li class="list-inline-item">\n                <button class="btn-main btn-circle btn-white" (click)="messagerie()">\n                    <img src="assets/icon/envelope.svg" alt="">\n                </button>\n            </li>\n            <li class="list-inline-item">\n                <button class="btn-main btn-circle" (click)="toggleModal()">\n                    <img src="assets/icon/plus.svg" alt="">\n                </button>\n            </li>\n        </ul>\n    </div>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-planning/prof-planning.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_7__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ProfPlanningPage);
    return ProfPlanningPage;
}());

//# sourceMappingURL=prof-planning.js.map

/***/ }),

/***/ 594:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NotificationsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_moment__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_moment___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_moment__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__pages_nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var NotificationsPage = /** @class */ (function () {
    function NotificationsPage(navCtrl, navParams, alertCtrl, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.alertCtrl = alertCtrl;
        this.apiSerivce = apiSerivce;
        __WEBPACK_IMPORTED_MODULE_3__pages_nouveautes_nouveautes__["a" /* NouveautesPage */].newNotif = false;
        __WEBPACK_IMPORTED_MODULE_2_moment__["locale"]('fr');
    }
    NotificationsPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'notifications')
            .subscribe(function (result) {
            console.log(result);
            _this._result = result;
        }, function (error) { return console.log("Error :: " + error); });
    };
    NotificationsPage.prototype.ngOnInit = function () {
        this.getData();
    };
    NotificationsPage.prototype.notification = function (notification) {
        var confirmAlert = this.alertCtrl.create({
            title: notification.label,
            message: notification.message,
            cssClass: 'notification-alert',
            buttons: [{
                    text: 'Fermer',
                    role: 'cancel'
                }]
        });
        confirmAlert.present();
    };
    NotificationsPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad Messages & NotesPage');
    };
    NotificationsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-notifications',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/notifications/notifications.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n	<button ion-button menuToggle>\n	<ion-icon name="menu"></ion-icon>\n	</button>\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content>\n	<div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n        <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n        <h3 [innerHtml]="_result.empty_text"></h3>\n    </div>\n	<section *ngIf="_result?.data?.length > 0" >\n		<div class="notification-item" *ngFor="let result of _result.data" (click) = "notification(result)">\n			<div>\n				<img src="{{result.img}}" class="img-responsive" alt="">\n			</div>\n			<div>\n				<h3 >{{result.label}}</h3>\n				<p><span class="eleve">{{result.eleve}}</span>{{result.date | amTimeAgo }}</p>\n			</div>\n		</div>\n	</section>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/notifications/notifications.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */]])
    ], NotificationsPage);
    return NotificationsPage;
}());

//# sourceMappingURL=notifications.js.map

/***/ }),

/***/ 595:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ForgotPasswordPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ForgotPasswordPage = /** @class */ (function () {
    function ForgotPasswordPage(navCtrl, navParams, toastCtrl, apiSerivce, loadingCtrl, formBuilder, viewCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        this.viewCtrl = viewCtrl;
        this.formGroup = this.formBuilder.group({
            email: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required]
        });
    }
    ForgotPasswordPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ForgotPasswordPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ForgotPasswordPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId,
            key: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'forgot-password')
            .subscribe(function (result) {
            _this._resultPage = result;
            _this.loading.dismiss();
            _this.loadingBlur = null;
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    ForgotPasswordPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ForgotPasswordPage.prototype.closeModal = function () {
        this.viewCtrl.dismiss();
    };
    ForgotPasswordPage.prototype.valider = function () {
        var _this = this;
        this.apiSerivce.postData("email=" + this.email, 'forgot-password').subscribe(function (result) {
            _this._result = result;
            if (_this._result.error === false) {
                _this.sendNotification(_this._result.msg, 'toast-success');
                _this.viewCtrl.dismiss();
            }
            else {
                _this.sendNotification(_this._result.msg, 'toast-error');
                _this.email = null;
            }
        }, function (error) {
        });
    };
    ForgotPasswordPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    ForgotPasswordPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-forgot-password',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/forgot-password/forgot-password.html"*/'<ion-content padding>\n\n    <button ion-button (click)="closeModal()" [ngClass]="\'close-modal\'">\n\n        <ion-icon name="ios-close-outline"></ion-icon>\n\n    </button>\n\n    <div *ngIf="_resultPage" class="vertical-center">\n\n        <div class="container">\n\n            <div class="banner text-center">\n\n                <img src="assets/imgs/icon/forgot.svg" alt="">\n\n                <h1>{{_resultPage.translation.title}}</h1>\n\n                <p>{{_resultPage.translation.intro}}</p>\n\n                <form (ngSubmit)="valider()" [formGroup]="formGroup" >\n\n                  <div class="form-group">\n\n                    <input type="email" name="email" formControlName="email" [(ngModel)]="email" required placeholder="{{_resultPage.translation.email}}">\n\n                  </div>\n\n                  <button type="submit" class="btn-main btn-block text-uppercase" [disabled]="!formGroup.valid" >{{_resultPage.translation.valider}}</button>\n\n                </form>\n\n            </div>\n\n        </div>\n\n    </div>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/forgot-password/forgot-password.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ViewController"]])
    ], ForgotPasswordPage);
    return ForgotPasswordPage;
}());

//# sourceMappingURL=forgot-password.js.map

/***/ }),

/***/ 596:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ConnexionPhoneNumberPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ConnexionPhoneNumberPage = /** @class */ (function () {
    function ConnexionPhoneNumberPage(navCtrl, navParams, toastCtrl, apiSerivce, loadingCtrl, formBuilder, viewCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        this.viewCtrl = viewCtrl;
        this.formGroup = this.formBuilder.group({
            tel: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            code: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator]
        });
        this.stat = '1';
    }
    ConnexionPhoneNumberPage.prototype.closeModal = function () {
        this.viewCtrl.dismiss();
    };
    ConnexionPhoneNumberPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({}, 'connexion_phonenumber')
            .subscribe(function (result) {
            _this._resultPage = result;
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ConnexionPhoneNumberPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ConnexionPhoneNumberPage.prototype.valider = function () {
        /*
        this.apiSerivce.postData("tel=" + this.tel, 'connexion_phonenumber').subscribe(
            result => {
          
              this._result = result;
    
              if(this._result.error === false)
              {
    
                this.sendNotification(this._result.msg, 'toast-success');
                this.viewCtrl.dismiss();
    
                this.stat = '2';
    
              }
              else
              {
                this.sendNotification(this._result.msg, 'toast-error');
                this.tel = null;
    
                
                this.stat = '1';
              }
    
            },
            error =>  {
    
            }
        );
        */
        this.sendNotification('Votre code secret...', 'toast-success');
        this.stat = '2';
    };
    ConnexionPhoneNumberPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    ConnexionPhoneNumberPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-connexion-phonenumber',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/connexion-phonenumber/connexion-phonenumber.html"*/'<ion-content padding>\n\n    <button ion-button (click)="closeModal()" [ngClass]="\'close-modal\'">\n\n        <ion-icon name="ios-close-outline"></ion-icon>\n\n    </button>\n\n    <div class="vertical-center">\n\n        <div class="container">\n\n            <div class="banner text-center" *ngIf="stat == \'1\' ">\n\n                <img *ngIf="_resultPage?.data?.form1_icone" src="{{_resultPage.data.form1_icone}}" alt="">\n\n                <h1 *ngIf="_resultPage?.data?.form1_title">{{_resultPage.data.form1_title}}</h1>\n\n                <p *ngIf="_resultPage?.data?.form1_subtitle">{{_resultPage.data.form1_subtitle}}</p>\n\n                <form (ngSubmit)="valider()" [formGroup]="formGroup" >\n\n                  <div class="form-group">\n\n                    <input type="number" name="tel" formControlName="tel" [(ngModel)]="tel" required placeholder="Votre numéro de téléphone">\n\n                  </div>\n\n                  <button type="submit" class="btn-main btn-block text-uppercase" [disabled]="!formGroup.valid" >Valider</button>\n\n                </form>\n\n            </div>\n\n            <div class="banner text-center" *ngIf="stat == \'2\' ">\n\n                <img *ngIf="_resultPage?.data?.form1_icone" src="{{_resultPage.data.form2_icone}}" alt="">\n\n                <h1 *ngIf="_resultPage?.data?.form2_title">{{_resultPage.data.form2_title}}</h1>\n\n                <p *ngIf="_resultPage?.data?.form2_subtitle">{{_resultPage.data.form2_subtitle}}</p>\n\n                <form (ngSubmit)="valider()" [formGroup]="formGroup" >\n\n                  <div class="form-group">\n\n                    <input type="number" name="code" formControlName="code" [(ngModel)]="code" required placeholder="Votre code secret">\n\n                  </div>\n\n                  <button type="submit" class="btn-main btn-block text-uppercase" [disabled]="!formGroup.valid" >Se connecter</button>\n\n                </form>\n\n            </div>\n\n        </div>\n\n    </div>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/connexion-phonenumber/connexion-phonenumber.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ViewController"]])
    ], ConnexionPhoneNumberPage);
    return ConnexionPhoneNumberPage;
}());

//# sourceMappingURL=connexion-phonenumber.js.map

/***/ }),

/***/ 597:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfCoursDetailsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var ProfCoursDetailsPage = /** @class */ (function () {
    function ProfCoursDetailsPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this._toggleRetard = false;
        this._toggleDiscipline = false;
        this.showPopUp = false;
        this.cours = navParams.get('cours');
        console.log(this.cours);
    }
    ProfCoursDetailsPage.prototype.togglesPopUp = function () {
        this._toggleRetard = false;
        this.showPopUp = !(this.showPopUp) ? true : false;
    };
    ProfCoursDetailsPage.prototype.toggleRetard = function (eleve) {
        if (eleve && eleve.absent)
            return false;
        if (!this._toggleRetard) {
            this.currentEleve = eleve;
            this.retardMinutes = eleve.retard ? eleve.retard : 10;
        }
        else {
            this.currentEleve = null;
        }
        this._toggleRetard = !this._toggleRetard;
    };
    ProfCoursDetailsPage.prototype.toggleDiscipline = function (eleve) {
        if (eleve && eleve.absent)
            return false;
        if (!this._toggleDiscipline) {
            this.currentEleve = eleve;
            if (this.currentEleve.action.type) {
                this.typeDiscipline = this.currentEleve.action.type;
                this.typeDisciplineId = this.currentEleve.action.type.id;
                this.commentaireDiscipline = this.currentEleve.action.commentaire;
            }
            else {
                this.typeDiscipline = null;
                this.typeDisciplineId = null;
                this.commentaireDiscipline = null;
            }
        }
        else {
            this.currentEleve = null;
        }
        this._toggleDiscipline = !this._toggleDiscipline;
        console.log(this.typeDiscipline);
    };
    ProfCoursDetailsPage.prototype.disciplineChange = function (type) {
        var DisciplineType = this._result.data.types.find(function (x) { return x.id === type.target.value; });
        if (DisciplineType !== undefined) {
            // You can access Id or Name of the found server object.
            console.log(DisciplineType);
            this.typeDiscipline = DisciplineType;
        }
    };
    ProfCoursDetailsPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            cours_id: this.cours.id,
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'prof_cours_details')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ProfCoursDetailsPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfCoursDetailsPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ProfCoursDetailsPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ProfCoursDetailsPage.prototype.submit = function (eleve) {
        var _this = this;
        this.apiSerivce.postData("enseignant_id=" + __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId + "&eleve=" + JSON.stringify(eleve), 'prof_cours_details').subscribe(function (result) {
        }, function (error) {
            _this.loading.dismiss();
        });
    };
    ProfCoursDetailsPage.prototype.checkAbsence = function (eleve) {
        eleve.absent = !eleve.absent;
        this.submit(eleve);
    };
    ProfCoursDetailsPage.prototype.checkRetard = function () {
        this._toggleRetard = false;
        this.currentEleve.retard = this.retardMinutes;
        this.submit(this.currentEleve);
        this.currentEleve = null;
        this.retardMinutes = null;
    };
    ProfCoursDetailsPage.prototype.checkDiscipline = function () {
        this._toggleDiscipline = false;
        this.currentEleve.action.type = this.typeDiscipline;
        this.currentEleve.action.commentaire = this.commentaireDiscipline;
        this.submit(this.currentEleve);
        this.currentEleve = null;
        this.commentaireDiscipline = null;
        this.typeDiscipline = null;
        this.typeDisciplineId = null;
    };
    ProfCoursDetailsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-cours-details',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-cours-details/prof-cours-details.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n        <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n        </button>\n    </ion-navbar>\n</ion-header>\n    \n<ion-content>\n    <div class="page-container" *ngIf="_result">\n        <div class="main-banner banner-bottom-left-raduis main-bg">\n            <div class="">\n                <h6 class="text-uppercase text-center">{{_result.data.classe}}</h6>\n                <h3>{{_result.translation.title}}</h3>\n            </div>\n        </div>\n        <div class="seance-date banner-bottom-left-raduis">\n            <h4>{{_result.data.date}}</h4>\n            <p>{{_result.data.creneau}}</p>\n        </div>\n        <div class="etudiant-list">\n            <div class="item" *ngFor="let eleve of _result.data?.eleves">\n                <ion-grid>\n                    <ion-row align-items-center>\n                        <ion-col>\n                            <div class="image">\n                                <img src="{{eleve.photo}}" class="img-circle" alt="">\n                            </div>\n                            <span>{{eleve.nom}}</span>\n                        </ion-col>\n                        <ion-col>\n                            <div class="item-action text-center">\n                                <ul class="list-inline">\n                                    <li class="list-inline-item" [ngClass] = "{ active : eleve.absent }" (click)="checkAbsence(eleve)">\n                                        <img src="{{_result.icons.absence}}" class="img-circle" alt="">\n                                    </li>\n                                    <li class="list-inline-item" [ngClass] = "{ active : eleve.retard > 0 }"  (click)="toggleRetard(eleve)">\n                                        <img src="{{_result.icons.retard}}" class="img-circle" alt="">\n                                    </li>\n                                    <li class="list-inline-item"  [ngClass] = "{ active : eleve.action.type }"  (click)="toggleDiscipline(eleve)">\n                                        <img *ngIf="!eleve.action.type" src="{{_result.icons.discipline}}" class="img-circle" alt="">\n                                        <img *ngIf="eleve.action.type && eleve.action.type.value < 0" src="{{_result.icons.blame}}" class="img-circle" alt="">\n                                        <img *ngIf="eleve.action.type && eleve.action.type.value > 0" src="{{_result.icons.smile}}" class="img-circle" alt="">\n                                    </li>\n                                </ul>\n                            </div>\n                        </ion-col>\n                    </ion-row>\n                </ion-grid>\n            </div>\n            <!-- end item -->\n        </div>\n    </div>\n</ion-content>\n<div *ngIf="_result" class="main-modal" [ngClass]="_toggleDiscipline ? \'show-main-modal\' : \'\'">\n    <div class="main-modal-center">\n        <div class="main-modal-header">\n            <div class="main-modal-header-text">\n                <img src="{{_result?.icons?.discipline}}" alt="">\n                <h4>{{_result.translation.disciplines}}</h4>\n            </div>\n            <img src="assets/icon/close.svg" class="main-close" (click)="toggleDiscipline()" alt="">\n        </div>\n        <div class="main-modal-body">\n            <form action="">\n                \n                <div class="discipline-form">\n                    <form action="">\n                        <label for="">{{_result.translation.discipline}} <span class="type-discipline-value" *ngIf="typeDiscipline" [ngStyle]="{\'background-color\': typeDiscipline.color}">{{typeDiscipline.value}}</span></label>\n                        <div class="form-group">\n                            <select name="type" id="" [(ngModel)]="typeDisciplineId" (change)="disciplineChange($event)" class="form-control main-input">\n                                <optgroup  *ngFor="let nature of _result?.data?.natures" label="{{nature.label}}">\n                                    <option *ngFor="let type of nature.types" value="{{type.id}}">{{type.label}}</option>\n                                </optgroup>\n                            </select>\n                        </div>\n                        <div class="form-group">\n                            <textarea name="commentaire" [(ngModel)]="commentaireDiscipline" cols="30" rows="10" class="form-control main-input" placeholder="{{_result.translation.commentaire}}"></textarea>\n                        </div>\n                        <div class="btn-main" (click)="checkDiscipline()" >{{_result.translation.enregistrer}}</div>\n                    </form>\n                </div>\n            </form>\n        </div>\n    </div>\n</div>\n<div *ngIf="_result" class="action-small-modal" [ngClass]="_toggleRetard ? \'show-modal\' : \'\'">\n    <div class="bottom-modal">\n        <div class="default-modal-header">\n            <div class="default-modal-header-text">\n                <img src="assets/icon/clock.svg" alt="">\n                <h4>{{_result.translation.retard}}</h4>\n                <p *ngIf="currentEleve" >{{currentEleve?.nom}} {{_result?.data?.creneau}}</p>\n                <span>{{_result?.data?.date}}</span>\n\n            </div>\n            <div class="m-close" (click)="toggleRetard()">\n                <img src="assets/icon/close.svg" alt="">\n            </div>\n        </div>\n        <div class="d-modal-action">\n            <div class="discipline-form">\n                <form action="">\n                    <label for="">{{_result.translation.minutes_retard}}</label>\n                    <div class="form-group">\n                        <select name="retard" id="" [(ngModel)]="retardMinutes" class="form-control main-input">\n                            <option *ngFor="let minute of _result?.data?.minutes" value="{{minute}}">{{minute}}</option>\n                        </select>\n                    </div>\n                    <div class="btn-main" (click)="checkRetard()" >{{_result.translation.enregistrer}}</div>\n                </form>\n            </div>\n        </div>\n    </div>\n</div>\n<!-- end item -->\n<div class="action-bottom" *ngIf="_result?.legende">\n    <div class="row align-items-center">\n        <h6>{{_result.legende.title}}</h6>\n        <ul class="list-inline">\n            <li *ngFor="let element of _result.legende.elements">\n                <img src="{{element.icon}}" alt="">\n                <span>{{element.title}}</span>\n            </li>\n        </ul>\n    </div>\n</div>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-cours-details/prof-cours-details.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ProfCoursDetailsPage);
    return ProfCoursDetailsPage;
}());

//# sourceMappingURL=prof-cours-details.js.map

/***/ }),

/***/ 598:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfDevoirsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__prof_devoir_form_prof_devoir_form__ = __webpack_require__(599);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__prof_devoir_details_prof_devoir_details__ = __webpack_require__(600);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var ProfDevoirsPage = /** @class */ (function () {
    function ProfDevoirsPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.type = this.navParams.get('type');
    }
    ProfDevoirsPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
            type: this.type
        }, 'prof_contents')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ProfDevoirsPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfDevoirsPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ProfDevoirsPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ProfDevoirsPage.prototype.ajouterDevoir = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__prof_devoir_form_prof_devoir_form__["a" /* ProfDevoirFormPage */], {
            type: this.type
        });
    };
    ProfDevoirsPage.prototype.devoirDetails = function (result) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_4__prof_devoir_details_prof_devoir_details__["a" /* ProfDevoirDetailsPage */], {
            result: result
        });
    };
    ProfDevoirsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-devoirs',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoirs/prof-devoirs.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n        <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n        </button>\n    </ion-navbar>\n</ion-header>\n<ion-content>\n  <div class="page-container bg-gris">\n      <div class="bg-white">\n            <div class="d-main-banner banner-bottom-left-raduis main-bg px-5">\n              <div class="">\n                  <h6 *ngIf="_result" class="text-uppercase text-center">{{_result.groupe}}</h6>\n                  <h3 *ngIf="_result">{{_result.title}}</h3>\n              </div>\n            </div>\n            <div class="header-banner-raduis text-center" hidden>\n                <p>Filtrer par classe</p>\n            </div>\n      </div>\n     \n      <div class="to-do-lists" *ngIf="_result">\n          <div class="item" *ngFor="let devoir of _result.data" (click)="devoirDetails(devoir)">\n              <div class="image">\n                  <img src="{{devoir.image}}" class="img-circle" alt="">\n              </div>\n              <div class="item-description">\n                  <span *ngIf="devoir.classes" >{{devoir.classes}}</span>\n                  <h3>{{devoir.title}}</h3>\n                  <span>{{devoir.date}}</span>\n                  <span><ion-icon name="eye"></ion-icon> {{devoir.vues}}</span>\n                  <ion-icon name="arrow-forward" class="arrow-right"></ion-icon>\n              </div>\n          </div>\n      </div>\n  </div>\n</ion-content>\n<div class="actions">\n    <ul class="list-inline">\n        <li class="list-inline-item">\n            <button class="btn-main btn-circle" (click)="ajouterDevoir()">\n                <img src="assets/icon/plus.svg" alt="">\n            </button>\n        </li>\n    </ul>\n</div>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoirs/prof-devoirs.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ProfDevoirsPage);
    return ProfDevoirsPage;
}());

//# sourceMappingURL=prof-devoirs.js.map

/***/ }),

/***/ 599:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfDevoirFormPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__prof_planning_prof_planning__ = __webpack_require__(40);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_picker_ngx__ = __webpack_require__(79);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_chooser__ = __webpack_require__(80);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_native_base64__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__ionic_native_file_path__ = __webpack_require__(82);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};










var ProfDevoirFormPage = /** @class */ (function () {
    function ProfDevoirFormPage(navCtrl, navParams, apiSerivce, alertCtrl, base64, camera, fileChooser, plt, filePicker, filePath, toastCtrl, loadingCtrl, _ngZone, formBuilder) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.alertCtrl = alertCtrl;
        this.base64 = base64;
        this.camera = camera;
        this.fileChooser = fileChooser;
        this.plt = plt;
        this.filePicker = filePicker;
        this.filePath = filePath;
        this.toastCtrl = toastCtrl;
        this.loadingCtrl = loadingCtrl;
        this._ngZone = _ngZone;
        this.formBuilder = formBuilder;
        this.content = {};
        if (this.navParams.get('content')) {
            this.content = this.navParams.get('content');
        }
        else {
            this.content.id = null;
            this.content.type = this.navParams.get('type');
        }
        this.formGroup = this.formBuilder.group({
            titre: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            classes: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            matiere: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            image: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            file: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            description: ['', (this.content.type == 'devoir') ? __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required : __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            date: ['', (this.content.type == 'devoir') ? __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required : __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            id: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator]
        });
    }
    ProfDevoirFormPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ProfDevoirFormPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 3000000
        });
        this.loading.present();
    };
    ProfDevoirFormPage.prototype.getData = function () {
        var _this = this;
        var params = {
            op: 'prof-content',
            enseignant_id: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].enseignantId,
            type: this.content.type,
            add: this.content.id ? false : true
        };
        this.apiSerivce.getData(params, 'prof_content')
            .subscribe(function (result) {
            _this._resultPage = result;
            _this.loading.dismiss();
        }, function (error) {
            _this.loading.dismiss();
            console.log("Error :: " + error);
        });
    };
    ProfDevoirFormPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfDevoirFormPage.prototype.showToast = function (message) {
        var toast = this.toastCtrl.create({
            message: message,
            duration: 2000,
            position: 'middle'
        });
        toast.present(toast);
    };
    ProfDevoirFormPage.prototype.submit = function () {
        var _this = this;
        this.presentLoadingCustom();
        this.content.file = this.fileArray;
        this.content.image = this.imageArr;
        this.apiSerivce.postData("enseignant_id=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].enseignantId + "&image=" + this.image + "&content=" + JSON.stringify(this.content), 'prof_content').subscribe(function (result) {
            _this.loading.dismiss();
            var res = result;
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
            var alert = _this.alertCtrl.create({
                cssClass: 'success_alert_boti',
                title: res.title,
                message: res.message,
                buttons: [{
                        text: 'Fermer',
                        role: 'cancel',
                        handler: function () {
                        }
                    }]
            });
            alert.present();
        }, function (error) {
            _this.loading.dismiss();
        });
    };
    ProfDevoirFormPage.prototype.openGallery = function () {
        var _this = this;
        var options = {
            sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
            destinationType: this.camera.DestinationType.FILE_URI
        };
        this.camera
            .getPicture(options)
            .then(function (imageData) {
            _this.presentToast("Image chosen successfully");
            _this.convertToBase64(imageData, true);
        })
            .catch(function (e) {
        });
    };
    ProfDevoirFormPage.prototype.chooseFile = function () {
        if (this.plt.is("ios")) {
            this.chooseFileForIos();
        }
        else {
            this.chooseFileForAndroid();
        }
    };
    ProfDevoirFormPage.prototype.chooseFileForIos = function () {
        var _this = this;
        this.filePicker
            .pickFile()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(uri, false);
        })
            .catch(function (err) { return console.log("Error", err); });
    };
    ProfDevoirFormPage.prototype.chooseFileForAndroid = function () {
        var _this = this;
        this.fileChooser
            .open()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(uri, false);
        })
            .catch(function (e) {
        });
    };
    ProfDevoirFormPage.prototype.convertToBase64 = function (imageUrl, isImage) {
        var _this = this;
        this.filePath
            .resolveNativePath(imageUrl)
            .then(function (filePath) {
            _this.base64.encodeFile(filePath).then(function (base64Fichier) {
                if (isImage == false) {
                    _this.fileArray = {
                        extention: filePath.split(".").pop(),
                        base64File: base64Fichier.split(",").pop() //split(",").pop() depends on the requirement, if back end API is able to extract
                        //the file mime type then you can do this, if back end expects  UI update the Mime type
                        //  then don't use split(",").pop()
                    };
                }
                else {
                    _this.imageArr = {
                        extention: filePath.split(".").pop(),
                        base64Img: base64Fichier.split(",").pop() //same comment for image follows here.
                    };
                }
            }, function (err) {
            });
        })
            .catch(function (err) { return console.log(err); });
    };
    ProfDevoirFormPage.prototype.presentToast = function (message) {
        var toast = this.toastCtrl.create({
            message: message,
            duration: 3000,
            position: 'top'
        });
        toast.onDidDismiss(function () {
        });
        toast.present();
    };
    ProfDevoirFormPage.prototype.uploadImageAndFile = function () {
    };
    ProfDevoirFormPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-devoir-form',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoir-form/prof-devoir-form.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n        <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n        </button>\n        <ion-title *ngIf="_resultPage">{{_resultPage.data.title}}</ion-title>\n    </ion-navbar>\n</ion-header>\n\n<ion-content>\n    <div class="form-header" hidden>\n        <h3><img src="assets/icon/edit-black.svg" alt=""></h3>\n        <img src="assets/icon/close.svg"  menuToggle class="close-form" alt="">\n    </div>\n\n    <form *ngIf="_resultPage" [formGroup]="formGroup">\n      <div class="form-body">\n            <div class="form-group">\n                <ion-item>\n                    <ion-label>{{_resultPage.translation.classes}}</ion-label>\n                    <ion-select multiple="true" *ngIf="_resultPage?.data?.classes" [(ngModel)]="content.classes" name="classes" formControlName="classes" okText="{{_resultPage.translation.choisir}}" cancelText="{{_resultPage.translation.fermer}}" placeholder="{{_resultPage.translation.classes}}">\n                        <ion-option *ngFor="let classe of _resultPage.data.classes" [value]="classe.id" >{{classe.label}}</ion-option>\n                    </ion-select>\n                </ion-item>\n            </div>\n            <div class="form-group">\n                <ion-item>\n                    <ion-label>{{_resultPage.translation.matiere}}</ion-label>\n                    <ion-select *ngIf="_resultPage?.data?.matieres" [(ngModel)]="content.matiere" name="matiere" formControlName="matiere" okText="{{_resultPage.translation.choisir}}" cancelText="{{_resultPage.translation.fermer}}" placeholder="{{_resultPage.translation.matiere}}">\n                        <ion-option *ngFor="let matiere of _resultPage.data.matieres" [value]="matiere.id" >{{matiere.label}}</ion-option>\n                    </ion-select>\n                </ion-item>\n            </div>\n          <div class="form-group">\n              <input type="text" [(ngModel)]="content.titre"  name="titre" formControlName="titre" class="form-control main-input" placeholder="{{_resultPage.translation.titre}}">\n          </div>\n          <div class="form-group" *ngIf="content && content.type == \'devoir\'">\n              <textarea  [(ngModel)]="content.description"  name="description" formControlName="description" class="form-control main-input" placeholder="{{_resultPage.translation.description}}"></textarea>\n          </div>\n            <div class="form-group"  *ngIf="content && content.type == \'devoir\'">\n                <label>{{_resultPage.translation.date}}</label>\n                <ion-calendar [type]="\'string\'" [(ngModel)]="content.date"  name="date" formControlName="date"  [format]="\'YYYY-MM-DD\'"> </ion-calendar>\n            </div>\n          <ul class="list-unstyled upload-file">\n              <li class="upload-file-container" (tap)="openGallery()"  *ngIf="content && content.type == \'devoir\'">\n                  <img src="assets/icon/photo-upload.svg" alt="">\n                  <span>{{_resultPage.translation.photo}}</span>\n              </li>\n              <li class="upload-file-container" (tap)="chooseFile()">\n                  <img src="assets/icon/attachment-upload.svg" alt="">\n                  <span>{{_resultPage.translation.fichier}}</span>\n              </li>\n          </ul>\n      </div>\n    </form>\n\n    <ion-fab bottom >\n        <button [disabled]="!formGroup.valid" (click)="submit()" class="btn-main btn-block text-center fixed-bottom">{{_resultPage.translation.enregistrer}}</button>\n    </ion-fab>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoir-form/prof-devoir-form.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_8__ionic_native_base64__["a" /* Base64 */],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_chooser__["a" /* FileChooser */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_picker_ngx__["a" /* IOSFilePicker */],
            __WEBPACK_IMPORTED_MODULE_9__ionic_native_file_path__["a" /* FilePath */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_0__angular_core__["NgZone"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], ProfDevoirFormPage);
    return ProfDevoirFormPage;
}());

//# sourceMappingURL=prof-devoir-form.js.map

/***/ }),

/***/ 60:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NouveautesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_moment__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_moment___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_moment__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_common__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_fcm_fcm__ = __webpack_require__(113);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__pages_post_post__ = __webpack_require__(203);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__pages_notifications_notifications__ = __webpack_require__(594);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};








var NouveautesPage = /** @class */ (function () {
    function NouveautesPage(navCtrl, navParams, events, fcm, apiSerivce, datePipe, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.events = events;
        this.fcm = fcm;
        this.apiSerivce = apiSerivce;
        this.datePipe = datePipe;
        this.loadingCtrl = loadingCtrl;
        __WEBPACK_IMPORTED_MODULE_2_moment__["locale"]('fr');
        this.eleve = __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    NouveautesPage_1 = NouveautesPage;
    NouveautesPage.prototype.getData = function () {
        var _this = this;
        console.log(__WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */]);
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'nouveautes')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
        setTimeout(function () {
            _this.events.publish('network:type');
        }, 10000);
    };
    NouveautesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    NouveautesPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    NouveautesPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    NouveautesPage.prototype.post = function (post) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_6__pages_post_post__["a" /* PostPage */], {
            result: post
        });
    };
    Object.defineProperty(NouveautesPage.prototype, "newNotif", {
        get: function () {
            return NouveautesPage_1.newNotif;
        },
        enumerable: true,
        configurable: true
    });
    NouveautesPage.prototype.notifications = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_7__pages_notifications_notifications__["a" /* NotificationsPage */]);
    };
    NouveautesPage = NouveautesPage_1 = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-nouveautes',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/nouveautes/nouveautes.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n    <div  class="menu_notif" (click)="notifications()">\n        <div class="img_container">\n            <div *ngIf="newNotif" class="statut-notif"></div>\n            <img class="img-responsive" src="assets/imgs/notification.svg" />\n        </div>\n    </div>\n    <div  class="menu_active_eleve">\n        <div class="img_container">\n            <div class="statut-eleve"></div>\n            <img class="img-responsive" src="{{eleve.img}}" />\n        </div>\n    </div>\n  </ion-navbar>\n</ion-header>\n\n<ion-content padding>\n\n  <section *ngIf="_result?.data?.length > 0" >\n  <div class="card radius shadowDepth1 "  *ngFor="let result of _result.data" (click)="post(result)">\n    <div  [attr.data-text]="!result.image?result.categorie:\'\'" class="card__image border-tlr-radius">\n      <img  src="{{result.image}}" alt="" class="border-tlr-radius">\n    </div>\n  \n    <div class="card__content card__padding">\n      <div class="card__share">\n        <a id="share" class="share-toggle share-icon" href="#"></a>\n      </div>\n      <div class="card__meta">\n        <time>{{ result.date | amCalendar }}</time>\n        <span class="categorie">{{result.categorie}}</span>\n      </div>\n  \n      <article class="card__article">\n        <h2>{{result.title}}</h2>\n        <p [innerHtml]="result.intro"></p>\n      </article>\n    </div>\n  \n    <div *ngIf="result.user" class="card__action">\n      <div class="card__author">\n        <img src="{{result.user.photo}}" alt="user">\n        <div class="card__author-content">\n          Par <a href="#">{{result.user.nom}}</a>\n        </div>\n      </div>\n    </div>\n  </div>\n  </section>\n\n    <div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n        <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n        <h3 [innerHtml]="_result.empty_text"></h3>\n    </div>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/nouveautes/nouveautes.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_4__providers_fcm_fcm__["a" /* FcmProvider */],
            __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_3__angular_common__["DatePipe"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], NouveautesPage);
    return NouveautesPage;
    var NouveautesPage_1;
}());

//# sourceMappingURL=nouveautes.js.map

/***/ }),

/***/ 600:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfDevoirDetailsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_moment__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_moment___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_moment__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__angular_common__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__ = __webpack_require__(108);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};









var ProfDevoirDetailsPage = /** @class */ (function () {
    function ProfDevoirDetailsPage(navCtrl, navParams, iab, datePipe, apiSerivce, sanitizer, file, transfer, platform) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.iab = iab;
        this.datePipe = datePipe;
        this.apiSerivce = apiSerivce;
        this.sanitizer = sanitizer;
        this.file = file;
        this.transfer = transfer;
        this.platform = platform;
        this.fileTransfer = this.transfer.create();
        __WEBPACK_IMPORTED_MODULE_3_moment__["locale"]('fr');
        this.eleve = __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].activeEleve;
        this.result = navParams.get('result');
        this.description = this.getInnerHTMLValue();
    }
    ProfDevoirDetailsPage.prototype.getData = function () {
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */].keyToken,
            post: this.result.id
        }, 'post_view')
            .subscribe(function (result) {
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ProfDevoirDetailsPage.prototype.ngOnInit = function () {
        //this.getData();
    };
    ProfDevoirDetailsPage.prototype.getInnerHTMLValue = function () {
        return this.sanitizer.bypassSecurityTrustHtml(this.result.description);
    };
    ProfDevoirDetailsPage.prototype.ionViewDidLoad = function () {
    };
    ProfDevoirDetailsPage.prototype.downloadFile = function (file) {
        var browser = this.iab.create(file.link);
    };
    ProfDevoirDetailsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-devoir-details',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoir-details/prof-devoir-details.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title>{{result.categorie}}</ion-title>\n    </ion-navbar>\n</ion-header>\n\n\n<ion-content padding>\n\n    <div class="card-post" >\n\n        <div class="card__meta">\n            <span class="categorie">{{result.categorie}}</span>\n            <time>{{ result.date | amTimeAgo }}</time>\n            <h2>{{result.title}}</h2>\n        </div>\n\n        <div *ngIf="result.user" class="card__action">\n            <div class="card__author">\n              <img src="{{result.user.photo}}" alt="user">\n              <div class="card__author-content">\n                Par <a href="#">{{result.user.nom}}</a>\n              </div>\n            </div>\n        </div>\n        \n        <div *ngIf="result.image" class="card__image border-tlr-radius">\n          <img  src="{{result.image}}" alt="" class="border-tlr-radius">\n        </div>\n\n\n        <article class="card__article">\n            <p [innerHtml]="description"></p>\n        </article>\n\n        <div *ngIf="result.file" class="card__file">\n            <button (click)="downloadFile(result.file)" class="btn btn-main" >{{result.file.text}}</button>\n        </div>\n      \n      </div>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-devoir-details/prof-devoir-details.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__["a" /* InAppBrowser */],
            __WEBPACK_IMPORTED_MODULE_4__angular_common__["DatePipe"],
            __WEBPACK_IMPORTED_MODULE_8__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__["DomSanitizer"],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_file_transfer__["a" /* FileTransfer */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"]])
    ], ProfDevoirDetailsPage);
    return ProfDevoirDetailsPage;
}());

//# sourceMappingURL=prof-devoir-details.js.map

/***/ }),

/***/ 601:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return CompteProfPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__app_app_component__ = __webpack_require__(111);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};







var CompteProfPage = /** @class */ (function () {
    function CompteProfPage(navCtrl, camera, navParams, toastCtrl, apiSerivce, loadingCtrl, formBuilder) {
        this.navCtrl = navCtrl;
        this.camera = camera;
        this.navParams = navParams;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        this.prof = __WEBPACK_IMPORTED_MODULE_5__app_app_component__["a" /* MyApp */].enseignant;
        console.log(this.prof);
        this.formGroup = this.formBuilder.group({
            sexe: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            nom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            prenom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            tel: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            email: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
        if (this.prof.nom)
            this.nom = this.prof.nom;
        if (this.prof.prenom)
            this.prenom = this.prof.prenom;
        if (this.prof.email)
            this.email = this.prof.email;
        if (this.prof.tel)
            this.tel = this.prof.tel;
        if (this.prof.img)
            this.photo = this.prof.img;
        if (this.prof.sexe) {
            console.log(this.prof.sexe);
            this.sexe = this.prof.sexe;
        }
    }
    CompteProfPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    CompteProfPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'compte-prof')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    CompteProfPage.prototype.ngOnInit = function () {
        this.getData();
    };
    CompteProfPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    CompteProfPage.prototype.cropImage = function () {
        var _this = this;
        var options = {
            quality: 70,
            destinationType: this.camera.DestinationType.DATA_URL,
            sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
            saveToPhotoAlbum: false,
            allowEdit: true,
            targetWidth: 400,
            targetHeight: 400
        };
        this.camera.getPicture(options).then(function (imageData) {
            // imageData is either a base64 encoded string or a file URI
            // If it's base64:
            _this.photo = 'data:image/jpeg;base64,' + imageData;
        }, function (err) {
            // Handle error
        });
    };
    CompteProfPage.prototype.send = function () {
        var _this = this;
        var query = "sexe=" + this.sexe +
            "&enseignant_id=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken;
        if (this.nom)
            query += "&nom=" + this.nom;
        if (this.prenom)
            query += "&prenom=" + this.prenom;
        if (this.email)
            query += "&email=" + this.email;
        if (this.tel)
            query += "&tel=" + this.tel;
        if (this.photo != this.prof.img)
            query += "&photo=" + this.photo;
        this.apiSerivce.postData(query, 'compte-prof').subscribe(function (result) {
            _this._result = result;
            __WEBPACK_IMPORTED_MODULE_5__app_app_component__["a" /* MyApp */].enseignant = _this._result.enseignant;
            console.log(_this._result.enseignant);
            if (_this._result.error === true) {
                _this.sendNotification(_this._result.msg, 'toast-error');
            }
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_6__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    CompteProfPage.prototype.post = function () {
    };
    CompteProfPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    CompteProfPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-compte-prof',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-prof/compte-prof.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title>{{prof.nomcomplet}}</ion-title>\n    </ion-navbar>\n  </ion-header>\n  \n  <ion-content>\n    <section class="form-compte" *ngIf="prof && _result" >\n      <form (ngSubmit)="send()" [formGroup]="formGroup" >\n  \n          <div  class="photo_eleve" (click)="cropImage()">\n              <div class="img_container">\n                  <div class="photo_icon">\n                      <ion-icon name="ios-camera"></ion-icon>\n                      <span>{{_result.translation.modifier}}</span>\n                  </div>\n                  <img class="img-responsive" src="{{photo}}" />\n              </div>\n          </div>\n  \n    \n          <div *ngIf="_result?.translation" class="form-group">\n              <label for="">{{_result.translation.sexe}}</label>\n              <ion-item>\n                  <ion-select okText="Ok" cancelText="Fermer" placeholder="{{_result.translation.sexe}}" formControlName="sexe" [(ngModel)]="sexe" >\n                    <ion-option *ngFor="let sexe of _result.translation.sexe_types"  [value]="sexe.value" >{{sexe.label}}</ion-option>\n                  </ion-select>\n              </ion-item>\n          </div>\n  \n          <div class="form-group">\n              <label for="">{{_result.translation.nom_fr}}</label>\n              <ion-item>\n                  <input type="text" class="form-control" placeholder="{{_result.translation.nom_fr}}" formControlName="nom" [(ngModel)]="nom"  />\n              </ion-item>\n          </div>\n  \n          <div class="form-group">\n              <label for="">{{_result.translation.prenom_fr}}</label>\n              <ion-item>\n                  <input type="text" class="form-control" placeholder="{{_result.translation.prenom_fr}}" formControlName="prenom" [(ngModel)]="prenom"  />\n              </ion-item>\n          </div>\n  \n          <div class="form-group">\n              <label for="">{{_result.translation.email}}</label>\n              <ion-item>\n                  <input type="email" class="form-control" placeholder="{{_result.translation.email}}" formControlName="email" [(ngModel)]="email"  />\n              </ion-item>\n          </div>\n  \n          <div class="form-group">\n              <label for="">{{_result.translation.tel}}</label>\n              <ion-item>\n                  <input type="number" class="form-control" placeholder="{{_result.translation.tel}}" formControlName="tel" [(ngModel)]="tel"  />\n              </ion-item>\n          </div>\n          \n      <div class="btn-actions bottom">\n          <button type="submit" class="btn-block btn-main text-uppercase btn-icon" [disabled]="!formGroup.valid" >\n              {{_result.translation.enregistrer}}\n              <img src="assets/imgs/icon/right-arrow.svg" alt="">\n          </button>\n      </div>\n      </form>\n    </section>\n  \n  </ion-content>\n  '/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-prof/compte-prof.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], CompteProfPage);
    return CompteProfPage;
}());

//# sourceMappingURL=compte-prof.js.map

/***/ }),

/***/ 602:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfExamensPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__prof_examens_details_prof_examens_details__ = __webpack_require__(603);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ProfExamensPage = /** @class */ (function () {
    function ProfExamensPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
    }
    ProfExamensPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken
        }, 'prof_examens')
            .subscribe(function (result) {
            _this._result = result;
            //this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ProfExamensPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfExamensPage.prototype.ionViewDidLoad = function () {
        //this.presentLoadingCustom();
    };
    ProfExamensPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ProfExamensPage.prototype.examenDetails = function (result) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__prof_examens_details_prof_examens_details__["a" /* ProfExamensDetailsPage */], {
            examen: result
        });
    };
    ProfExamensPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-examens',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-examens/prof-examens.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n        <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n        </button>\n    </ion-navbar>\n</ion-header>\n<ion-content>\n    <div class="page-container bg-gris">\n        <div class="bg-white">\n                <div class="d-main-banner banner-bottom-left-raduis main-bg px-5">\n                <div class="">\n                    <h3 *ngIf="_result">{{_result.title}}</h3>\n                    <ul class="list-switcher list-inline hidden">\n                        <li class="active">Programmé</li>\n                        <li>Passé</li>\n                    </ul>\n                </div>\n                </div>\n        </div>\n\n        <div class="aucun-examens" hidden *ngIf="_result">\n            <img src="{{_result.empty.img}}" alt="">\n            <h3>{{_result.empty.label}}</h3>\n        </div>\n        \n        <div class="to-do-lists" *ngIf="_result">\n            <div class="item" *ngFor="let result of _result.data" (click)="examenDetails(result)">\n                <div class="image">\n                    <span class="date">\n                        <div class="day">{{result.date.jour}}</div>\n                        <span class="month text-uppercase">{{result.date.mois}}</span>\n                    </span>\n                </div>\n                <div class="item-description">\n                    <h3>{{result.unite}}</h3>\n                    <span class="main-color">{{result.matiere}}</span>\n                    <span class="group">{{result.classe}}</span>\n                    <ul class="list-unstyled">\n                        <li><img src="assets/icon/clock-gris.svg" alt=""> {{result.seance}}</li>\n                        <li><img src="assets/icon/salle.svg" alt=""> {{result.salle}}</li>\n                    </ul>\n                    <ion-icon name="arrow-forward" class="arrow-right"></ion-icon>\n                </div>\n            </div>\n            <!-- end item -->\n        </div>\n    </div>\n</ion-content>\n<div class="actions hidden">\n    <ul class="list-inline">\n        <li class="list-inline-item">\n            <button class="btn-main btn-circle" (click)="showExamenform()">\n                <img src="assets/icon/plus.svg" alt="">\n            </button>\n        </li>\n    </ul>\n</div>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-examens/prof-examens.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ProfExamensPage);
    return ProfExamensPage;
}());

//# sourceMappingURL=prof-examens.js.map

/***/ }),

/***/ 603:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfExamensDetailsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ProfExamensDetailsPage = /** @class */ (function () {
    function ProfExamensDetailsPage(navCtrl, navParams, alertCtrl, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.alertCtrl = alertCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.showPopUp = false;
        this.examen = navParams.get('examen');
    }
    ProfExamensDetailsPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            examen_id: this.examen.id,
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'prof_examens_details')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ProfExamensDetailsPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfExamensDetailsPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ProfExamensDetailsPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ProfExamensDetailsPage.prototype.submit = function () {
        var _this = this;
        this.apiSerivce.postData("enseignant_id=" + __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId + "&examen_id=" + this.examen.id + "&notes=" + JSON.stringify(this._result.data.eleves), 'prof_examens_details').subscribe(function (result) {
            var res = result;
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
            var alert = _this.alertCtrl.create({
                cssClass: 'success_alert_boti',
                title: res.title,
                message: res.message,
                buttons: [{
                        text: 'Fermer',
                        role: 'cancel',
                        handler: function () {
                        }
                    }]
            });
            alert.present();
        }, function (error) {
            _this.loading.dismiss();
        });
    };
    ProfExamensDetailsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-examens-details',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-examens-details/prof-examens-details.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n        <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n        </button>\n    </ion-navbar>\n</ion-header>\n    \n<ion-content>\n    <div class="page-container" *ngIf="_result">\n        <div class="main-banner banner-bottom-left-raduis main-bg">\n            <div class="">\n                <h6 class="text-uppercase text-center">{{_result.data.classe}}</h6>\n                <h3>{{_result.translation.title}}</h3>\n            </div>\n        </div>\n        <div class="seance-date banner-bottom-left-raduis">\n            <h4>{{_result.data.date}}</h4>\n            <p>{{_result.data.creneau}}</p>\n        </div>\n        <div class="etudiant-list">\n            <div class="item" *ngFor="let eleve of _result.data?.eleves">\n                <ion-grid>\n                    <ion-row align-items-center>\n                        <ion-col>\n                            <div class="image">\n                                <img src="{{eleve.photo}}" class="img-circle" alt="">\n                            </div>\n                            <span>{{eleve.nom}}</span>\n                        </ion-col>\n                        <ion-col>\n                            <div class="item-action text-center">\n								<input type="number" [disabled]="!_result || !_result.data?.btnsave" class="form-control" [(ngModel)]="eleve.note" placeholder="{{_result.translation.note}}" />\n                            </div>\n                        </ion-col>\n                    </ion-row>\n                </ion-grid>\n            </div>\n            <!-- end item -->\n        </div>\n    </div>\n</ion-content>\n<div *ngIf="_result && _result.data?.btnsave" class="btn-actions bottom">\n    <button type="submit" (click)="submit()" class="btn-block btn-main text-uppercase btn-icon" >\n            {{_result.translation.enregistrer}}\n        <img src="assets/imgs/icon/right-arrow.svg" alt="">\n    </button>\n</div>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-examens-details/prof-examens-details.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ProfExamensDetailsPage);
    return ProfExamensDetailsPage;
}());

//# sourceMappingURL=prof-examens-details.js.map

/***/ }),

/***/ 604:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfMessagesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__prof_nouveau_message_prof_nouveau_message__ = __webpack_require__(204);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__prof_conversation_prof_conversation__ = __webpack_require__(605);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var ProfMessagesPage = /** @class */ (function () {
    function ProfMessagesPage(navCtrl, navParams, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
    }
    ProfMessagesPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            enseignant_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'prof_messages')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) { return console.log("Error :: " + error); });
    };
    ProfMessagesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ProfMessagesPage.prototype.nouveauMessage = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__prof_nouveau_message_prof_nouveau_message__["a" /* ProfNouveauMessagePage */], { translation: this._result.translation });
    };
    ProfMessagesPage.prototype.detailsMessage = function (reference) {
        reference.translation = this._result.translation;
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_4__prof_conversation_prof_conversation__["a" /* ProfConversationPage */], { reference: reference });
    };
    ProfMessagesPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad Messages & NotesPage');
    };
    ProfMessagesPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-messages',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-messages.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n	<button ion-button menuToggle>\n	<ion-icon name="menu"></ion-icon>\n	</button>\n	<ion-title></ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content>\n	<div *ngIf="_result" class="page-container">\n		<div class="bg-white">\n				<div class="d-main-banner banner-bottom-left-raduis main-bg px-5">\n				<div class="">\n					<h6 class="text-uppercase text-center"></h6>\n					<h3>{{_result.translation.title}}</h3>\n				</div>\n				</div>\n		</div>\n\n		<ul class="contact-list" *ngIf="_result?.data?.length > 0" >\n\n			<li class="person align-items-center d-flex" *ngFor="let result of _result.data" (click) = "detailsMessage(result)">\n				<span class="avatar">\n					<img src="{{result.img}}" alt="{{result.at}}" />\n				</span>\n				<span class="info">\n					<span class="name">{{result.at}}</span>\n					<span class="status-msg"><ion-icon [ngClass]="{\'vu\': result.vu_le}" name="ios-done-all"></ion-icon><span>{{result.sujet}}</span></span>\n					<span class="last-login">{{result.envoye_le}}</span>\n				</span>\n			</li>\n\n\n		</ul><!-- /.contact-list -->\n		<div *ngIf="_result?.empty == true" class="aucun-messages">\n			<img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n			<h3 [innerHtml]="_result.empty_text"></h3>\n		</div>\n	</div> \n	<ion-fab>\n		<button ion-fab class="background-danger fixed-bottom" (click) = "nouveauMessage()">\n			<ion-icon name="create" ></ion-icon>\n		</button>\n	</ion-fab>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-messages.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */]])
    ], ProfMessagesPage);
    return ProfMessagesPage;
}());

//# sourceMappingURL=prof-messages.js.map

/***/ }),

/***/ 605:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ProfConversationPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__prof_nouveau_message_prof_nouveau_message__ = __webpack_require__(204);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var ProfConversationPage = /** @class */ (function () {
    function ProfConversationPage(navCtrl, navParams, apiSerivce, formBuilder) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.formBuilder = formBuilder;
        this.reference = navParams.get('reference');
        console.log(this.reference);
        this._result = this.reference.conversation;
        this.formGroup = this.formBuilder.group({
            message: ['', __WEBPACK_IMPORTED_MODULE_3__angular_forms__["Validators"].required],
        });
    }
    ProfConversationPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad DetailsMessagePage');
    };
    ProfConversationPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            reference: this.reference.id,
            enseignant_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'messages')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) { return console.log("Error :: " + error); });
    };
    ProfConversationPage.prototype.ngOnInit = function () {
        // this.getData();
    };
    ProfConversationPage.prototype.nouveauMessage = function (message, sujet) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_2__prof_nouveau_message_prof_nouveau_message__["a" /* ProfNouveauMessagePage */], { messageRef: message, sujet: sujet, });
    };
    ProfConversationPage.prototype.send = function () {
        var _this = this;
        var query = "sujet=" + '' +
            "&message=" + this.message +
            "&enseignant_id=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken;
        query += "&ref=" + this.reference.id;
        this.apiSerivce.postData(query, 'prof_messages').subscribe(function (result) {
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_5__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    ProfConversationPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-prof-conversation',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-conversation/prof-conversation.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n    <button ion-button menuToggle>\n    <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="reference?.translation">{{reference.translation.title}}</ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content>\n        <div class="chatbox" *ngIf="reference && _result?.length > 0">\n\n				<div class="person align-items-center d-flex">\n					<span class="avatar">\n						<img src="{{reference.img}}" alt="Debby Jones" />\n					</span>\n					<span class="info">\n						<span class="name">{{reference.at}}</span>\n						<span class="login-status">{{reference.sujet}}</span>\n					</span>\n				</div><!-- /.person -->\n\n				<div class="chatbox-messages">\n					<div class="messages clear" *ngFor="let message of _result">\n						<span class="avatar" *ngIf="message.class == \'sender\' ">\n							<img src="{{message.img}}" alt="{{message.at}}" />\n						</span>\n						<div class="{{message.class}}">\n							<div class="message-container">\n								<div class="message">\n									<p>{{message.message}}</p>\n								</div>\n								<span class="delivered">{{message.envoye_le}}</span>\n							</div><!-- /.message-container -->\n\n						</div><!-- /.sender -->\n					</div><!-- /.messages -->\n\n				</div><!-- /.chatbox-messages -->\n\n\n				<div class="message-form-container">\n					\n                    <form (ngSubmit)="send()"  class="message-form" [formGroup]="formGroup" >\n						<textarea id="message" name="message"  [(ngModel)]="message" formControlName="message" placeholder="{{reference.translation.message}}"></textarea>\n						<button class="send-btn" type="submit" [disabled]="!formGroup.valid">\n							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.2 30.1"><path class="st0" d="M2.1 14.6C8.9 12 28.5 4 28.5 4l-3.9 22.6c-0.2 1.1-1.5 1.5-2.3 0.8l-6.1-5.1 -4.3 4 0.7-6.7 13-12.3 -16 10 1 5.7 -3.3-5.3 -5-1.6C1.5 15.8 1.4 14.8 2.1 14.6z"/></svg>\n						</button>\n					</form><!-- /.search-form -->\n\n\n				</div><!-- /.message-form-container -->\n\n			</div><!-- /.chatbox -->\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/prof-messages/prof-conversation/prof-conversation.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_3__angular_forms__["FormBuilder"]])
    ], ProfConversationPage);
    return ProfConversationPage;
}());

//# sourceMappingURL=prof-conversation.js.map

/***/ }),

/***/ 606:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return CoursPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_youtube_video_player__ = __webpack_require__(590);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_android_permissions__ = __webpack_require__(110);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};







var CoursPage = /** @class */ (function () {
    function CoursPage(navCtrl, navParams, apiSerivce, loadingCtrl, platform, file, alertCtrl, androidPermissions, transfer, youtube) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.platform = platform;
        this.file = file;
        this.alertCtrl = alertCtrl;
        this.androidPermissions = androidPermissions;
        this.transfer = transfer;
        this.youtube = youtube;
        this.currentJustify = 'justified';
        this.results = [];
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    CoursPage.prototype.ionViewWillEnter = function () {
        this.load();
    };
    CoursPage.prototype.load = function () {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    CoursPage.prototype.next = function (next_date) {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
            next_week: next_date
        }, 'cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    CoursPage.prototype.prev = function (prev_date) {
        var _this = this;
        this.presentLoadingCustom();
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
            last_week: prev_date
        }, 'cours')
            .subscribe(function (results) {
            _this.results = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    CoursPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    CoursPage.prototype.openYoutube = function (id) {
        this.youtube.openVideo(id);
    };
    CoursPage.prototype.downloadFile = function (fileName, filePath) {
        var _this = this;
        //here encoding path as encodeURI() format.  
        var url = encodeURI(filePath);
        this.fileTransfer = this.transfer.create();
        var directory = '';
        if (this.platform.is('cordova')) {
            directory = this.file.externalRootDirectory + '/Download/';
        }
        else {
            directory = this.file.documentsDirectory;
        }
        this.androidPermissions.requestPermissions([
            this.androidPermissions.PERMISSION.WRITE_EXTERNAL_STORAGE
        ]).then(function (result) {
            // here iam mentioned this line this.file.externalRootDirectory is a native pre-defined file path storage. You can change a file path whatever pre-defined method.  
            _this.fileTransfer.download(url, directory + fileName, true).then(function (entry) {
                //here logging our success downloaded file path in mobile.  
                var alertSuccess = _this.alertCtrl.create({
                    title: "T\u00E9l\u00E9chargement r\u00E9ussi !",
                    cssClass: 'notification-alert',
                    message: fileName + " a \u00E9t\u00E9 t\u00E9l\u00E9charg\u00E9 avec succ\u00E8s sur : " + entry.toURL(),
                    buttons: ['Ok']
                });
                alertSuccess.present();
            }, function (error) {
                //here logging our error its easier to find out what type of error occured.  
                console.log('download failed: ' + error);
            });
        });
    };
    CoursPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-cours',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/cours/cours.html"*/'<ion-content>\n\n    <div *ngIf="results" class="user-picture-top-page">\n\n        <div>\n\n            <img src="{{eleve.img}}" alt="{{eleve.nomcomplet}}">\n\n        </div>\n\n        <div>\n\n            <h1 *ngIf="results?.translation">{{results.translation.title}}</h1>\n\n            <span>{{eleve.nomcomplet}}</span>\n\n        </div>\n\n    </div>\n\n   <ion-card *ngIf="results">\n\n      <ion-card-header>\n\n            <ion-grid class="header-grid">\n\n              <ion-row class="flex-item-center">\n\n                  <ion-col col-2>\n\n                      <button ion-button icon-only class="btn-fleche" (click)="prev(results.last_week)">\n\n                          <ion-icon name="md-arrow-back"></ion-icon>\n\n                        </button>\n\n                  </ion-col>\n\n                  <ion-col col-8>\n\n                    <h3 class="timetable-week">{{results.label_du}}</h3>\n\n                    <h3 class="timetable-week">{{results.label_au}}</h3>\n\n                  </ion-col>\n\n                  <ion-col col-2>\n\n                      <button ion-button icon-only class="btn-fleche" (click)="next(results.next_week)">\n\n                          <ion-icon name="md-arrow-forward"></ion-icon>\n\n                        </button>\n\n                  </ion-col>\n\n              </ion-row>\n\n            </ion-grid>\n\n      </ion-card-header>\n\n      <ion-card-content>\n\n        <ngb-tabset [justify]="currentJustify">\n\n          <ngb-tab  *ngFor="let result of results.seances" title="{{result.label}}">\n\n            <ng-template ngbTabContent>\n\n                <h3 class="aucun-cours" *ngIf="result.seances.length == 0">{{results.translation.aucun_cours}}</h3>\n\n                <ion-grid class="seances-grid" *ngFor="let seance of result.seances">\n\n                  <ion-row class="flex-item-center">\n\n                      <ion-col class="left-grid" col-2>\n\n                        <div class="timetable-date">\n\n                              <span>\n\n                                {{seance.start}}\n\n                              </span>\n\n                              <span>\n\n                                {{seance.end}}\n\n                              </span>\n\n                        </div>\n\n                      </ion-col>\n\n                      <ion-col class="right-grid" col-10>\n\n                          <h6>\n\n                              <span *ngIf="seance.matiere" class="timetable-matiere">{{seance.matiere}}</span>\n\n                              <span *ngIf="seance.matiere_intro" class="timetable-matiere_intro">{{seance.matiere_intro}}</span>\n\n                          </h6>\n\n                          <p>\n\n                            <ion-icon ios="ios-contact-outline" md="ios-contact-outline" class="timetable-icon"></ion-icon>\n\n                            <span *ngIf="seance.enseignant" class="timetable-enseignant">{{seance.enseignant}}</span>\n\n                            <ion-icon ios="ios-navigate-outline" md="ios-navigate-outline"  class="timetable-icon"></ion-icon>\n\n                            <span *ngIf="seance.salle" class="timetable-salle">{{seance.salle}}</span>\n\n                          </p>\n\n                          <div class="links">\n\n                                <span *ngIf="seance.links" class="links">\n\n                                    <span *ngFor="let link of seance.links">\n\n                                        <a *ngIf="link.type == \'youtube\'" href="#" (click)="openYoutube(link.link)">\n\n                                            <img src="{{link.icon}}" />\n\n                                        </a>\n\n                                        <a *ngIf="link.type != \'youtube\'" href="{{link.link}}">\n\n                                            <img src="{{link.icon}}" />\n\n                                        </a>\n\n                                    </span>\n\n                                </span>\n\n                                <span *ngIf="seance.files" class="files">\n\n                                    <span *ngFor="let file of seance.files">\n\n                                        <a href="#" (click)="downloadFile(file.name, file.link)">\n\n                                            <img src="{{file.icon}}" />\n\n                                        </a>\n\n                                    </span>\n\n                                </span>\n\n                          </div>\n\n                          <span *ngIf="seance.retard" class="timetable-retard"> {{seance.retard}}</span>\n\n                          <span *ngIf="seance.absent" class="timetable-absent"> {{seance.absent}}</span>\n\n                          <div *ngIf="seance.examen && results?.translation" class="examen">{{results.translation.examen}}</div>\n\n                      </ion-col>\n\n                  </ion-row>\n\n                </ion-grid>\n\n            </ng-template>\n\n          </ngb-tab>\n\n      </ngb-tabset>\n\n      </ion-card-content>\n\n    </ion-card>\n\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/cours/cours.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_android_permissions__["a" /* AndroidPermissions */],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_transfer__["a" /* FileTransfer */],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_youtube_video_player__["a" /* YoutubeVideoPlayer */]])
    ], CoursPage);
    return CoursPage;
}());

//# sourceMappingURL=cours.js.map

/***/ }),

/***/ 607:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ExamensPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var ExamensPage = /** @class */ (function () {
    function ExamensPage(navCtrl, navParams, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    ExamensPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'examens')
            .subscribe(function (result) {
            _this._result = result;
        }, function (error) { return console.log("Error :: " + error); });
    };
    ExamensPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ExamensPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad Examens & NotesPage');
    };
    ExamensPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-examens',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/examens/examens.html"*/'<ion-content>\n	\n	<div class="container_result">\n	<div class="user-picture-top-page">\n		<div>\n			<img src="{{eleve.img}}" alt="{{eleve.nomcomplet}}">\n		</div>\n		<div>\n			<h1 *ngIf="_result?.translation">{{_result.translation.title}}</h1>\n			<span>{{eleve.nomcomplet}}</span>\n		</div>\n	</div>\n\n	<section *ngIf="_result?.data?.length > 0" >\n		<ion-grid class="examen_item" *ngFor="let result of _result.data" >\n			<ion-row align-items-center class="">\n				<ion-col col-3>\n					<div class="date_examen">\n						<span class="jour">{{result.date.jour}}</span>\n						<span class="mois">{{result.date.mois}}</span>\n					</div>\n				</ion-col>\n				<ion-col col-9>\n					<div class="examen_infos" >\n						<div>\n							<h1> {{result.matiere}}\n							</h1>\n							<span class="prof">{{result.enseignant}}</span>\n							<span class="seance">{{result.seance}}</span>\n						</div>\n						<div>\n							<img src="{{result.icone}}" alt="{{eleve.statut}}">\n							<span class="statut" [ngStyle]="{\'color\': result.color }">{{result.statut}}</span>\n						</div>\n					</div>\n				</ion-col>\n			</ion-row>\n		</ion-grid>\n	</section>\n\n	<div *ngIf="_result?.empty == true" class="vertical-center-absolute no-result ">\n		<img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n		<h3 [innerHtml]="_result.empty_text"></h3>\n	</div>\n	</div>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/examens/examens.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */]])
    ], ExamensPage);
    return ExamensPage;
}());

//# sourceMappingURL=examens.js.map

/***/ }),

/***/ 608:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return DisciplinePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__home_home__ = __webpack_require__(112);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




/**
 * Generated class for the DisciplinePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var DisciplinePage = /** @class */ (function () {
    function DisciplinePage(navCtrl, navParams, apiSerivce) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    DisciplinePage.prototype.ionViewWillEnter = function () {
        this.load();
    };
    DisciplinePage.prototype.load = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'discipline')
            .subscribe(function (result) {
            _this._result = result[0];
        }, function (error) { return console.log("Error :: " + error); });
    };
    DisciplinePage.prototype.retour = function () {
        this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__home_home__["a" /* HomePage */]);
    };
    DisciplinePage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad DisciplinePage');
    };
    DisciplinePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-discipline',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/discipline/discipline.html"*/'<ion-content>\n  <div class="score-container">\n      <h1 *ngIf="_result" >{{_result.label}}</h1>\n      <p *ngIf="_result">{{_result.description}}</p>\n      <h2  *ngIf="_result" class="score" [ngStyle]="{\'background\': _result.color}" > {{_result.score}}</h2>\n      <div class="actions">\n        <h3 *ngIf="_result?.actions?.title" >{{_result.actions.title}}</h3>\n        <div *ngIf="_result?.actions?.data?.length > 0">\n            <div *ngFor="let action of _result.actions.data" class="action-box">\n                <div class="valeur-thumb">\n                    <span [ngStyle]="{\'background\': action.couleur}">{{action.valeur}}</span>\n                </div>\n                <div class="action-info">\n                  <time>{{action.date}}</time>\n                  <h6>{{action.type}}</h6>\n                  <p>{{action.commentaire}} </p>\n                  <span [ngStyle]="{\'border-color\': action.couleur, \'color\': action.couleur}" >{{action.nature}}</span>\n                </div>\n            </div>\n        </div>\n        <div *ngIf="_result?.actions?.empty == true" class="no-result ">\n          <img src="{{_result.actions.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n          <h3 [innerHtml]="_result.actions.empty_text"></h3>\n        </div>\n      </div>\n  </div>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/discipline/discipline.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */]])
    ], DisciplinePage);
    return DisciplinePage;
}());

//# sourceMappingURL=discipline.js.map

/***/ }),

/***/ 609:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ConversationPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__nouveau_message_nouveau_message__ = __webpack_require__(208);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_android_permissions__ = __webpack_require__(110);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var ConversationPage = /** @class */ (function () {
    function ConversationPage(navCtrl, navParams, platform, file, alertCtrl, androidPermissions, transfer) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.platform = platform;
        this.file = file;
        this.alertCtrl = alertCtrl;
        this.androidPermissions = androidPermissions;
        this.transfer = transfer;
        this.result = navParams.get('result');
    }
    ConversationPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad DetailsMessagePage');
    };
    ConversationPage.prototype.nouveauMessage = function (message, sujet) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_2__nouveau_message_nouveau_message__["a" /* NouveauMessagePage */], { messageRef: message, sujet: sujet, translation: this.result.translation });
    };
    ConversationPage.prototype.downloadFile = function (fileName, filePath) {
        var _this = this;
        //here encoding path as encodeURI() format.  
        var url = encodeURI(filePath);
        this.fileTransfer = this.transfer.create();
        var directory = '';
        if (this.platform.is('cordova')) {
            directory = this.file.externalRootDirectory + '/Download/';
        }
        else {
            directory = this.file.documentsDirectory;
        }
        this.androidPermissions.requestPermissions([
            this.androidPermissions.PERMISSION.WRITE_EXTERNAL_STORAGE
        ]).then(function (result) {
            // here iam mentioned this line this.file.externalRootDirectory is a native pre-defined file path storage. You can change a file path whatever pre-defined method.  
            _this.fileTransfer.download(url, directory + fileName, true).then(function (entry) {
                //here logging our success downloaded file path in mobile.  
                var alertSuccess = _this.alertCtrl.create({
                    title: "T\u00E9l\u00E9chargement r\u00E9ussi !",
                    cssClass: 'notification-alert',
                    message: fileName + " a \u00E9t\u00E9 t\u00E9l\u00E9charg\u00E9 avec succ\u00E8s sur : " + entry.toURL(),
                    buttons: ['Ok']
                });
                alertSuccess.present();
            }, function (error) {
                //here logging our error its easier to find out what type of error occured.  
                console.log('download failed: ' + error);
            });
        });
    };
    ConversationPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-conversation',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/conversation/conversation.html"*/'<ion-header no-border>\n<ion-navbar transparent>\n    <button ion-button menuToggle>\n    <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title *ngIf="result?.translation">{{result.translation.title}}</ion-title>\n</ion-navbar>\n</ion-header>\n\n<ion-content>\n<section *ngFor="let message of result.conversation">\n  <ion-grid class="white-bg conversation">\n    <div class="user-info">\n        <ion-row class="flex-item-center">\n                <ion-col col-2 class="text-center">\n                    <img src="{{message.img}}" class="img-circle vertical-middle" alt="">\n                </ion-col>\n                <ion-col col10>\n                    <h5>{{message.at}}</h5>\n                    <span class="date">{{message.envoye_le}}</span>\n                </ion-col>\n\n        </ion-row>\n    </div>\n  </ion-grid>\n  <ion-grid class="py-0 px-0">\n    <div class="message-content white-bg px-5">\n        <h2>{{message.sujet}}</h2>\n        <p>\n          {{message.message}}\n        </p>\n        <div *ngFor="let file of message.files">   \n            <div (click)="downloadFile(file.name, file.link)" class="download-file-container" >\n                <img src="assets/icon/attachment-upload.svg" alt="">\n                <span>{{file.name}}</span>\n            </div>\n        </div>\n    </div>\n  </ion-grid>\n</section>\n<div class="message-content white-bg px-5">\n    <div class="actions">\n        <ul class="list-inline list-unstyled">\n            <li>\n                <button class="btn-main background-info" (click) = "nouveauMessage(result.conversation[0].id,result.conversation[0].sujet)">{{result.translation.repondre}}</button>\n            </li>\n        </ul>\n    </div>\n</div>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/messages/conversation/conversation.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_android_permissions__["a" /* AndroidPermissions */],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_transfer__["a" /* FileTransfer */]])
    ], ConversationPage);
    return ConversationPage;
}());

//# sourceMappingURL=conversation.js.map

/***/ }),

/***/ 610:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return CompteElevePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__compte_compte__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};








var CompteElevePage = /** @class */ (function () {
    function CompteElevePage(navCtrl, camera, transfer, file, navParams, apiSerivce, loadingCtrl, formBuilder) {
        this.navCtrl = navCtrl;
        this.camera = camera;
        this.transfer = transfer;
        this.file = file;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        if (this.navParams.get('eleve'))
            this.eleve = this.navParams.get('eleve');
        this.formGroup = this.formBuilder.group({
            sexe: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            nom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            prenom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            nomar: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            prenomar: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].nullValidator],
            datenaissance: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
        this.datenaissance = this.eleve.datenaissance;
        if (this.eleve.nom)
            this.nom = this.eleve.nom;
        if (this.eleve.prenom)
            this.prenom = this.eleve.prenom;
        if (this.eleve.nomar)
            this.nomar = this.eleve.nomar;
        if (this.eleve.prenomar)
            this.prenomar = this.eleve.prenomar;
        if (this.eleve.img)
            this.photo = this.eleve.img;
        if (this.eleve.sexe)
            this.sexe = this.eleve.sexe;
    }
    CompteElevePage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    CompteElevePage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    CompteElevePage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */].parentId,
            key: __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'compte-eleve')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
            _this.loadingBlur = null;
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    CompteElevePage.prototype.ngOnInit = function () {
        this.getData();
    };
    CompteElevePage.prototype.cropImage = function () {
        var _this = this;
        var options = {
            quality: 70,
            destinationType: this.camera.DestinationType.DATA_URL,
            sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
            saveToPhotoAlbum: false,
            allowEdit: true,
            targetWidth: 400,
            targetHeight: 400
        };
        this.camera.getPicture(options).then(function (imageData) {
            // imageData is either a base64 encoded string or a file URI
            // If it's base64:
            _this.photo = 'data:image/jpeg;base64,' + imageData;
        }, function (err) {
            // Handle error
        });
    };
    CompteElevePage.prototype.send = function () {
        var _this = this;
        var query = "datenaissance=" + this.datenaissance +
            "&sexe=" + this.sexe +
            "&eleve_id=" + this.eleve.id +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */].keyToken;
        if (this.nom)
            query += "&nom=" + this.nom;
        if (this.prenom)
            query += "&prenom=" + this.prenom;
        if (this.nomar)
            query += "&nomar=" + this.nomar;
        if (this.prenomar)
            query += "&prenomar=" + this.prenomar;
        if (this.photo != this.eleve.img)
            query += "&photo=" + this.photo;
        this.apiSerivce.postData(query, 'compte-eleve').subscribe(function (result) {
            _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_6__compte_compte__["a" /* ComptePage */]);
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    CompteElevePage.prototype.post = function () {
    };
    CompteElevePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-compte-eleve',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-eleve/compte-eleve.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title>{{eleve.nomcomplet}}</ion-title>\n  </ion-navbar>\n</ion-header>\n\n<ion-content>\n  <section class="form-compte" *ngIf="eleve && _result" >\n    <form (ngSubmit)="send()" [formGroup]="formGroup" >\n\n        <div  class="photo_eleve" (click)="cropImage()">\n            <div class="img_container">\n                <div class="photo_icon">\n                    <ion-icon name="ios-camera"></ion-icon>\n                    <span>{{_result.translation.modifier}}</span>\n                </div>\n                <img class="img-responsive" src="{{photo}}" />\n            </div>\n        </div>\n\n  \n        <div *ngIf="_result?.translation" class="form-group">\n            <label for="">{{_result.translation.sexe}}</label>\n            <ion-item *ngIf="_result.translation">\n                <ion-select okText="Ok" cancelText="Fermer" placeholder="{{_result.translation.sexe}}" formControlName="sexe" [(ngModel)]="sexe" >\n                    <ion-option *ngFor="let sexe of _result.translation.sexe_types"  [value]="sexe.value" >{{sexe.label}}</ion-option>\n                </ion-select>\n            </ion-item>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.nom_prenom_fr}}</label>\n            <ion-grid>\n                <ion-row>\n                    <ion-col>\n                        <ion-item>\n                            <input type="text" class="form-control" placeholder="{{_result.translation.nom_fr}}" formControlName="nom" [(ngModel)]="nom"  />\n                        </ion-item>\n                    </ion-col>\n                    <ion-col>\n                        <ion-item>\n                            <input type="text" class="form-control" placeholder="{{_result.translation.prenom_fr}}" formControlName="prenom" [(ngModel)]="prenom"  />\n                        </ion-item>\n                    </ion-col>\n                </ion-row>\n            </ion-grid>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.nom_prenom_ar}}</label>\n            <ion-grid>\n                <ion-row>\n                    <ion-col>\n                        <ion-item>\n                            <input type="text" class="form-control" placeholder="{{_result.translation.nom_ar}}" formControlName="nomar" [(ngModel)]="nomar"  />\n                        </ion-item>\n                    </ion-col>\n                    <ion-col>\n                        <ion-item>\n                            <input type="text" class="form-control" placeholder="{{_result.translation.prenom_ar}}" formControlName="prenomar" [(ngModel)]="prenomar"  />\n                        </ion-item>\n                    </ion-col>\n                </ion-row>\n            </ion-grid>\n        </div>\n        <div class="form-group">\n            <label for="">{{_result.translation.date_naissance}}</label>\n            <ion-item >\n                <ion-datetime displayFormat="DD/MM/YYYY" placeholder="{{_result.translation.date_naissance}}" doneText="{{_result.translation.choisir}}" cancelText="{{_result.translation.annuler}}"  formControlName="datenaissance" [(ngModel)]="datenaissance"></ion-datetime>\n            </ion-item>\n        </div>\n    <div class="btn-actions bottom">\n        <button type="submit" class="btn-block btn-main text-uppercase btn-icon" [disabled]="!formGroup.valid" >\n            {{_result.translation.enregistrer}}\n            <img src="assets/imgs/icon/right-arrow.svg" alt="">\n        </button>\n    </div>\n    </form>\n  </section>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-eleve/compte-eleve.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_transfer__["a" /* FileTransfer */],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_7__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], CompteElevePage);
    return CompteElevePage;
}());

//# sourceMappingURL=compte-eleve.js.map

/***/ }),

/***/ 611:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return CompteParentPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__compte_compte__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var CompteParentPage = /** @class */ (function () {
    function CompteParentPage(navCtrl, camera, navParams, toastCtrl, apiSerivce, loadingCtrl, formBuilder) {
        this.navCtrl = navCtrl;
        this.camera = camera;
        this.navParams = navParams;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        if (this.navParams.get('parent'))
            this.parent = this.navParams.get('parent');
        this.formGroup = this.formBuilder.group({
            sexe: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            nom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            prenom: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            tel: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            email: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
        console.log(this.parent);
        if (this.parent.nom)
            this.nom = this.parent.nom;
        if (this.parent.prenom)
            this.prenom = this.parent.prenom;
        if (this.parent.email)
            this.email = this.parent.email;
        if (this.parent.tel)
            this.tel = this.parent.tel;
        if (this.parent.img)
            this.photo = this.parent.img;
        if (this.parent.sexe)
            this.sexe = this.parent.sexe;
    }
    CompteParentPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    CompteParentPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].parentId,
            key: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'compte-parent')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    CompteParentPage.prototype.ngOnInit = function () {
        this.getData();
    };
    CompteParentPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    CompteParentPage.prototype.cropImage = function () {
        var _this = this;
        var options = {
            quality: 70,
            destinationType: this.camera.DestinationType.DATA_URL,
            sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
            saveToPhotoAlbum: false,
            allowEdit: true,
            targetWidth: 400,
            targetHeight: 400
        };
        this.camera.getPicture(options).then(function (imageData) {
            // imageData is either a base64 encoded string or a file URI
            // If it's base64:
            _this.photo = 'data:image/jpeg;base64,' + imageData;
        }, function (err) {
            // Handle error
        });
    };
    CompteParentPage.prototype.send = function () {
        var _this = this;
        console.log(this.sexe);
        var query = "sexe=" + this.sexe +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */].keyToken;
        if (this.nom)
            query += "&nom=" + this.nom;
        if (this.prenom)
            query += "&prenom=" + this.prenom;
        if (this.email)
            query += "&email=" + this.email;
        if (this.tel)
            query += "&tel=" + this.tel;
        if (this.photo != this.parent.img)
            query += "&photo=" + this.photo;
        this.apiSerivce.postData(query, 'compte-parent').subscribe(function (result) {
            _this._result = result;
            if (_this._result.error === true) {
                _this.sendNotification(_this._result.msg, 'toast-error');
            }
            else {
                _this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__compte_compte__["a" /* ComptePage */]);
            }
        }, function (error) {
            //alert(error);
            console.log("Error :: " + error);
            //this.loading.dismiss().catch();
        });
    };
    CompteParentPage.prototype.post = function () {
    };
    CompteParentPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    CompteParentPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-compte-parent',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-parent/compte-parent.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title>{{parent.nomcomplet}}</ion-title>\n  </ion-navbar>\n</ion-header>\n\n<ion-content>\n  <section class="form-compte" *ngIf="parent && _result" >\n    <form (ngSubmit)="send()" [formGroup]="formGroup" >\n\n        <div  class="photo_eleve" (click)="cropImage()">\n            <div class="img_container">\n                <div class="photo_icon">\n                    <ion-icon name="ios-camera"></ion-icon>\n                    <span>{{_result.translation.modifier}}</span>\n                </div>\n                <img class="img-responsive" src="{{photo}}" />\n            </div>\n        </div>\n\n  \n        <div *ngIf="_result?.translation" class="form-group">\n            <label for="">{{_result.translation.sexe}}</label>\n            <ion-item>\n                <ion-select okText="Ok" cancelText="Fermer" placeholder="{{_result.translation.sexe}}" formControlName="sexe" [(ngModel)]="sexe" >\n                    <ion-option *ngFor="let sexe_type of _result.translation.sexe_types"  [value]="sexe_type.value" >{{sexe_type.label}}</ion-option>\n                </ion-select>\n            </ion-item>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.nom_fr}}</label>\n			<ion-item>\n				<input type="text" class="form-control" placeholder="{{_result.translation.nom_fr}}" formControlName="nom" [(ngModel)]="nom"  />\n			</ion-item>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.prenom_fr}}</label>\n			<ion-item>\n				<input type="text" class="form-control" placeholder="{{_result.translation.prenom_fr}}" formControlName="prenom" [(ngModel)]="prenom"  />\n			</ion-item>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.email}}</label>\n			<ion-item>\n				<input type="email" class="form-control" placeholder="{{_result.translation.email}}" formControlName="email" [(ngModel)]="email"  />\n			</ion-item>\n        </div>\n\n        <div class="form-group">\n            <label for="">{{_result.translation.tel}}</label>\n			<ion-item>\n				<input type="number" class="form-control" placeholder="{{_result.translation.tel}}" formControlName="tel" [(ngModel)]="tel"  />\n			</ion-item>\n        </div>\n		\n    <div class="btn-actions bottom">\n        <button type="submit" class="btn-block btn-main text-uppercase btn-icon" [disabled]="!formGroup.valid" >\n            {{_result.translation.enregistrer}}\n            <img src="assets/imgs/icon/right-arrow.svg" alt="">\n        </button>\n    </div>\n    </form>\n  </section>\n\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/compte-parent/compte-parent.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_5__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], CompteParentPage);
    return CompteParentPage;
}());

//# sourceMappingURL=compte-parent.js.map

/***/ }),

/***/ 612:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ChangePasswordPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ChangePasswordPage = /** @class */ (function () {
    function ChangePasswordPage(navCtrl, navParams, toastCtrl, apiSerivce, loadingCtrl, formBuilder, viewCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.toastCtrl = toastCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
        this.viewCtrl = viewCtrl;
        this.formGroup = this.formBuilder.group({
            actuel: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            nouveau: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
            confirmer: ['', __WEBPACK_IMPORTED_MODULE_2__angular_forms__["Validators"].required],
        });
    }
    ChangePasswordPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ChangePasswordPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ChangePasswordPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId,
            key: __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'change-password')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
            _this.loadingBlur = null;
        }, function (error) {
            console.log("Error :: " + error);
            _this.loading.dismiss();
        });
    };
    ChangePasswordPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ChangePasswordPage.prototype.closeModal = function () {
        this.viewCtrl.dismiss();
    };
    ChangePasswordPage.prototype.valider = function () {
        var _this = this;
        var query = "actuel=" + this.actuel +
            "&nouveau=" + this.nouveau +
            "&confirmer=" + this.confirmer +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */].keyToken;
        this.apiSerivce.postData(query, 'change-password').subscribe(function (result) {
            _this._result = result;
            if (_this._result.error === false) {
                _this.sendNotification(_this._result.msg, 'toast-success');
                _this.viewCtrl.dismiss();
            }
            else {
                _this.sendNotification(_this._result.msg, 'toast-error');
                _this.actuel = null;
                _this.nouveau = null;
                _this.confirmer = null;
            }
        }, function (error) {
        });
    };
    ChangePasswordPage.prototype.sendNotification = function (message, classe) {
        var notification = this.toastCtrl.create({
            message: message,
            duration: 100000,
            cssClass: classe,
            position: 'middle',
            showCloseButton: true,
            closeButtonText: 'Ok'
        });
        notification.present();
    };
    ChangePasswordPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-change-password',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/change-password/change-password.html"*/'<ion-content padding>\n\n    <button ion-button (click)="closeModal()" [ngClass]="\'close-modal\'">\n\n        <ion-icon name="ios-close-outline"></ion-icon>\n\n    </button>\n\n    <div *ngIf="_result" class="vertical-center">\n\n        <div class="container">\n\n            <div class="banner text-center">\n\n                <img src="https://image.flaticon.com/icons/svg/1162/1162263.svg" alt="">\n\n                <h1>{{_result.translation.title}}</h1>\n\n                <p>{{_result.translation.intro}}</p>\n\n                <form (ngSubmit)="valider()" [formGroup]="formGroup" >\n\n                <div class="form-group">\n\n                    <input type="password" name="actuel" formControlName="actuel" [(ngModel)]="actuel" required placeholder="{{_result.translation.actuel}}">\n\n                </div>\n\n                <div class="form-group">\n\n                    <input type="password" name="nouveau" formControlName="nouveau" [(ngModel)]="nouveau" required placeholder="{{_result.translation.nouveau}}">\n\n                </div>\n\n                <div class="form-group">\n\n                    <input type="password" name="confirmer" formControlName="confirmer" [(ngModel)]="confirmer" required placeholder="{{_result.translation.confirmer}}">\n\n                </div>\n\n                  <button type="submit" class="btn-main btn-block text-uppercase" [disabled]="!formGroup.valid" >{{_result.translation.enregistrer}}</button>\n\n                </form>\n\n            </div>\n\n        </div>\n\n    </div>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/change-password/change-password.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_3__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ViewController"]])
    ], ChangePasswordPage);
    return ChangePasswordPage;
}());

//# sourceMappingURL=change-password.js.map

/***/ }),

/***/ 613:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return DetailsDemandePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__nouvelle_demande_nouvelle_demande__ = __webpack_require__(210);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



//declare var $: any;
var DetailsDemandePage = /** @class */ (function () {
    function DetailsDemandePage(navCtrl, navParams, elRef) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.elRef = elRef;
        this.demande = [];
        this.demande = this.navParams.get('demande');
    }
    DetailsDemandePage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad DetailsDemandePage');
    };
    DetailsDemandePage.prototype.jQueryInit = function () {
        /* var self = this; */
        /*
         $(this.elRef.nativeElement).ready(function() {
         });
        
        $('nav').scroll(function(){
     
           if($(document).offsetTop > 0){
               alert('test');
           }
        });
     
        */
    };
    DetailsDemandePage.prototype.ngAfterViewInit = function () {
        this.jQueryInit();
    };
    DetailsDemandePage.prototype.nouvelle_demande = function () {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_2__nouvelle_demande_nouvelle_demande__["a" /* NouvelleDemandePage */]);
    };
    DetailsDemandePage.prototype.getColor = function (etat) {
        switch (etat) {
            case 'En Cours': {
                return "en-cours";
            }
            case 'Traitée': {
                return "cloturee";
            }
            case 'Bloquée': {
                return "refusee";
            }
            case 'Nouvelle': {
                return "nouvelle";
            }
            case 'Accepté': {
                return "accepte";
            }
            case 'Livrée': {
                return "livree";
            }
            default: {
                return "en-cours";
            }
        }
    };
    DetailsDemandePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-details-demande',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/details-demande/details-demande.html"*/'<ion-header no-border>\n  <ion-navbar transparent>\n    <button ion-button menuToggle>\n      <ion-icon name="menu"></ion-icon>\n    </button>\n    <ion-title>{{demande.type}}</ion-title>\n  </ion-navbar>\n</ion-header>\n\n<ion-content>\n    <section class="details-demande">\n     <div class="overlay"></div>\n      <div class="content-scroll">\n           <div class="demande">\n                    <div class="varow-xs row-pad-sm">\n                          <div class="vacol-xs-8 text-left">\n                              <div class="border"></div>\n                              <h4>{{demande.type}}</h4>\n                              <span class="date">{{demande.cree}} : {{demande.created_at}}</span>\n                          </div>\n                          <div class="vacol-xs-4 text-center">\n                              <img src="{{demande.icone}}" class="imgicon" alt="">\n                              <h6 class="{{getColor(demande.statut)}}">{{demande.statut}}</h6>\n                          </div>\n                    </div>\n             </div>\n             <ul class="list-unstyled">\n                  <li *ngFor="let reponce of demande[\'reponses\']"><span>{{reponce.label}} : </span>{{reponce.reponse}}\n                  </li>\n             </ul>\n      </div>\n    </section>\n</ion-content>\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/demandes/details-demande/details-demande.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"], __WEBPACK_IMPORTED_MODULE_0__angular_core__["ElementRef"]])
    ], DetailsDemandePage);
    return DetailsDemandePage;
}());

//# sourceMappingURL=details-demande.js.map

/***/ }),

/***/ 614:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return RessourcesPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__pages_post_post__ = __webpack_require__(203);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var RessourcesPage = /** @class */ (function () {
    function RessourcesPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    RessourcesPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'ressources')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    RessourcesPage.prototype.ngOnInit = function () {
        this.getData();
    };
    RessourcesPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    RessourcesPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    RessourcesPage.prototype.post = function (post) {
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__pages_post_post__["a" /* PostPage */], {
            result: post
        });
    };
    RessourcesPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-ressources',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/ressources/ressources.html"*/'<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n      <div  class="menu_active_eleve">\n          <div class="img_container">\n              <div class="statut-eleve"></div>\n              <img class="img-responsive" src="{{eleve.img}}" />\n          </div>\n      </div>\n    </ion-navbar>\n  </ion-header>\n  \n  <ion-content padding>\n    <section *ngIf="_result?.data?.length > 0" >\n    <div class="card radius shadowDepth1" *ngFor="let result of _result.data" (click)="post(result)">\n        <div  [attr.data-text]="!result.image?result.categorie:\'\'" class="card__image border-tlr-radius">\n          <img  src="{{result.image}}" alt="" class="border-tlr-radius">\n        </div>\n    \n      <div class="card__content card__padding">\n        <div class="card__share">\n          <a id="share" class="share-toggle share-icon" href="#"></a>\n        </div>\n        <div class="card__meta">\n          <span class="categorie">{{result.categorie}}</span>\n          <time>{{result.date | amCalendar}}</time>\n        </div>\n    \n        <article class="card__article">\n          <h2>{{result.title}}</h2>\n          <p [innerHtml]="result.intro"></p>\n        </article>\n      </div>\n    \n      <div *ngIf="result.user" class="card__action">\n        <div class="card__author">\n          <img src="{{result.user.photo}}" alt="user">\n          <div class="card__author-content">\n            Par <a href="#">{{result.user.nom}}</a>\n          </div>\n        </div>\n      </div>\n    </div>\n    </section>\n  \n      <div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n          <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n          <h3 [innerHtml]="_result.empty_text"></h3>\n      </div>\n  \n  </ion-content>\n  '/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/ressources/ressources.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], RessourcesPage);
    return RessourcesPage;
}());

//# sourceMappingURL=ressources.js.map

/***/ }),

/***/ 615:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return DevoirsPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_in_app_browser__ = __webpack_require__(108);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_picker_ngx__ = __webpack_require__(79);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_chooser__ = __webpack_require__(80);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_base64__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_native_file_path__ = __webpack_require__(82);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__ionic_native_android_permissions__ = __webpack_require__(110);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};













var DevoirsPage = /** @class */ (function () {
    function DevoirsPage(navCtrl, navParams, alertCtrl, apiSerivce, loadingCtrl, base64, camera, fileChooser, filePicker, filePath, toastCtrl, sanitizer, iab, platform, file, androidPermissions, transfer) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.alertCtrl = alertCtrl;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.base64 = base64;
        this.camera = camera;
        this.fileChooser = fileChooser;
        this.filePicker = filePicker;
        this.filePath = filePath;
        this.toastCtrl = toastCtrl;
        this.sanitizer = sanitizer;
        this.iab = iab;
        this.platform = platform;
        this.file = file;
        this.androidPermissions = androidPermissions;
        this.transfer = transfer;
        this.platform = platform;
        this.eleve = __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    DevoirsPage.prototype.ngAfterViewInit = function () {
    };
    DevoirsPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'devoirs_date_v2')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    DevoirsPage.prototype.getInnerHTMLValue = function (result) {
        return this.sanitizer.bypassSecurityTrustHtml(result.description);
    };
    DevoirsPage.prototype.downloadFile = function (fileName, filePath) {
        var _this = this;
        //here encoding path as encodeURI() format.  
        var url = encodeURI(filePath);
        this.fileTransfer = this.transfer.create();
        var directory = '';
        if (this.platform.is('cordova')) {
            directory = this.file.externalRootDirectory + '/Download/';
        }
        else {
            directory = this.file.documentsDirectory;
        }
        this.androidPermissions.requestPermissions([
            this.androidPermissions.PERMISSION.WRITE_EXTERNAL_STORAGE
        ]).then(function (result) {
            // here iam mentioned this line this.file.externalRootDirectory is a native pre-defined file path storage. You can change a file path whatever pre-defined method.  
            _this.fileTransfer.download(url, directory + fileName, true).then(function (entry) {
                //here logging our success downloaded file path in mobile.  
                var alertSuccess = _this.alertCtrl.create({
                    title: "T\u00E9l\u00E9chargement r\u00E9ussi !",
                    cssClass: 'notification-alert',
                    message: fileName + " a \u00E9t\u00E9 t\u00E9l\u00E9charg\u00E9 avec succ\u00E8s sur : " + entry.toURL(),
                    buttons: ['Ok']
                });
                alertSuccess.present();
            }, function (error) {
                //here logging our error its easier to find out what type of error occured.  
                console.log('download failed: ' + error);
            });
        });
    };
    DevoirsPage.prototype.ngOnInit = function () {
        this.getData();
    };
    DevoirsPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    DevoirsPage.prototype.marquerFait = function (devoir) {
        this.send_file(devoir);
    };
    DevoirsPage.prototype.deleteFile = function (devoir, file) {
        var index = devoir.devoir_fait.files.indexOf(file);
        if (index !== -1) {
            devoir.devoir_fait.files.splice(index, 1);
        }
    };
    DevoirsPage.prototype.send_file = function (devoir) {
        var _this = this;
        var query = "&eleve_id=" + this.eleve.id +
            "&parent_id=" + __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].parentId +
            "&key=" + __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].keyToken +
            "&devoir=" + JSON.stringify({ id: devoir.id, files: devoir.devoir_fait.files });
        var loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].loaderHtml,
        });
        loading.present();
        this.apiSerivce.postData(query, 'devoirs_date_v2').subscribe(function (result) {
            var res = result;
            if (res.file_sent) {
                devoir.devoir_fait.file_sent = true;
                console.log(devoir);
            }
            else {
                devoir.devoir_fait.fait = true;
            }
            loading.dismiss();
            var alert = _this.alertCtrl.create({
                cssClass: 'success_alert_boti',
                title: res.title,
                message: res.message,
                buttons: [{
                        text: 'Fermer',
                        role: 'cancel',
                        handler: function () {
                        }
                    }]
            });
            alert.present();
        }, function (error) {
            _this.loading.dismiss();
        });
    };
    DevoirsPage.prototype.chooseFile = function (devoir) {
        if (this.platform.is("ios")) {
            this.chooseFileForIos(devoir);
        }
        else {
            this.chooseFileForAndroid(devoir);
        }
    };
    DevoirsPage.prototype.chooseFileForIos = function (devoir) {
        var _this = this;
        this.filePicker
            .pickFile()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(devoir, uri, false);
        })
            .catch(function (err) { return console.log("Error", err); });
    };
    DevoirsPage.prototype.chooseFileForAndroid = function (devoir) {
        var _this = this;
        this.fileChooser
            .open()
            .then(function (uri) {
            _this.presentToast("File chosen successfully");
            _this.convertToBase64(devoir, uri, false);
        })
            .catch(function (e) {
        });
    };
    DevoirsPage.prototype.convertToBase64 = function (devoir, imageUrl, isImage) {
        var _this = this;
        this.filePath
            .resolveNativePath(imageUrl)
            .then(function (filePath) {
            _this.base64.encodeFile(filePath).then(function (base64Fichier) {
                var fileName = filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length);
                _this.fileFormat = {
                    name: fileName,
                    extention: filePath.split(".").pop(),
                    base64File: base64Fichier.split(",").pop()
                };
                devoir.devoir_fait.files.push(_this.fileFormat);
                _this.fileFormat = null;
            }, function (err) {
            });
        })
            .catch(function (err) { return console.log(err); });
    };
    DevoirsPage.prototype.presentToast = function (message) {
        var toast = this.toastCtrl.create({
            message: message,
            duration: 3000,
            position: 'top'
        });
        toast.onDidDismiss(function () {
        });
        toast.present();
    };
    DevoirsPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    DevoirsPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-devoirs',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/devoirs/devoirs.html"*/'<ion-header no-border>\n\n  <ion-navbar transparent>\n\n    <button ion-button menuToggle>\n\n      <ion-icon name="menu"></ion-icon>\n\n    </button>\n\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n\n    <div  class="menu_notif" (click)="notifications()">\n\n        <div class="img_container">\n\n            <div *ngIf="newNotif" class="statut-notif"></div>\n\n            <img class="img-responsive" src="assets/imgs/notification.svg" />\n\n        </div>\n\n    </div>\n\n    <div  class="menu_active_eleve">\n\n        <div class="img_container">\n\n            <div class="statut-eleve"></div>\n\n            <img class="img-responsive" src="{{eleve.img}}" />\n\n        </div>\n\n    </div>\n\n  </ion-navbar>\n\n</ion-header>\n\n\n\n<ion-content>\n\n\n\n  <div *ngIf="_result?.empty == true" class="vertical-center no-result ">\n\n    <img src="{{_result.empty_icon}}" class="img-responsive" alt="Aucune donnée">\n\n    <h3 [innerHtml]="_result.empty_text"></h3>\n\n  </div>\n\n  <div class="container_result">\n\n    <section *ngIf="_result?.data?.length > 0">\n\n      <ngb-accordion #acc="ngbAccordion" class="card card-md">\n\n        <ngb-panel *ngFor="let result of _result.data">\n\n          <ng-template ngbPanelTitle>\n\n            <span class="devoirs-date">{{result.date}}</span>\n\n            <span class="devoirs-count">{{result.devoirs.length}}</span>\n\n          </ng-template>\n\n          <ng-template ngbPanelContent>\n\n              <div *ngFor="let devoir of result.devoirs">\n\n                  <article class="card__article">\n\n                    <h4 class="devoir-title">{{devoir.title}}</h4>\n\n                    <p [innerHtml]="getInnerHTMLValue(devoir)"></p>\n\n                    <div class="devoir_infos">\n\n                        <span *ngIf="devoir.matiere" class="devoir-matiere">{{devoir.matiere}}</span>\n\n                        <ion-icon name="person" class="timetable-icon"></ion-icon>\n\n                        <span *ngIf="devoir.enseignant" class="devoir-enseignant">{{devoir.enseignant}}</span>\n\n                        <button  *ngIf="devoir.file" (click)="downloadFile(devoir.file.name, devoir.file.link)" class="btn btn-main" >{{devoir.file.text}}</button>\n\n                    </div>\n\n                  </article>\n\n                  <div *ngIf="devoir.devoir_fait.fait && !devoir.devoir_fait.file_sent" class="card__file_upload">\n\n                      <p class="text_done">{{devoir.txt_fait}}</p>\n\n                      <div *ngFor="let file of devoir.devoir_fait.files" class="files">\n\n                          <div  class="exists-file-container" >\n\n                              <ion-icon name="ios-attach-outline"></ion-icon> <span>{{file.name}}</span>\n\n                              <ion-icon class="delete-btn" (click)="deleteFile(devoir, file)" name="ios-close-circle-outline"></ion-icon>\n\n                          </div>\n\n                      </div>\n\n                      <ion-grid>\n\n                        <ion-row align-items-center>\n\n                            <ion-col>\n\n                                <div (click)="chooseFile(devoir)" class="download-file-container" >\n\n                                    <ion-icon name="ios-attach-outline"></ion-icon> <span>Joindre un fichier</span>\n\n                                </div>\n\n                            </ion-col>\n\n                            <ion-col class="text-right">\n\n                              <button (click)="send_file(devoir)" [disabled]="devoir.devoir_fait.files.length == 0" class="btn btn-main" >Envoyer</button>\n\n                            </ion-col>\n\n                        </ion-row>\n\n                    </ion-grid>\n\n                  </div>\n\n                  <div *ngIf="!devoir.devoir_fait.fait" class="card__file">\n\n                      <button (click)="marquerFait(devoir)" class="btn btn-main" >{{devoir.txt_btn_fait}}</button>\n\n                  </div>\n\n                  <div *ngIf="devoir.devoir_fait.file_sent" class="card__file_sent">\n\n                      <img class="img-responsive" src="{{devoir.icon_sent}}" />\n\n                      <p>{{devoir.txt_sent}}</p>\n\n                  </div>\n\n                  <hr />\n\n              </div>\n\n          </ng-template>\n\n        </ngb-panel>\n\n      </ngb-accordion>\n\n    </section>\n\n\n\n  </div>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/devoirs/devoirs.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["AlertController"],
            __WEBPACK_IMPORTED_MODULE_12__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_7__ionic_native_base64__["a" /* Base64 */],
            __WEBPACK_IMPORTED_MODULE_6__ionic_native_camera__["a" /* Camera */],
            __WEBPACK_IMPORTED_MODULE_5__ionic_native_file_chooser__["a" /* FileChooser */],
            __WEBPACK_IMPORTED_MODULE_4__ionic_native_file_picker_ngx__["a" /* IOSFilePicker */],
            __WEBPACK_IMPORTED_MODULE_8__ionic_native_file_path__["a" /* FilePath */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["ToastController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_platform_browser__["DomSanitizer"],
            __WEBPACK_IMPORTED_MODULE_3__ionic_native_in_app_browser__["a" /* InAppBrowser */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Platform"],
            __WEBPACK_IMPORTED_MODULE_9__ionic_native_file__["a" /* File */],
            __WEBPACK_IMPORTED_MODULE_11__ionic_native_android_permissions__["a" /* AndroidPermissions */],
            __WEBPACK_IMPORTED_MODULE_10__ionic_native_file_transfer__["a" /* FileTransfer */]])
    ], DevoirsPage);
    return DevoirsPage;
}());

//# sourceMappingURL=devoirs.js.map

/***/ }),

/***/ 616:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ContactPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var ContactPage = /** @class */ (function () {
    function ContactPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
    }
    ContactPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    ContactPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    ContactPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'contact')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    ContactPage.prototype.ngOnInit = function () {
        this.getData();
    };
    ContactPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-contact',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/contact/contact.html"*/'<!--\n  Generated template for the ReservationPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header no-border>\n    <ion-navbar transparent>\n      <button ion-button menuToggle>\n        <ion-icon name="menu"></ion-icon>\n      </button>\n      <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n    </ion-navbar>\n</ion-header>\n\n<ion-content [ngClass]="{\'loadingblur\': loadingBlur}" padding>\n\n    <div class="vertical-center" *ngIf="_result?.data" >\n        <div class="container">\n            <div class="main-block text-center">\n                <img *ngIf="_result?.data?.icone" src="{{_result.data.icone}}" class="img-responsive " alt="">\n                <h3 *ngIf="_result?.data?.title" >{{_result.data.title}}</h3>\n                <p *ngIf="_result?.data?.text">{{_result.data.text}}</p>\n                <span *ngIf="_result?.data?.tel" class="phone"><a href="tel:{{_result.data.tel}}">{{_result.data.tel}}</a></span>\n\n                <div *ngIf="_result?.data?.socials" class="social-contact">\n                    <p *ngIf="_result?.data?.social_text" >{{_result.data.social_text}}</p>\n                    <a *ngFor="let social of _result.data.socials" href="{{social.link}}" [ngStyle]="{\'color\': social.color }" ><ion-icon name="{{social.icone}}"></ion-icon></a>\n                </div>\n                \n            </div>\n        </div>\n    </div>\n</ion-content>\n  \n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/contact/contact.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], ContactPage);
    return ContactPage;
}());

//# sourceMappingURL=contact.js.map

/***/ }),

/***/ 617:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MiseAJourPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__prof_planning_prof_planning__ = __webpack_require__(40);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};






var MiseAJourPage = /** @class */ (function () {
    function MiseAJourPage(navCtrl, navParams, apiSerivce, loadingCtrl, formBuilder) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.formBuilder = formBuilder;
    }
    MiseAJourPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    MiseAJourPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    MiseAJourPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].eleveId,
            enseignant_id: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId,
            key: __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'mise-a-jour')
            .subscribe(function (result) {
            _this._result = result;
            _this.loading.dismiss();
        }, function (error) { return console.log("Erreur :: " + error); });
    };
    MiseAJourPage.prototype.ngOnInit = function () {
        this.getData();
    };
    MiseAJourPage.prototype.closeModal = function () {
        if (__WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].parentId) {
            this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__nouveautes_nouveautes__["a" /* NouveautesPage */]);
        }
        else if (__WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */].enseignantId) {
            this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_5__prof_planning_prof_planning__["a" /* ProfPlanningPage */]);
        }
    };
    MiseAJourPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-mise-a-jour',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/mise-a-jour/mise-a-jour.html"*/'<ion-content [ngClass]="{\'loadingblur\': loadingBlur}" padding>\n    \n    <div class="vertical-center" *ngIf="_result?.data" >\n        <div class="container">\n            <div class="main-block text-center">\n                <img *ngIf="_result?.data?.icone" src="{{_result.data.icone}}" class="img-responsive " alt="">\n                <h3 *ngIf="_result?.data?.title" >{{_result.data.title}}</h3>\n                <p *ngIf="_result?.data?.text">{{_result.data.text}}</p>\n                <a *ngIf="_result?.data?.link" href="{{_result.data.link.href}}" >{{_result.data.link.text}}</a>\n                <button *ngIf="_result?.data?.optional && _result?.translation" ion-button (click)="closeModal()" [ngClass]="\'close-modal\'">\n                    {{_result.translation.annuler}}\n                </button>\n            </div>\n        </div>\n    </div>\n</ion-content>\n  \n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/mise-a-jour/mise-a-jour.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_4__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"],
            __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormBuilder"]])
    ], MiseAJourPage);
    return MiseAJourPage;
}());

//# sourceMappingURL=mise-a-jour.js.map

/***/ }),

/***/ 618:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return NoNetworkPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_storage__ = __webpack_require__(58);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var NoNetworkPage = /** @class */ (function () {
    function NoNetworkPage(navCtrl, navParams, events, storage, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.events = events;
        this.storage = storage;
        this.loadingCtrl = loadingCtrl;
        this.error = navParams.get('error');
    }
    NoNetworkPage.prototype.ngOnInit = function () {
    };
    NoNetworkPage.prototype.ressayer = function () {
        this.events.publish('compte:check');
    };
    NoNetworkPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-no-network',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/no-network/no-network.html"*/'<ion-content padding>\n    <div class="vertical-center" *ngIf="!error" >\n        <div class="container">\n            <div class="main-block text-center">\n                <img src="assets/imgs/no-network.svg" class="img-responsive " alt="">\n                <h3>Dommage.</h3>\n                <p>Absence de connexion internet. Veuillez vérifier votre connexion et réessayer.</p>\n				<button class="btn btn-main" (click)="ressayer()" >RÉESSAYER</button>\n            </div>\n        </div>\n    </div>\n\n    <div class="vertical-center"  *ngIf="error">\n        <div class="container">\n            <div class="main-block text-center error_500">\n                <div class="square"></div>\n                <img src="assets/imgs/error500.svg" class="img-responsive " alt="">\n                <h1>500</h1>\n                <h3>Dommage. Une erreur s\'est produite</h3>\n                <p>Absence de connexion internet. Veuillez vérifier votre connexion et réessayer.</p>\n				<button class="btn btn-main" (click)="ressayer()" >RÉESSAYER</button>\n            </div>\n        </div>\n    </div>\n</ion-content>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/no-network/no-network.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["Events"],
            __WEBPACK_IMPORTED_MODULE_2__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], NoNetworkPage);
    return NoNetworkPage;
}());

//# sourceMappingURL=no-network.js.map

/***/ }),

/***/ 619:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return EtatPaiementPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__ = __webpack_require__(7);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var EtatPaiementPage = /** @class */ (function () {
    function EtatPaiementPage(navCtrl, navParams, apiSerivce, loadingCtrl) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.apiSerivce = apiSerivce;
        this.loadingCtrl = loadingCtrl;
        this.eleve = __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].activeEleve;
    }
    EtatPaiementPage.prototype.ionViewWillEnter = function () {
        this.getData();
    };
    EtatPaiementPage.prototype.getData = function () {
        var _this = this;
        this.apiSerivce.getData({
            parent_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].parentId,
            eleve_id: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].eleveId,
            key: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].keyToken,
        }, 'paiements')
            .subscribe(function (results) {
            _this._result = results;
            _this.loading.dismiss();
        }, function (error) { return console.log("Error :: " + error); });
    };
    EtatPaiementPage.prototype.ionViewDidLoad = function () {
        this.presentLoadingCustom();
    };
    EtatPaiementPage.prototype.presentLoadingCustom = function () {
        this.loading = this.loadingCtrl.create({
            spinner: 'hide',
            content: __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */].loaderHtml,
            duration: 10000
        });
        this.loading.present();
    };
    EtatPaiementPage.prototype.progressColor = function (pctg) {
        if (pctg == 0) {
            return '#af222a';
        }
        else if (pctg < 50) {
            return '#3e71b2';
        }
        else {
            return '#e37931';
        }
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Input"])('progress'),
        __metadata("design:type", Object)
    ], EtatPaiementPage.prototype, "progress", void 0);
    EtatPaiementPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'page-etat-paiement',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/pages/etat-paiement/etat-paiement.html"*/'<ion-header no-border>\n\n  <ion-navbar transparent>\n\n    <button ion-button menuToggle>\n\n      <ion-icon name="menu"></ion-icon>\n\n    </button>\n\n    <ion-title *ngIf="_result?.translation">{{_result.translation.title}}</ion-title>\n\n    <div  class="menu_active_eleve">\n\n        <div class="img_container">\n\n            <div class="statut-eleve"></div>\n\n            <img class="img-responsive" src="{{eleve.img}}" />\n\n        </div>\n\n    </div>\n\n  </ion-navbar>\n\n</ion-header>\n\n\n\n<ion-content>\n\n    <div class="user-picture-top-page">\n\n        <div>\n\n            <img src="{{eleve.img}}" alt="{{eleve.nomcomplet}}">\n\n        </div>\n\n        <div>\n\n            <h1>{{eleve.nomcomplet}}</h1>\n\n            <span>{{eleve.niveau}}</span>\n\n        </div>\n\n    </div>\n\n  <section *ngIf="_result">\n\n    <ngb-accordion #acc="ngbAccordion" >\n\n        <ngb-panel *ngFor="let result of _result.etat">\n\n          <ng-template  ngbPanelTitle>\n\n                <div class="paiement-state" [ngStyle]="{\'background-color\': progressColor(result.pct)}"></div>\n\n                <span class="paiement-mois" >{{result.month}}</span>\n\n                <div class="progress-wrapper">\n\n                    <span class="pct">{{result.pct}} %</span>\n\n                    <span class="total-paye">{{_result.translation.total_paye}}</span>\n\n                    <round-progress [current]="result.pct" [responsive]="true" [duration]="4000" [stroke]="7" [max]="100" [color]="progressColor(result.pct)" [background]="\'#eaeaea\'"></round-progress>\n\n                </div>\n\n                <ion-icon name="ios-arrow-dropup-outline">\n\n                  <span>{{_result.translation.details}}</span>\n\n                </ion-icon>\n\n          </ng-template>\n\n\n\n          <ng-template ngbPanelContent>\n\n            <ion-item *ngFor="let detail of result.details" class="details_encaissement">\n\n                <div class="paiement-details-item">\n\n                  <h2> <i class="fa fa-{{detail.icone}}"></i>{{detail.label}} <span>({{detail.montant}} dhs - {{detail.pctg}} %)</span></h2>\n\n                </div>\n\n              </ion-item>\n\n          </ng-template>\n\n\n\n          <ng-template ngbPanelContent>\n\n            <ion-item *ngFor="let encaissement of result.encaissements">\n\n                <div class="paiement-encaissements-item">\n\n                  <h2>{{encaissement.datepaiement}} <span>({{encaissement.recu}}  - {{encaissement.montant}} dhs - {{encaissement.modepaiement}} %)</span></h2>\n\n                </div>\n\n              </ion-item>\n\n          </ng-template>\n\n        </ngb-panel>\n\n      </ngb-accordion>\n\n    </section>\n\n</ion-content>\n\n'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/pages/etat-paiement/etat-paiement.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavController"],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["NavParams"],
            __WEBPACK_IMPORTED_MODULE_2__providers_apiservice__["a" /* ApiService */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["LoadingController"]])
    ], EtatPaiementPage);
    return EtatPaiementPage;
}());

//# sourceMappingURL=etat-paiement.js.map

/***/ }),

/***/ 621:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser_dynamic__ = __webpack_require__(622);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__app_module__ = __webpack_require__(626);



Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["enableProdMode"])();
Object(__WEBPACK_IMPORTED_MODULE_0__angular_platform_browser_dynamic__["a" /* platformBrowserDynamic */])().bootstrapModule(__WEBPACK_IMPORTED_MODULE_2__app_module__["a" /* AppModule */]);
//# sourceMappingURL=main.js.map

/***/ }),

/***/ 626:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_ionic_angular__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__angular_http__ = __webpack_require__(306);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__ionic_storage__ = __webpack_require__(58);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__angular_common__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__ = __webpack_require__(664);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8_angular2_moment_moment_module__ = __webpack_require__(951);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8_angular2_moment_moment_module___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_8_angular2_moment_moment_module__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__ionic_native_sim__ = __webpack_require__(581);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10_angular_svg_round_progressbar__ = __webpack_require__(970);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10_angular_svg_round_progressbar___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_10_angular_svg_round_progressbar__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__ionic_native_file__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__ionic_native_file_transfer__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__ionic_native_camera__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_14__ionic_native_file_picker_ngx__ = __webpack_require__(79);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_15__ionic_native_file_chooser__ = __webpack_require__(80);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__ionic_native_base64__ = __webpack_require__(81);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_17__ionic_native_file_path__ = __webpack_require__(82);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_18__ionic_native_in_app_browser__ = __webpack_require__(108);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_19__ionic_native_app_version__ = __webpack_require__(584);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_20__ionic_native_network__ = __webpack_require__(585);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_21_ion2_calendar__ = __webpack_require__(971);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_21_ion2_calendar___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_21_ion2_calendar__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_22__ionic_native_youtube_video_player__ = __webpack_require__(590);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_23__ionic_native_android_permissions__ = __webpack_require__(110);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_24__components_accordion_accordion__ = __webpack_require__(975);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_25__app_component__ = __webpack_require__(111);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_26__pages_home_home__ = __webpack_require__(112);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_27__pages_connexion_connexion__ = __webpack_require__(202);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_28__pages_discipline_discipline__ = __webpack_require__(608);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_29__pages_cours_cours__ = __webpack_require__(606);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_30__pages_messages_messages__ = __webpack_require__(207);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_31__pages_messages_nouveau_message_nouveau_message__ = __webpack_require__(208);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_32__pages_messages_conversation_conversation__ = __webpack_require__(609);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_33__pages_notifications_notifications__ = __webpack_require__(594);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_34__pages_ressources_ressources__ = __webpack_require__(614);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_35__pages_devoirs_devoirs__ = __webpack_require__(615);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_36__pages_nouveautes_nouveautes__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_37__pages_post_post__ = __webpack_require__(203);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_38__pages_demandes_demandes__ = __webpack_require__(209);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_39__pages_forgot_password_forgot_password__ = __webpack_require__(595);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_40__pages_change_password_change_password__ = __webpack_require__(612);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_41__pages_connexion_phonenumber_connexion_phonenumber__ = __webpack_require__(596);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_42__pages_demandes_nouvelle_demande_nouvelle_demande__ = __webpack_require__(210);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_43__pages_demandes_details_demande_details_demande__ = __webpack_require__(613);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_44__pages_compte_eleve_compte_eleve__ = __webpack_require__(610);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_45__pages_compte_parent_compte_parent__ = __webpack_require__(611);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_46__pages_compte_prof_compte_prof__ = __webpack_require__(601);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_47__pages_absences_absences__ = __webpack_require__(206);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_48__pages_compte_compte__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_49__pages_contact_contact__ = __webpack_require__(616);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_50__pages_mise_a_jour_mise_a_jour__ = __webpack_require__(617);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_51__pages_no_network_no_network__ = __webpack_require__(618);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_52__pages_etat_paiement_etat_paiement__ = __webpack_require__(619);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_53__pages_examens_examens__ = __webpack_require__(607);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_54__pages_suivipedagogique_tabs_suivipedagogique_tabs__ = __webpack_require__(205);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_55__providers_apiservice__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_56__pages_prof_planning_prof_planning__ = __webpack_require__(40);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_57__pages_prof_cours_details_prof_cours_details__ = __webpack_require__(597);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_58__pages_prof_examens_prof_examens__ = __webpack_require__(602);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_59__pages_prof_examens_details_prof_examens_details__ = __webpack_require__(603);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_60__pages_prof_devoirs_prof_devoirs__ = __webpack_require__(598);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_61__pages_prof_devoir_form_prof_devoir_form__ = __webpack_require__(599);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_62__pages_prof_devoir_details_prof_devoir_details__ = __webpack_require__(600);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_63__pages_prof_messages_prof_messages__ = __webpack_require__(604);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_64__pages_prof_messages_prof_nouveau_message_prof_nouveau_message__ = __webpack_require__(204);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_65__pages_prof_messages_prof_conversation_prof_conversation__ = __webpack_require__(605);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_66__ionic_native_status_bar__ = __webpack_require__(591);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_67__ionic_native_splash_screen__ = __webpack_require__(592);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_68__ionic_native_firebase__ = __webpack_require__(593);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_69__providers_fcm_fcm__ = __webpack_require__(113);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_70_angularfire2__ = __webpack_require__(976);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
























// Component 












































/*
const firebase = {
  apiKey: "AIzaSyBZ_F3GCZAvYeGtOBZ4KypF17zwVRqZ5Zg",
  authDomain: "mariefrance-10cfd.firebaseapp.com",
  projectId: "mariefrance-10cfd",
  storageBucket: "mariefrance-10cfd.appspot.com",
  messagingSenderId: "495878342683",
  appId: "1:495878342683:web:9607df104fb8e645bed0be"
}
// Marie de France */
/*
const firebase = {
    apiKey: "AIzaSyCvTql3x0_gQIEUgoL0f4_3vq91ftlgWMo",
    authDomain: "lissen-1cad5.firebaseapp.com",
    databaseURL: "https://lissen-1cad5.firebaseio.com",
    projectId: "lissen-1cad5",
    storageBucket: "lissen-1cad5.appspot.com",
    messagingSenderId: "192606816103",
    appId: "1:192606816103:web:fb8fe4f1a7c55a47c691c2",
    measurementId: "G-003CJ43ZZB"
}
// LISSEN */
/*
const firebase = {
    apiKey: "AIzaSyCOfnypezTdNxWyCKkr2YPxmVmr6ufClYQ",
    authDomain: "gs-azzaitoune.firebaseapp.com",
    databaseURL: "https://gs-azzaitoune.firebaseio.com",
    projectId: "gs-azzaitoune",
    storageBucket: "gs-azzaitoune.appspot.com",
    messagingSenderId: "38088867921",
    appId: "1:38088867921:web:cf05e574793e11eb563870",
    measurementId: "G-4TKHYKZ7MR"
}
// GS AZZAITOUNE */
/*
const firebase = {
    apiKey: "AIzaSyCy1FW1GYwhkwrYcHgvv6Gm0sebHcpFAGE",
    authDomain: "escalade-school.firebaseapp.com",
    databaseURL: "https://escalade-school.firebaseio.com",
    projectId: "escalade-school",
    storageBucket: "",
    messagingSenderId: "985144791506",
    appId: "1:985144791506:web:1b328fabcbe23e66c6b80b"
    
}
// Escalade */
//*
var firebase = {
    apiKey: "AIzaSyCstrLqHxobcAwNsL3QDpgZG17KhrzhAr8",
    authDomain: "gs-ouhoud.firebaseapp.com",
    databaseURL: "https://gs-ouhoud.firebaseio.com",
    projectId: "gs-ouhoud",
    storageBucket: "gs-ouhoud.appspot.com",
    messagingSenderId: "1068592451085"
};
// GS OUHOUD*/



var AppModule = /** @class */ (function () {
    function AppModule() {
    }
    AppModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["NgModule"])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_25__app_component__["a" /* MyApp */],
                __WEBPACK_IMPORTED_MODULE_26__pages_home_home__["a" /* HomePage */],
                __WEBPACK_IMPORTED_MODULE_54__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */],
                __WEBPACK_IMPORTED_MODULE_24__components_accordion_accordion__["a" /* AccordionComponent */],
                __WEBPACK_IMPORTED_MODULE_29__pages_cours_cours__["a" /* CoursPage */],
                __WEBPACK_IMPORTED_MODULE_53__pages_examens_examens__["a" /* ExamensPage */],
                __WEBPACK_IMPORTED_MODULE_47__pages_absences_absences__["a" /* AbsencesPage */],
                __WEBPACK_IMPORTED_MODULE_28__pages_discipline_discipline__["a" /* DisciplinePage */],
                __WEBPACK_IMPORTED_MODULE_52__pages_etat_paiement_etat_paiement__["a" /* EtatPaiementPage */],
                __WEBPACK_IMPORTED_MODULE_49__pages_contact_contact__["a" /* ContactPage */],
                __WEBPACK_IMPORTED_MODULE_48__pages_compte_compte__["a" /* ComptePage */],
                __WEBPACK_IMPORTED_MODULE_30__pages_messages_messages__["a" /* MessagesPage */],
                __WEBPACK_IMPORTED_MODULE_31__pages_messages_nouveau_message_nouveau_message__["a" /* NouveauMessagePage */],
                __WEBPACK_IMPORTED_MODULE_32__pages_messages_conversation_conversation__["a" /* ConversationPage */],
                __WEBPACK_IMPORTED_MODULE_35__pages_devoirs_devoirs__["a" /* DevoirsPage */],
                __WEBPACK_IMPORTED_MODULE_36__pages_nouveautes_nouveautes__["a" /* NouveautesPage */],
                __WEBPACK_IMPORTED_MODULE_38__pages_demandes_demandes__["a" /* DemandesPage */],
                __WEBPACK_IMPORTED_MODULE_42__pages_demandes_nouvelle_demande_nouvelle_demande__["a" /* NouvelleDemandePage */],
                __WEBPACK_IMPORTED_MODULE_43__pages_demandes_details_demande_details_demande__["a" /* DetailsDemandePage */],
                __WEBPACK_IMPORTED_MODULE_34__pages_ressources_ressources__["a" /* RessourcesPage */],
                __WEBPACK_IMPORTED_MODULE_37__pages_post_post__["a" /* PostPage */],
                __WEBPACK_IMPORTED_MODULE_51__pages_no_network_no_network__["a" /* NoNetworkPage */],
                __WEBPACK_IMPORTED_MODULE_44__pages_compte_eleve_compte_eleve__["a" /* CompteElevePage */],
                __WEBPACK_IMPORTED_MODULE_45__pages_compte_parent_compte_parent__["a" /* CompteParentPage */],
                __WEBPACK_IMPORTED_MODULE_46__pages_compte_prof_compte_prof__["a" /* CompteProfPage */],
                __WEBPACK_IMPORTED_MODULE_39__pages_forgot_password_forgot_password__["a" /* ForgotPasswordPage */],
                __WEBPACK_IMPORTED_MODULE_40__pages_change_password_change_password__["a" /* ChangePasswordPage */],
                __WEBPACK_IMPORTED_MODULE_41__pages_connexion_phonenumber_connexion_phonenumber__["a" /* ConnexionPhoneNumberPage */],
                __WEBPACK_IMPORTED_MODULE_33__pages_notifications_notifications__["a" /* NotificationsPage */],
                __WEBPACK_IMPORTED_MODULE_50__pages_mise_a_jour_mise_a_jour__["a" /* MiseAJourPage */],
                __WEBPACK_IMPORTED_MODULE_27__pages_connexion_connexion__["a" /* ConnexionPage */],
                __WEBPACK_IMPORTED_MODULE_56__pages_prof_planning_prof_planning__["a" /* ProfPlanningPage */],
                __WEBPACK_IMPORTED_MODULE_57__pages_prof_cours_details_prof_cours_details__["a" /* ProfCoursDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_60__pages_prof_devoirs_prof_devoirs__["a" /* ProfDevoirsPage */],
                __WEBPACK_IMPORTED_MODULE_58__pages_prof_examens_prof_examens__["a" /* ProfExamensPage */],
                __WEBPACK_IMPORTED_MODULE_59__pages_prof_examens_details_prof_examens_details__["a" /* ProfExamensDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_61__pages_prof_devoir_form_prof_devoir_form__["a" /* ProfDevoirFormPage */],
                __WEBPACK_IMPORTED_MODULE_62__pages_prof_devoir_details_prof_devoir_details__["a" /* ProfDevoirDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_63__pages_prof_messages_prof_messages__["a" /* ProfMessagesPage */],
                __WEBPACK_IMPORTED_MODULE_64__pages_prof_messages_prof_nouveau_message_prof_nouveau_message__["a" /* ProfNouveauMessagePage */],
                __WEBPACK_IMPORTED_MODULE_65__pages_prof_messages_prof_conversation_prof_conversation__["a" /* ProfConversationPage */]
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__["BrowserModule"],
                __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__["c" /* NgbModule */],
                __WEBPACK_IMPORTED_MODULE_8_angular2_moment_moment_module__["MomentModule"],
                __WEBPACK_IMPORTED_MODULE_10_angular_svg_round_progressbar__["RoundProgressModule"],
                __WEBPACK_IMPORTED_MODULE_3_ionic_angular__["IonicModule"].forRoot(__WEBPACK_IMPORTED_MODULE_25__app_component__["a" /* MyApp */], {}, {
                    links: []
                }),
                __WEBPACK_IMPORTED_MODULE_70_angularfire2__["a" /* AngularFireModule */].initializeApp(firebase),
                __WEBPACK_IMPORTED_MODULE_5__ionic_storage__["a" /* IonicStorageModule */].forRoot(),
                __WEBPACK_IMPORTED_MODULE_21_ion2_calendar__["CalendarModule"],
                __WEBPACK_IMPORTED_MODULE_4__angular_http__["c" /* HttpModule */],
                __WEBPACK_IMPORTED_MODULE_2__angular_forms__["FormsModule"]
            ],
            bootstrap: [__WEBPACK_IMPORTED_MODULE_3_ionic_angular__["IonicApp"]],
            entryComponents: [
                __WEBPACK_IMPORTED_MODULE_25__app_component__["a" /* MyApp */],
                __WEBPACK_IMPORTED_MODULE_26__pages_home_home__["a" /* HomePage */],
                __WEBPACK_IMPORTED_MODULE_54__pages_suivipedagogique_tabs_suivipedagogique_tabs__["a" /* SuiviPedagogiqueTabsPage */],
                __WEBPACK_IMPORTED_MODULE_29__pages_cours_cours__["a" /* CoursPage */],
                __WEBPACK_IMPORTED_MODULE_53__pages_examens_examens__["a" /* ExamensPage */],
                __WEBPACK_IMPORTED_MODULE_47__pages_absences_absences__["a" /* AbsencesPage */],
                __WEBPACK_IMPORTED_MODULE_28__pages_discipline_discipline__["a" /* DisciplinePage */],
                __WEBPACK_IMPORTED_MODULE_52__pages_etat_paiement_etat_paiement__["a" /* EtatPaiementPage */],
                __WEBPACK_IMPORTED_MODULE_49__pages_contact_contact__["a" /* ContactPage */],
                __WEBPACK_IMPORTED_MODULE_48__pages_compte_compte__["a" /* ComptePage */],
                __WEBPACK_IMPORTED_MODULE_30__pages_messages_messages__["a" /* MessagesPage */],
                __WEBPACK_IMPORTED_MODULE_31__pages_messages_nouveau_message_nouveau_message__["a" /* NouveauMessagePage */],
                __WEBPACK_IMPORTED_MODULE_32__pages_messages_conversation_conversation__["a" /* ConversationPage */],
                __WEBPACK_IMPORTED_MODULE_35__pages_devoirs_devoirs__["a" /* DevoirsPage */],
                __WEBPACK_IMPORTED_MODULE_36__pages_nouveautes_nouveautes__["a" /* NouveautesPage */],
                __WEBPACK_IMPORTED_MODULE_38__pages_demandes_demandes__["a" /* DemandesPage */],
                __WEBPACK_IMPORTED_MODULE_42__pages_demandes_nouvelle_demande_nouvelle_demande__["a" /* NouvelleDemandePage */],
                __WEBPACK_IMPORTED_MODULE_43__pages_demandes_details_demande_details_demande__["a" /* DetailsDemandePage */],
                __WEBPACK_IMPORTED_MODULE_34__pages_ressources_ressources__["a" /* RessourcesPage */],
                __WEBPACK_IMPORTED_MODULE_37__pages_post_post__["a" /* PostPage */],
                __WEBPACK_IMPORTED_MODULE_39__pages_forgot_password_forgot_password__["a" /* ForgotPasswordPage */],
                __WEBPACK_IMPORTED_MODULE_40__pages_change_password_change_password__["a" /* ChangePasswordPage */],
                __WEBPACK_IMPORTED_MODULE_41__pages_connexion_phonenumber_connexion_phonenumber__["a" /* ConnexionPhoneNumberPage */],
                __WEBPACK_IMPORTED_MODULE_51__pages_no_network_no_network__["a" /* NoNetworkPage */],
                __WEBPACK_IMPORTED_MODULE_50__pages_mise_a_jour_mise_a_jour__["a" /* MiseAJourPage */],
                __WEBPACK_IMPORTED_MODULE_33__pages_notifications_notifications__["a" /* NotificationsPage */],
                __WEBPACK_IMPORTED_MODULE_44__pages_compte_eleve_compte_eleve__["a" /* CompteElevePage */],
                __WEBPACK_IMPORTED_MODULE_45__pages_compte_parent_compte_parent__["a" /* CompteParentPage */],
                __WEBPACK_IMPORTED_MODULE_46__pages_compte_prof_compte_prof__["a" /* CompteProfPage */],
                __WEBPACK_IMPORTED_MODULE_27__pages_connexion_connexion__["a" /* ConnexionPage */],
                __WEBPACK_IMPORTED_MODULE_56__pages_prof_planning_prof_planning__["a" /* ProfPlanningPage */],
                __WEBPACK_IMPORTED_MODULE_57__pages_prof_cours_details_prof_cours_details__["a" /* ProfCoursDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_60__pages_prof_devoirs_prof_devoirs__["a" /* ProfDevoirsPage */],
                __WEBPACK_IMPORTED_MODULE_58__pages_prof_examens_prof_examens__["a" /* ProfExamensPage */],
                __WEBPACK_IMPORTED_MODULE_59__pages_prof_examens_details_prof_examens_details__["a" /* ProfExamensDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_61__pages_prof_devoir_form_prof_devoir_form__["a" /* ProfDevoirFormPage */],
                __WEBPACK_IMPORTED_MODULE_62__pages_prof_devoir_details_prof_devoir_details__["a" /* ProfDevoirDetailsPage */],
                __WEBPACK_IMPORTED_MODULE_63__pages_prof_messages_prof_messages__["a" /* ProfMessagesPage */],
                __WEBPACK_IMPORTED_MODULE_64__pages_prof_messages_prof_nouveau_message_prof_nouveau_message__["a" /* ProfNouveauMessagePage */],
                __WEBPACK_IMPORTED_MODULE_65__pages_prof_messages_prof_conversation_prof_conversation__["a" /* ProfConversationPage */]
            ],
            providers: [
                __WEBPACK_IMPORTED_MODULE_66__ionic_native_status_bar__["a" /* StatusBar */],
                __WEBPACK_IMPORTED_MODULE_9__ionic_native_sim__["a" /* Sim */],
                __WEBPACK_IMPORTED_MODULE_67__ionic_native_splash_screen__["a" /* SplashScreen */],
                __WEBPACK_IMPORTED_MODULE_6__angular_common__["DatePipe"],
                __WEBPACK_IMPORTED_MODULE_13__ionic_native_camera__["a" /* Camera */],
                __WEBPACK_IMPORTED_MODULE_11__ionic_native_file__["a" /* File */],
                __WEBPACK_IMPORTED_MODULE_20__ionic_native_network__["a" /* Network */],
                __WEBPACK_IMPORTED_MODULE_12__ionic_native_file_transfer__["a" /* FileTransfer */],
                __WEBPACK_IMPORTED_MODULE_19__ionic_native_app_version__["a" /* AppVersion */],
                __WEBPACK_IMPORTED_MODULE_18__ionic_native_in_app_browser__["a" /* InAppBrowser */],
                __WEBPACK_IMPORTED_MODULE_55__providers_apiservice__["a" /* ApiService */],
                __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__["c" /* NgbModule */],
                __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__["d" /* NgbTabsetConfig */],
                __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__["a" /* NgbAccordionConfig */],
                __WEBPACK_IMPORTED_MODULE_7__ng_bootstrap_ng_bootstrap__["b" /* NgbDatepickerConfig */],
                __WEBPACK_IMPORTED_MODULE_68__ionic_native_firebase__["a" /* Firebase */],
                __WEBPACK_IMPORTED_MODULE_69__providers_fcm_fcm__["a" /* FcmProvider */],
                __WEBPACK_IMPORTED_MODULE_14__ionic_native_file_picker_ngx__["a" /* IOSFilePicker */],
                __WEBPACK_IMPORTED_MODULE_15__ionic_native_file_chooser__["a" /* FileChooser */],
                __WEBPACK_IMPORTED_MODULE_17__ionic_native_file_path__["a" /* FilePath */],
                __WEBPACK_IMPORTED_MODULE_16__ionic_native_base64__["a" /* Base64 */],
                __WEBPACK_IMPORTED_MODULE_22__ionic_native_youtube_video_player__["a" /* YoutubeVideoPlayer */],
                __WEBPACK_IMPORTED_MODULE_23__ionic_native_android_permissions__["a" /* AndroidPermissions */],
                { provide: __WEBPACK_IMPORTED_MODULE_1__angular_core__["ErrorHandler"], useClass: __WEBPACK_IMPORTED_MODULE_3_ionic_angular__["IonicErrorHandler"] }
            ]
        })
    ], AppModule);
    return AppModule;
}());

//# sourceMappingURL=app.module.js.map

/***/ }),

/***/ 7:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ApiService; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_http__ = __webpack_require__(306);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_Observable__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_Observable___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_rxjs_Observable__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_Rx__ = __webpack_require__(182);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_Rx___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_rxjs_Rx__);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var ApiService = /** @class */ (function () {
    function ApiService(http) {
        this.http = http;
        this._postsURL = "https://boti.education/p/mariefrance/botiapi/";
    }
    ApiService_1 = ApiService;
    ApiService.prototype.getData = function (params, url) {
        return this.http
            .get(this._postsURL + url, {
            params: params
        })
            .map(function (response) {
            return response.json();
        })
            .catch(this.handleError);
    };
    ApiService.prototype.postData = function (params, url) {
        if (ApiService_1.urlLoadingPost)
            return null;
        ApiService_1.urlLoadingPost = true;
        var type = "application/x-www-form-urlencoded; charset=UTF-8", headers = new __WEBPACK_IMPORTED_MODULE_1__angular_http__["a" /* Headers */]({ 'Content-Type': type }), options = new __WEBPACK_IMPORTED_MODULE_1__angular_http__["d" /* RequestOptions */]({ headers: headers });
        return this.http
            .post(this._postsURL + url, params, options)
            .map(function (response) {
            ApiService_1.urlLoadingPost = false;
            return response.json();
        })
            .catch(this.handleError);
    };
    ApiService.prototype.handleError = function (error) {
        ApiService_1.urlLoadingPost = false;
        ApiService_1.urlLoadingGet = false;
        console.log(error);
        return __WEBPACK_IMPORTED_MODULE_2_rxjs_Observable__["Observable"].throw(error.statusText);
    };
    ApiService.versionAr = false;
    ApiService.loaderHtml = "\n    <div class=\"custom-spinner-container\">\n      <div class=\"custom-spinner-box\">\n      <img src=\"assets/imgs/loading/three-dots.svg\" />\n      <p>Veuillez patienter...</p>\n      </div>\n    </div>";
    ApiService = ApiService_1 = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Injectable"])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1__angular_http__["b" /* Http */]])
    ], ApiService);
    return ApiService;
    var ApiService_1;
}());

//# sourceMappingURL=apiservice.js.map

/***/ }),

/***/ 954:
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./af": 449,
	"./af.js": 449,
	"./ar": 450,
	"./ar-dz": 451,
	"./ar-dz.js": 451,
	"./ar-kw": 452,
	"./ar-kw.js": 452,
	"./ar-ly": 453,
	"./ar-ly.js": 453,
	"./ar-ma": 454,
	"./ar-ma.js": 454,
	"./ar-sa": 455,
	"./ar-sa.js": 455,
	"./ar-tn": 456,
	"./ar-tn.js": 456,
	"./ar.js": 450,
	"./az": 457,
	"./az.js": 457,
	"./be": 458,
	"./be.js": 458,
	"./bg": 459,
	"./bg.js": 459,
	"./bm": 460,
	"./bm.js": 460,
	"./bn": 461,
	"./bn.js": 461,
	"./bo": 462,
	"./bo.js": 462,
	"./br": 463,
	"./br.js": 463,
	"./bs": 464,
	"./bs.js": 464,
	"./ca": 465,
	"./ca.js": 465,
	"./cs": 466,
	"./cs.js": 466,
	"./cv": 467,
	"./cv.js": 467,
	"./cy": 468,
	"./cy.js": 468,
	"./da": 469,
	"./da.js": 469,
	"./de": 470,
	"./de-at": 471,
	"./de-at.js": 471,
	"./de-ch": 472,
	"./de-ch.js": 472,
	"./de.js": 470,
	"./dv": 473,
	"./dv.js": 473,
	"./el": 474,
	"./el.js": 474,
	"./en-au": 475,
	"./en-au.js": 475,
	"./en-ca": 476,
	"./en-ca.js": 476,
	"./en-gb": 477,
	"./en-gb.js": 477,
	"./en-ie": 478,
	"./en-ie.js": 478,
	"./en-il": 479,
	"./en-il.js": 479,
	"./en-in": 480,
	"./en-in.js": 480,
	"./en-nz": 481,
	"./en-nz.js": 481,
	"./en-sg": 482,
	"./en-sg.js": 482,
	"./eo": 483,
	"./eo.js": 483,
	"./es": 484,
	"./es-do": 485,
	"./es-do.js": 485,
	"./es-us": 486,
	"./es-us.js": 486,
	"./es.js": 484,
	"./et": 487,
	"./et.js": 487,
	"./eu": 488,
	"./eu.js": 488,
	"./fa": 489,
	"./fa.js": 489,
	"./fi": 490,
	"./fi.js": 490,
	"./fil": 491,
	"./fil.js": 491,
	"./fo": 492,
	"./fo.js": 492,
	"./fr": 493,
	"./fr-ca": 494,
	"./fr-ca.js": 494,
	"./fr-ch": 495,
	"./fr-ch.js": 495,
	"./fr.js": 493,
	"./fy": 496,
	"./fy.js": 496,
	"./ga": 497,
	"./ga.js": 497,
	"./gd": 498,
	"./gd.js": 498,
	"./gl": 499,
	"./gl.js": 499,
	"./gom-deva": 500,
	"./gom-deva.js": 500,
	"./gom-latn": 501,
	"./gom-latn.js": 501,
	"./gu": 502,
	"./gu.js": 502,
	"./he": 503,
	"./he.js": 503,
	"./hi": 504,
	"./hi.js": 504,
	"./hr": 505,
	"./hr.js": 505,
	"./hu": 506,
	"./hu.js": 506,
	"./hy-am": 507,
	"./hy-am.js": 507,
	"./id": 508,
	"./id.js": 508,
	"./is": 509,
	"./is.js": 509,
	"./it": 510,
	"./it-ch": 511,
	"./it-ch.js": 511,
	"./it.js": 510,
	"./ja": 512,
	"./ja.js": 512,
	"./jv": 513,
	"./jv.js": 513,
	"./ka": 514,
	"./ka.js": 514,
	"./kk": 515,
	"./kk.js": 515,
	"./km": 516,
	"./km.js": 516,
	"./kn": 517,
	"./kn.js": 517,
	"./ko": 518,
	"./ko.js": 518,
	"./ku": 519,
	"./ku.js": 519,
	"./ky": 520,
	"./ky.js": 520,
	"./lb": 521,
	"./lb.js": 521,
	"./lo": 522,
	"./lo.js": 522,
	"./lt": 523,
	"./lt.js": 523,
	"./lv": 524,
	"./lv.js": 524,
	"./me": 525,
	"./me.js": 525,
	"./mi": 526,
	"./mi.js": 526,
	"./mk": 527,
	"./mk.js": 527,
	"./ml": 528,
	"./ml.js": 528,
	"./mn": 529,
	"./mn.js": 529,
	"./mr": 530,
	"./mr.js": 530,
	"./ms": 531,
	"./ms-my": 532,
	"./ms-my.js": 532,
	"./ms.js": 531,
	"./mt": 533,
	"./mt.js": 533,
	"./my": 534,
	"./my.js": 534,
	"./nb": 535,
	"./nb.js": 535,
	"./ne": 536,
	"./ne.js": 536,
	"./nl": 537,
	"./nl-be": 538,
	"./nl-be.js": 538,
	"./nl.js": 537,
	"./nn": 539,
	"./nn.js": 539,
	"./oc-lnc": 540,
	"./oc-lnc.js": 540,
	"./pa-in": 541,
	"./pa-in.js": 541,
	"./pl": 542,
	"./pl.js": 542,
	"./pt": 543,
	"./pt-br": 544,
	"./pt-br.js": 544,
	"./pt.js": 543,
	"./ro": 545,
	"./ro.js": 545,
	"./ru": 546,
	"./ru.js": 546,
	"./sd": 547,
	"./sd.js": 547,
	"./se": 548,
	"./se.js": 548,
	"./si": 549,
	"./si.js": 549,
	"./sk": 550,
	"./sk.js": 550,
	"./sl": 551,
	"./sl.js": 551,
	"./sq": 552,
	"./sq.js": 552,
	"./sr": 553,
	"./sr-cyrl": 554,
	"./sr-cyrl.js": 554,
	"./sr.js": 553,
	"./ss": 555,
	"./ss.js": 555,
	"./sv": 556,
	"./sv.js": 556,
	"./sw": 557,
	"./sw.js": 557,
	"./ta": 558,
	"./ta.js": 558,
	"./te": 559,
	"./te.js": 559,
	"./tet": 560,
	"./tet.js": 560,
	"./tg": 561,
	"./tg.js": 561,
	"./th": 562,
	"./th.js": 562,
	"./tl-ph": 563,
	"./tl-ph.js": 563,
	"./tlh": 564,
	"./tlh.js": 564,
	"./tr": 565,
	"./tr.js": 565,
	"./tzl": 566,
	"./tzl.js": 566,
	"./tzm": 567,
	"./tzm-latn": 568,
	"./tzm-latn.js": 568,
	"./tzm.js": 567,
	"./ug-cn": 569,
	"./ug-cn.js": 569,
	"./uk": 570,
	"./uk.js": 570,
	"./ur": 571,
	"./ur.js": 571,
	"./uz": 572,
	"./uz-latn": 573,
	"./uz-latn.js": 573,
	"./uz.js": 572,
	"./vi": 574,
	"./vi.js": 574,
	"./x-pseudo": 575,
	"./x-pseudo.js": 575,
	"./yo": 576,
	"./yo.js": 576,
	"./zh-cn": 577,
	"./zh-cn.js": 577,
	"./zh-hk": 578,
	"./zh-hk.js": 578,
	"./zh-mo": 579,
	"./zh-mo.js": 579,
	"./zh-tw": 580,
	"./zh-tw.js": 580
};
function webpackContext(req) {
	return __webpack_require__(webpackContextResolve(req));
};
function webpackContextResolve(req) {
	var id = map[req];
	if(!(id + 1)) // check for number or string
		throw new Error("Cannot find module '" + req + "'.");
	return id;
};
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 954;

/***/ }),

/***/ 975:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AccordionComponent; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};

var AccordionComponent = /** @class */ (function () {
    function AccordionComponent(renderer) {
        this.renderer = renderer;
        this.accordionExapanded = false;
    }
    AccordionComponent.prototype.ngOnInit = function () {
        this.renderer.setElementStyle(this.accordionContent.nativeElement, "transition", "all 500ms ease");
        this.renderer.setElementStyle(this.accordionContent.nativeElement, "webkitTransition", "all 500ms ease");
    };
    AccordionComponent.prototype.toggleAccordion = function () {
        if (this.accordionExapanded) {
            this.renderer.setElementStyle(this.accordionContent.nativeElement, "max-height", "0px");
        }
        else {
            this.renderer.setElementStyle(this.accordionContent.nativeElement, "max-height", "500px");
        }
        this.accordionExapanded = !this.accordionExapanded;
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["ViewChild"])('acc'),
        __metadata("design:type", Object)
    ], AccordionComponent.prototype, "accordionContent", void 0);
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Input"])('title'),
        __metadata("design:type", String)
    ], AccordionComponent.prototype, "title", void 0);
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Input"])('description'),
        __metadata("design:type", String)
    ], AccordionComponent.prototype, "description", void 0);
    AccordionComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["Component"])({
            selector: 'accordion',template:/*ion-inline-start:"/var/www/html/boti/boti_mobile/botischool/src/components/accordion/accordion.html"*/'<div class="accordion" [ngClass] = "{ active : accordionExapanded }">\n    <div class="accordion-title" (click)="toggleAccordion()">\n        {{ title }} <i class="fa fa-angle-right" aria-hidden="true"></i>\n    </div>\n    <div class="accordion-body" #acc>\n      <p [innerHtml]="description"></p>\n    </div>\n</div>'/*ion-inline-end:"/var/www/html/boti/boti_mobile/botischool/src/components/accordion/accordion.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_0__angular_core__["Renderer"]])
    ], AccordionComponent);
    return AccordionComponent;
}());

//# sourceMappingURL=accordion.js.map

/***/ })

},[621]);
//# sourceMappingURL=main.js.map