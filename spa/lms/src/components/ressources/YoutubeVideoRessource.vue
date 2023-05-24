<template>
  <div class="content-ressource">
    <iframe
      width="100%"
      height="100%"
      :title="'kdjkdkj'"
      :src="link.replace('watch?v=', 'embed/')"
      frameborder="0"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen
    ></iframe>
  </div>
</template>

<script>
export default {
  props: ["link"],
};
</script>

<style></style>
