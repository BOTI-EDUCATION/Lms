<template>
  <div>
    <!-- v-if="!courseExists" -->
    <div
      class="courses-container"
      v-bind:style="[courseExists ? { display: 'none' } : '']"
    >
      <h1 class="header-learning"></h1>
      <div class="grid">
        <div
          class="bx"
          :style="
            rubriques_tree &&
            rubriques_tree[0] &&
            rubriques_tree[0].lecons &&
            rubriques_tree[0].lecons[0]
              ? ''
              : 'overflow-y: hidden;'
          "
        >
          <div
            style="    height: 100%;"
            v-if="
              rubriques_tree &&
                rubriques_tree[0] &&
                rubriques_tree[0].lecons &&
                rubriques_tree[0].lecons[0]
            "
          >
            <div
              class="col"
              v-for="rubrique in rubriques_tree"
              v-bind:key="rubrique.id"
            >
              <h3 class="title">{{ rubrique.label }}</h3>
              <div class="fx fx-wrap fx-courses">
                <div
                  v-for="(rub, index) in rubrique.lecons"
                  v-bind:key="rub.id"
                  class="course-div"
                >
                  <!-- [attr.data-index]="i" -->
                  <div
                    class="lec"
                    style="width: 80%;"
                    :class="{ 'selected-lecon': rub.label == course.label }"
                    @click="
                      getCourse(
                        rub.id,
                        index - 1,
                        rubrique.lecons.length,
                        rubrique.lecons[index + 1]
                      )
                    "
                  >
                    <span class="number">{{ (index += 1) }} . </span>
                    <span
                      class="lecon"
                      :class="{ 'selected-lecon': rub.label == course.label }"
                    >
                      {{ rub.label }}
                    </span>
                  </div>
                  <div style="display: flex;align-items: center;">
                    <span
                      style="margin-right: 2px;"
                      @click="showCourse(rub.id)"
                    >
                      <img
                        width="40px"
                        :src="url_base + 'assets/lms/icons/jouer.png'"
                        alt=""
                      />
                    </span>
                    <span
                      class="percent close-learn"
                      v-if="rub.percent > 0 && rub.percent <= 75"
                      >{{ rub.percent }}
                    </span>
                    <span
                      class="percent success-learn"
                      v-else-if="rub.percent > 75"
                      >{{ rub.percent }}
                    </span>
                    <span class="percent no-learn" v-else-if="rub.percent == 0"
                      >{{ rub.percent }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            v-else
            style="height: 90%;display: flex;justify-content: center;align-items: center;"
          >
            <img
              :src="url_base + '/assets/schools/lms/icons/empty-folder.png'"
              width="50%"
              height="50%"
              style="object-fit: contain;"
              alt=""
            />
          </div>
        </div>

        <div class="bx">
          <div
            class="bx-child"
            v-if="course"
            style="text-align: center;justify-content: space-evenly;"
          >
            <div>
              <h4>Cours {{ course_number }} / {{ rub_count }}</h4>
              <div class="course-title" style="color:black">
                <h3>{{ course.label }}</h3>
              </div>
            </div>
            <div
              class="obs"
              v-if="
                (course.objectifs != '<p></p>' && course.objectifs) ||
                  (course.prerequis != '<p></p>' && course.prerequis) ||
                  (course.syllabus != '<p></p>' && course.syllabus) ||
                  (course.instructions != '<p></p>' && course.instructions)
              "
            >
              <div
                v-if="course.objectifs != '<p></p>' && course.objectifs"
                class="child"
                data-toggle="collapse"
                href="#collapseObjectifs"
                role="button"
                aria-expanded="false"
                aria-controls="collapseObjectifs"
              >
                <span> Objectifs pédagogiques </span
                ><svg
                  viewBox="0 0 1024 1024"
                  class="icon"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#aeabab"
                  stroke="#aeabab"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M256 120.768L306.432 64 768 512l-461.568 448L256 903.232 659.072 512z"
                      fill="#aeabab"
                    ></path>
                  </g>
                </svg>
              </div>
              <div
                v-if="course.objectifs != '<p></p>' && course.objectifs"
                class="collapse"
                id="collapseObjectifs"
              >
                <p v-html="course.objectifs"></p>
              </div>
              <div
                v-if="course.syllabus != '<p></p>' && course.syllabus"
                class="child"
                data-toggle="collapse"
                href="#collapseSyllabus"
                role="button"
                aria-expanded="false"
                aria-controls="collapseSyllabus"
              >
                <span> Syllabus </span
                ><svg
                  viewBox="0 0 1024 1024"
                  class="icon"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#aeabab"
                  stroke="#aeabab"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M256 120.768L306.432 64 768 512l-461.568 448L256 903.232 659.072 512z"
                      fill="#aeabab"
                    ></path>
                  </g>
                </svg>
              </div>
              <div
                class="collapse"
                id="collapseSyllabus"
                v-if="course.syllabus != '<p></p>'"
              >
                <p v-html="course.syllabus"></p>
              </div>
              <div
                v-if="course.instructions != '<p></p>' && course.instructions"
                class="child"
                data-toggle="collapse"
                href="#collapseInstructions"
                role="button"
                aria-expanded="false"
                aria-controls="collapseInstructions"
              >
                <span> Instructions </span
                ><svg
                  viewBox="0 0 1024 1024"
                  class="icon"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#aeabab"
                  stroke="#aeabab"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M256 120.768L306.432 64 768 512l-461.568 448L256 903.232 659.072 512z"
                      fill="#aeabab"
                    ></path>
                  </g>
                </svg>
              </div>
              <div
                v-if="course.instructions != '<p></p>' && course.instructions"
                class="collapse"
                id="collapseInstructions"
              >
                <p v-html="course.instructions"></p>
              </div>
              <div
                v-if="course.prerequis != '<p></p>' && course.prerequis"
                class="child"
                data-toggle="collapse"
                href="#collapsePrerequis"
                role="button"
                aria-expanded="false"
                aria-controls="collapsePrerequis"
              >
                <span> Prérequis </span
                ><svg
                  viewBox="0 0 1024 1024"
                  class="icon"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#aeabab"
                  stroke="#aeabab"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M256 120.768L306.432 64 768 512l-461.568 448L256 903.232 659.072 512z"
                      fill="#aeabab"
                    ></path>
                  </g>
                </svg>
              </div>
              <div
                v-if="course.prerequis != '<p></p>'"
                class="collapse"
                id="collapsePrerequis"
              >
                <p v-html="course.prerequis"></p>
              </div>
            </div>
            <button
              @click="showCourse(course.id)"
              class="btn btn-success"
              style="border-color: transparent !important;"
            >
              Démarrer le cours
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- start course div  -->
    <div class="fx-course" v-if="courseExists && !startLearn">
      <div class="text">
        <h1>{{ course.label }}</h1>
        <div class="img" v-if="course.icone">
          <img
            style="margin:0 0  10px 0;height: 70vh;"
            :src="course.icone"
            alt=""
          />
        </div>
        <button
          @click="startCourse(course.id)"
          class="menuLevel choice"
          v-if="
            !(course.last_content_learn && this.course.last_ressource_learn)
          "
        >
          Commencer
          <svg
            fill="#ffffff"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M17.707,17.707a1,1,0,0,1-1.414-1.414L19.586,13H2a1,1,0,0,1,0-2H19.586L16.293,7.707a1,1,0,0,1,1.414-1.414l5,5a1,1,0,0,1,0,1.414Z"
              ></path>
            </g>
          </svg>
        </button>
        <button @click="startCourse(course.id)" class="menuLevel choice" v-else>
          Continuer
          <svg
            fill="#ffffff"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M17.707,17.707a1,1,0,0,1-1.414-1.414L19.586,13H2a1,1,0,0,1,0-2H19.586L16.293,7.707a1,1,0,0,1,1.414-1.414l5,5a1,1,0,0,1,0,1.414Z"
              ></path>
            </g>
          </svg>
        </button>
      </div>
    </div>
    <!-- start course div  -->
    <!-- button for closing the tab selected  -->
    <div class="courses-actions">
      <div class="zone">
        <button class="btn-close ion-button" @click="close_modal()">
          <svg
            viewBox="0 0 24 24"
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
              <title>Close</title>
              <g
                id="Page-1"
                stroke="none"
                stroke-width="1"
                fill="none"
                fill-rule="evenodd"
              >
                <g id="Close">
                  <rect
                    id="Rectangle"
                    fill-rule="nonzero"
                    x="0"
                    y="0"
                    width="24"
                    height="24"
                  ></rect>
                  <line
                    x1="16.9999"
                    y1="7"
                    x2="7.00001"
                    y2="16.9999"
                    id="Path"
                    stroke="#ffffff"
                    stroke-width="2"
                    stroke-linecap="round"
                  ></line>
                  <line
                    x1="7.00006"
                    y1="7"
                    x2="17"
                    y2="16.9999"
                    id="Path"
                    stroke="#ffffff"
                    stroke-width="2"
                    stroke-linecap="round"
                  ></line>
                </g>
              </g>
            </g>
          </svg>
        </button>
      </div>
    </div>

    <!-- show course ressources div  -->
    <div
      style="overflow: auto;height: 93vh;position: relative;"
      v-if="courseExists && startLearn"
    >
      <span
        class="etape-type"
        :style="{
          'background-color': ressources[currentRessource].etape_color,
        }"
        >{{ ressources[currentRessource].type_label }}
      </span>

      <!-- timer : {{ timer_val }} -->
      <div v-if="ressource && loaded" class="main-course-div">
        <div v-if="ressource.type_id == 1 && ressource.content">
          <title-ressource :content="ressource.content"></title-ressource>
        </div>
        <div v-if="ressource.type_id == 2 && ressource.content">
          <html-ressource :content="ressource.content"></html-ressource>
        </div>
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
        <div v-if="ressource.type_id == 4 && ressource.link">
          <youtube-video-ressource
            :link="ressource.link"
          ></youtube-video-ressource>
        </div>
        <div v-if="ressource.type_id == 5 && ressource.content"></div>
        <div v-if="ressource.type_id == 6 && ressource.content">
          <text-ressource :ressource="ressource" :url_base="url_base">
          </text-ressource>
        </div>

        <div v-if="ressource.type_id == 7 && ressource.content">
          <slides-ressource :content="ressource.content"></slides-ressource>
        </div>
        <div v-if="ressource.type_id == 8 && ressource.content">
          <true-false-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></true-false-ressource>
        </div>
        <div v-if="ressource.type_id == 9 && ressource.content">
          <open-question-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></open-question-ressource>
        </div>
        <div v-if="ressource.type_id == 10">
          <question-image-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></question-image-ressource>
        </div>
        <div v-if="ressource.type_id == 11 && ressource.content">
          <structure-complete-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></structure-complete-ressource>
        </div>
        <div v-if="ressource.type_id == 12 && ressource.content">
          <glossary-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></glossary-ressource>
        </div>
        <div v-if="ressource.type_id == 13 && ressource.content">
          <video-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></video-ressource>
        </div>
        <div v-if="ressource.type_id == 14 && ressource.content">
          <schemas-ressource :ressource="ressource"></schemas-ressource>
        </div>
        <div v-if="ressource.type_id == 16 && ressource.content">
          <schema-struct-ressource
            :ressource="ressource"
          ></schema-struct-ressource>
        </div>
        <div v-if="ressource.type_id == 18 && ressource.content">
          <image-word-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></image-word-ressource>
        </div>
        <div v-if="ressource.type_id == 19 && ressource.content">
          <image-paragraph-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></image-paragraph-ressource>
        </div>
        <div v-if="ressource.type_id == 20 && ressource.content">
          <audio-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></audio-ressource>
        </div>
        <div v-if="ressource.type_id == 17">
          <linking-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></linking-ressource>
        </div>
        <div v-if="ressource.type_id == 21">
          <table-ressource
            :ressource="ressource"
            :url_base="url_base"
          ></table-ressource>
        </div>
        <div v-if="ressource.type_id == 22">
          <word-complete-ressource
            :ressource="ressource"
          ></word-complete-ressource>
        </div>
        <div v-if="ressource.type_id == 24">
          <word-arrow-ressource :ressource="ressource"></word-arrow-ressource>
        </div>
        <div v-if="ressource.type_id == 26">
          <schemas-colores-ressource
            :ressource="ressource"
          ></schemas-colores-ressource>
        </div>
      </div>
      <div style="display: flex;" v-if="loaded">
        <span
          v-if="courseExists && startLearn"
          class="timer"
          :class="least30Sec ? 'timer-warning' : ''"
        >
          {{ this.times }}
        </span>
        <text-audio-action
          v-if="
            ressource &&
              courseExists &&
              startLearn &&
              ressource.type_id == 6 &&
              ressource.content
          "
        ></text-audio-action>
      </div>
    </div>
    <!-- show timer  -->

    <!-- show timer  -->
    <!-- show course ressources div  -->
    <div class="lecons-actions">
      <div class="zone">
        <div v-if="!courseExists" class="fx filter">
          <div
            @click="isProfileClicked = !isProfileClicked"
            class="symbol-label user-image"
            style="position: relative;cursor: pointer;"
            v-bind:style="{
              'background-image': 'url(' + $session.user.image + ')',
            }"
          >
            <div
              v-show="isProfileClicked"
              style="position: absolute;
              top: -125px;
              background: white;
              width: 200px;
              box-shadow: rgba(0, 0, 0, 0.45) 0px 0px 1px;
              left: 65px;
              height: 100px;
              padding: 5px;
              border-radius: 5px;"
            >
              <ul>
                <li>
                  <a :href="logout">Login</a>
                </li>
              </ul>
            </div>
          </div>
          <button
            :class="{ 'active-navs': this.showNavigationLevel }"
            class="menuLevel filter-menu"
            @click="showMatterNavigation()"
          >
            <div>
              <span> Matière</span>

              <span> {{ filters.unite_label }}</span>
            </div>
            <div>
              <img :src="url_base + '/assets/lms/icons/Arrow.png'" alt="" />
            </div>
          </button>
          <button
            :class="{ 'active-navs': showMatterLevel }"
            class="menuLevel filter-menu"
            @click="showLevelNavigation()"
          >
            <div>
              <span> Le niveau </span> <span> {{ filters.niveau_label }}</span>
            </div>
            <div>
              <img :src="url_base + '/assets/lms/icons/Arrow.png'" alt="" />
            </div>
          </button>
        </div>
        <div
          style="width: 100%; justify-content: space-between;align-items: center;"
          v-if="courseExists && !startLearn"
          class="fx filter"
        >
          <div style="display: flex;height: fit-content;">
            <button
              class="menuLevel choice"
              style="box-shadow: none;margin-right: 25px;"
              @click="returnToMainPage()"
            >
              <div
                class="d-flex"
                style="justify-content: space-between;flex-direction:row;align-items: center;"
              >
                <svg
                  fill="#000000"
                  viewBox="0 0 24 24"
                  id="right-arrow"
                  data-name="Flat Color"
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon flat-color"
                  transform="rotate(180)"
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
                <span v-show="false" class="et-sui" style="margin-left: 10px;">
                  Retour au programme</span
                >
              </div>
            </button>
            <button @click="startCourse(course.id)" class="menuLevel choice">
              <div
                class="d-flex"
                style="justify-content: space-between;flex-direction:row;align-items: center;"
              >
                <span class="et-sui" v-show="false">Etape suivante </span
                ><svg
                  fill="#ffffff"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                  stroke="#ffffff"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M17.707,17.707a1,1,0,0,1-1.414-1.414L19.586,13H2a1,1,0,0,1,0-2H19.586L16.293,7.707a1,1,0,0,1,1.414-1.414l5,5a1,1,0,0,1,0,1.414Z"
                    ></path>
                  </g>
                </svg>
              </div>
            </button>
          </div>
          <div style="display: flex;height: fit-content;">
            <button
              class="menuLevel choice"
              style="box-shadow: none;margin-right: 25px;"
              @click="returnToMainPage()"
            >
              <div
                class="d-flex"
                style="justify-content: space-between;flex-direction:row;align-items: center;"
              >
                <svg
                  fill="#000000"
                  viewBox="0 0 24 24"
                  id="right-arrow"
                  data-name="Flat Color"
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon flat-color"
                  transform="rotate(180)"
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
                <span v-show="false" class="et-sui" style="margin-left: 10px;">
                  Retour au programme</span
                >
              </div>
            </button>
            <button @click="startCourse(course.id)" class="menuLevel choice">
              <div
                class="d-flex"
                style="justify-content: space-between;flex-direction:row;align-items: center;"
              >
                <span class="et-sui" v-show="false">Etape suivante </span
                ><svg
                  fill="#ffffff"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                  stroke="#ffffff"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M17.707,17.707a1,1,0,0,1-1.414-1.414L19.586,13H2a1,1,0,0,1,0-2H19.586L16.293,7.707a1,1,0,0,1,1.414-1.414l5,5a1,1,0,0,1,0,1.414Z"
                    ></path>
                  </g>
                </svg>
              </div>
            </button>
          </div>
        </div>
        <div
          style="width: 100%; justify-content: space-between;align-items: center;"
          v-if="courseExists && startLearn"
          class="fx filter "
        >
          <div class="d-flex" style="align-items: center;display: flex;">
            <button
              class="btn-rounded btn-trans btn-shadow close-btn"
              @click="returnToMainCourse()"
            >
              <svg
                width="40px"
                height="40px"
                viewBox="0 0 24 24"
                version="1.1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                fill="#f86836"
                stroke="#f86836"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <title>Close</title>
                  <g
                    id="Page-1"
                    stroke="none"
                    stroke-width="1"
                    fill="none"
                    fill-rule="evenodd"
                  >
                    <g id="Close">
                      <rect
                        id="Rectangle"
                        fill-rule="nonzero"
                        x="0"
                        y="0"
                        width="24"
                        height="24"
                      ></rect>
                      <line
                        x1="16.9999"
                        y1="7"
                        x2="7.00001"
                        y2="16.9999"
                        id="Path"
                        stroke="#f86836"
                        stroke-width="2"
                        stroke-linecap="round"
                      ></line>
                      <line
                        x1="7.00006"
                        y1="7"
                        x2="17"
                        y2="16.9999"
                        id="Path"
                        stroke="#f86836"
                        stroke-width="2"
                        stroke-linecap="round"
                      ></line>
                    </g>
                  </g>
                </g>
              </svg>
            </button>
            <span v-if="false" class="main-color">{{
              ressources[currentRessource].label
            }}</span>
            <button
              @click="previusRessource()"
              style="margin-right: 15px;"
              class="btn-rounded  btn-shadow btn-material btn-hero mr-2"
            >
              <svg
                width="40px"
                height="40px"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M15 19.9201L8.47997 13.4001C7.70997 12.6301 7.70997 11.3701 8.47997 10.6001L15 4.08008"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-miterlimit="10"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></path>
                </g>
              </svg>
            </button>

            <button
              @click="nextRessource()"
              style="margin-right: 15px;"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <svg
                width="40px"
                height="40px"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M8.91016 19.9201L15.4302 13.4001C16.2002 12.6301 16.2002 11.3701 15.4302 10.6001L8.91016 4.08008"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-miterlimit="10"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></path>
                </g>
              </svg>
            </button>
            <button
              style="margin-right: 15px;"
              v-if="!fullScreen"
              @click="fullScreenEcran()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <img
                width="60%"
                :src="url_base + 'assets/lms/icons/full-size.png'"
                alt=""
              />
            </button>
            <button
              style="margin-right: 15px;"
              v-else
              @click="outScreenEcran()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <img
                width="60%"
                :src="url_base + 'assets/lms/icons/minimize.png'"
                alt=""
              />
            </button>
          </div>
          <div class="d-flex" style="align-items: center;display: flex;">
            <button
              style="margin-right: 15px;"
              @click="previusRessource()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <svg
                width="40px"
                height="40px"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M15 19.9201L8.47997 13.4001C7.70997 12.6301 7.70997 11.3701 8.47997 10.6001L15 4.08008"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-miterlimit="10"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></path>
                </g>
              </svg>
            </button>

            <button
              style="margin-right: 15px;"
              @click="nextRessource()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <svg
                width="40px"
                height="40px"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M8.91016 19.9201L15.4302 13.4001C16.2002 12.6301 16.2002 11.3701 15.4302 10.6001L8.91016 4.08008"
                    stroke="#ffffff"
                    stroke-width="1.5"
                    stroke-miterlimit="10"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></path>
                </g>
              </svg>
            </button>
            <button
              style="margin-right: 15px;"
              v-if="!fullScreen"
              @click="fullScreenEcran()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <img
                width="60%"
                :src="url_base + 'assets/lms/icons/full-size.png'"
                alt=""
              />
            </button>
            <button
              style="margin-right: 15px;"
              v-else
              @click="outScreenEcran()"
              class="btn-rounded  btn-shadow btn-material btn-hero"
            >
              <img
                width="60%"
                :src="url_base + 'assets/lms/icons/minimize.png'"
                alt=""
              />
            </button>
          </div>
        </div>
        <ul
          :class="{ showListLevel: showNavigationLevel }"
          class="list"
          v-if="showNavigationLevel && !courseExists"
        >
          <li
            v-for="niveau in niveaux"
            v-bind:key="niveau.id"
            :class="{ active: niveau.value === filters.niveau_id }"
            @click="filterByNiveau(niveau)"
          >
            <!-- <span> {{niveau.text}}</span> -->
            <span>{{ niveau.text }}</span>
          </li>
        </ul>
        <ul
          :class="{ showListLevel: showMatterLevel }"
          class="list"
          v-if="showMatterLevel && !courseExists"
        >
          <li
            :class="{ active: unite.value === filters.unite_id }"
            v-for="unite in unites"
            v-bind:key="unite.id"
            @click="filterCourseByUnite(unite)"
          >
            <span>{{ unite.text }}</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- button for closing the tab selected  -->
  </div>
</template>

<script>
import TextAudioAction from "../components/ressources/actions/TextAudioAction.vue";
import AudioRessource from "../components/ressources/AudioRessource.vue";
import GlossaryRessource from "../components/ressources/GlossaryRessource.vue";
import HtmlRessource from "../components/ressources/HtmlRessource.vue";
import ImageParagraphRessource from "../components/ressources/ImageParagraphRessource.vue";
import LinkingRessource from "../components/ressources/LinkingRessource.vue";
import OpenQuestionRessource from "../components/ressources/OpenQuestionRessource.vue";
import SchemasRessource from "../components/ressources/SchemasRessource.vue";
import SchemaStructRessource from "../components/ressources/SchemaStructRessource.vue";
import SlidesRessource from "../components/ressources/SlidesRessource.vue";
import StructureCompleteRessource from "../components/ressources/StructureCompleteRessource.vue";
import TextRessource from "../components/ressources/TextRessource.vue";
import TitleRessource from "../components/ressources/TitleRessource.vue";
import TrueFalseRessource from "../components/ressources/TrueFalseRessource.vue";
import VideoRessource from "../components/ressources/VideoRessource.vue";
import YoutubeVideoRessource from "../components/ressources/YoutubeVideoRessource.vue";
import QuestionImageRessource from "../components/ressources/QuestionImageRessource.vue";
import ImageWordRessource from "../components/ressources/ImageWordRessource.vue";
import TableRessource from "../components/ressources/TableRessource.vue";
import WordCompleteRessource from "../components/ressources/WordCompleteRessource.vue";
import PdfRessource from "../components/ressources/PdfRessource.vue";
import WordArrowRessource from "../components/ressources/WordArrowRessource.vue";
import SchemasColoresRessource from "../components/ressources/SchemasColoresRessource.vue";
export default {
  components: {
    TitleRessource,
    HtmlRessource,
    YoutubeVideoRessource,
    TextRessource,
    SlidesRessource,
    TrueFalseRessource,
    OpenQuestionRessource,
    StructureCompleteRessource,
    GlossaryRessource,
    VideoRessource,
    SchemasRessource,
    SchemaStructRessource,
    ImageParagraphRessource,
    AudioRessource,
    LinkingRessource,
    TextAudioAction,
    QuestionImageRessource,
    ImageWordRessource,
    TableRessource,
    WordCompleteRessource,
    PdfRessource,
    WordArrowRessource,
    SchemasColoresRessource,
  },

  data() {
    return {
      url: "borne_home",
      fullScreen: false,
      loaded: false,
      isProfileClicked: false,
      logout:
        window.document
          .querySelector("meta[name=base_api]")
          .getAttribute("content") + "login?logout",
      entered: false,
      zoomOnImage: false,
      course: null,
      courseExists: false,
      showNavigationLevel: false,
      showMatterLevel: false,
      niveaux: null,
      ressources: null,
      etape: null,
      contentStep: null,
      secondes: 0,
      minutes: 0,
      myTimer: null,
      times: "00:00",
      endTimer: false,
      least30Sec: false,
      timeCountQuestion: null,
      unites: null,
      rubriques_tree: null,
      file_content_index: 0,
      rubriques: null,
      unite: null,
      niveau: null,
      course_number: 0,
      rub_count: null,
      index_slide: 0,
      sildes_count: 0,
      filters: {
        unite_id: null,
        unite_label: null,
        niveau_id: null,
        niveau_label: null,
      },
      startLearn: false,
      showObjectifs: false,
      showSyllabus: false,
      showInstructions: false,
      showPrerequis: false,
      currentRessource: 0,
      currentContent: 0,

      isTrue: null,
      ressource: null,
      url_base: `${document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")}`,
      // alias: this.$route.params.alias,
    };
  },
  metaInfo() {
    return {
      title: `${(this.salon_name ? this.salon_name + " :" : "") +
        " Téléchargeable sur iPhone, iPad ou sur les appareils Android"}`,
    };
  },
  created() {
    this.fetch();
  },
  mounted() {
    LMSScriptHeader.init();
    LMSScript.init();
  },
  methods: {
    fetch() {
      if (!this.entered) {
        const params = {};
        console.log("ndndnd");
        console.log("ndndndssss");
        // check if there is an id on router
        let course = this.$route.params.pathMatch.split("/")[7];
        let etapeC = this.$route.params.pathMatch.split("/")[8];
        let contentSteps = this.$route.params.pathMatch.split("/")[9];

        if (course) {
          course = Number(course);
          this.getCourseFiltered(course);
        }
        if (course && etapeC) {
          this.loaded = false;
          etapeC = Number(etapeC);
          this.etape = etapeC;
          this.showCourse(course);
          this.startCourse(course);
        }
        if (course && etapeC && contentSteps) {
          contentSteps = Number(contentSteps);
          this.etape = etapeC;
          this.contentStep = contentSteps;
          this.showCourse(course);
          this.startCourse(course);
        }
        if (!course && !etapeC && !contentSteps) {
          this.getCourses();
        }
        // this.url_base = "http://localhost/lms/boti";
        // console.log(this.url_base);
        // console.log(
        //   document.querySelector("meta[name=base_api]").getAttribute("content")
        // );
      }
    },
    async getCourse(value, courseIndex = 0, runCount = 0, next_course = 0) {
      await axios.get(`/lecons/${value}`).then((res) => {
        this.course = res.data.lecon;
        this.filters.unite_id = this.course.unite_id;
        this.filters.niveau_id = this.course.niveau_id;
        this.rub_count = res.data.lecon.rub_count;
        this.course_number = courseIndex + 1;
        //this.rub_count = runCount;
      });
    },
    async getCourses() {
      await axios.get("/lecons/").then((res) => {
        this.course =
          res.data.rubriques_tree && res.data.rubriques_tree[0]
            ? res.data.rubriques_tree[0].lecons
              ? res.data.rubriques_tree[0].lecons[0]
              : []
            : [];
        this.filters.unite_id = this.course.unite_id
          ? this.course.unite_id
          : res.data.unites[0].value;
        this.filters.niveau_id = this.course.niveau_id
          ? this.course.niveau_id
          : res.data.niveaux[0].value;
        this.niveaux = res.data.niveaux;
        this.unites = res.data.unites;
        this.rubriques_tree = res.data.rubriques_tree;
        if (
          res.data.rubriques_tree &&
          res.data.rubriques_tree[0] &&
          res.data.rubriques_tree[0].lecons &&
          res.data.rubriques_tree[0].lecons[0]
        ) {
          this.course_number = 1;
        }
        this.rub_count =
          res.data.rubriques_tree && res.data.rubriques_tree[0]
            ? res.data.rubriques_tree[0].lecons
              ? res.data.rubriques_tree[0].lecons[0].rub_count
              : 0
            : 0;
        for (let i = 0; i < res.data.unites.length; i++) {
          if (this.filters.unite_id == res.data.unites[i].value) {
            this.filters.unite_label = res.data.unites[i].text;
          }
        }
        for (let i = 0; i < res.data.niveaux.length; i++) {
          if (this.filters.niveau_id == res.data.niveaux[i].value) {
            this.filters.niveau_label = res.data.niveaux[i].text;
          }
        }
      });
    },
    async getCourseFiltered(id) {
      await axios
        .get("/borne_lecon/" + id)
        .then((res) => {
          this.course = res.data.lecon;
          this.filters.unite_id = this.course.unite_id;
          this.filters.niveau_id = this.course.niveau_id;
          this.niveaux = res.data.niveaux;
          this.unites = res.data.unites;
          this.rubriques_tree = res.data.rubriques_tree;
          this.course_number = 1;
          this.rub_count = res.data.lecon.rub_count;
          for (let i = 0; i < res.data.unites.length; i++) {
            if (this.filters.unite_id == res.data.unites[i].value) {
              this.filters.unite_label = res.data.unites[i].text;
            }
          }
          for (let i = 0; i < res.data.niveaux.length; i++) {
            if (this.filters.niveau_id == res.data.niveaux[i].value) {
              this.filters.niveau_label = res.data.niveaux[i].text;
            }
          }
          for (let i = 0; i < res.data.niveaux.length; i++) {
            if (this.filters.niveau_id == res.data.niveaux[i].value) {
              this.filters.niveau_label = res.data.niveaux[i].text;
            }
          }
        })
        .catch((err) => {});
    },

    fullScreenEcran() {
      this.fullScreen = true;
      var elem = document.documentElement;
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
      } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
      }
    },
    outScreenEcran() {
      this.fullScreen = false;
      var elem = document.documentElement;
      document.exitFullscreen();
    },
    // ui design
    showLevelNavigation() {
      this.showNavigationLevel = !this.showNavigationLevel;
      this.showMatterLevel = false;
    },
    showMatterNavigation() {
      this.showMatterLevel = !this.showMatterLevel;
      this.showNavigationLevel = false;
    },
    closeCourseModal() {
      this.course = null;
      this.index_slide = 0;
    },

    filterCourseByUnite(element) {
      this.filters.unite_id = element.value;
      this.filters.unite_label = element.text;
      this.showMatterLevel = false;
      axios
        .get(
          `/borne_home?niveau_id=${this.filters.niveau_id}&unite_id=${this.filters.unite_id}`
        )
        .then((res) => {
          this.rubriques_tree = res.data.rubriques_tree;
          this.course = res.data.rubriques_tree[0].lecons[0];
          this.course_number = 1;
          this.rub_count = res.data.rubriques_tree[0].lecons.length;
        });
    },

    filterByNiveau(element) {
      this.filters.niveau_id = element.value;
      this.filters.niveau_label = element.text;
      this.showNavigationLevel = false;
      axios
        .get(
          `/borne_home?niveau_id=${this.filters.niveau_id}&unite_id=${this.filters.unite_id}`
        )
        .then((res) => {
          this.rubriques_tree = res.data.rubriques_tree;
          this.course = res.data.rubriques_tree[0].lecons[0];
          this.course_number = 1;
          this.rub_count = res.data.rubriques_tree[0].lecons.length;
        });
    },

    async showCourse(id) {
      this.course = null;
      this.entered = true;
      this.loaded = false;
      await axios.get(`/borne_lecon/${id}`).then((res) => {
        if (res.data.ressources.length > 0) {
          if (res.data.ressources[0].contents.length > 0) {
            this.ressources = res.data.ressources;
            this.course = res.data.lecon;
            this.courseExists = true;
          } else {
            swal.fire(
              "il n'y a pas de contenu sur les ressources",
              "",
              "error"
            );
          }
        } else {
          swal.fire("Il n'y a pas de ressources", "", "error");
        }
      });
      this.loaded = true;
    },
    close_modal(target = "") {
      this.popoverCtrl.dismiss({
        dismiss: true,
      });
    },
    changeFileContent(action, ressource) {
      if (action == "prev") {
        if (ressource.files[this.file_content_index - 1]) {
          // this.file_content = this.ressource_content.files[
          //   this.file_content_index - 1
          // ];
          this.file_content_index = this.file_content_index - 1;
        }
      }
      if (action == "next") {
        if (ressource.files[this.file_content_index + 1]) {
          // this.file_content = this.ressource_content.files[
          //   this.file_content_index + 1
          // ];
          this.file_content_index = this.file_content_index + 1;
        }
      }
    },
    showLevelNavigation() {
      this.showNavigationLevel = !this.showNavigationLevel;
      this.showMatterLevel = false;
    },
    showMatterNavigation() {
      this.showMatterLevel = !this.showMatterLevel;
      this.showNavigationLevel = false;
    },
    closeCourseModal() {
      this.course = null;
      this.index_slide = 0;
    },
    prevSlide(i) {
      if (this.index_slide > 0) {
        this.index_slide--;
      }
      if (this.index_slide == -1) {
        this.index_slide = 0;
      }
    },
    nextSlide(i) {
      if (this.index_slide >= 0) {
        this.index_slide++;
      }
      if (this.index_slide - i === 0) {
        this.index_slide = 0;
      }
    },
    timer() {
      this.secondes += 1;
      if (this.secondes == 60) {
        this.secondes = 0;
        this.minutes++;
      }
      if (this.minutes == 60) {
        this.minutes = 0;
        this.endTimer = true;
      }

      let sec = this.secondes >= 10 ? this.secondes : `0${this.secondes}`;
      let min = this.minutes >= 10 ? this.minutes : `0${this.minutes}`;
      let howLess =
        Number(this.ressource.duree) * 60 - (this.minutes * 60 + this.secondes);
      if (howLess == 0) {
        //this.endTimer = true;
        console.log("end");
      }
      if (howLess == 30) {
        this.least30Sec = true;
      }
      this.times = min + ":" + sec;
    },
    show_objectifs() {
      this.showObjectifs = !this.showObjectifs;
    },
    show_syllabus() {
      this.showSyllabus = !this.showSyllabus;
    },
    show_intro() {
      this.showInstructions = !this.showInstructions;
    },
    show_prerequis() {
      this.showPrerequis = !this.showPrerequis;
    },
    returnToMainPage() {
      this.courseExists = false;
      this.currentRessource = 0;
      this.currentContent = 0;
      this.startLearn = false;
      this.etape = null;
      this.contentStep = null;
      this.initialize();
      this.fetch();
    },
    returnToMainCourse() {
      this.initialize();
      this.loaded = false;
      this.courseExists = true;
      this.currentRessource = 0;
      this.currentContent = 0;
      this.startLearn = false;
      this.etape = null;
      this.contentStep = null;
      clearInterval(this.myTimer);
      this.fetch();
    },
    async startCourse(id) {
      this.loaded = false;
      this.initialize();
      this.startLearn = true;
      this.course = null;
      this.ressources = null;
      //this.showCourse(id);
      await axios.get(`/borne_lecon/${id}`).then((res) => {
        if (res.data.ressources.length > 0) {
          if (res.data.ressources[0].contents.length > 0) {
            this.ressources = res.data.ressources;
            this.course = res.data.lecon;
            this.courseExists = true;
          } else {
            swal.fire(
              "il n'y a pas de contenu sur les ressources",
              "",
              "error"
            );
          }
        } else {
          swal.fire("Il n'y a pas de ressources", "", "error");
        }
      });
      if (this.course.last_content_learn && this.course.last_ressource_learn) {
        this.contentStep = this.course.last_content_learn;
        this.etape = this.course.last_ressource_learn;
      }
      if (this.etape) {
        for (let i = 0; i < this.ressources.length; i++) {
          if (this.ressources[i].id == this.etape) {
            this.currentRessource = i;
          }
        }
      }
      if (this.contentStep) {
        console.log("from here");
        for (let i = 0; i < this.ressources.length; i++) {
          for (let j = 0; j < this.ressources[i].contents.length; j++) {
            if (
              this.ressources[i].id == this.etape &&
              this.ressources[i].contents[j].id == this.contentStep
            ) {
              this.currentContent = j;
            }
          }
        }
      }
      this.ressource = this.ressources[this.currentRessource].contents[
        this.currentContent
      ];

      clearInterval(this.myTimer);
      this.myTimer = setInterval(() => {
        this.timer();
      }, 1000);
    },
    initialize() {
      this.isStartSpeak = false;
      clearInterval(this.myTimer);
      this.isResume = false;
      this.secondes = 0;
      this.minutes = 0;
      this.times = "00:00";
      this.endTimer = false;
      this.least30Sec = false;
      speechSynthesis.cancel();

      var textarea = document.getElementById("responseRessource");
      var trueBtn = document.getElementById("answer-true");
      var falseBtn = document.getElementById("answer-false");
      var structureAnswers = document.getElementsByClassName("structureAnswer");
      if (textarea) {
        textarea.value = "";
      }
      if (trueBtn) {
        trueBtn.classList.remove("bx-active-btn");
      }
      if (falseBtn) {
        falseBtn.classList.remove("bx-active-btn");
      }
      if (structureAnswers) {
        for (let index = 0; index < structureAnswers.length; index++) {
          structureAnswers[index].style.color = "white";
        }
      }
      this.loaded = true;
    },
    async nextRessource() {
      this.initialize();

      if (
        this.currentContent >= 0 &&
        this.currentContent <
          this.ressources[this.currentRessource].contents.length - 1
      ) {
        this.currentContent += 1;
      } else {
        if (
          this.currentRessource >= 0 &&
          this.currentRessource < this.ressources.length - 1
        ) {
          this.currentRessource += 1;
          this.currentContent = 0;
        } else {
          swal.fire(
            "Leçon complete",
            "La leçon a été complete avec succés.",
            "success"
          );
          swal
            .fire({
              title: "Êtes-vous sûr ?",
              text: "Go to next leçon !",
              type: "question",
              showCancelButton: true,
              confirmButtonText: "Ok",
            })
            .then(
              function(result) {
                let next_lecon = -1;
                if (result.value) {
                  for (let i = 0; i < this.rubriques_tree.length; i++) {
                    for (
                      let j = 0;
                      j < this.rubriques_tree[i].lecons.length;
                      j++
                    ) {
                      if (
                        this.course.id == this.rubriques_tree[i].lecons[j].id
                      ) {
                        console.log("here", this.rubriques_tree[i].lecons[j]);
                        next_lecon = this.rubriques_tree[i].lecons[j + 1]
                          ? this.rubriques_tree[i].lecons[j + 1].id
                          : this.rubriques_tree[i + 1].lecons[0].id;
                        break;
                      }
                    }
                    console.log("hshs", next_lecon);
                    if (next_lecon != -1) {
                      console.log("ddd", next_lecon);
                      this.showCourse(next_lecon);
                      //this.startCourse(next_lecon);
                    }
                  }
                  // this.getCourseFiltered(this.course.next_lecon);
                }
              }.bind(this)
            );
          this.courseExists = false;
          this.startLearn = false;
          this.etape = null;
          this.contentStep = null;
          this.currentRessource = 0;
          this.currentContent = 0;
        }
      }
      this.ressource = this.ressources[this.currentRessource].contents[
        this.currentContent
      ];
      clearInterval(this.myTimer);
      this.myTimer = setInterval(() => {
        this.timer();
      }, 1000);
      let formData = new FormData();
      formData.append("lecon", this.course.id);
      formData.append("step", this.ressource.id);
      formData.append("ressource", this.ressources[this.currentRessource].id);
      await axios
        .post("/saveTeacherTracking", formData)
        .then((res) => {})
        .catch((err) => {
          console.log(err);
        });
      if (!this.courseExists && !this.startLearn && !this.contentStep) {
        this.fetch();
      }
    },

    async previusRessource() {
      this.initialize();

      if (this.currentContent == 0) {
        if (this.currentRessource == 0) {
          swal.fire(
            "Leçon complete",
            "return à la liste des leçons",
            "success"
          );
          this.courseExists = false;
          this.currentRessource = 0;
          this.currentContent = 0;
          this.startLearn = false;
          this.etape = null;
          this.contentStep = null;

          //this.currentRessource = this.ressources.length - 1;
        } else if (this.currentRessource > 0) {
          this.currentRessource -= 1;
        }
        this.currentContent =
          this.ressources[this.currentRessource].contents.length - 1;
      } else if (this.currentContent > 0) {
        this.currentContent -= 1;
      }
      this.ressource = this.ressources[this.currentRessource].contents[
        this.currentContent
      ];
      clearInterval(this.myTimer);
      this.myTimer = setInterval(() => {
        this.timer();
      }, 1000);
      //this.fetch();
    },
  },
};
</script>
<style lang="scss">
.ion-header {
  .ion-toolbar {
    text-align: start;
    h1 {
      color: #171656;
      position: relative;
      width: 50%;
      margin-left: 2.5%;
      &::after {
        content: "";
        position: absolute;
        width: 1px;
        height: 65px;
        background: #e3e3e3;
        right: -9px;
        top: -25px;
      }
    }
  }
}
.ion-content {
  position: relative;
}

.audio {
  text-align: center;
  flex: 1;
  audio::-webkit-media-controls-play-button {
    color: #ffffff;
  }
  audio::-webkit-media-controls-panel {
    background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
    color: #ffffff;
  }
}
li {
  list-style-type: none;
  padding: 10px;
  width: fit-content;
  cursor: pointer;
  border-radius: 5px;
  margin-top: 5px;
  &:hover {
    color: white;
    background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
  }
}
li.active {
  color: white !important;
  background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
}

a {
  text-decoration: none;
}
.content-2 {
  display: block;
  figure {
    table {
      width: 100%;
      height: 100%;
      tr {
        border: 1px solid #171656;
      }
      td {
        text-align: center;
        border: 1px solid grey;
      }
    }
  }
  figure.image {
    margin: 10px 0;
    text-align: center;
  }
}
.btn-hero {
  background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
  border-radius: 50%;
  width: 50px;
  height: 50px;
  outline: none;
  box-shadow: 0px 0px 3px #000000ab;
}
.btn {
  color: white;
  padding: 10px;
  border-radius: 5px;
}
.btn-success {
  background-color: #f5574b;
  outline: none;
}
.slide {
  display: none;
}
.btn-cntr {
  display: none;
  cursor: pointer;
}

.slide-active {
  display: block;
}

.list {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 0px;
  padding: 0px;
  opacity: 0;
  transition: 0.9s;
  height: 120px;
  overflow: auto;
  &::-webkit-scrollbar-thumb {
    padding: 10px;
  }
  &::-webkit-scrollbar {
    height: 4px !important;
  }
}

.showListLevel {
  margin-left: 0px !important;
  opacity: 1 !important;
  height: 100%;
  padding: 0 20px;
  border-radius: 13px;
  li {
    margin-right: 5px;
    margin-top: 0px;
    border-radius: 60px;
    box-shadow: 0px 0px 2px #00000030;
    color: #f34e54;
    span {
      min-width: 150px;
      max-width: 100%;
      display: flex;
      font-weight: 600;
      justify-content: center;
      white-space: nowrap;
    }
    &:hover {
      color: white;
    }
  }
}
.menuLevel {
  display: flex;
  align-items: center;
  background: transparent;
  width: fit-content;
  padding: 5px 20px;
  color: #9b9b9b;
  border-radius: 50px;
  transition: 0.4s;
  margin-right: 5px;
  cursor: pointer;
  box-shadow: 0px 0px 2px #00000030;
  justify-content: space-between;
  width: 200px;
  height: 50px;
  text-align: initial;
  div {
    display: flex;
    flex-direction: column;
    span {
      font-weight: 500;
      font-size: 16px;
    }
  }
  svg {
    width: 30px;
  }

  cursor: pointer;
  .menu {
    font-size: 30px;
    margin-right: 5px;
  }
}
.filter-menu {
  span {
    &:nth-child(2) {
      color: #171656;
      font-weight: 700;
    }
  }
}
.choice {
  box-shadow: 0 0 3px 0px #00000061;
  background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  width: fit-content;
  &:nth-child(1) {
    color: #f7623c;
    background: #fff0ec;
  }
}
.active-nav {
  background-color: #f86836;
  border-color: #f86836;
  color: white;
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 5px;
  .bx {
    position: relative;
    &::after {
      content: "";
      position: absolute;
      width: 1px;
      height: 200%;
      top: -26%;
      right: -18px;
      background: #e9e9e9;
    }
    .bx-child {
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      height: 100%;
      // margin-top: 50px;
      h4 {
        color: #f5574b;
      }
      .obs {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 30px 0;
        width: 100%;
        .detail {
          width: 80%;
          p {
            height: 0px;
            width: 100%;
            background: rgb(230, 230, 230);
            box-shadow: 0 0 1px 0px #00000075;
            padding: 0px;
            margin: 0;
            min-width: 341px;
            opacity: 0;

            -webkit-transition: height 0.5s ease, padding 0.5s ease,
              opacity 0.5s ease;
            -moz-transition: height 0.5s ease, padding 0.5s ease,
              opacity 0.5s ease;
            -ms-transition: height 0.5s ease, padding 0.5s ease,
              opacity 0.5s ease;
            -o-transition: height 0.5s ease, padding 0.5s ease,
              opacity 0.5s ease;
            transition: height 0.5s ease, padding 0.5s ease, opacity 0.5s ease;
          }
        }
        .active-obs-div {
          p {
            height: 100%;
            padding: 10px 3px;
            opacity: 1;
          }
        }
        .child {
          width: 80%;
          padding: 9px 10px;
          text-align: initial;
          border: 1px solid #8080802e;
          color: #171656;
          display: flex;
          justify-content: space-between;
          align-items: center;
          cursor: pointer;
          span {
            font-weight: 700;
          }
          svg {
            width: 30px;
          }
        }
      }
      .btn-success {
        background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
        border-radius: 50px;
        box-shadow: 0 0 3px 0px #00000061;
        padding: 15px 20px;
        outline: none;
      }
    }
    &:nth-child(1) {
      height: 60vh;

      overflow-y: scroll;
      width: 100%;
      padding-right: 10px;
      overflow-x: hidden;
    }
    &:nth-child(2) {
      margin-left: 40px;
    }
    &:nth-child(2)::after {
      left: -1px;
    }

    .col {
      .title {
        color: #171656;
        position: relative;
        margin-left: 5.3%;
        &::after {
          content: "";
          background-color: #f7623c;
          position: absolute;
          width: 14px;
          height: 55px;
          border-radius: 6px;
          // left: -7%;
          left: -8.5%;
          top: 50%;
          transform: translateY(-50%);
        }
      }
      .fx {
        .course-div {
          color: #7b7b7c;
          margin-bottom: 5px;
          display: flex;
          cursor: pointer;
          justify-content: space-between;
          align-items: center;
          margin-left: 5.3%;
          cursor: pointer;
          .lecon {
            font-weight: 300;
            margin-left: 5px;
          }
          .percent {
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 0px 0px 1px #00000012;
            font-size: 12.5px;
            font-weight: 700;
          }
        }
      }
    }
  }
}
.close-learn {
  background: #ffc107 !important;
  color: white;
}
.success-learn {
  background: #28a745 !important;
  color: white;
}
.no-learn {
  background: #f24a58 !important;
  color: white;
}
.v-carousel__controls {
  display: none;
}
.timer {
  color: #f55a47;
  margin-left: 25px;
  text-align: center;
  padding: 15px;
  font-size: 20px;
  display: block;
  width: fit-content;
  font-weight: 700;
}
.timer-warning {
  color: white;
  background-color: #bb4646;
}
.img-zoom {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff85;
}
.img-zoom img {
  width: 80%;
  height: 80%;
  object-fit: contain;
}
.wrapper-ress {
  margin: auto;
  height: 100%;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  .img {
    height: 50%;
    width: 40%;
  }
  .question {
    font-size: 2rem;
    margin-bottom: 5px;
    width: 100%;
  }

  .checkbox-answers {
    display: flex;
    margin-top: 40px;
    .bx {
      padding: 10px 20px;
      color: #171656;
      border-radius: 40px;
      border: 1px solid #8080803d;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 5px;
    }
    .bx-active-btn {
      color: white;
      background: #4aad1b;
    }
  }
}
.user-image {
  background-image: url(/lms/assets/icons/woman.jpg);
  width: 50px;
  height: 50px;
  background-position: center;
  background-size: cover;
  border: 1px solid #00000029;
  padding: 5px;
  border-radius: 50%;
  margin-right: 10px;
  box-shadow: 0px 0px 1px #0000004f;
}
#responseRessource {
  color: #171656;
  font-weight: 600;
}
.courses-actions {
  position: fixed;
  bottom: 4%;
  left: 73%;
  padding-bottom: calc(0px + var(--ion-safe-area-bottom, 0));
  width: 100%;
  width: fit-content;
  background: transparent;
  .zone {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    height: 120px;
    margin: 5px;
    z-index: 9999;
    .btn-close {
      --border-radius: 50%;
      width: 3.5rem;
      height: 3.5rem;
      --background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
      --box-shadow: 0px 2px 10px rgba(246, 94, 65, 0.4);
      --padding-bottom: 0;
      --padding-start: 0;
      --padding-end: 0;
      --padding-top: 0;
      svg {
        width: 1.2rem;
      }
    }
  }
}

.collapse {
  width: 80%;
  background: #f7f7f7;
  color: black;

  p {
    margin-top: 5px;
    color: #171656;
    font-weight: 600;
  }
}
.modal {
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0, 0, 0);
  background-color: rgba(0, 0, 0, 0.4);
}

.selected-lecon {
  color: #171656;
  font-weight: 600 !important;
}

.content-image-word {
  img {
    object-fit: contain;
  }
}
.img-zoom {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff85;
  cursor: zoom-out;
}
.img-zoom img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}
.course-modal {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.288);
  transform: translate(-50%, -50%);
  span {
    font-weight: 700;
    color: #171656;
  }
  div {
    background: white;
    padding: 50px;
    border-radius: 5px;
    position: fixed;
    top: 50%;
    left: 50%;
    width: 350px;
    transform: translate(-50%, -50%);
    .icon-1 {
      padding: 0;
      top: 9%;
      left: 9%;
      width: fit-content;
      height: fit-content;
      position: absolute;
      svg {
        width: 60px;
        height: 60px;
      }
    }
    .icon-2 {
      padding: 0;
      top: 87%;
      left: 90%;
      width: fit-content;
      position: absolute;
      height: fit-content;
      svg {
        width: 60px;
        height: 60px;
      }
    }
  }
}
#textarea {
  outline: none;
}
#textarea::selection {
  background-color: rgba(255, 0, 0, 0.2);
}

.content-ressource {
  color: #171656;

  height: 77vh;
  width: 80%;
  overflow-y: auto;
  padding: 60px 20px;
  box-shadow: 0px 0px 6px 0px #00000033;
  display: flex;
  border-radius: 35px;
  margin: 20px auto;
  position: relative;
  justify-content: space-between;
  .img {
    overflow: hidden;
    border-radius: 15px;
    // width: 380px;
    width: 36%;
    margin: 0 40px 0 0 !important;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
  svg {
    width: 50px !important;
  }
}
.content-1 {
  h3 {
    margin: auto;
  }
}
.content-6 {
  p {
    margin: 0;
    width: 80%;
    p {
      margin: 0;
    }
  }
  div {
    width: 50%;
    overflow-y: auto;
  }
  height: 65vh;
  width: 80%;
  overflow: hidden;
  padding: 20px;
  border: 1px solid #dfdfdf;
  display: flex;
  border-radius: 35px;
  margin: 20px auto;
  justify-content: space-between;
  .img {
    overflow: hidden;
    border-radius: 15px;
    width: 380px;
    height: 500px;
    margin: 0 40px 0 0 !important;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
  svg {
    width: 50px !important;
  }
}
.change-pdf {
  width: fit-content;
  text-align: center;
  margin-left: auto;
  padding: 5px 10px;
  border-radius: 10px;
  background: #f1465d;
  a {
    color: white;
    font-weight: bold;
  }
}
.courses-container {
  overflow: hidden;
  width: 90%;
  margin: 20px;
  box-shadow: 0px 0px 6px 0px #00000033;
  border-radius: 30px;
}
.lecons-actions {
  position: absolute;
  bottom: 0;
  padding-bottom: calc(0px + var(--ion-safe-area-bottom, 0));
  width: 100%;
  background: #fff;
  .zone {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    height: 78px;
    padding: 10px;
    margin: 5px;
    border-top: 1px solid #f0f1f2;
    .fx {
      display: flex;
    }
  }
}
.fx-course {
  height: 90vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  .text {
    text-align: center;
    h1 {
      color: #171656;
    }
    button {
      margin: auto !important;
    }
  }
}

::-webkit-scrollbar {
  width: 5px;
  height: 3px;
}

/* Track */
::-webkit-scrollbar-track {
  background: var(--lightestgrey);
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
.tab-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0px 5px 15px;
}
.v-window__prev,
.v-window__next {
  height: 40px;
  width: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.header-learning {
  color: #171656;
  margin-top: 5%;
  margin-left: 3%;
  font-size: 25px;
  font-weight: 600;
  min-height: 30px;
}
.etape-type {
  // background: #f7623c;
  position: absolute;
  z-index: 10;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: fit-content;
  color: white;
  margin: auto;
  font-size: 20px;
  padding: 3px 20px;
  border-bottom-left-radius: 50px;
  border-bottom-right-radius: 50px;
}
input[type="range"]:focus {
  outline: none;
}
.close-btn {
  background: #fff0ec;
  border: 1px solid #f7623c;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 6px;
  margin-right: 15px;
}
input[type="range"]::-webkit-slider-runnable-track {
  width: 100%;
  height: 5px;
  cursor: pointer;
  animation-delay: 0.2s;
  box-shadow: 0px 0px 0px #000000;
  background: #2497e3;
  border-radius: 1px;
  border: 0px solid #000000;
}
input[type="range"]::-webkit-slider-thumb {
  box-shadow: 0px 0px 0px #000000;
  border: 1px solid #2497e3;
  height: 18px;
  width: 18px;
  border-radius: 25px;
  background: #a1d0ff;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -7px;
}
input[type="range"]:focus::-webkit-slider-runnable-track {
  background: #2497e3;
}
input[type="range"]::-moz-range-track {
  width: 100%;
  height: 5px;
  cursor: pointer;
  animation-delay: 0.2s;
  box-shadow: 0px 0px 0px #000000;
  background: #2497e3;
  border-radius: 1px;
  border: 0px solid #000000;
}
input[type="range"]::-moz-range-thumb {
  box-shadow: 0px 0px 0px #000000;
  border: 1px solid #2497e3;
  height: 18px;
  width: 18px;
  border-radius: 25px;
  background: #a1d0ff;
  cursor: pointer;
}
input[type="range"]::-ms-track {
  width: 100%;
  height: 5px;
  cursor: pointer;
  animation-delay: 0.2s;
  background: transparent;
  border-color: transparent;
  color: transparent;
}
input[type="range"]::-ms-fill-lower {
  background: #2497e3;
  border: 0px solid #000000;
  border-radius: 2px;
  box-shadow: 0px 0px 0px #000000;
}
input[type="range"]::-ms-fill-upper {
  background: #2497e3;
  border: 0px solid #000000;
  border-radius: 2px;
  box-shadow: 0px 0px 0px #000000;
}
input[type="range"]::-ms-thumb {
  margin-top: 1px;
  box-shadow: 0px 0px 0px #000000;
  border: 1px solid #2497e3;
  height: 18px;
  width: 18px;
  border-radius: 25px;
  background: #a1d0ff;
  cursor: pointer;
}
input[type="range"]:focus::-ms-fill-lower {
  background: #2497e3;
}
input[type="range"]:focus::-ms-fill-upper {
  background: #2497e3;
}
.container {
  padding: 0;
  width: 100% !important;
}
#textarea {
  font-size: 1.3rem;
}
.html {
  font-size: 1.5rem;
  li {
    cursor: initial;
    list-style: inside;
    &:hover {
      background: transparent;
      color: black;
    }
  }
}
.word-ressource {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2.8rem;
  font-weight: 700;
  color: #171656;
  font-family: sans-serif;
}

@media (min-width: 1264px) {
  .container {
    max-width: 100% !important;
  }
}

@media (min-width: 960px) {
  .container {
    max-width: 100% !important;
  }
}
@media (min-width: 1500px) {
  .container {
    width: 100% !important;
  }

  #textarea {
    font-size: 1.3rem;
  }
  .content-1 {
    h3 {
      font-size: 4rem;
    }
  }
  .wrapper-ress {
    .question {
      font-size: 3.5rem;
      margin: 0;
    }

    .checkbox-answers {
      font-size: 4rem;
    }
    .img {
      height: 70%;
      width: 50%;
      object-fit: cover;
      margin-right: 0 !important;
    }
    #responseRessource {
      overflow: auto;
      height: 300px;
      font-size: 3rem;
    }
  }
  .content-ressource {
    width: 98%;
    overflow-y: auto;
    .html {
      font-size: 1.5rem;
      overflow: auto;
    }
  }

  .fx-course {
    .text {
      h1 {
        font-size: 2rem;
      }
      button {
        font-size: 1rem;
      }
    }
  }
  .menuLevel {
    div {
      span.et-sui {
        font-size: 1rem;
      }
    }
  }
}
</style>
