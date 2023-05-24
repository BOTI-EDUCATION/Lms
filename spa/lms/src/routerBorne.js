import VueRouter from "vue-router";

// Pages

import Home from "./componentsBorne/HomeComponent.vue";
import LeconDetails from "./componentsBorne/LeconDetailsComponent.vue";

// Routes
const routes = [
  {
    path: "/",
    name: "borne_home",
    component: Home,
  },
  {
    path: "/details-lecon",
    component: LeconDetails,
    name: "details_lecon",
  },
  {
    path: "/preview/:rcode?/:etape?/:ressource?",
    name: "details-lecon_",
    component: LeconDetails,
  },
  {
    path: "*",
    name: "home",
    component: Home,
  },
];

const router = new VueRouter({
  mode: "history",
  base:
    document.querySelector("meta[name=base_path]").getAttribute("content") +
    "lms",
  routes,
});

export default router;
