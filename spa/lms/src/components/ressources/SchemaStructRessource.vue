<template>
  <div class="content-ressource blob-ress">
    <div class="container" style="position: relative;">
      <div class="wrapper-ress">
        <h3 class="question-guide mt-5">
          {{ ressource.answer }}
        </h3>
        <div
          class="row m-0 "
          style="width: 100%;height: 100%;justify-content: center;align-items: center;"
        >
          <div
            class="col"
            style="max-width: 600px !important;display: flex;align-items: center;justify-content: center;flex-direction: column;"
          >
            <div
              class="row m-0 con-blob mb-2"
              style="background-color: #673ab7;"
            >
              <p class="blob-text">
                {{ ressource.content[0].content }}
              </p>
            </div>
            <div class="row m-0 con-blob" style="background-color: #009688;">
              <p class="blob-text">
                {{ ressource.content[1].content }}
              </p>
            </div>
          </div>
          <div class="main-blob">
            <div
              class="blob"
            >
              <span style="color:white">{{
                ressource.content[4].content
              }}</span>
            </div>
          </div>
          <div
            class="col"
            style="max-width: 600px !important;display: flex;align-items: center;justify-content: center;flex-direction: column;"
          >
            <div
              class="row m-0 con-blob mb-2"
              style="background-color: #ff9800;"
            >
              <p class="blob-text" placeholder="Answer">
                {{ ressource.content[2].content }}
              </p>
            </div>
            <div class="row m-0 con-blob" style="background-color: #03a9f4;">
              <p class="blob-text" placeholder="Answer">
                {{ ressource.content[3].content }}
              </p>
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
.con-blob {
  border-radius: 50%;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-position: center;
  background-size: cover;
  height: 250px;
  width: 250px;
  margin-top: 8px;
}
.blob {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 300px;
  height: 300px;
}

.blob-text {
  font-size: 15px;
    text-align: center;
    font-weight: 600;
    color: white;
    width: 87%;
    overflow: auto;
    overflow-y: auto;
    height: 100px;
    line-break: anywhere;
    height: 47%;
    margin: 0;
}
.blob-title {
  overflow: auto;
  font-size: 15px;
  width: 48%;
  overflow-wrap: break-word;
  color: #171656;
  font-weight: 600;
}
.main-blob {
  width: 300px;
  display: flex;
  align-items: center;
  background: #F44336;
  border-radius: 50%;
  box-shadow: 1px 1px 3px 2px #00000061;
}
.question-guide{
  font-size: 2rem;
}
@media (min-width: 1500px) {
  .question-guide{
    font-size: 4rem;
  }
  .main-blob {
    width: 650px;
    height: 650px;
  }
  .blob {
    width: 100%;
    height: 100%;
    font-size: 55px;
  }
  .con-blob {
    height: 550px;
    width: 550px;
    box-shadow: 1px 1px 3px 2px #00000061;
  }
  .blob-text {
    font-size: 40px;
  }
  .blob-title {
    font-size: 40px;
    font-weight: 700;
  }
}
</style>
