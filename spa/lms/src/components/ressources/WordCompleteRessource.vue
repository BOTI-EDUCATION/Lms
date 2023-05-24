<template>
  <div class="content-ressource">
    <div style="position: relative;overflow-x: hidden;width: 100%;">
      <div class="wrapper-ress">
        <div class="question">
          {{ ressource.answer }}
        </div>
        <div class="row word-complete">
          <template v-for="(item, index) in ressource.content">
            <div class="col" :key="index">
              <div class="word-complete-struct">
                <span class="word-content" style="display: block;width: 49%;white-space: nowrap;overflow-x: auto;text-align: initial;">{{ item.content }}</span>
                <div style="font-size: 2rem;display: flex;margin-right: 15px;">
                  :
                </div>
                <span class="word-answer" style="overflow-x: auto;min-width: 50%;width: fit-content;">{{ item.answer }}</span>
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

<style>
.word-complete {
  width: 100%;
  margin: 0;
  padding: 20px;
  flex-direction: column;
  height: 60vh;
  overflow-y: auto;
  flex-wrap: nowrap !important;
  overflow-x: hidden;
}
.word-complete-struct {
  color: #171656;
  display: flex;
  align-items: center;
  height: 7vh;
}
.word-content {
  font-size: 2rem;
}
.word-answer {
  border: 1px solid #d4d4d4;
  border-radius: 5px;
  font-size: 2rem;
  min-height: 50px;
  display: block;
}
/* ::-webkit-scrollbar{

} */
</style>
