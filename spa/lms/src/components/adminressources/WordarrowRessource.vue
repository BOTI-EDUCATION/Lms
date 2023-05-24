<template>
  <div>
    <div class="con-fx-ty">
      <div
        class="etape-title"
        v-for="etape in ressources"
        :key="etape.id"
        v-if="etape.id == content.ressource_id"
        :style="{ 'background-color': etape.etape_color }"
      >
        {{ etape.type_label }}
      </div>
      <div class="form-group mt-2">
        <input
          type="text"
          style="font-size: 30px;color: #171656;text-align: center;font-weight: 600;"
          class="form-control form-control-solid col-10 m-auto"
          placeholder="Question ?"
          v-model="content.answer"
        />
      </div>
      <div class="row word-complete">
        <template v-for="(item, index) in content.content">
          <div class="col" :key="index">
            <div class="d-flex">
              <input
                :key="index"
                type="text"
                class="form-control form-control-solid mb-2 col-3"
                placeholder="début"
                v-model="item.content"
              />
              <div class="d-flex" style="font-size: 2rem;">:</div>
              <input
                :key="index"
                type="text"
                class="form-control form-control-solid  col-4 mr-1"
                placeholder="complét"
                v-model="item.firstanswer"
              />
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
              <input
                :key="index"
                type="text"
                class="form-control form-control-solid  col-4 mr-1"
                placeholder="complét"
                v-model="item.secondanswer"
              />
              <a
                href="javascript:void(0)"
                @click="removeStructures(index, 1)"
                class="btn btn-icon btn-light btn-hover-danger btn-sm mr-1"
                style="font-weight: bold; font-size: 18px"
              >
                <span class="svg-icon svg-icon-md svg-icon-danger">
                  <!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg-->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    version="1.1"
                  >
                    <g
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <rect x="0" y="0" width="24" height="24" />
                      <path
                        d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                        fill="#000000"
                        fill-rule="nonzero"
                      />
                      <path
                        d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                        fill="#000000"
                        opacity="0.3"
                      />
                    </g>
                  </svg>
                  <!--end::Svg Icon-->
                </span>
              </a>
            </div>
          </div>
        </template>
      </div>
    </div>

    <button
      type="button"
      @click="newStructureArrows()"
      class="
                    mt-2
                    btn btn-nice btn-primary
                    spinner-darker-info spinner-right
                  "
    >
      New structure
    </button>
  </div>
</template>

<script>
export default {
  props: [content, ressources],
  methods: {
    newStructureArrows() {
      this.$emit("newStructureArrow");
    },
    removeStructures(index) {
      this.$emit("removeStructure", index);
    },
  },
};
</script>

<style></style>
