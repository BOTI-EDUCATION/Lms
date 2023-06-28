<template>
  <div class="content-ressource" style="justify-content: center;">
    <div style="position: relative;overflow-x: hidden;">
      <div class="wrapper-ress" style="justify-content: flex-start;">
        <div
          class="question"
          style="margin-top: 100px;
    font-size: 1.5rem;
"
        >
          {{ ressource.answer }}
        </div>
        <div class="structure-complete">
          <div
            class="mr-2 structure"
            v-for="(con, i) in ressource.content"
            :key="i"
          >
            <span style="margin-right: 15px;font-size: 1.5rem;">{{
              con.content
            }}</span>
            <input
              type="text"
              :value="con.answer"
              style="color: white;border-radius: 15px;width: 30%;height: 10vh;overflow-x: auto;font-weight: 600;font-size: 1.5rem;"
              class="form-control form-control-solid structureAnswer"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="check-answer">
      <button @click="showStructureAnswers()">
        <img
          width="50px"
          height="50px"
          :src="url_base + '/assets/lms/icons/magic-wand.png'"
          alt=""
        />
      </button>
      <button @click="showStructureAnswers()">
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
    showStructureAnswers() {
      var structureAnswers = document.getElementsByClassName("structureAnswer");
      for (let index = 0; index < structureAnswers.length; index++) {
        structureAnswers[index].style.color = "black";
      }
    },
  },
};
</script>

<style lang="scss">
.structure-complete {
  display: flex;
  flex-wrap: wrap;
  margin-top: 30px;
  font-size: 1.2rem;
  width: 100%;
  .structure {
    display: flex;
    margin-right: 5px;
    align-items: center;
    margin-top: 5px;
    span {
      margin-right: 5px;
      font-weight: 700;
      min-width: 100px;
      max-width: fit-content;
      overflow-y: auto;
      min-height: 60px;
      white-space: nowrap;
    }
  }
}

.check-answer {
  position: absolute;
  right: 2%;
  bottom: 2%;
  width: 97%;
  display: flex;
  justify-content: space-between;
}
.magic-brasher {
  width: 100%;
  display: flex;
  justify-content: space-between;
}
@media (min-width: 1500px) {
  .structure-complete {
    font-size: 2.5rem;
  }
  .structureAnswer {
    // font-size: 2rem;
  }
}
</style>
