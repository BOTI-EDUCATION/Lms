<template>
  <div class="content-ressource">
    <div class="container" style="position: relative;">
      <div class="wrapper-ress">
        <div class="question">
          {{ ressource.content }}
        </div>
        <div class="col-6" v-if="ressource.answer">
          <textarea
            class="form-control form-control-solid"
            id="responseRessource"
            rows="8"
          ></textarea>
        </div>
      </div>
      <button
        v-if="ressource.answer"
        class="check-answer"
        @click="showResponse(ressource.answer)"
      >
        <img
          width="50px"
          height="50px"
          :src="url_base + '/assets/lms/icons/magic-wand.png'"
          alt=""
        />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
  methods: {
    showResponse(answer) {
      document.getElementById("responseRessource").value = answer;
    },
  },
};
</script>

<style lang="scss">
.question {
  font-size: 2rem;
  margin-bottom: 5px;
  width: 100%;
}
@media (min-width: 1500px) {
  .wrapper-ress {
    .question {
      font-size: 3.5rem;
      margin: 0;
    }
  }
}
</style>
