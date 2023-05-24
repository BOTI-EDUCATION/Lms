<template>
  <div class="content-ressource content-image-paragraph ">
    <div class="img col-5" style="height: 100%;">
      <img
        :src="
          url_base +
            '/assets/schools/' +
            url_base.split('/')[4] +
            '/lms/lecons_files/' +
            ressource.image +
            '?v=12354'
        "
        style="width: 100% !important;object-fit: contain;"
        alt=""
      />
    </div>

    <div class="col-5 paragraph-ressource" v-html="ressource.content"></div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
};
</script>

<style lang="scss">
.content-image-paragraph {
  img {
    object-fit: contain;
  }
}
.paragraph-ressource {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow-y: auto;
  letter-spacing: 3px;
  line-height: 1.5;
  font-weight: 700;
  color: #171656;
  li {
    list-style-type: disc;
    &:hover {
      list-style-type: disc;
      background: white !important;
      color: black;
    }
  }
  p {
    font-size: 1.5rem;
    span {
      color: rgb(63, 66, 84);
      width: 100%;
      display: block;
      word-break: break-all;
    }
  }
}
@media (min-width: 1500px) {
  .paragraph-ressource {
    font-size: 4rem;
  }
}
</style>
