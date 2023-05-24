<template>
  <div>
    <div class="content-ressource content-image-paragraph">
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
          style="width: 100% !important"
          alt=""
        />
      </div>
      <div class="col-6 audio-ressource">
        <span style="margin-bottom: 5px;">
          {{ ressource.content }}
        </span>
      </div>
    </div>
    <div class="audio">
      <audio controls>
        <source
          id="source"
          :src="
            url_base +
              '/assets/schools/' +
              url_base.split('/')[4] +
              '/lms/lecons_files/' +
              ressource.audio
          "
        />
      </audio>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
};
</script>

<style lang="scss">
.audio-ressource {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  font-size: 2.8rem;
  font-weight: 700;
  color: #171656;
  font-family: sans-serif;
  text-align: center;
}
.content-image-paragraph {
  img {
    object-fit: contain;
  }
}
</style>
