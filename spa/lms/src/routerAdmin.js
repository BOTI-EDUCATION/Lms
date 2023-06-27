import VueRouter from "vue-router";

// Pages
import Register from "./components/Register";
import Login from "./components/Login";
import ForgotPassword from "./components/ForgotPassword";
import Profile from "./components/ProfileComponent.vue";
import ChangePassword from "./components/ChangePassword";
import Home from "./components/HomeComponent.vue";

import Utilisateur from "./components/UtilisateurComponent.vue";
import Niveau from "./components/niveaux/niveaux.vue";
import Unite from "./components/UniteComponent.vue";
import Users from "./components/users/Users.vue";
import Enseignants from "./components/Enseignants/Enseignants.vue";
import Tracking from "./components/tracking/Tracking.vue";
import RessourceType from "./components/RessourceTypeComponent.vue";
import EtapeType from "./components/EtapeTypeComponent.vue";
import SingleEtape from "./components/SingleEtapeTypeComponent.vue";

import Lecon from "./components/lecon/LeconComponent.vue";
import LeconItem from "./components/lecon/LeconItemComponent.vue";

import Unites from "./components/unites/Unites.vue";

// Routes
const routes = [
  {
    path: "/",
    name: "home",
    component: Home,
    meta: {},
  },

  {
    path: "/register",
    name: "register",
    component: Register,
    meta: {},
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      layout: "no-sidebar",
    },
  },
  {
    path: "/forgot-password",
    name: "forgot-password",
    component: ForgotPassword,
    meta: {
      layout: "no-sidebar",
    },
  },
  {
    path: "/change-password/:token",
    name: "change-password",
    component: ChangePassword,
    meta: {
      layout: "no-sidebar",
    },
  },
  {
    path: "/equipe",
    component: Utilisateur,
    meta: {},
  },
  {
    path: "/profile",
    component: Profile,
    name: "profile",
    meta: {},
  },
  {
    path: "/levels",
    component: Niveau,
    name: "levels",
    meta: {},
  },
  {
    path: "/unites",
    component: Unite,
    name: "unites",
    meta: {},
  },
  {
    path: "/users",
    component: Users,
    name: "users",
    meta: {},
  },
  {
    path: "/enseignants",
    component: Enseignants,
    name: "enseignants",
    meta: {},
  },
  {
    path: "/tracking",
    component: Tracking,
    name: "tracking",
    meta: {},
  },
  {
    path: "/ressource_types",
    component: RessourceType,
    name: "ressource_types",
    meta: {},
  },
  {
    path: "/etape_types",
    component: EtapeType,
    name: "etape_types",
    meta: {},
  },
  {
    path: "/single_etape_type/:id",
    component: SingleEtape,
    name: "single_etape_type",
    meta: {},
  },
  {
    path: "/lecons",
    component: Lecon,
    name: "lecons",
    meta: {},
  },
  {
    path: "/lecons/view",
    component: LeconItem,
    name: "lecon_item",
    meta: {},
  },
  {
    path: "*",
    name: "404",
    component: Home,
    meta: {},
  },
  {
    path: "/unites-types",
    component: Unites,
    name: "allUnites",
    meta: {},
  },
];

const router = new VueRouter({
  mode: "history",
  base:
    document.querySelector("meta[name=base_path]").getAttribute("content") +
    "/lms/admin",
  routes,
});

export default router;
