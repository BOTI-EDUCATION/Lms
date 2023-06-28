<template>
  <div class="content-ressource">
    <div style="position: relative;overflow-x: hidden;width: 100%;">
      <div class="wrapper-ress">
        <div class="question">
          {{ ressource.answer }}
        </div>
        <div
          class="word-complete"
          style="flex-direction: row;flex-direction: row;display: flex;overflow-x: auto;justify-content: space-evenly;align-items: center;"
        >
          <template v-for="(item, index) in ressource.content">
            <div :key="index">
              <div class="schemas-color">
                <span
                  class="word-content"
                  style="display: block;width: fit-content;white-space: nowrap;    width: 100%;    overflow-x: auto;"
                  >{{ item.content }}</span
                >
                <span class="word-answer">{{ item.firstanswer }}</span>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource"],
};
</script>

<style scoped>
.schemas-color {
  width: 250px;
  height: 350px;
  padding: 10px;
  border: 3px solid #8bc34a;
  border-image: linear-gradient(#f6b73c, #9f420c, #e91e63) 30;
  margin-right: 5px;
  border-width: 4px;
}
.word-answer {
  height: 88%;
  width: 100%;

  display: block;
  line-break: anywhere;
  border: none;
  overflow-y: auto;
}
</style>
