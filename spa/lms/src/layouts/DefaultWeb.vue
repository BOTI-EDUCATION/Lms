<script>
import NavBar from "../componentsBorne/NavBar";
import SideBar from "../componentsBorne/SideBar";
import FooterBar from "../componentsBorne/FooterBar";

export default {
  components: {
    NavBar,
    SideBar,
    FooterBar,
  },
};
</script>

<template>
  <div id="app_web">
    <side-bar />

    <div id="wrapper">
      <slot />

      <footer-bar />
    </div>
  </div>
</template>
