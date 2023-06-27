<template>
  <div>
    <v-carousel
      @change="changeSlide()"
      :interval="1000000"
      v-model="currentStepIndex"
      cycle
      height="100%"
      hide-delimiters
      data-bs-interval="false"
      data-interval="false"
    >
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div
            style="text-align: end;"
            v-if="
              ressource.type_id == 3
              // && ressource.files[0]
            "
          >
            <!-- <pdf-ressource :ressource="ressource"></pdf-ressource> -->
            <div class="content-ressource">
              <!-- :src="
              'https://boti.education//p/demo/assets/schools/demo/lms//lecons_files/6tne70x9gn/1.jpg'
            " -->
              <div class="img" style="width: 100%;margin:0 !important;">
                <img
                  style="object-fit: contain;"
                  :src="
                    'https://boti.education/' +
                      ressource.files[file_content_index]
                  "
                  alt=""
                />
              </div>
            </div>
            <div class="change-pdf">
              <a
                href="#"
                @click="changeFileContent('prev', ressource)"
                style="margin-right: 10px;"
                ><svg
                  style="width:50px"
                  viewBox="0 -6.5 36 36"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  fill="#000000"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <title>left-arrow</title>
                    <desc>Created with Sketch.</desc>
                    <g
                      id="icons"
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <g
                        id="ui-gambling-website-lined-icnos-casinoshunter"
                        transform="translate(-342.000000, -159.000000)"
                        fill="#ffffff"
                        fill-rule="nonzero"
                      >
                        <g
                          id="square-filled"
                          transform="translate(50.000000, 120.000000)"
                        >
                          <path
                            d="M317.108012,39.2902857 L327.649804,49.7417043 L327.708994,49.7959169 C327.889141,49.9745543 327.986143,50.2044182 328,50.4382227 L328,50.5617773 C327.986143,50.7955818 327.889141,51.0254457 327.708994,51.2040831 L327.6571,51.2479803 L317.108012,61.7097143 C316.717694,62.0967619 316.084865,62.0967619 315.694547,61.7097143 C315.30423,61.3226668 315.30423,60.6951387 315.694547,60.3080911 L324.702666,51.3738496 L292.99947,51.3746291 C292.447478,51.3746291 292,50.9308997 292,50.3835318 C292,49.8361639 292.447478,49.3924345 292.99947,49.3924345 L324.46779,49.3916551 L315.694547,40.6919089 C315.30423,40.3048613 315.30423,39.6773332 315.694547,39.2902857 C316.084865,38.9032381 316.717694,38.9032381 317.108012,39.2902857 Z M327.115357,50.382693 L316.401279,61.0089027 L327.002151,50.5002046 L327.002252,50.4963719 L326.943142,50.442585 L326.882737,50.382693 L327.115357,50.382693 Z"
                            id="left-arrow"
                            transform="translate(310.000000, 50.500000) scale(-1, 1) translate(-310.000000, -50.500000) "
                          ></path>
                        </g>
                      </g>
                    </g>
                  </g></svg
              ></a>
              <a href="#" @click="changeFileContent('next', ressource)">
                <svg
                  style="width:50px"
                  viewBox="0 -6.5 36 36"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  fill="#000000"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <title>right-arrow</title>
                    <desc>Created with Sketch.</desc>
                    <g
                      id="icons"
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <g
                        id="ui-gambling-website-lined-icnos-casinoshunter"
                        transform="translate(-212.000000, -159.000000)"
                        fill="#ffffff"
                        fill-rule="nonzero"
                      >
                        <g
                          id="square-filled"
                          transform="translate(50.000000, 120.000000)"
                        >
                          <path
                            d="M187.108012,39.2902857 L197.649804,49.7417043 L197.708994,49.7959169 C197.889141,49.9745543 197.986143,50.2044182 198,50.4382227 L198,50.5617773 C197.986143,50.7955818 197.889141,51.0254457 197.708994,51.2040831 L197.6571,51.2479803 L187.108012,61.7097143 C186.717694,62.0967619 186.084865,62.0967619 185.694547,61.7097143 C185.30423,61.3226668 185.30423,60.6951387 185.694547,60.3080911 L194.702666,51.3738496 L162.99947,51.3746291 C162.447478,51.3746291 162,50.9308997 162,50.3835318 C162,49.8361639 162.447478,49.3924345 162.99947,49.3924345 L194.46779,49.3916551 L185.694547,40.6919089 C185.30423,40.3048613 185.30423,39.6773332 185.694547,39.2902857 C186.084865,38.9032381 186.717694,38.9032381 187.108012,39.2902857 Z M197.115357,50.382693 L186.401279,61.0089027 L197.002151,50.5002046 L197.002252,50.4963719 L196.943142,50.442585 L196.882737,50.382693 L197.115357,50.382693 Z"
                            id="right-arrow"
                          ></path>
                        </g>
                      </g>
                    </g>
                  </g></svg
              ></a>
            </div>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 4 && ressource.link">
            <youtube-video-ressource
              :link="ressource.link"
            ></youtube-video-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 5 && ressource.content"></div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 6 && ressource.content">
            <text-ressource :ressource="ressource" :url_base="url_base">
            </text-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 7 && ressource.content">
            <slides-ressource :content="ressource.content"></slides-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 8 && ressource.content">
            <true-false-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></true-false-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 9 && ressource.content">
            <open-question-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></open-question-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 10">
            <question-image-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></question-image-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 11 && ressource.content">
            <structure-complete-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></structure-complete-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 12 && ressource.content">
            <glossary-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></glossary-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 13 && ressource.content">
            <video-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></video-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 14 && ressource.content">
            <schemas-ressource :ressource="ressource"></schemas-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 16 && ressource.content">
            <schema-struct-ressource
              :ressource="ressource"
            ></schema-struct-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 18 && ressource.content">
            <image-word-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></image-word-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 19 && ressource.content">
            <image-paragraph-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></image-paragraph-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 20 && ressource.content">
            <audio-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></audio-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 17">
            <linking-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></linking-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 21">
            <table-ressource
              :ressource="ressource"
              :url_base="url_base"
            ></table-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 22">
            <word-complete-ressource
              :ressource="ressource"
            ></word-complete-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 24">
            <word-arrow-ressource :ressource="ressource"></word-arrow-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item>
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 26">
            <schemas-colores-ressource
              :ressource="ressource"
            ></schemas-colores-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>

      <!-- :interval="interval" -->
      <v-carousel-item :key="1">
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 1 && ressource.content">
            <title-ressource :content="ressource.content"></title-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
      <v-carousel-item :key="2">
        <v-sheet
          height="100%"
          width="100%"
          class="sheet-slider"
          style="background:#fff;color:#000;"
        >
          <div v-if="ressource.type_id == 2 && ressource.content">
            <html-ressource :content="ressource.content"></html-ressource>
          </div>
        </v-sheet>
      </v-carousel-item>
    </v-carousel>
  </div>
</template>
