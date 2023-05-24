<template>
  <div id="app">
    <component :is="layout">
      <router-view :key="$route.fullPath" />
    </component>
  </div>
</template>

<script>
var default_layout = "default";

export default {
  metaInfo: {
    title: "",
    titleTemplate: "%s | lms",
  },
  computed: {
    _layout() {
      let user = this.$auth.user();
      if (user) {
        if (user && user["role_id"] == 1) default_layout = "admin";

        return (this.$route.meta.layout || default_layout) + "-layout";
      }
      return "no-sidebar-layout";
    },

    layout() {
      default_layout = "admin";
      return (this.$route.meta.layout || default_layout) + "-layout";
    },
  },

  created() {
    // nothing defined here (when this.$route.path is other than "/")
    console.log(this.$route, this.$route.meta.layout);
  },

  updated() {
    // something defined here whatever the this.$route.path
    console.log(this.$route, this.$route.meta.layout);
  },
};
</script>
<style>
* {
  font-family: "Manrope", sans-serif;
}
</style>
