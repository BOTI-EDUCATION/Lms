<template>
  <div class="content-ressource">
    <div class="container" style="position: relative;">
      <div class="wrapper-ress">
        <div class="question">
          {{ ressource.answer }}
        </div>
        <div
          style="width: 100%;justify-content: space-between;height: 80%;justify-content: space-between;overflow-y: auto;"
          class="row"
        >
          <div class="ll-col">
            <template v-for="(item, index) in ressource.content.contents">
              <div
                class="d-flex link-word"
                :id="'link-' + item.content"
                style="flex-direction: column-reverse;align-items: end;width: 100%;"
                :key="index"
              >
                <span>{{ item.content }}</span>
              </div>
            </template>
          </div>
          <div class="ll-col">
            <template v-for="(item, index) in ressource.content.answers">
              <div
                class="d-flex link-answer"
                :id="'answer-' + item.answer"
                style="flex-direction: column-reverse;align-items: end;width: 100%;"
                :key="index"
              >
                <span>{{ item.answer }}</span>
              </div>
            </template>
          </div>
        </div>
      </div>
      <button class="check-answer" @click="showLinks(ressource.content)">
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
    showLinks(content) {
      for (let i = 0; i < content.selected.length; i++) {
        var divLink = document.getElementById(
          "link-" + content.selected[i].link
        );
        var divAnswer = document.getElementById(
          "answer-" + content.selected[i].answer
        );
        divLink.style.backgroundColor = `${content.selected[i].color}`;
        divAnswer.style.backgroundColor = `${content.selected[i].color}`;
        divLink.style.color = `white`;
        divAnswer.style.color = `white`;
      }
    },
  },
};
</script>

<style lang="scss">
.ll-col {
  display: flex;
  width: 215px;
  overflow-x: hidden;
  overflow-y: auto;
  flex-direction: column;
  align-items: center;
  width: 35%;
  justify-content: center;
  .link-answer {
    border: 1px solid #8080802e;
  }
  .link-word {
    border: 1px solid #8080802e;
  }
}
.check-answer {
  position: absolute;
  right: 2%;
  bottom: 2%;
}
.link-answer {
  background: #f3f6f9;
  padding: 5px;
  margin: 5px;
  border-radius: 10px;
  color: #171656;
  font-size: 3rem;
}
.link-word {
  background: #f3f6f9;
  padding: 5px;
  margin: 5px;
  border-radius: 10px;
  font-size: 2rem;
  color: #171656;
}
@media (mix-width: 1500px) {
  .link-word {
    font-size: 4rem;
  }
}
</style>
