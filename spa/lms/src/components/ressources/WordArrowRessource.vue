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
                <span class="word-content" style="display: block;width: 49%;white-space: nowrap;overflow-x: auto;text-align: initial;">{{ item.content }} :</span>
                <!-- <div style="font-size: 2rem;display: flex;margin-right: 15px;">
                  
                </div> -->
                <span class="word-answer col-4" style="overflow-x: auto;">{{ item.firstanswer }}</span>
                <span class="col-2">
                  <svg
                    fill="#000000"
                    viewBox="0 0 24 24"
                    id="right-arrow"
                    data-name="Flat Color"
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon flat-color"
                  >
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g
                      id="SVGRepo_tracerCarrier"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    ></g>
                    <g id="SVGRepo_iconCarrier">
                      <path
                        id="primary"
                        d="M21.71,11.29l-3-3a1,1,0,0,0-1.42,1.42L18.59,11H3a1,1,0,0,0,0,2H18.59l-1.3,1.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l3-3A1,1,0,0,0,21.71,11.29Z"
                        style="fill: #f7623c;"
                      ></path>
                    </g>
                  </svg>
                </span>

                <span class="word-answer col-4" style="overflow-x: auto;">{{ item.secondanswer }}</span>
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
</style>
