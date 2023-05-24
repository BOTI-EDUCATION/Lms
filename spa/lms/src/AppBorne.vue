<template>
  <div id="app_web">
    <component :is="layout">
      <router-view :key="$route.fullPath" />
    </component>
  </div>
</template>

<script>
const default_layout = "default";
export default {
  computed: {
    layout() {
      return (this.$route.meta.layout || default_layout) + "-layout";
    },
  },

  created() {
    //console.log(this.$session);
    // nothing defined here (when this.$route.path is other than "/")
    //console.log(this.$route, this.$route.meta.layout);
  },

  updated() {
    // something defined here  the this.$route.path
    // console.log(this.$route, this.whatever.$route.meta.layout);
  },
};
</script>
<style>
* {
  font-family: "Manrope", sans-serif;
}
</style>
