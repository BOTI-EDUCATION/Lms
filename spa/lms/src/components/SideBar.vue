<template>
  <div></div>
</template>

<script>
export default {
  data() {
    return {};
  },
  mounted() {
    KTLayoutAsideMenu.init("kt_aside_menu");
    KTLayoutAsideToggle.init("kt_aside_toggle");
    KTLayoutHeaderMenu.init("kt_header_menu", "kt_header_menu_wrapper");
    KTLayoutQuickUser.init("kt_quick_user");
  },
  methods: {},
};
</script>

