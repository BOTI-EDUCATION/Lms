<script>
import NavBar from "../components/NavBar";
import FooterBar from "../components/FooterBar";

export default {
  components: {
    NavBar,
    FooterBar
  }
};
</script>

<template>
  <div>
      <slot />
  </div>
</template>
