<template>
  <div class="content-ressource">
    <!-- :src="
              'https://boti.education//p/demo/assets/schools/demo/lms//lecons_files/6tne70x9gn/1.jpg'
            " -->
    <div class="img" style="width: 100%;margin:0 !important;">
      <img
        style="    object-fit: contain;"
        :src="'https://boti.education/' + ressource.files[file_content_index]"
        alt=""
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource"],
};
</script>

<style></style>
