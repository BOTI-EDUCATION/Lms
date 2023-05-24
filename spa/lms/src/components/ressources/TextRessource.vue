<template>
  <div class="content-ressource">
    <div class="img" style="height: 100%;">
      <img
        :src="
          url_base +
            '/assets/schools/' +
            url_base.split('/')[4] +
            '/lms/lecons_files/' +
            ressource.image +
            '?v=12354'
        "
        style="width: 100% !;object-fit: contain;"
        alt=""
      />
    </div>

    <textarea
      id="textarea"
      style="color: black;width: 60%;border: none;background-color: white; resize: none;"
      readonly
      v-html="
        ressource.content
          .replace('<p>', '')
          .replace('</p>', '')
          .replace('</br>', '')
          .replace('<strong>', '')
          .replace('</strong>', '')
      "
    ></textarea>
  </div>
  
</template>

<script>
export default {
  props: ["ressource",'url_base'],
};
</script>

<style></style>
