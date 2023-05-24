<template>
  <div class="content-ressource" style="overflow: hidden;">
    <div
      class="container"
      style="position: relative;overflow-y: auto;padding-top: 30px;"
    >
      <div class="wrapper-ress">
        <h3>
          {{ ressource.answer }}
        </h3>
        <div
          class="row m-0 "
          style="width: 100%;height: 100%;align-items: center;"
        >
          <div class="col blob-col">
            <div
              class="row"
              style="flex-direction: column;margin-bottom: 80px;width:100%"
            >
              <div class="col" style="    text-align: initial;">
                <span class="schemat-question">{{
                  ressource.content[0].content
                }}</span>
              </div>
              <div class="col">
                <span
                  type="text"
                  class="form-control form-control-solid schemat-answer"
                  >{{ ressource.content[0].answer }}</span
                >
              </div>
            </div>
            <div
              class="row"
              style="flex-direction: column;margin-bottom: 80px;width:100%"
            >
              <div class="col" style="    text-align: initial;">
                <span class="schemat-question">{{
                  ressource.content[1].content
                }}</span>
              </div>
              <div class="col">
                <span class="form-control form-control-solid schemat-answer">{{
                  ressource.content[1].answer
                }}</span>
              </div>
            </div>
          </div>
          <div class="main-cube">
            <div class="cube">
              <div
                :style="{
                  'background-color': ressource.content[4].color,
                }"
              >
                <span>{{ ressource.content[4].content }}</span>
              </div>
              <div
                :style="{
                  'background-color': ressource.content[5].color,
                }"
              >
                <span>{{ ressource.content[5].content }}</span>
              </div>
              <div
                :style="{
                  'background-color': ressource.content[6].color,
                }"
              >
                <span>{{ ressource.content[6].content }}</span>
              </div>
              <div
                :style="{
                  'background-color': ressource.content[7].color,
                }"
              >
                <span>{{ ressource.content[7].content }}</span>
              </div>
            </div>
          </div>
          <div class="col blob-col">
            <div
              class="row"
              style="flex-direction: column;margin-bottom: 80px;width:100%"
            >
              <div class="col" style="    text-align: initial;">
                <span class="schemat-question">{{
                  ressource.content[2].content
                }}</span>
              </div>
              <div class="col">
                <span class="form-control form-control-solid schemat-answer">{{
                  ressource.content[2].answer
                }}</span>
              </div>
            </div>
            <div
              class="row"
              style="flex-direction: column;margin-bottom: 80px;width:100%"
            >
              <div class="col" style="    text-align: initial;">
                <span class="schemat-question">{{
                  ressource.content[3].content
                }}</span>
              </div>
              <div class="col">
                <span class="form-control form-control-solid schemat-answer">{{
                  ressource.content[3].answer
                }}</span>
              </div>
            </div>
          </div>
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

<style lang="scss">
.blob-col {
  align-items: center;
  justify-content: center;
  display: flex;
  flex-direction: column;
}
.main-cube {
  width: 500px;
  height: 500px;
}
.schemat-answer {
  background-color: white;
  resize: none;
  font-size: 15px;
  color: #171656;
  text-align: center;
  font-weight: 600;
  height: 150px;
  // width: 350px !important;
  width: 100%;
  overflow: hidden;
  word-wrap: break-word;
  overflow-y: auto;
}
.schemat-question {
  font-size: 15px;
  color: #171656;
  font-weight: 600;
  width: 100%;
  display: block;
}
.cube {
  border-radius: 50%;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  justify-content: center;
  width: 100%;
  height: 100%;
  align-items: center;
  gap: 10px;
}
.cube div {
  width: 100%;
  height: 100%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
}
.cube div:nth-child(1),
.cube div:nth-child(2) {
  align-items: end;
  justify-content: center;
  padding-bottom: 13px;
  font-size: 1.5rem;
}
.cube div:nth-child(3),
.cube div:nth-child(4) {
  align-items: flex-start;
  justify-content: center;
  padding-top: 13px;
  padding-right: 13px;
  font-size: 1.5rem;
}
@media (min-width: 1500px) {
  .schemat-answer {
    font-size: 2rem;
  }
  .schemat-question {
    font-size: 2rem;
  }
}
</style>
