<template>
  <div class="content-ressource content-8">
    <div class="container" style="    position: relative;">
      <div class="wrapper-ress">
        <div class="question">
          {{ ressource.content }}
        </div>
        <div class="checkbox-answers">
          <div class="bx" id="answer-true" :class="{ isTrue: 'bx-active-btn' }">
            <button>
              Vrai
            </button>
          </div>
          <div
            class="bx"
            id="answer-false"
            :class="{ isFalse: 'bx-active-btn' }"
          >
            <button>
              Faux
            </button>
          </div>
        </div>
      </div>
      <div class="magic-brasher">
        <button @click="checkIfTrue(ressource.answer)">
          <img
            width="50px"
            height="50px"
            :src="url_base + '/assets/lms/icons/magic-wand.png'"
            alt=""
          />
        </button>
        <button @click="checkIfTrue(ressource.answer)">
          <img
            width="50px"
            height="50px"
            :src="url_base + '/assets/lms/icons/magic-wand.png'"
            alt=""
          />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
  
  methods: {
    checkIfTrue(answer) {
      if (answer == "true") {
        document.getElementById("answer-true").classList.add("bx-active-btn");
      } else {
        document.getElementById("answer-false").classList.add("bx-active-btn");
      }
    },
  },
};
</script>

<style scoped>
.check-answer {
  position: absolute;
  right: 2%;
  bottom: 2%;
}
.check-answer:nth-child(1) {
  left: 2%;
}
.magic-brasher {
  width: 100%;
  display: flex;
  justify-content: space-between;
}
</style>
