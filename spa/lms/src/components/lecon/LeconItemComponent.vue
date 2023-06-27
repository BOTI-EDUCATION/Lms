<template>
  <div>
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
      <div
        class="
          container-fluid
          d-flex
          align-items-center
          justify-content-between
          flex-wrap flex-sm-nowrap
        "
      >
        <!--begin::Details-->
        <div class="d-flex align-items-center flex-wrap mr-2">
          <!--begin::Title-->
          <h5 v-if="lecon" class="text-dark font-weight-bold mt-2 mb-2 mr-5">
            {{ lecon.label }}
          </h5>
          <!--end::Title-->
          <!--begin::Breadcrumb-->
          <ul
            class="
              breadcrumb breadcrumb-transparent breadcrumb-dot
              font-weight-bold
              p-0
              my-2
              font-size-sm
            "
          >
            <li class="breadcrumb-item">
              <router-link to="/lecons" class="text-muted"
                >Structure pédagogique</router-link
              >
            </li>
            <li class="breadcrumb-item">
              <a href="#" class="text-muted">{{ lecon.label }}</a>
            </li>
          </ul>
          <!--end::Breadcrumb-->
        </div>
        <!--end::Details-->

        <div class="d-flex align-items-center">
          <div
            class="dropdown dropdown-inline"
            v-if="lecon"
            data-toggle="tooltip"
            title="Quick actions"
            data-placement="left"
          >
            <a
              href="#"
              class="btn btn-nice btn-sm btn-primary"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              Actions
              <span class="ml-2 svg-icon svg-icon-primary svg-icon-lg">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Files/File-plus.svg-->
                <i class="flaticon-settings pr-0"></i>
                <!--end::Svg Icon-->
              </span>
            </a>
            <div
              class="
                dropdown-menu
                p-0
                m-0
                dropdown-menu-md dropdown-menu-right
                py-3
              "
            >
              <!--begin::Navigation-->
              <ul class="navi navi-hover py-5 pl-0">
                <li
                  class="navi-item"
                  :style="lecon.enabled == 0 ? 'display:none;' : ''"
                >
                  <!-- url_base  -->

                  <a
                    :href="url_base + lecon.id"
                    target="_blank"
                    class="navi-link pr-0 w-100 text-left"
                  >
                    <span class="navi-icon">
                      <i class="flaticon-eye"></i>
                    </span>
                    <span class="navi-text">Aperçu</span>
                  </a>
                </li>
                <li class="navi-item" v-show="lecon.enabled == 0">
                  <button
                    @click="activer_lecon()"
                    class="navi-link pr-0 w-100 text-left"
                  >
                    <span class="navi-icon">
                      <i class="flaticon2-shield"></i>
                    </span>
                    <span class="navi-text">Activer</span>
                  </button>
                </li>
                <li class="navi-item" v-show="lecon.enabled == 1">
                  <button
                    @click="bloquer_lecon()"
                    class="navi-link pr-0 w-100 text-left"
                  >
                    <span class="navi-icon">
                      <i class="flaticon2-delete"></i>
                    </span>
                    <span class="navi-text">Désactiver</span>
                  </button>
                </li>
                <li class="navi-item">
                  <button
                    style="padding-left: 17px;"
                    @click="deleteLecon(lecon)"
                    class="navi-link pr-0 w-100 text-left"
                  >
                    <span class="navi-icon" style="opacity: 0.5;">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-trash"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                        />
                        <path
                          fill-rule="evenodd"
                          d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                        />
                      </svg>
                    </span>
                    <span class="navi-text">Supprimer</span>
                  </button>
                </li>
                <li class="navi-item">
                  <button
                    @click="addRessource()"
                    data-toggle="modal"
                    href="#modal_form_ressource"
                    class="navi-link pr-0 w-100 text-left"
                  >
                    <span class="navi-icon">
                      <i class="flaticon-plus"></i>
                    </span>
                    <span class="navi-text">Nouvelle étape</span>
                  </button>
                </li>
              </ul>

              <!--end::Navigation-->
            </div>
          </div>
          <!--end::Dropdowns-->
        </div>
      </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
          <!--begin::Card header-->
          <div class="card-header card-header-tabs-line nav-tabs-line-3x">
            <!--begin::Toolbar-->
            <div class="card-toolbar">
              <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                <!--begin::Item-->
                <li class="nav-item mr-3">
                  <a
                    class="nav-link active"
                    data-toggle="tab"
                    href="#kt_user_edit_tab_5"
                  >
                    <span class="nav-text font-size-lg">Ressources</span>
                  </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item mr-3">
                  <a
                    class="nav-link"
                    data-toggle="tab"
                    href="#kt_user_edit_tab_0"
                  >
                    <span class="nav-text font-size-lg">Informations</span>
                  </a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item mr-3">
                  <a
                    class="nav-link"
                    data-toggle="tab"
                    href="#kt_user_edit_tab_1"
                  >
                    <span class="nav-text font-size-lg">Détails</span>
                  </a>
                </li>
                <!--end::Item-->
              </ul>
            </div>
            <!--end::Toolbar-->
          </div>
          <!--end::Card header-->
          <!--begin::Card body-->
          <div class="card-body">
            <div class="tab-content">
              <!--begin::Tab-->
              <div
                class="tab-pane show active px-7"
                id="kt_user_edit_tab_5"
                role="tabpanel"
              >
                <!--begin::Accordion-->
                <div style="text-align: right; margin-bottom: 10px">
                  <a
                    class="btn btn-nice btn-sm btn-primary"
                    @click="addRessource()"
                    data-toggle="modal"
                    href="#modal_form_ressource"
                  >
                    <i class="ki ki-plus icon-1x"></i> Nouvelle étape
                  </a>
                </div>
                <div
                  class="accordion accordion-solid accordion-toggle-plus"
                  id="accordionExample6"
                >
                  <draggable
                    class="list-group"
                    style="height: 100%"
                    :list="ressources"
                    @end="onEndDrag()"
                    :group="'ressources'"
                    itemKey="id"
                  >
                    <div
                      class="d-flex align-items-center mb-2"
                      v-for="ressource in ressources"
                      v-bind:key="ressource.id"
                    >
                      <i class="fas fa-arrows-alt cursor-pointer mr-3"></i>
                      <div
                        class="card w-100"
                        style="overflow: hidden !important;"
                      >
                        <div
                          class="card-header"
                          :id="'headingOne_' + ressource.id"
                        >
                          <div
                            class="card-title collapsed"
                            style="justify-content: space-between;"
                            data-toggle="collapse"
                            :data-target="'#collapseOne6_' + ressource.id"
                          >
                            <div style="display: flex;align-items: center;">
                              <!-- :src="ressource.image_url" -->
                              <div
                                style="display: flex;flex-direction: column;justify-content: center;align-items: center;margin-right: 10px;"
                              >
                                <img
                                  style="width: 45px;"
                                  :src="
                                    getRessourceEtapeImage(ressource.type_label)
                                  "
                                  alt="not"
                                />
                                <span
                                  v-if="ressource.type_label"
                                  class="label label-info label-inline mt-2"
                                  >{{ ressource.type_label }}</span
                                >
                              </div>

                              <div style="margin-right:5px">
                                <h5 style="font-weight: 700 !important">
                                  {{ ressource.label }}
                                </h5>
                                <p
                                  class="
                                  font-inter font-weight-400 font-size-sm
                                  mr-2
                                "
                                  style="width: 80%;"
                                >
                                  {{ ressource.introduction }}
                                </p>
                              </div>
                            </div>

                            <div style="margin-right: 15px;">
                              <a
                                v-if="$session.user.role == 'admin'"
                                :href="url_base + lecon.id + '/' + ressource.id"
                                target="_blank"
                                class="navi-link pr-0 w-100 text-left mr-2"
                              >
                                <span class="navi-icon">
                                  <i class="flaticon-eye"></i>
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div
                          :id="'collapseOne6_' + ressource.id"
                          class="collapse"
                          data-parent="#accordionExample6"
                        >
                          <div class="card-body">
                            <div class="row" style="justify-content: end">
                              <div class="col-4">
                                <div class="form-group">
                                  <div
                                    class="input-group"
                                    style="justify-content: end;"
                                  >
                                    <div class="input-group-append ">
                                      <button
                                        @click="showTypesContent(ressource.id)"
                                        class="btn btn-primary rounded"
                                        style="height: 40px"
                                        type="button"
                                      >
                                        <i class="flaticon2-plus"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div>
                              <div class="content-grid">
                                <draggable
                                  :list="ressource.contents"
                                  @end="onEndDragContents(ressource.contents)"
                                  :group="'ressource' + ressource.id"
                                  itemKey="id"
                                  :element="'div'"
                                >
                                  <!-- content-grid-child -->

                                  <div
                                    class="main-content-grid-box"
                                    v-for="(content,
                                    index) in ressource.contents"
                                    v-bind:key="content.id"
                                  >
                                    <div
                                      class="content-grid-box"
                                      v-bind:key="content.id"
                                    >
                                      <div
                                        style="text-align: center;font-weight: 900;"
                                      >
                                        <span>
                                          Ressource numéro :
                                          {{ index + 1 }}
                                        </span>
                                        <a
                                          v-if="$session.user.role == 'admin'"
                                          :href="
                                            url_base +
                                              lecon.id +
                                              '/' +
                                              ressource.id +
                                              '/' +
                                              content.id
                                          "
                                          target="_blank"
                                          class="navi-link pr-0 w-100 text-left ml-2 mr-2"
                                        >
                                          <span class="navi-icon">
                                            <i class="flaticon-eye"></i>
                                          </span>
                                        </a>
                                      </div>
                                      <div
                                        class="img"
                                        v-for="res in ressource_types"
                                        :key="res.id"
                                        v-if="content.type_label == res.text"
                                      >
                                        <img
                                          width="220px"
                                          :src="res.image_url"
                                          alt="s"
                                        />
                                      </div>
                                      <div class="content-grid-box-data">
                                        <div class="content-grid-box-text">
                                          {{ content.type_label }} ({{
                                            content.duree
                                          }}
                                          minutes)
                                        </div>
                                        <div class="content-grid-box-actions">
                                          <a
                                            href="javascript:void(0)"
                                            @click="
                                              editContent(content, ressource.id)
                                            "
                                            class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3"
                                          >
                                            <span
                                              class="svg-icon svg-icon-md svg-icon-primary"
                                            >
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
                                                  <rect
                                                    x="0"
                                                    y="0"
                                                    width="24"
                                                    height="24"
                                                  />
                                                  <path
                                                    d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                                    fill="#000000"
                                                    fill-rule="nonzero"
                                                    transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"
                                                  />
                                                  <path
                                                    d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                                    fill="#000000"
                                                    fill-rule="nonzero"
                                                    opacity="0.3"
                                                  />
                                                </g>
                                              </svg>
                                            </span>
                                          </a>
                                          <a
                                            href="javascript:void(0)"
                                            @click="deleteContent(content.id)"
                                            class="
                                        btn
                                        btn-icon
                                        btn-light
                                        btn-hover-danger
                                        btn-sm
                                      "
                                          >
                                            <span
                                              class="
                                          svg-icon svg-icon-md svg-icon-danger
                                        "
                                            >
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
                                                  <rect
                                                    x="0"
                                                    y="0"
                                                    width="24"
                                                    height="24"
                                                  />
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
                                    </div>
                                    <div class="ml-1">
                                      <!-- <svg  
                                      width="30px"
                                      height="30px"
                                      viewBox="0 -6.5 38 38"
                                      version="1.1"
                                      xmlns="http://www.w3.org/2000/svg"
                                      xmlns:xlink="http://www.w3.org/1999/xlink"
                                      fill="#000000"
                                      transform="rotate(0)"
                                    >
                                      <g
                                        id="SVGRepo_bgCarrier"
                                        stroke-width="0"
                                      ></g>
                                      <g
                                        id="SVGRepo_tracerCarrier"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke="#CCCCCC"
                                        stroke-width="3.1159999999999997"
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
                                            transform="translate(-1511.000000, -158.000000)"
                                            fill="#f7623c"
                                            fill-rule="nonzero"
                                          >
                                            <g
                                              id="1"
                                              transform="translate(1350.000000, 120.000000)"
                                            >
                                              <path
                                                d="M187.812138,38.5802109 L198.325224,49.0042713 L198.41312,49.0858421 C198.764883,49.4346574 198.96954,49.8946897 199,50.4382227 L198.998248,50.6209428 C198.97273,51.0514917 198.80819,51.4628128 198.48394,51.8313977 L198.36126,51.9580208 L187.812138,62.4197891 C187.031988,63.1934036 185.770571,63.1934036 184.990421,62.4197891 C184.205605,61.6415481 184.205605,60.3762573 184.990358,59.5980789 L192.274264,52.3739093 L162.99947,52.3746291 C161.897068,52.3746291 161,51.4850764 161,50.3835318 C161,49.2819872 161.897068,48.3924345 162.999445,48.3924345 L192.039203,48.3917152 L184.990421,41.4019837 C184.205605,40.6237427 184.205605,39.3584519 184.990421,38.5802109 C185.770571,37.8065964 187.031988,37.8065964 187.812138,38.5802109 Z"
                                                id="right-arrow"
                                              ></path>
                                            </g>
                                          </g>
                                        </g>
                                      </g>
                                    </svg> -->
                                    </div>
                                  </div>
                                </draggable>
                              </div>
                            </div>
                            <div class="row" style="justify-content: end">
                              <div class="col-4">
                                <div class="form-group">
                                  <div
                                    class="input-group"
                                    style="justify-content: end;"
                                  >
                                    <a
                                      href="javascript:void(0)"
                                      @click="editRessource(ressource)"
                                      class="
                                        btn
                                        btn-icon
                                        btn-light
                                        btn-hover-primary

                                        btn-sm
                                        mx-3
                                      "
                                    >
                                      <span
                                        class="
                                          svg-icon svg-icon-md svg-icon-primary
                                        "
                                      >
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
                                            <rect
                                              x="0"
                                              y="0"
                                              width="24"
                                              height="24"
                                            />
                                            <path
                                              d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                              fill="#000000"
                                              fill-rule="nonzero"
                                              transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"
                                            />
                                            <path
                                              d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                              fill="#000000"
                                              fill-rule="nonzero"
                                              opacity="0.3"
                                            />
                                          </g>
                                        </svg>
                                      </span>
                                    </a>
                                    <a
                                      @click="deleteUnite(ressource)"
                                      href="javascript:void(0)"
                                      class="btn btn-icon btn-light btn-hover-danger btn-sm mx-3"
                                    >
                                      <span
                                        class="svg-icon  svg-icon-md svg-icon-danger"
                                      >
                                        <!-- begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg -->

                                        <svg
                                          xmlns="http://www.w3.org/2000/svg"
                                          width="16"
                                          height="16"
                                          fill="currentColor"
                                          class="bi bi-trash"
                                          viewBox="0 0 16 16"
                                        >
                                          <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                                          />
                                          <path
                                            fill-rule="evenodd"
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                                          />
                                        </svg>
                                        <!--end::Svg Icon-->
                                      </span>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </draggable>
                </div>
                <!--end::Accordion-->
              </div>
              <div
                class="tab-pane px-7"
                id="kt_user_edit_tab_0"
                role="tabpanel"
              >
                <!--begin::Row-->
                <div class="row">
                  <div class="col-xl-2"></div>
                  <div class="col-xl-7 my-2">
                    <!--begin::Group-->
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Titre</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <input
                          class="
                            form-control form-control-lg form-control-solid
                          "
                          type="text"
                          v-model="lecon.label"
                        />
                        <span
                          v-show="error.errorTitre"
                          class="alert alert-danger mt-2"
                          style="display: block;font-weight: 700;"
                          >{{ error.errorTitre }}</span
                        >
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Introduction</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <textarea
                          class="form-control form-control-solid"
                          :placeholder="'Introduction'"
                          v-model="lecon.introduction"
                          rows="3"
                        ></textarea>
                        <span
                          v-show="error.errorIntro"
                          class="alert alert-danger mt-2"
                          style="display: block;font-weight: 700;"
                          >{{ error.errorIntro }}</span
                        >
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Lecon Image</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <div
                          class="image-input image-input-empty image-input-outline"
                          id="kt_lecon_edit_avatar"
                          v-bind:style="{
                            'background-image': 'url(' + lecon.icone + ')',
                          }"
                        >
                          <div
                            class="image-input-wrapper"
                            style="width: 400px !important;height: 250px !important;"
                          ></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageLeconChange"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <!--end::Group-->
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Niveau</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <v-autocomplete
                          v-model="lecon.niveau_id"
                          :items="niveaux"
                          dense
                          filled
                        ></v-autocomplete>
                      </div>
                      <span
                        v-show="error.errorNiveau"
                        class="alert alert-danger mt-2"
                        style="display: block;font-weight: 700;"
                        >{{ error.errorNiveau }}</span
                      >
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Unité</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <v-autocomplete
                          v-model="lecon.unite_id"
                          :items="unites"
                          dense
                          filled
                        ></v-autocomplete>
                      </div>
                      <span
                        v-show="error.errorUnit"
                        class="alert alert-danger mt-2"
                        style="display: block;font-weight: 700;"
                        >{{ error.errorUnit }}</span
                      >
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Composante</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <v-autocomplete
                          v-model="lecon.rubrique_id"
                          :items="rubriques"
                          dense
                          filled
                        ></v-autocomplete>
                      </div>
                      <span
                        v-show="error.errorUnit"
                        class="alert alert-danger mt-2"
                        style="display: block;font-weight: 700;"
                        >{{ error.errorComposante }}</span
                      >
                    </div>
                    <div class="form-group row d-none">
                      <label
                        class="col-form-label col-3 text-lg-right text-left"
                        >Matière</label
                      >
                      <div class="col-9 pt-0 pb-0">
                        <v-autocomplete
                          v-model="lecon.matiere_id"
                          :items="matieres"
                          dense
                          filled
                        ></v-autocomplete>
                      </div>
                    </div>

                    <validation-errors
                      :errors="validationErrors"
                      v-if="validationErrors"
                    ></validation-errors>
                    <!--end::Group-->
                  </div>
                </div>
                <!--end::Row-->
                <div class="card-footer pb-0">
                  <div class="row">
                    <div class="col-xl-2"></div>
                    <div class="col-xl-7">
                      <div class="row">
                        <div class="col-3"></div>
                        <div class="col-9 pt-0 pb-0">
                          <button
                            type="button"
                            @click="saveLecon($event)"
                            class="
                              btn btn-nice btn-primary
                              spinner-darker-info spinner-right
                            "
                          >
                            Enregistrer
                          </button>
                          <a
                            href="#"
                            class="btn btn-nice btn-clean font-weight-bold"
                            >Annuler</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="tab-pane px-7"
                id="kt_user_edit_tab_1"
                role="tabpanel"
              >
                <div class="example-preview">
                  <ul class="nav nav-pills pl-0" id="myTab1" role="tablist">
                    <li class="nav-item">
                      <a
                        class="nav-link active"
                        id="syllabus-tab-1"
                        data-toggle="tab"
                        href="#syllabus-1"
                      >
                        <span class="nav-icon">
                          <i class="flaticon2-layers-1"></i>
                        </span>
                        <span class="nav-text">Syllabus</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        id="objectifs-tab-1"
                        data-toggle="tab"
                        href="#objectifs-1"
                        aria-controls="profile"
                      >
                        <span class="nav-icon">
                          <i class="flaticon2-layers-1"></i>
                        </span>
                        <span class="nav-text">Objectifs</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        id="prerequis-tab-1"
                        data-toggle="tab"
                        href="#prerequis-1"
                        aria-controls="profile"
                      >
                        <span class="nav-icon">
                          <i class="flaticon2-layers-1"></i>
                        </span>
                        <span class="nav-text">Pré-requis</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        id="instructions-tab-1"
                        data-toggle="tab"
                        href="#instructions-1"
                        aria-controls="profile"
                      >
                        <span class="nav-icon">
                          <i class="flaticon2-layers-1"></i>
                        </span>
                        <span class="nav-text">Instructions</span>
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content mt-5" id="myTabContent1">
                    <div
                      class="tab-pane fade show active"
                      id="syllabus-1"
                      role="tabpanel"
                      aria-labelledby="syllabus-tab-1"
                    >
                      <div class="form-group">
                        <label for="recipient-name" class="form-control-label"
                          >Syllabus :</label
                        >
                        <!-- Use the component in the right place of the template -->
                        <tiptap-vuetify
                          :extensions="extensions"
                          v-model="lecon.syllabus"
                        />
                        <span
                          v-show="leconErrs.syllabusErr"
                          class="mt-2"
                          style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                          >{{ leconErrs.syllabusErr }}</span
                        >
                      </div>
                    </div>
                    <div
                      class="tab-pane fade"
                      id="objectifs-1"
                      role="tabpanel"
                      aria-labelledby="objectifs-tab-1"
                    >
                      <div class="form-group">
                        <label for="recipient-name" class="form-control-label"
                          >Objectifs :</label
                        >
                        <!-- Use the component in the right place of the template -->
                        <tiptap-vuetify
                          :extensions="extensions"
                          v-model="lecon.objectifs"
                        />
                      </div>
                    </div>
                    <div
                      class="tab-pane fade"
                      id="prerequis-1"
                      role="tabpanel"
                      aria-labelledby="prerequis-tab-1"
                    >
                      <div class="form-group">
                        <label for="recipient-name" class="form-control-label"
                          >Pré-requis :</label
                        >
                        <!-- Use the component in the right place of the template -->
                        <tiptap-vuetify
                          :extensions="extensions"
                          v-model="lecon.prerequis"
                        />
                      </div>
                    </div>
                    <div
                      class="tab-pane fade"
                      id="instructions-1"
                      role="tabpanel"
                      aria-labelledby="instructions-tab-1"
                    >
                      <div class="form-group">
                        <label for="recipient-name" class="form-control-label"
                          >Instructions :</label
                        >
                        <!-- Use the component in the right place of the template -->
                        <tiptap-vuetify
                          :extensions="extensions"
                          v-model="lecon.instructions"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <!--end::Row-->
                <div class="card-footer pb-0">
                  <div class="row">
                    <div class="col-xl-2"></div>
                    <div class="col-xl-7">
                      <div class="row">
                        <div class="col-3"></div>
                        <div class="col-9 pt-0 pb-0">
                          <button
                            type="button"
                            @click="saveLecon($event)"
                            class="
                              btn btn-nice btn-primary
                              spinner-darker-info spinner-right
                            "
                          >
                            Enregistrer
                          </button>
                          <a
                            href="#"
                            class="btn btn-nice btn-clean font-weight-bold"
                            >Annuler</a
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--end::Tab-->
            </div>
          </div>
          <!--begin::Card body-->
        </div>
        <!--end::Card-->
      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->

    <div
      class="modal fade"
      id="modal_form_ressource"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="!ressource.id" class="modal-title" id="exampleModalLabel">
              Nouvelle étape
            </h5>
            <h5 v-if="ressource.id" class="modal-title" id="exampleModalLabel">
              Modifier l'étape
            </h5>
            <button
              @click="hideValidationsText"
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <div class="mb-3">
            <div class="modal-body">
              <div class="form-group">
                <label class="form-control-label">Type</label>
                <v-autocomplete
                  v-model="ressource.type_id"
                  :items="etape_types"
                  dense
                  filled
                ></v-autocomplete>
                <span
                  v-show="error.errorType"
                  class="mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ error.errorType }}</span
                >
              </div>
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label"
                  >Titre</label
                >
                <input
                  type="text"
                  class="form-control form-control-solid"
                  placeholder="Titre"
                  v-model="ressource.label"
                />
                <span
                  v-show="error.errorTitre"
                  class="mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ error.errorTitre }}</span
                >
              </div>
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label"
                  >Introduction</label
                >
                <textarea
                  class="form-control form-control-solid"
                  :placeholder="'Introduction'"
                  v-model="ressource.introduction"
                  rows="3"
                ></textarea>
                <span
                  v-show="error.errorIntro"
                  class="mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ error.errorIntro }}</span
                >
              </div>
              <validation-errors
                :errors="validationErrors"
                v-if="validationErrors"
              ></validation-errors>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm(), hideValidationsText()"
                class="btn btn-nice btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                type="button"
                @click="saveRessource($event)"
                class="
                  btn btn-nice btn-primary
                  spinner-darker-info spinner-right
                "
              >
                Enregistrer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modal_form_content"
      tabindex="-1"
      role="dialog"
      style="overflow-y: auto;"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg"
        v-bind:class="{ 'modal-xl': content.type_id == 2 }"
        role="document"
      >
        <div class="modal-content" style="width: 100vh;">
          <div class="modal-header">
            <h5 v-if="!content.id" class="modal-title" id="exampleModalLabel">
              Nouveau contenu
            </h5>
            <h5 v-if="content.id" class="modal-title" id="exampleModalLabel">
              Modifier le contenu
            </h5>
            <button
              @click="hideValidationsText"
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <form class="mb-3">
            <div class="modal-body">
              <div class="form-group mb-4" v-if="content.type_id == 1">
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
                  <div class="title-type">
                    <input
                      type="text"
                      style="font-size: 30px;color: #171656;text-align: center;font-weight: 600;"
                      class="form-control form-control-solid col-10"
                      placeholder="Titre"
                      v-model="content.content"
                    />
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 2">
                <label for="recipient-name" class="form-control-label"
                  >HTML</label
                >

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
                  <div class="html-type mt-3 col-12 ml-auto mr-auto">
                    <!-- <ckeditor
                      :editor="editor"
                      :config="editorConfig"
                      v-model="content.content"
                    ></ckeditor> -->
                    <vue-editor v-model="content.content"></vue-editor>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 7">
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
                  <div class="slides-type">
                    <v-carousel
                      cycle
                      height="100%"
                      :interval="1000000"
                      hide-delimiters
                      data-bs-interval="false"
                      data-interval="false"
                    >
                      <v-carousel-item
                        v-for="(item, index) in content.content"
                        :key="index"
                      >
                        <v-sheet
                          height="100%"
                          style="background:#fff;color:#000"
                        >
                          <div
                            style="margin: 0 auto;max-width: 80%;text-align: left;margin-top:16p;margin-bottom:16px"
                          >
                            <label
                              for="recipient-name"
                              class="form-control-label"
                              :key="index"
                            >
                              <b> Slide {{ index + 1 }} </b>
                            </label>

                            <!-- <ckeditor
                              :key="index"
                              :editor="editor"
                              :config="editorConfig"
                              v-model="item.content"
                            ></ckeditor> -->
                            <vue-editor v-model="item.content"></vue-editor>
                          </div>

                          <div
                            class="col-12 slide"
                            style="justify-content: end;"
                          >
                            <a
                              href="javascript:void(0)"
                              @click="content.content.splice(index, 1)"
                              class="btn btn-icon btn-light btn-hover-danger btn-sm m-1"
                              style="font-weight: bold; font-size: 18px"
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-danger"
                              >
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
                        </v-sheet>
                      </v-carousel-item>
                    </v-carousel>
                  </div>
                </div>

                <button
                  type="button"
                  @click="content.content.push({ content: '' })"
                  class="
                    mt-2
                    btn btn-nice btn-primary
                    spinner-darker-info spinner-right
                  "
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="26"
                    height="26"
                    fill="currentColor"
                    class="bi bi-plus"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                    />
                  </svg>
                </button>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 6">
                <br />

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
                  <div class="text-type">
                    <div class="img" style="height: 100%;">
                      <div class="form-group mt-2 text-center">
                        <label class="d-block text-center">Text Image</label>
                        <div
                          class="image-input image-input-empty image-input-outline"
                          id="kt_user_edit_avatar"
                          style="background-position: center;
                          background-size: contain;"
                          v-bind:style="{
                            'background-image':
                              'url(' +
                              url_base +
                              '/assets/schools/' +
                              url_base.split('/')[4] +
                              '/lms/lecons_files/' +
                              content.content.images[activeTextImage] +
                              ')',
                          }"
                        >
                          <div class="image-input-wrapper"></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageTextChangeSave"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                        <template>
                          <v-sheet
                            class="mx-auto"
                            elevation="8"
                            max-width="800"
                            style="background: white;width: 25vh;overflow: hidden;overflow-x: auto;"
                          >
                            <v-slide-group
                              v-model="model"
                              class="pa-4"
                              active-class="success"
                              style="width: 30vh;display: block;overflow-x: auto;"
                              show-arrows
                            >
                              <div
                                style="overflow-x: hidden;overflow-y: auto;height: 30vh;width: 21vh;"
                              >
                                <v-slide-item
                                  v-for="(img, index) in content.content.images"
                                  :key="index"
                                >
                                  <v-card
                                    :color="
                                      activeTextImage == index
                                        ? undefined
                                        : 'grey lighten-1'
                                    "
                                    class="ma-5"
                                    height="100"
                                    width="100"
                                    style="background-size: cover;background-position: center;position: relative;"
                                    v-bind:style="[
                                      {
                                        'background-image':
                                          'url(' +
                                          url_base_ +
                                          '/assets/schools/' +
                                          url_base_.split('/')[4] +
                                          '/lms/lecons_files/' +
                                          img +
                                          ')',
                                      },
                                      activeTextImage != index
                                        ? { opacity: '0.5' }
                                        : '',
                                    ]"
                                    @click="toggleTextImage(index)"
                                  >
                                    <a
                                      href="javascript:void(0)"
                                      @click="removeTextImage(index)"
                                      class="btn btn-icon btn-light btn-hover-danger btn-sm mr-1"
                                      style="font-weight: bold;position: absolute;top: 0;right: -40%;"
                                    >
                                      <span
                                        class="svg-icon svg-icon-md svg-icon-danger"
                                      >
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
                                            <rect
                                              x="0"
                                              y="0"
                                              width="24"
                                              height="24"
                                            />
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
                                      </span>
                                    </a>
                                  </v-card>
                                </v-slide-item>
                              </div>
                            </v-slide-group>
                          </v-sheet>

                          <button
                            v-show="false"
                            type="button"
                            @click="newImageText()"
                            class="mt-2 btn btn-nice btn-primary spinner-darker-info spinner-right"
                            style="width: fit-content;margin-left: auto;display: block;"
                          >
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="26"
                              height="26"
                              fill="currentColor"
                              class="bi bi-plus"
                              viewBox="0 0 16 16"
                            >
                              <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                              />
                            </svg>
                          </button>
                        </template>
                      </div>
                    </div>
                    <textarea
                      class="form-control form-control-solid"
                      id="textarea"
                      style="color: black;width: 60%;border: none;resize: none;"
                      v-model="content.content.text"
                    ></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 3">
                <div>
                  <label for="recipient-name" class="form-control-label"
                    >Document PDF</label
                  >
                  <v-file-input
                    small-chips
                    v-model="content.file"
                    @change="setFileName()"
                    outlined
                    dense
                    accept="application/pdf"
                    truncate-length="13"
                  ></v-file-input>
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >Titre du document PDF</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Titre de pdf"
                    v-model="content.content"
                  />
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 4">
                <div>
                  <label for="recipient-name" class="form-control-label"
                    >Lien Youtube de la vidéo</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Lien Youtube de la vidéo"
                    v-model="content.link"
                    @keyup="getVedeoData()"
                  />
                  <!-- @change="" -->

                  <span
                    v-show="contentErrs.contentYoutubeLinkErr"
                    class="mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ contentErrs.contentYoutubeLinkErr }}</span
                  >
                  <span
                    v-show="contentErrs.contentLinkYoutubeRequest"
                    class="mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ contentErrs.contentLinkYoutubeRequest }}</span
                  >
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >Vidéo Youtube</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Titre de la vidéo"
                    v-model="content.content"
                  />
                </div>
                <div class="con-fx-ty" style="overflow: hidden;">
                  <div
                    class="etape-title"
                    v-for="etape in ressources"
                    :key="etape.id"
                    v-if="etape.id == content.ressource_id"
                    :style="{ 'background-color': etape.etape_color }"
                  >
                    {{ etape.type_label }}
                  </div>
                  <div class="youtube-type" v-if="content.link">
                    <iframe
                      width="100%"
                      height="100%"
                      :src="content.link.replace('watch?v=', 'embed/')"
                      :title="'kdjkdkj'"
                      frameborder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen
                    ></iframe>
                  </div>
                </div>
                <!-- <div
                  style="width:300px; padding: 10px;box-shadow: 0px 0px 22px -4px rgb(66 66 66 / 43%);   background: white;text-align: center;overflow: hidden;    margin: 10px auto;border-radius: 5px;"
                  v-show="content.img"
                >
                  <img width="100%" :src="content.img" alt="no img" />
                </div> -->
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 5">
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >Embed</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Titre de la vidéo"
                    v-model="content.content"
                  />
                </div>
                <div>
                  <label for="recipient-name" class="form-control-label"
                    >Js Script</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Js Script"
                    v-model="content.link"
                  />
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 8">
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >vrai ou faux</label
                  >
                </div>
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
                  <div class="quiz-type">
                    <div class="question">
                      <input
                        type="text"
                        style="font-size: 2rem;color: #171656;text-align: center;font-weight: 500;margin: auto;"
                        class="form-control form-control-solid col-10"
                        placeholder="Question ?"
                        v-model="content.content"
                      />
                    </div>
                    <div class="checkbox-answers">
                      <div
                        @click="changeAnswerState(1)"
                        class="bx"
                        id="answer-true"
                        :style="[
                          content.answer || content.answer == true
                            ? { background: '#4aad1b', color: 'white' }
                            : {},
                        ]"
                      >
                        <button @click.prevent="">
                          Vrai
                        </button>
                      </div>
                      <div
                        @click="changeAnswerState(0)"
                        class="bx"
                        id="answer-false"
                        :style="[
                          !content.answer || content.answer == false
                            ? { background: '#4aad1b', color: 'white' }
                            : {},
                        ]"
                      >
                        <button @click.prevent="">
                          Faux
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 9">
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >open question</label
                  >
                </div>

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
                  <div class="open-question-type">
                    <div class="form-group">
                      <input
                        type="text"
                        style="font-size: 30px;color: #171656;text-align: center;font-weight: 600;"
                        class="form-control form-control-solid col-10 m-auto"
                        placeholder="Question ?"
                        v-model="content.content"
                      />
                    </div>
                    <div class="form-group">
                      <textarea
                        v-model="content.answer"
                        class="form-control form-control-solid"
                        id="responseRessource"
                        rows="8"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 10">
                <div class="form-group">
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
                    <div class="question-image-type">
                      <input
                        type="text"
                        style="font-size: 30px;color: #171656;text-align: center;font-weight: 600;"
                        class="form-control form-control-solid col-10 m-auto mb-2 mt-2"
                        placeholder="question ?"
                        v-model="content.content"
                      />

                      <div class="form-group mt-2 text-center ">
                        <label class="d-block text-center"
                          >Question Image</label
                        >
                        <div
                          class="image-input image-input-empty image-input-outline "
                          id="kt_user_edit_avatar"
                          v-bind:style="{
                            'background-image': 'url(' + content.file + ')',
                          }"
                        >
                          <div class="image-input-wrapper"></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageChange"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <button
                  type="button"
                  @click="newImage()"
                  class="
                mt-2
                btn btn-nice btn-primary
                spinner-darker-info spinner-right
              "
                >
                  New Image
                </button>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 11">
                <input
                  type="text"
                  class="form-control form-control-solid"
                  placeholder="question ?"
                  v-model="content.answer"
                />
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
                  <div class="structure-type">
                    <template v-for="(item, index) in content.content">
                      <div
                        class="col-4 d-flex"
                        style="flex-direction: column-reverse;align-items: end;"
                        :key="index"
                      >
                        <a
                          href="javascript:void(0)"
                          @click="removeStructure(index, 1)"
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
                        <div class="d-flex">
                          <input
                            :key="index"
                            type="text"
                            class="form-control form-control-solid mb-2 mr-1"
                            placeholder="début"
                            v-model="item.content"
                          />
                          <input
                            :key="index"
                            type="text"
                            class="form-control form-control-solid mr-1"
                            placeholder="complét"
                            v-model="item.answer"
                          />
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <button
                  type="button"
                  @click="newStructure()"
                  class="
                    mt-2
                    btn btn-nice btn-primary
                    spinner-darker-info spinner-right
                  "
                >
                  New structure
                </button>
                <!-- </div> -->
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 12">
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
                  <div class="slides-type">
                    <v-carousel
                      @change="onSlideChange"
                      :interval="1000000"
                      v-model="currentSlideIndex"
                      cycle
                      height="100%"
                      hide-delimiters
                      data-bs-interval="false"
                      data-interval="false"
                    >
                      <!-- :interval="interval" -->
                      <v-carousel-item
                        v-for="(item, index) in content.content"
                        :key="index"
                      >
                        <v-sheet
                          height="100%"
                          style="background:#fff;color:#000"
                        >
                          <div
                            style="margin: 0 auto;max-width: 80%;text-align: left;margin-top:16p;margin-bottom:16px"
                          >
                            <label
                              for="recipient-name"
                              class="form-control-label"
                              :key="index"
                            >
                            </label>
                            <div class="glossary-type mt-2">
                              <input
                                type="text"
                                style="font-size: 30px;color: #171656;text-align: center;font-weight: 600; margin: auto;"
                                class="form-control form-control-solid col-10"
                                placeholder="Name"
                                v-model="
                                  content.content[currentSlideIndex]
                                    .glossaryName
                                "
                              />
                              <input
                                type="text"
                                style="font-size: 30px;color: #171656;text-align: center;font-weight: 600; margin: auto;"
                                class="form-control form-control-solid mb-2 col-10 mt-2"
                                placeholder="definition"
                                v-model="
                                  content.content[currentSlideIndex]
                                    .glossaryDescription
                                "
                              />
                              <div class="form-group mt-2 text-center ">
                                <label class="d-block text-center"
                                  >Gloassary Image</label
                                >
                                <!-- id="kt_user_edit_avatar" -->
                                <div
                                  class="image-input image-input-empty image-input-outline "
                                  :id="'kt_glossary' + glossaryItem + '_avatar'"
                                  v-bind:style="{
                                    'background-image':
                                      'url(' +
                                      url_base +
                                      '/assets/schools/' +
                                      url_base.split('/')[4] +
                                      '/lms/lecons_files/' +
                                      glossaryItem.image +
                                      ')',
                                  }"
                                >
                                  <div class="image-input-wrapper"></div>
                                  <label
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change"
                                    data-toggle="tooltip"
                                    title
                                    data-original-title="Change avatar"
                                  >
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input
                                      type="file"
                                      name="image"
                                      @change="
                                        onImageGlossaryChange($event, index)
                                      "
                                      accept=".png, .jpg, .jpeg, .svg"
                                    />
                                    <input
                                      type="hidden"
                                      name="profile_avatar_remove"
                                    />
                                  </label>
                                  <span
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel"
                                    data-toggle="tooltip"
                                    title="Cancel avatar"
                                  >
                                    <i
                                      class="ki ki-bold-close icon-xs text-muted"
                                    ></i>
                                  </span>
                                  <span
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="remove"
                                    data-toggle="tooltip"
                                    title="Remove avatar"
                                  >
                                    <i
                                      class="ki ki-bold-close icon-xs text-muted"
                                    ></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <template>
                              <v-sheet
                                class="mx-auto"
                                elevation="8"
                                max-width="800"
                                style="background: white;"
                              >
                                <v-slide-group
                                  v-model="model"
                                  class="pa-4"
                                  active-class="success"
                                  show-arrows
                                >
                                  <!-- v-slot="{ active, toggle }" -->
                                  <v-slide-item
                                    v-for="(gloss, y) in item.glossary"
                                    :key="y"
                                  >
                                    <v-card
                                      :color="
                                        activeGlossary == y
                                          ? undefined
                                          : 'grey lighten-1'
                                      "
                                      class="ma-5"
                                      height="100"
                                      width="100"
                                      style="background-size: cover;background-position: center;position: relative;"
                                      v-bind:style="[
                                        {
                                          'background-image':
                                            'url(' +
                                            url_base_ +
                                            '/assets/schools/' +
                                            url_base_.split('/')[4] +
                                            '/lms/lecons_files/' +
                                            gloss.image +
                                            ')',
                                        },
                                        activeGlossary != y
                                          ? { opacity: '0.5' }
                                          : '',
                                      ]"
                                      @click="toggleGlossary(index, y, gloss)"
                                    >
                                      <v-row
                                        class="fill-height"
                                        align="center"
                                        justify="center"
                                      >
                                        <!-- <div class="d-flex flex-column">
                                        <span>{{ item.content }}</span>
                                        <span>{{ item.answer }}</span>
                                        </div> -->
                                      </v-row>
                                      <a
                                        href="javascript:void(0)"
                                        @click="removeGlossary(index, y)"
                                        class="btn btn-icon btn-light btn-hover-danger btn-sm mr-1"
                                        style="font-weight: bold;position: absolute;top: 0;right: -40%;"
                                      >
                                        <span
                                          class="svg-icon svg-icon-md svg-icon-danger"
                                        >
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
                                              <rect
                                                x="0"
                                                y="0"
                                                width="24"
                                                height="24"
                                              />
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
                                        </span>
                                      </a>
                                    </v-card>
                                  </v-slide-item>
                                </v-slide-group>
                              </v-sheet>
                              <button
                                type="button"
                                @click="newGLossaryItem(index)"
                                class="mt-2 btn btn-nice btn-primary spinner-darker-info spinner-right"
                                style="width: fit-content;margin-left: auto;display: block;"
                              >
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="26"
                                  height="26"
                                  fill="currentColor"
                                  class="bi bi-plus"
                                  viewBox="0 0 16 16"
                                >
                                  <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                                  />
                                </svg>
                              </button>
                            </template>
                          </div>
                          <div
                            class="col-12 slide"
                            style="justify-content: end;"
                          >
                            <a
                              href="javascript:void(0)"
                              @click="removeSlide(index)"
                              class="btn btn-icon btn-light btn-hover-danger btn-sm m-1"
                              style="font-weight: bold; font-size: 18px"
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-danger"
                              >
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
                        </v-sheet>
                      </v-carousel-item>
                    </v-carousel>
                  </div>
                </div>

                <a
                  type="button"
                  href="javascript:void(0)"
                  @click="newGLossary()"
                  class="btn btn-nice btn-primary spinner-darker-info spinner-right mt-3"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="26"
                    height="26"
                    fill="currentColor"
                    class="bi bi-plus"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
                    />
                  </svg>
                </a>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 13">
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >Vidéo</label
                  >
                  <v-file-input
                    small-chips
                    v-model="content.file"
                    @change="setFileName()"
                    outlined
                    dense
                    accept="video/mp4,video/x-m4v,video/*"
                    truncate-length="13"
                  ></v-file-input>
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="form-control-label"
                    >Titre du vidéo</label
                  >
                  <input
                    type="text"
                    class="form-control form-control-solid"
                    placeholder="Titre de pdf"
                    v-model="content.content"
                  />
                </div>
                <div class="con-fx-ty" style="height: 500px;">
                  <div
                    class="etape-title"
                    v-for="etape in ressources"
                    :key="etape.id"
                    v-if="etape.id == content.ressource_id"
                    :style="{ 'background-color': etape.etape_color }"
                  >
                    {{ etape.type_label }}
                  </div>

                  <div v-if="content.id" style="margin-top: 50px;">
                    <video width="100%" height="240" controls>
                      <source id="source" :src="content.file" />
                    </video>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 14">
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
                  <input
                    type="text"
                    style="font-size: 25px;color: #171656;text-align: center;font-weight: 600;"
                    class="form-control mt-1 mb-1 mr-auto ml-auto form-control-solid col-10"
                    placeholder="Guide"
                    v-model="content.answer"
                  />
                  <div
                    class="row m-0 "
                    style="width: 100%;height: 100%;align-items: center;"
                  >
                    <div class="col">
                      <div
                        class="row"
                        style="flex-direction: column;margin-bottom: 80px;"
                      >
                        <div class="col">
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[0].content"
                          />
                        </div>
                        <div class="col">
                          <textarea
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Answer"
                            v-model="content.content[0].answer"
                          ></textarea>
                        </div>
                      </div>
                      <div
                        class="row"
                        style="flex-direction: column;margin-bottom: 80px;"
                      >
                        <div class="col">
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[1].content"
                          />
                        </div>
                        <div class="col">
                          <textarea
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Answer"
                            v-model="content.content[1].answer"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                    <div style="width: 300px;height: 300px;">
                      <div class="cube">
                        <div
                          :style="{
                            'background-color': content.content[4].color,
                          }"
                          style="justify-content: center;"
                        >
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[4].content"
                          />
                        </div>
                        <div
                          :style="{
                            'background-color': content.content[5].color,
                          }"
                          style="justify-content: center;"
                        >
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[5].content"
                          />
                        </div>
                        <div
                          :style="{
                            'background-color': content.content[6].color,
                          }"
                          style="justify-content: center;"
                        >
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[6].content"
                          />
                        </div>
                        <div
                          :style="{
                            'background-color': content.content[7].color,
                          }"
                          style="justify-content: center;"
                        >
                          <input
                            type="text"
                            style="font-size: 15px;;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[7].content"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div
                        class="row"
                        style="flex-direction: column;margin-bottom: 80px;"
                      >
                        <div class="col">
                          <input
                            type="text"
                            style="font-size: 15px;=;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[2].content"
                          />
                        </div>
                        <div class="col">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid col-10"
                            placeholder="Answer"
                            v-model="content.content[2].answer"
                          ></textarea>
                        </div>
                      </div>
                      <div
                        class="row"
                        style="flex-direction: column;margin-bottom: 80px;"
                      >
                        <div class="col">
                          <input
                            type="text"
                            style="font-size: 15px;=;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid"
                            placeholder="Question"
                            v-model="content.content[3].content"
                          />
                        </div>
                        <div class="col">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;"
                            class="form-control form-control-solid"
                            placeholder="Answer"
                            v-model="content.content[3].answer"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    class="color-pickers-con"
                    style="display: flex;justify-content: center;margin-bottom: 5px;"
                  >
                    <input
                      type="color"
                      class="color-in"
                      placeholder="color"
                      v-model="content.content[4].color"
                    />
                    <input
                      type="color"
                      class="color-in"
                      placeholder="color"
                      v-model="content.content[5].color"
                    />
                    <input
                      type="color"
                      class="color-in"
                      placeholder="color"
                      v-model="content.content[6].color"
                    />
                    <input
                      type="color"
                      class="color-in"
                      placeholder="color"
                      v-model="content.content[7].color"
                    />
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 16">
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
                  <input
                    type="text"
                    style="font-size: 25px;color: #171656;text-align: center;font-weight: 600;"
                    class="form-control mt-1 mb-1 mr-auto ml-auto form-control-solid col-10"
                    placeholder="Guide"
                    v-model="content.answer"
                  />
                  <div
                    class="row m-0 "
                    style="width: 100%;height: 100%;align-items: center;padding: 0px 10px;"
                  >
                    <div class="col">
                      <div
                        class="row col-blob"
                        style="background-color: #673ab7;"
                      >
                        <div class="">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;    width: 100%;"
                            class="form-control form-control-solid"
                            placeholder="Answer"
                            v-model="content.content[0].content"
                          ></textarea>
                        </div>
                      </div>
                      <div
                        class="row col-blob"
                        style="background-color: #009688;"
                      >
                        <div class="">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;    width: 100%;"
                            class="form-control form-control-solid"
                            placeholder="Answer"
                            v-model="content.content[1].content"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                    <div
                      style="display: flex;justify-content: center;align-items: center; width: 300px;height: 300px;background: #f44336;border-radius: 50%;"
                    >
                      <div class="blob">
                        <div>
                          <input
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;width: 80%;display: block;margin: auto;"
                            class="form-control form-control-solid col-10"
                            placeholder="Question"
                            v-model="content.content[4].content"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div
                        class="row col-blob"
                        style="background-color: #ff9800;"
                      >
                        <div class="">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;    width: 100%;"
                            class="form-control form-control-solid"
                            placeholder="Answer"
                            v-model="content.content[2].content"
                          ></textarea>
                        </div>
                      </div>
                      <div
                        class="row col-blob"
                        style="background-color: #00BCD4;"
                      >
                        <div class="">
                          <textarea
                            type="text"
                            style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;    width: 100%;"
                            class="form-control form-control-solid"
                            placeholder="Answer"
                            v-model="content.content[3].content"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 17">
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
                  <input
                    type="text"
                    style="font-size: 25px;color: #171656;text-align: center;font-weight: 600;"
                    class="form-control mt-1 mb-1 mr-auto ml-auto form-control-solid col-10"
                    placeholder="Guide"
                    v-model="content.answer"
                  />
                  <div
                    class="structure-type mt-5"
                    style="justify-content: space-around;"
                  >
                    <div class="link-col ll-col">
                      <template
                        v-for="(item, index) in content.content.contents"
                      >
                        <div
                          class=" d-flex"
                          style="flex-direction: column-reverse;align-items: end;"
                          :key="index + 1"
                        >
                          <div class="d-flex">
                            <a
                              href="javascript:void(0)"
                              @click="removeLink(index, 1)"
                              class="btn btn-icon btn-light btn-hover-danger btn-sm mr-1 mb-1"
                              style="font-weight: bold; font-size: 18px"
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-danger"
                              >
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
                            <input
                              :key="index + 1"
                              type="text"
                              class="form-control form-control-solid mb-2 mr-1"
                              :placeholder="'Link ' + (index + 1)"
                              v-model="item.content"
                            />
                            <!-- class="link-me-arrow" -->
                            <div
                              :id="'link-' + item.content"
                              class="btn btn-icon btn-light btn-hover-success btn-sm mr-1 mb-1"
                              style="width: fit-content;padding: 2px;color: black;"
                              @click.prevent="
                                linkMeFromContent(item.content, index)
                              "
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-success"
                                style="width: 55px;"
                                :style="{ 'background-color': item.color }"
                              >
                                Answer
                              </span>
                            </div>
                          </div>
                        </div>
                      </template>
                    </div>
                    <div id="mainLines"></div>
                    <div class="link-answer ll-col">
                      <template
                        v-for="(item, index) in content.content.answers"
                      >
                        <div
                          class="d-flex"
                          style="flex-direction: column-reverse;align-items: end;"
                          :key="index + 1"
                        >
                          <div class="d-flex">
                            <div
                              :id="'answer-' + item.answer"
                              class="btn btn-icon btn-light btn-hover-success btn-sm mr-1 mb-1"
                              style="width: fit-content;padding: 2px;color: black;"
                              @click.prevent="
                                linkMeFromAnswer(item.answer, index)
                              "
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-success"
                                style="width: 55px;"
                                :style="{ 'background-color': item.color }"
                              >
                                Word
                              </span>
                            </div>
                            <input
                              :key="index + 1"
                              type="text"
                              class="form-control form-control-solid mb-2 mr-1"
                              :placeholder="'Answer ' + (index + 1)"
                              v-model="item.answer"
                            />
                            <a
                              href="javascript:void(0)"
                              @click="removeAnswer(index, 1)"
                              class="btn btn-icon btn-light btn-hover-danger btn-sm mr-1 mb-1"
                              style="font-weight: bold; font-size: 18px"
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-danger"
                              >
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
                </div>
                <button
                  type="button"
                  @click="newLink()"
                  class="mt-2 btn btn-nice btn-primary spinner-darker-info spinner-right"
                >
                  New link
                </button>
                <button
                  type="button"
                  @click="newAnswer()"
                  class="mt-2 btn btn-nice btn-primary spinner-darker-info spinner-right"
                >
                  New answer
                </button>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 18">
                <br />

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
                  <div class="text-type" style="justify-content: space-around;">
                    <div class="img" style="height: 100%;">
                      <div class="form-group mt-2 text-center">
                        <label class="d-block text-center">Text Image</label>
                        <div
                          class="image-input image-input-empty image-input-outline"
                          id="kt_user_edit_avatar"
                          style="background-position: center;
                          background-size: contain;"
                          v-bind:style="{
                            'background-image': 'url(' + content.file + ')',
                          }"
                        >
                          <div class="image-input-wrapper"></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageTextChange"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div style="display: flex;">
                      <input
                        type="text"
                        style="font-size: 15px;color: #171656;text-align: center;font-weight: 600;width: 80%;display: block;margin: auto;"
                        class="form-control form-control-solid col-10"
                        placeholder="Word"
                        v-model="content.content"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 19">
                <br />

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
                  <div class="text-type" style="justify-content: space-around;">
                    <div class="img col-5" style="height: 100%;">
                      <div class="form-group mt-2 text-center">
                        <label class="d-block text-center">Text Image</label>
                        <div
                          class="image-input image-input-empty image-input-outline"
                          id="kt_user_edit_avatar"
                          style="background-position: center;
                          background-size: contain;"
                          v-bind:style="{
                            'background-image': 'url(' + content.file + ')',
                          }"
                        >
                          <div class="image-input-wrapper"></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageTextChange"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="html-type mt-3 col-6">
                      <!-- <ckeditor
                        :editor="editor"
                        :config="editorConfig"
                        v-model="content.content"
                      ></ckeditor> -->
                      <vue-editor v-model="content.content"></vue-editor>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 20">
                <br />
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
                  <div class="text-type" style="justify-content: space-around;">
                    <div class="img col-5" style="height: 100%;">
                      <div class="form-group mt-2 text-center">
                        <label class="d-block text-center">Text Image</label>
                        <div
                          class="image-input image-input-empty image-input-outline"
                          id="kt_user_edit_avatar"
                          style="background-position: center;
                          background-size: contain;"
                          v-bind:style="{
                            'background-image': 'url(' + content.file + ')',
                          }"
                        >
                          <div class="image-input-wrapper"></div>
                          <label
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="change"
                            data-toggle="tooltip"
                            title
                            data-original-title="Change avatar"
                          >
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input
                              type="file"
                              name="image"
                              @change="onImageTextChange"
                              accept=".png, .jpg, .jpeg, .svg"
                            />
                            <input type="hidden" name="profile_avatar_remove" />
                          </label>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="cancel"
                            data-toggle="tooltip"
                            title="Cancel avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                          <span
                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                            data-action="remove"
                            data-toggle="tooltip"
                            title="Remove avatar"
                          >
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="html-type mt-3 col-6">
                      <div class="form-group">
                        <div class="form-group">
                          <input
                            type="text"
                            class="form-control form-control-solid"
                            placeholder="Audio"
                            v-model="content.content"
                          />
                        </div>
                        <label for="recipient-name" class="form-control-label"
                          >Audio</label
                        >
                        <v-file-input
                          small-chips
                          v-model="content.audio"
                          outlined
                          dense
                          accept=""
                          truncate-length="13"
                        ></v-file-input>
                      </div>
                      <div v-if="content.id" style="margin-top: 50px;">
                        <audio controls>
                          <source
                            id="source"
                            :src="
                              this.url_base +
                                '/assets/schools/' +
                                url_base.split('/')[4] +
                                '/lms/lecons_files/' +
                                content.audio
                            "
                          />
                        </audio>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 21">
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
                  <div class="table-ress">
                    <div class="form-group mb-0 d-flex align-items-center">
                      <label class="mr-2">Rows : </label>
                      <v-autocomplete
                        :items="Array.from({ length: 10 }, (x, i) => i + 1)"
                        v-model="tableRows"
                        dense
                        filled
                      ></v-autocomplete>
                    </div>
                    <div class="form-group mb-0 d-flex align-items-center">
                      <label class="mr-2">Cells : </label>
                      <v-autocomplete
                        :items="Array.from({ length: 10 }, (x, i) => i + 1)"
                        v-model="tableCells"
                        dense
                        filled
                      ></v-autocomplete>
                    </div>
                    <button
                      @click.prevent="buildTable"
                      class="btn btn-nice btn-primary btn-sm"
                    >
                      Générer Table
                    </button>
                  </div>
                  <input
                    type="text"
                    style="font-size: 25px;color: #171656;text-align: center;font-weight: 600;"
                    class="form-control mt-1 mb-1 mr-auto ml-auto form-control-solid col-10"
                    placeholder="Guide"
                    v-model="content.answer"
                  />
                  <table
                    class="table rwd-table mt-4"
                    style="width: 80% !important;"
                    v-if="tableIsReady || content.content.rows.length > 0"
                  >
                    <tr v-for="(item, i) in content.content.rows" :key="i">
                      <th
                        v-for="(col, y) in content.content.rows[i].row"
                        :key="y"
                        style="width: 150px;border: 1px solid #ebedf3;vertical-align: middle;"
                      >
                        <div
                          class="d-flex"
                          style="min-width: 200px;width: fit-content;"
                        >
                          <input
                            :key="y"
                            type="text"
                            class="form-control form-control-solid mb-2 mr-1"
                            :placeholder="'Word'"
                            v-model="col.col"
                          />
                        </div>
                      </th>
                      <th
                        style="width: 150px;border: 1px solid #ebedf3;vertical-align: middle;"
                      ></th>
                    </tr>
                  </table>

                  <br /><br />
                </div>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 22">
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
                            class="form-control form-control-solid  col-9 mr-1"
                            placeholder="complét"
                            v-model="item.answer"
                          />
                          <a
                            href="javascript:void(0)"
                            @click="removeStructure(index, 1)"
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
                  @click="newStructure()"
                  class="
                    mt-2
                    btn btn-nice btn-primary
                    spinner-darker-info spinner-right
                  "
                >
                  New structure
                </button>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 24">
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
                            @click="removeStructure(index, 1)"
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
                  @click="newStructureArrow()"
                  class="mt-2 btn btn-nice btn-primary spinner-darker-info spinner-right"
                >
                  New structure
                </button>
              </div>
              <div class="form-group mb-4" v-if="content.type_id == 26">
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
                  <div
                    class="word-complete"
                    style="flex-direction: row;flex-direction: row;display: flex;overflow-x: auto;"
                  >
                    <template v-for="(item, index) in content.content">
                      <div :key="index" style="padding: 0 5px;">
                        <div class="d-flex">
                          <div class="schemas-color">
                            <input
                              :key="index"
                              type="text"
                              class="form-control form-control-solid mb-2"
                              placeholder="title"
                              v-model="item.content"
                            />
                            <textarea
                              style="height: 80%;"
                              :key="index"
                              type="text"
                              class="form-control form-control-solid"
                              placeholder="block"
                              v-model="item.firstanswer"
                            ></textarea>
                          </div>
                          <a
                            href="javascript:void(0)"
                            @click="removeSchemas(index, 1)"
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
                  @click="newSchemas()"
                  class="
                    mt-2
                    btn btn-nice btn-primary
                    spinner-darker-info spinner-right
                  "
                >
                  New schémas
                </button>
                <!-- </div> -->
              </div>
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label">
                  Durée
                  <small>( en minutes )</small>
                </label>
                <v-autocomplete
                  :items="Array.from({ length: 60 }, (x, i) => i + 1)"
                  dense
                  value="1"
                  v-model="content.duree"
                  solo
                ></v-autocomplete>
              </div>
              <validation-errors
                :errors="validationErrors"
                v-if="validationErrors"
              ></validation-errors>
            </div>

            <div class="modal-footer">
              <button
                @click="hideValidationsText, clearContent()"
                type="button"
                class="btn btn-nice btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                type="button"
                @click="saveContent($event)"
                class="
                  btn btn-nice btn-primary
                  spinner-darker-info spinner-right
                "
              >
                Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="modal_content_types"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg"
        v-bind:class="{ 'modal-xl': content.type_id == 2 }"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Content Types
            </h5>

            <button
              @click="hideValidationsText"
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <form class="mb-3">
            <div class="modal-body">
              <div class="row">
                <div
                  class="col-4"
                  style="cursor: pointer;height: 100%;"
                  v-for="res in ressource_types"
                  @click="addContentType(res.id, content.ressource_id)"
                  v-bind:key="res.id"
                >
                  <div
                    class="card card-hov"
                    style="width: 100%;align-items: center;padding: 10px;"
                  >
                    <div class="card-body">
                      <img
                        style="width: 100%;height: 50%;object-fit: cover;"
                        class="card-img-top"
                        :src="res.image_url"
                        alt="Card image cap"
                      />
                      <h5
                        class="card-title mb-0 mt-2"
                        style="text-align: center;"
                      >
                        {{ res.text }}
                      </h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button
                @click="hideValidationsText, clearContent()"
                type="button"
                class="btn btn-nice btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <!-- <button
                type="button"
                @click="saveContent($event)"
                class="
                  btn btn-nice btn-primary
                  spinner-darker-info spinner-right
                "
              >
                Creer ce type
              </button> -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
class UploadAdapter {
  constructor(loader) {
    // The file loader instance to use during the upload.
    this.loader = loader;
  }

  // Starts the upload process.
  upload() {
    return this.loader.file.then(
      (file) =>
        new Promise((resolve, reject) => {
          this._initRequest();
          this._initListeners(resolve, reject, file);
          this._sendRequest(file);
        })
    );
  }

  // Aborts the upload process.
  abort() {
    if (this.xhr) {
      this.xhr.abort();
    }
  }

  // Initializes the XMLHttpRequest object using the URL passed to the constructor.
  _initRequest() {
    const xhr = (this.xhr = new XMLHttpRequest());

    xhr.open(
      "POST",
      `${document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")}/ckeditor_upload`,
      true
    );
    xhr.responseType = "json";
  }

  // Initializes XMLHttpRequest listeners.
  _initListeners(resolve, reject, file) {
    const xhr = this.xhr;
    const loader = this.loader;
    const genericErrorText = `Couldn't upload file: ${file.name}.`;

    xhr.addEventListener("error", () => reject(genericErrorText));
    xhr.addEventListener("abort", () => reject());
    xhr.addEventListener("load", () => {
      const response = xhr.response;

      if (!response || response.error) {
        return reject(
          response && response.error ? response.error.message : genericErrorText
        );
      }

      resolve({
        default: response.link,
      });
    });

    if (xhr.upload) {
      xhr.upload.addEventListener("progress", (evt) => {
        if (evt.lengthComputable) {
          loader.uploadTotal = evt.total;
          loader.uploaded = evt.loaded;
        }
      });
    }
  }

  // Prepares the data and sends the request.
  _sendRequest(file) {
    // Prepare the form data.
    const data = new FormData();

    data.append("file", file);

    // Send the request.
    this.xhr.send(data);
  }
}

import draggable from "vuedraggable";
import VueDropify from "vue-dropify";
import {
  TiptapVuetify,
  Heading,
  Bold,
  Italic,
  Strike,
  Underline,
  Code,
  Paragraph,
  BulletList,
  OrderedList,
  ListItem,
  Link,
  Blockquote,
  HardBreak,
  HorizontalRule,
  History,
} from "tiptap-vuetify";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    "vue-dropify": VueDropify,
    TiptapVuetify,
    draggable,
    VueEditor,
  },
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        extraPlugins: [this.uploader],
        mediaEmbed: {
          previewsInData: true,
        },
        alignment: {
          options: ["left", "right", "center", "justify"],
        },
      },
      model: null,
      tableCells: 0,
      tableRows: 0,
      tableIsReady: false,
      activeTextImage: 0,
      activeGlossary: 0,
      currentSlideIndex: 0,
      activeGlossaryForm: false,
      glossaryItem: { content: "", answer: "", image: "" },
      activeGlossaryItem: 0,
      url: "lecons/" + (this.$route.params.id ? this.$route.params.id : ""),
      url_base: `${document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")}/lms/teaching/preview/`,
      url_base_: `${document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")}`,
      validationErrors: null,
      lecon: {
        id: null,
        label: null,
        icone: null,
        introduction: null,
        niveau_id: null,
        unites_id: null,
        matiere_id: null,
        rubrique_id: null,
        rubrique_label: null,
        new_rubrique: null,
        syllabus: null,
        objectifs: null,
        getUrlBorne: null,
        prerequis: null,
        instructions: null,
      },
      content: {
        id: null,
        ressource_id: null,
        type_id: null,
        content: null,
        answer: null,
        duree: 5,
        file: "",
        img: null,
        image: null,
        audio: null,
        link: null,
      },
      questionsType: [
        {
          value: 1,
          text: "vrais ou faux",
        },
        {
          value: 2,
          text: "question",
        },
      ],
      ressource: {
        id: null,
        label: null,
        image_url: null,
        introduction: null,
        lecon_id: null,
        type_id: null,
      },
      filter: {
        question_id: 1,
      },
      ressource_types: [],
      niveaux: [],
      etape_types: [],
      rubriques: [],
      unites: [],
      matieres: [],
      ressources: [],
      selectedLink: "",
      selectedAnswer: "",
      edit: false,
      extensions: [
        History,
        Blockquote,
        Link,
        Underline,
        Strike,
        Italic,
        ListItem,
        BulletList,
        OrderedList,
        [
          Heading,
          {
            options: {
              levels: [1, 2, 3],
            },
          },
        ],
        Bold,
        Code,
        HorizontalRule,
        Paragraph,
        HardBreak,
      ],
      error: {
        errorType: "",
        errorTitre: "",
        errorIntro: "",
        errorComposante: "",
        errorUnite: "",
        errorNiveau: "",
      },
      contentErrs: {
        contentYoutubeErr: "",
        contentYoutubeLinkErr: "",
        contentTitleErr: "",
        contentHtmlErr: "",
        contentEmbedErr: "",
        contentEmbedJsErr: "",
        contentTextErr: "",
        contentDocErr: "",
        contentDocFileErr: "",
        contentSlidesErr: "",
        contentLinkYoutubeRequest: "",
      },
      leconErrs: {
        syllabusErr: "",
      },
    };
  },

  mounted() {
    var avatar = new KTImageInput("kt_user_edit_avatar");
    var avatar_lecon = new KTImageInput("kt_lecon_edit_avatar");
  },
  computed: {
    _headers() {
      return this.headers.filter((x) => !x.hide);
    },
  },
  created() {
    this.fetch();
  },

  methods: {
    uploader(editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },

    fetch() {
      window.scrollTo(0, 0);
      let params = {};
      if (!this.$route.params.id)
        window.location.href = `${process.env.MIX_APP_URL}/admin0619/lecons`;
      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function(response) {
            this.lecon = response.data.lecon;
            this.ressource_types = response.data.ressource_types;
            this.etape_types = response.data.etape_types;
            this.niveaux = response.data.niveaux;
            this.rubriques = response.data.rubriques;
            this.unites = response.data.unites;
            this.matieres = response.data.matieres;
            this.ressources = response.data.ressources;
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },

    getRessourceEtapeImage(label) {
      for (let i = 0; i < this.etape_types.length; i++) {
        if (label == this.etape_types[i].text) {
          return this.etape_types[i].image_url;
        }
      }
    },
    activer_lecon(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point d'activer cette leçon !",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Activer",
        })
        .then(
          function(result) {
            if (result.value) {
              let params = {};
              params["id"] = this.lecon.id;

              axios
                .post("activateLecon/" + this.lecon.id)
                .then((response) => {
                  swal.fire(
                    "Leçon activée",
                    "La leçon a été activée avec succés.",
                    "success"
                  );
                  this.fetch();
                  this.lecon = response.data.lecon;
                  this.lecon.enabled = 1;
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },

    bloquer_lecon(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point de désactiver cette leçon !",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Désactiver",
        })
        .then(
          function(result) {
            if (result.value) {
              let params = {};
              params["id"] = this.lecon.id;
              axios
                .post("deactivateLecon/" + this.lecon.id)
                .then((response) => {
                  swal.fire(
                    "Leçon désactivée",
                    "Le leçàn a été désactivée avec succés.",
                    "success"
                  );
                  this.fetch();
                  this.lecon = response.data.lecon;
                  this.lecon.enabled = 0;
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },

    saveLecon(e) {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      $.each(this.lecon, function(key, value) {
        formData.append(key, value ? value : "");
      });
      let countErr = 0;
      if (!this.lecon.label) {
        this.error.errorTitre = "Titre is required";
        countErr++;
      }
      if (!this.lecon.niveau_id) {
        this.error.errorNiveau = "Niveau is required";
        countErr++;
      }
      if (!this.lecon.unite_id) {
        this.error.errorUnite = "Unite is required";
        countErr++;
      }
      if (!this.lecon.rubrique_id) {
        this.error.errorComposante = "Composante is required";
        countErr++;
      }
      if (countErr > 0) {
        return;
      }
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("lecons", formData, config)
        .then((response) => {
          this.fetch();
          this.validationErrors = null;
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          swal.fire("Le changement a été effectué!", "", "success");
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          }
        });
      this.error.errorTitre = "";
      this.error.errorIntro = "";
    },
    saveRessource(e) {
      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };

      this.ressource["lecon_id"] = this.lecon.id;

      let formData = new FormData();
      $.each(this.ressource, function(key, value) {
        formData.append(key, value ? value : "");
      });
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("lecons/ressource", formData, config)
        .then((response) => {
          $("#modal_form_ressource").modal("toggle");
          swal.fire(
            this.ressource.id ? "Etape modifiée !" : "Etape ajoutée !",
            this.ressource.id
              ? "L'étape à été modifiée avec succés !"
              : "L'étape à été ajoutée avec succés !",
            "success"
          );
          this.error.errorType = "";
          this.error.errorTitre = "";
          this.error.errorIntro = "";
          this.clearForm();
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          this.fetch();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          }
        });
    },
    changeQuestionType() {
      this.content.answer = null;
    },
    saveContent(e) {
      var avatar = new KTImageInput("kt_user_edit_avatar");

      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };
      console.log(this.content);
      let formData = new FormData();
      $.each(this.content, function(key, value) {
        if (key == "content") {
          formData.append(key, JSON.stringify(value ? value : ""));
        } else {
          formData.append(key, value ? value : "");
        }
      });
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("lecons/content", formData, config)
        .then((response) => {
          $("#modal_form_content").modal("toggle");
          swal.fire(
            this.content.id ? "Contenu modifié !" : "Contenu ajouté !",
            this.content.id
              ? "Le contenu à été modifié avec succés !"
              : "Le contenu à été ajouté avec succés !",
            "success"
          );
          e.target.disabled = false;
          e.target.classList.toggle("spinner");

          this.fetch();
          this.clearFormContent();
          this.activeGlossary = 0;
          this.currentSlideIndex = 0;
          this.activeGlossaryForm = false;
          this.glossaryItem = { content: "", answer: "", image: "" };
          this.activeGlossaryItem = 0;
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          }
        });
    },
    ressourcesOrder(without_popup = false, data) {
      this.overlay = true;
      let params = {};
      params["data"] = this.ressources;
      axios
        .post("lecons/ressources_order", params)
        .then(
          function(response) {
            this.fetch();
            if (!without_popup)
              swal.fire(
                response.data.title,
                response.data.message,
                response.data.icon
              );
          }.bind(this)
        )
        .catch((error) => {
          // code here when an upload is not valid
          this.overlay = false;
          console.log("check error: ", this.error);
        });
    },
    getRandomColor() {
      var letters = "0123456789ABCDEF";
      var color = "#";
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    },
    linkMeFromContent(content, index) {
      var divLink = document.getElementById("link-" + content);
      var divAnswer = document.getElementById("answer-" + this.selectedAnswer);
      for (let i = 0; i < this.content.content.selected.length; i++) {
        if (
          this.content.content.selected[i].link == content &&
          this.content.content.selected[i].answer == this.selectedAnswer
        ) {
          this.selectedLink = "";
          divLink.style.backgroundColor = `#F3F6F9`;
          divAnswer.style.backgroundColor = `#F3F6F9`;
          divLink.style.color = `black`;
          divAnswer.style.color = `black`;
          this.content.content.selected.splice(i, 1);
          return;
        }
      }
      this.selectedLink = content;
      if (this.selectedAnswer) {
        let color = this.getRandomColor();
        let obj = {
          link: this.selectedLink,
          answer: this.selectedAnswer,
          color: color,
        };
        this.content.content.selected.push(obj);
        divLink.style.backgroundColor = obj.color;
        divAnswer.style.backgroundColor = obj.color;
        divLink.style.color = `white`;
        divAnswer.style.color = `white`;
        this.selectedLink = "";
        this.selectedAnswer = "";
      }
    },
    newRow() {},
    newCol(i) {
      this.content.content.rows[i].row.push({ col: "" });
      console.log(this.content.content.rows);
    },
    buildTable() {
      this.content.content.rows = [];
      for (let i = 0; i < this.tableRows; i++) {
        this.content.content.rows.push({ row: [] });
        for (let y = 0; y < this.tableCells; y++) {
          this.content.content.rows[i].row.push({ col: "" });
        }
      }
      this.tableIsReady = true;
    },
    linkMeFromAnswer(answer, index) {
      var divLink = document.getElementById("link-" + this.selectedLink);
      var divAnswer = document.getElementById("answer-" + answer);
      for (let i = 0; i < this.content.content.selected.length; i++) {
        if (
          this.content.content.selected[i].link == this.selectedLink &&
          this.content.content.selected[i].answer == answer
        ) {
          this.selectedAnswer = "";

          divLink.style.backgroundColor = `#F3F6F9`;
          divAnswer.style.backgroundColor = `#F3F6F9`;
          divLink.style.color = `black`;
          divAnswer.style.color = `black`;
          this.content.content.selected.splice(i, 1);
          return;
        }
      }
      this.selectedAnswer = answer;
      if (this.selectedLink) {
        let color = this.getRandomColor();
        let obj = {
          link: this.selectedLink,
          answer: this.selectedAnswer,
          color: color,
        };
        this.content.content.selected.push(obj);
        divLink.style.backgroundColor = obj.color;
        divAnswer.style.backgroundColor = obj.color;
        divLink.style.color = `white`;
        divAnswer.style.color = `white`;
        this.selectedLink = "";
        this.selectedAnswer = "";
      }
    },
    toggleGlossary(index, y, item) {
      console.log(this.glossaryItem);
      this.activeGlossary = y;
      this.glossaryItem = item;
      this.activeGlossaryForm = true;
      if (!this.glossaryItem.image) {
        this.activeGlossaryForm = false;
      }
    },
    newImageText() {
      this.content.content.images.push("");
    },
    removeTextImage(index) {
      this.content.content.images.splice(index, 1);
    },
    toggleTextImage(index) {
      this.activeTextImage = index;
    },
    linedraw(x1, y1, x2, y2, col, content, answer) {
      if (x2 < x1) {
        var tmp;
        tmp = x2;
        x2 = x1;
        x1 = tmp;
        tmp = y2;
        y2 = y1;
        y1 = tmp;
      }
      var modal = document.getElementById("mainLines");
      var lineLength = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
      var m = (y2 - y1) / (x2 - x1);

      var degree = (Math.atan(m) * 180) / Math.PI;
      var contentLink = this.selectedLink ? this.selectedLink : content;
      var contentAnswer = this.selectedAnswer ? this.selectedAnswer : answer;
      modal.innerHTML +=
        "<div  data-link-delete='" +
        contentLink +
        "' data-answer-delete='" +
        contentAnswer +
        "'  data-delete='" +
        contentLink +
        "-" +
        contentAnswer +
        "' class='line' style='transform-origin: top left; transform: rotate(" +
        degree +
        "deg); width: " +
        lineLength +
        "px; height: 2px; background: " +
        col +
        "; position: absolute; top: " +
        (y1 - 125) +
        "px; left: " +
        (x1 - 264) +
        "px;'></div>";
    },

    newStructure() {
      this.content.content.push({ content: "" });
    },
    newSchemas() {
      this.content.content.push({ content: "" });
    },
    newStructureArrow() {
      this.content.content.push({ content: "" });
    },
    newGLossaryItem(index) {
      this.content.content[index].glossary.push({
        image: "",
      });
      this.glossaryItem = this.content.content[index].glossary[
        this.content.content[index].glossary.length - 1
      ];
      this.activeGlossary = this.content.content[index].glossary.length - 1;
      this.activeGlossaryForm = false;
    },
    newGLossary() {
      console.log(this.content);
      this.content.content.push({
        glossaryName: "",
        glossaryDescription: "",
        glossary: [],
      });
      this.content.content[this.content.content.length - 1].glossary.push({
        glossary: [{ image: "" }],
      });
      this.glossaryItem = this.content.content[
        this.content.content.length - 1
      ].glossary[0];
      this.currentSlideIndex = this.content.content.length - 1;
      this.activeGlossary = 0;
    },
    newLink() {
      this.content.content.contents.push({ content: "" });
    },
    newAnswer() {
      this.content.content.answers.push({ answer: "" });
    },
    newSelected() {
      this.content.content.selected.push({ selected: "" });
    },
    newImage() {
      this.content.file.push("");
    },
    removeStructure(index, i) {
      this.content.content.splice(index, 1);
    },
    removeSchemas(index, i) {
      this.content.content.splice(index, 1);
    },
    removeGlossary(index, y) {
      this.content.content[index].glossary.splice(y, 1);
      if (y > 0) {
        this.glossaryItem = this.content.content[
          this.currentSlideIndex
        ].glossary[y - 1];
      } else {
        // this.content.content.splice(index, 1);
      }
    },
    removeSlide(index) {
      console.log(index);
      if (index > 0) {
        this.currentSlideIndex -= 1;
        this.glossaryItem = this.content.content[
          this.currentSlideIndex
        ].glossary[0];
      } else {
        this.currentSlideIndex = 0;
        this.glossaryItem = null;
      }
      this.content.content.splice(index, 1);
      console.log(this.currentSlideIndex);
    },
    removeRow(i) {
      this.content.content.rows.splice(i, 1);
    },
    removeCol(i, y) {
      this.content.content.rows[i].row.splice(y, 1);
    },
    removeLine(dataDelete) {
      console.log(dataDelete);
      for (let i = 0; i < this.content.content.selected.length; i++) {
        if (
          this.content.content.selected[i].link +
            "-" +
            this.content.content.selected[i].answer ==
          dataDelete
        ) {
          var line = document.querySelector(
            `[data-link-delete="${dataDelete}"]`
          );
          if (line) {
            line.remove();
          }
          this.content.content.selected.splice(i, 1);
        }
      }
    },
    removeLink(index, i) {
      for (let i = 0; i < this.content.content.selected.length; i++) {
        if (
          this.content.content.selected[i].link ==
          this.content.content.contents[index].content
        ) {
          let wordLink = document.getElementById(
            "link-" + this.content.content.selected[i].link
          );
          let answerLink = document.getElementById(
            "answer-" + this.content.content.selected[i].answer
          );
          // wordLink.remove();
          answerLink.style.backgroundColor = "#F3F6F9";
          answerLink.style.color = "black";
          this.content.content.selected.splice(index, 1);

          // wordLink.style.backgroundColor = "#F3F6F9";
          // wordLink.style.color = "black";
        }
      }
      this.content.content.contents.splice(index, 1);
    },
    removeAnswer(index, i) {
      for (let i = 0; i < this.content.content.selected.length; i++) {
        if (
          this.content.content.selected[i].answer ==
          this.content.content.answers[index].answer
        ) {
          let wordLink = document.getElementById(
            "link-" + this.content.content.selected[i].link
          );
          let answerLink = document.getElementById(
            "answer-" + this.content.content.selected[i].answer
          );
          // wordLink.remove();
          wordLink.style.backgroundColor = "#F3F6F9";
          wordLink.style.color = "black";
          this.content.content.selected.splice(i, 1);
          // answerLink.style.backgroundColor = "#F3F6F9";
          // answerLink.style.color = "black";
        }
      }
      this.content.content.answers.splice(index, 1);
    },
    setColors() {
      console.log("hello");
      for (let i = 0; i < this.content.content.selected.length; i++) {
        var divLink = document.getElementById(
          "link-" + this.content.content.selected[i].link
        );
        var divAnswer = document.getElementById(
          "answer-" + this.content.content.selected[i].answer
        );
        divLink.style.backgroundColor = `${this.content.content.selected[i].color}`;
        divAnswer.style.backgroundColor = `${this.content.content.selected[i].color}`;
        divLink.style.color = `white`;
        divAnswer.style.color = `white`;
      }
    },
    removeImage(index, i) {
      this.content.file.splice(index, 1);
    },
    contentsOrder(without_popup = false, data) {
      this.overlay = true;
      let params = {};
      params["data"] = data;
      axios
        .post("lecons/contents_order", params)
        .then(
          function(response) {
            this.fetch();
            if (!without_popup)
              swal.fire(
                response.data.title,
                response.data.message,
                response.data.icon
              );
          }.bind(this)
        )
        .catch((error) => {
          // code here when an upload is not valid
          this.overlay = false;
          console.log("check error: ", this.error);
        });
    },
    addRessource() {
      this.clearForm();
      this.validationErrors = null;
    },
    editRessource(ressource) {
      $("#modal_form_ressource").modal("toggle");
      this.validationErrors = null;
      this.edit = true;
      $.each(
        this.ressource,
        function(key, value) {
          this.ressource[key] = ressource[key];
        }.bind(this)
      );
    },
    clearContent() {
      $.each(
        this.content,
        function(key, value) {
          this.content[key] = null;
        }.bind(this)
      );
      // this.content.content = null;
    },
    deleteUnite(ressource) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
        })
        .then(
          function(result) {
            if (result.value) {
              axios
                .post("/deleteRessource/" + ressource.id)
                .then((response) => {
                  if (response.data == "can't delete this item") {
                    swal.fire(
                      "Not Deleted !!",
                      "can't delete this item",
                      "error"
                    );
                  } else {
                    swal.fire(
                      "Deleted!",
                      "Your ressource has been deleted.",
                      "success"
                    );
                  }
                  this.fetch();
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    onImageQuestionChange(event, index) {
      this.content.file[index] = event.target.files[0];
    },
    onImageLeconChange(e) {
      this.lecon.icone = e.target.files[0];
    },
    changeAnswerState(state) {
      this.content.answer = state == 1 ? true : false;
    },
    deleteLecon(lecon) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
        })
        .then(
          function(result) {
            if (result.value) {
              axios
                .post("/deleteLecon/" + lecon.id)
                .then((response) => {
                  if (response.data == "can't delete this item") {
                    swal.fire(
                      "Not Deleted !!",
                      "can't delete this item",
                      "error"
                    );
                  } else {
                    swal.fire(
                      "Deleted!",
                      "Your ressource has been deleted.",
                      "success"
                    );
                  }
                  this.$router.push({ name: "lecons" });
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    addContent(ressource) {
      var avatar = new KTImageInput("kt_user_edit_avatar");

      this.clearFormContent();
      this.content.ressource_id = ressource;
      if (this.content.type_id == 7) {
        this.content.content = [];
      } else if (this.content.type_id == 11) {
        this.content.content = [];
        this.content.answer = [];
      }
      $("#modal_content_types").modal("toggle");
      $("#modal_form_content").modal("toggle");
      this.validationErrors = null;
    },
    showTypesContent(ressource) {
      this.content.ressource_id = ressource;
      $("#modal_content_types").modal("toggle");
    },
    onSlideChange() {
      if (this.content.content[this.currentSlideIndex].glossary[0]) {
        this.glossaryItem = this.content.content[
          this.currentSlideIndex
        ].glossary[0];
      } else {
        this.glossaryItem = { content: "", answer: "", image: "" };
      }
    },
    addContentType(content, ressource) {
      this.clearFormContent();
      this.content.type_id = content;
      this.content.duree = 5;
      this.content.ressource_id = ressource;
      if (this.content.type_id == 7) {
        this.content.content = [];
      } else if (this.content.type_id == 12) {
        if (this.content.content) {
          // this.glossaryItem = this.content.content[0].glossary[0];
        } else {
          this.content.content = [];
          this.currentSlideIndex = 0;
          // this.content.content.push({glossaryName:'',glossaryDescription:'' , glossary:[]});
        }
      } else if (
        this.content.type_id == 11 ||
        this.content.type_id == 22 ||
        this.content.type_id == 24
      ) {
        this.content.content = [];
        this.content.answer = [];
      } else if (
        this.content.type_id == 11 ||
        this.content.type_id == 22 ||
        this.content.type_id == 24
      ) {
        this.content.content = [];
        this.content.answer = [];
      } else if (this.content.type_id == 14 || this.content.type_id == 16) {
        if (this.content.content) {
        } else {
          this.content.content = [
            { content: "", answer: "" },
            { content: "", answer: "" },
            { content: "", answer: "" },
            { content: "", answer: "" },
            { content: "", color: "#F5932C" },
            { content: "", color: "#9A8DC3" },
            { content: "", color: "#68C3C8" },
            { content: "", color: "#88C069" },
          ];
        }
      } else if (this.content.type_id == 26) {
        if (this.content.content) {
        } else {
          this.content.content = [{ content: "", answer: "" }];
        }
      } else if (this.content.type_id == 17) {
        if (this.content.content) {
        } else {
          console.log(this.content.content);
          this.content.content = {
            contents: [],
            selected: [],
            answers: [],
          };
        }
      } else if (this.content.type_id == 21) {
        if (this.content.content) {
        } else {
          this.content.content = {
            rows: [],
          };
        }
      } else if (this.content.type_id == 6) {
        if (this.content.content) {
        } else {
          this.content.content = {
            text: "",
            images: [],
          };
        }
      }
      $("#modal_content_types").modal("toggle");
      $("#modal_form_content").modal("toggle");
      this.validationErrors = null;
      setTimeout(() => {
        var avatar = new KTImageInput("kt_user_edit_avatar");
      }, 2000);
    },
    editContent(content, ressource) {
      if (!content.file) {
        content.file =
          this.url_base_ +
          "/assets/schools/" +
          this.url_base_.split("/")[4] +
          "/lms/lecons_files/" +
          content.image;
      }
      $("#modal_form_content").modal("toggle");
      $("#kt_user_edit_avatar .image-input-wrapper").css(
        "background-image",
        "none"
      );

      this.validationErrors = null;
      this.edit = true;
      $.each(
        this.content,
        function(key, value) {
          this.content[key] = content[key];
        }.bind(this)
      );
      this.content.ressource_id = ressource;
      if (content.type_id == 12) {
        this.currentSlideIndex = 0;
        this.glossaryItem = this.content.content[0].glossary[0];
      }
      setTimeout(() => {
        if (content.type_id == 17) {
          for (let i = 0; i < content.content.selected.length; i++) {
            var divLink = document.getElementById(
              "link-" + content.content.selected[i].link
            );
            var divAnswer = document.getElementById(
              "answer-" + content.content.selected[i].answer
            );
            divLink.style.backgroundColor = `${content.content.selected[i].color}`;
            divAnswer.style.backgroundColor = `${content.content.selected[i].color}`;
            divLink.style.color = `white`;
            divAnswer.style.color = `white`;
          }
        }
      }, 400);
      setTimeout(() => {
        var avatar = new KTImageInput("kt_user_edit_avatar");
      }, 2000);
    },

    deleteContent(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point de supprimer ce contenu !",
          lecon: "question",
          showCancelButton: true,
          confirmButtonText: "Supprimer",
          cancelButtonText: "Annuler",
        })
        .then(
          function(result) {
            if (result.value) {
              axios
                .post("lecons/delete_content/" + id)
                .then((response) => {
                  this.fetch();
                  swal.fire(
                    "Contenu supprimé !",
                    "Le Contenu a été supprimé avec succés.",
                    "success"
                  );
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    clearForm() {
      this.edit = false;
      $.each(
        this.ressource,
        function(key, value) {
          this.ressource[key] = null;
        }.bind(this)
      );
    },

    clearFormContent() {
      this.edit = false;
      $.each(
        this.content,
        function(key, value) {
          this.content[key] = null;
        }.bind(this)
      );
    },
    onImageChange(e) {
      this.lecon.image = e.target.files[0];
      this.content.file = e.target.files[0];
    },
    onImageTextChange(e) {
      this.content.file = e.target.files[0];
    },
    onImageGlossaryChange(e, i, y) {
      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("File", e.target.files[0]);
      axios
        .post("saveGlossaryImage", formData, config)
        .then((response) => {
          this.content.content[i].glossary[this.activeGlossary].image =
            response.data.path;

          if (!this.activeGlossaryForm) {
            this.content.content[i].glossary.push({
              image: "",
            });
            this.glossaryItem = this.content.content[i].glossary[
              this.activeGlossary + 1
            ];
            this.activeGlossary = this.content.content[i].glossary.length - 1;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onImageTextChangeSave(e) {
      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("File", e.target.files[0]);
      axios
        .post("saveTextImage", formData, config)
        .then((response) => {
          this.content.content.images.push(response.data.path);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onImageDocument(e) {
      this.document.file = e.target.files[0];
    },
    onEndDrag(data) {
      this.ressourcesOrder(true);
    },
    onEndDragContents(data) {
      this.contentsOrder(true, data);
    },
    hideValidationsText() {
      this.error.errorType = "";
      this.error.errorTitre = "";
      this.error.errorIntro = "";
      this.contentErrs.contentYoutubeErr = "";
      this.contentErrs.contentYoutubeLinkErr = "";
      this.contentErrs.contentTitleErr = "";
      this.contentErrs.contentHtmlErr = "";
      this.contentErrs.contentEmbedErr = "";
      this.contentErrs.contentEmbedJsErr = "";
      this.contentErrs.contentTextErr = "";
      this.contentErrs.contentDocErr = "";
      this.contentErrs.contentDocFileErr = "";
    },
    validateContent() {
      let countErr = 0;
      if (this.content.type_id == 4) {
        if (!this.content.content) {
          this.contentErrs.contentYoutubeErr = "Title is required";
          countErr++;
        } else {
          this.contentErrs.contentYoutubeErr = "";
        }
        if (!this.content.link) {
          this.contentErrs.contentYoutubeLinkErr = "Link is required";
          countErr++;
        } else {
          this.contentErrs.contentYoutubeLinkErr = "";
        }
      } else if (this.content.type_id == 1) {
        if (!this.content.content) {
          this.contentErrs.contentTitleErr = "Title is required";
          countErr++;
        } else {
          this.contentErrs.contentTitleErr = "";
        }
      } else if (this.content.type_id == 2) {
        if (!this.content.content) {
          this.contentErrs.contentHtmlErr = "Html Content is required";
          countErr++;
        } else {
          this.contentErrs.contentHtmlErr = "";
        }
      } else if (this.content.type_id == 5) {
        if (!this.content.content) {
          this.contentErrs.contentEmbedErr = "Embed is required";
          countErr++;
        } else {
          this.contentErrs.contentEmbedErr = "";
        }
        if (!this.content.link) {
          this.contentErrs.contentEmbedJsErr = "Js script is required";
          countErr++;
        } else {
          this.contentErrs.contentEmbedJsErr = "";
        }
      } else if (this.content.type_id == 6) {
        if (!this.content.content) {
          this.contentErrs.contentTextErr = "Text is required";
          countErr++;
        } else {
          this.contentErrs.contentTextErr = "";
        }
      } else if (this.content.type_id == 3) {
        if (!this.content.content) {
          this.contentErrs.contentDocErr = "Document title is required";
          countErr++;
        } else {
          this.contentErrs.contentDocErr = "";
        }
        if (!this.content.file) {
          this.contentErrs.contentDocFileErr = "Document is required";
          countErr++;
        } else {
          this.contentErrs.contentDocFileErr = "";
        }
      } else if (this.content.type_id == 7) {
        for (let i = 0; i < this.content.content.length; i++) {
          if (!this.content.content[i].content) {
            this.contentErrs.contentSlidesErr = "Slide content is required";
            countErr++;
          } else {
            this.contentErrs.contentSlidesErr = "";
          }
        }
      }
      return countErr;
    },
    getVedeoData() {
      const vidurl = this.content.link.split("=")[1].split("&")[0];
      this.content.link = this.content.link.split("&")[0];

      $.getJSON(
        "https://www.googleapis.com/youtube/v3/videos?part=id%2C+snippet%2C+contentDetails&id=" +
          vidurl +
          "&key=AIzaSyAP9tC2-444emM6MzpwIafxocr0bAMkDko",
        { dataType: "json", url: vidurl },
        (data) => {
          if (data.items.length > 0) {
            this.content.content = data.items[0].snippet.title;
            this.content.img = data.items[0].snippet.thumbnails.standard.url;
            let youtubeTime = data.items[0].contentDetails.duration;
            if (youtubeTime.indexOf("H") === -1) {
              if (youtubeTime.indexOf("M") + 1 === 4) {
                youtubeTime = Number(youtubeTime.substr(2, 1)) + 1;
                this.content.duree = youtubeTime;
              } else {
                youtubeTime = Number(youtubeTime.substr(2, 2)) + 1;
                this.content.duree = youtubeTime;
              }
            } else {
              this.content.duree = 60;
            }
            this.contentErrs.contentLinkYoutubeRequest = "";
          } else {
            this.contentErrs.contentLinkYoutubeRequest = "invalide link";
            this.content.content = null;
            this.content.img = "";
            this.content.duree = 5;
            let youtubeTime = data.items[0].contentDetails.duration;
          }
        }
      );
    },
    setFileName(e) {
      if (this.content.file) {
        this.content.content = this.content.file.name.split(".")[0];
      } else {
        this.content.content = "";
      }
    },
  },
};
</script>
<style>
.image-input .image-input-wrapper {
  width: 220px !important;
  height: 220px !important;
}
.card-hov {
  transition: 0.5s;
}
.card-hov:hover {
  background: #c7c7c7;
  color: white;
}
.card-active {
  background: #c7c7c7;
  color: white;
}
.con-fx-ty {
  min-height: 350px;
  box-shadow: 0px 0px 5px #00000014;
  border-radius: 30px;
  margin-top: 25px;
  position: relative;
  text-align: center;
}
.etape-title {
  margin: auto;
  width: fit-content;
  /* background: #f7623c; */
  color: white;
  font-size: 20px;
  padding: 3px 60px;
  border-bottom-left-radius: 50px;
  border-bottom-right-radius: 50px;
}
.title-type {
  margin-top: 5px;
  min-height: 308px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.html-type {
  display: block;
  padding: 10px !important;
}
/* .html-type figure.image {
  margin: 10px 0;
  text-align: center;
} */

.html-type figure table {
  width: 100%;
  height: 100%;
}
.v-btn__content {
  background: #878f93;
}
.html-type figure table tr {
  border: 1px solid #171656;
}
.html-type figure table td {
  text-align: center;
  border: 1px solid grey;
}
.html-type blockquote {
  border-left: 5px solid #ccc;
  font-style: italic;
  margin-left: 0;
  margin-right: 0;
  overflow: hidden;
  padding-left: 1.5em;
  padding-right: 1.5em;
}
.word-complete {
  width: 100%;
  margin: 0;
  padding: 20px;
  flex-direction: column;
}
.youtube-type {
  height: 420px;
  margin-top: 10px;
}
.quiz-type {
  height: 314px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
.v-slide-group__content {
}
.v-slide-group__next i,
.v-slide-group__prev i {
  background: #80808096;
  color: white;
  border-radius: 50%;
}

.quiz-type .checkbox-answers {
  display: flex;
  margin-top: 40px;
}
.quiz-type .checkbox-answers .bx {
  padding: 15px 30px;
  color: #171656;
  border-radius: 40px;
  border: 1px solid #8080803d;
  display: flex;
  align-items: center;
  font-size: 20px;
  margin-right: 5px;
  cursor: pointer;
  font-weight: 600;
}
.quiz-type .checkbox-answers .bx-active-btn {
  color: white;
  background: #4aad1b;
}
.text-type {
  display: flex;
  margin-top: 13px;
  justify-content: space-between;
  padding: 5px;
}
.text-type .img {
  overflow: hidden;
  border-radius: 15px;
  width: 36%;
  margin: 0 40px 0 0 !important;
}

.text-type .img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.open-question-type {
  margin-top: 10px;
  height: 303px;
  display: flex;
  flex-direction: column;
  align-content: center;
  justify-content: center;
}
.ll-col {
  display: flex;
  width: 215px;
  overflow-x: hidden;
  overflow-y: auto;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.btn-hoverr-success:hover {
  background-color: #4caf50;
}
.btn-hoverr-success:hover svg {
  color: white;
  background-color: white;
}
.open-question-type textarea {
  color: #171656;
  font-weight: 600;
  width: 300px;
  margin: 0 auto;
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
.col-blob {
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  background-position: center;
  background-size: cover;
  height: 350px;
  width: 350px;
  height: 250px;
  width: 250px;
  background: aqua;
  border-radius: 50%;
  box-shadow: outset 0px 0px 4px 0px #00000014;
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
  justify-content: end;
  padding-bottom: 13px;
}
.cube div:nth-child(3),
.cube div:nth-child(4) {
  align-items: flex-start;
  justify-content: end;
  padding-top: 13px;
  padding-right: 13px;
}

.question {
  width: 100%;
}
/* .ck {
  width: 50%;
    overflow-y: auto;
    overflow-x: hidden;
    height: 302px;
} */
/* .ck-editor__editable { */
.ck-rounded-corners {
  /* width: 50%; */
  overflow-y: auto;
  overflow-x: hidden;
  height: 302px;
}
.slide {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.structure-type {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  flex-wrap: wrap;
  padding: 0 25px;
}
.link-me-arrow {
  margin: auto;
  width: 15px;
  height: 15px;
  /* border-radius: 50%; */
  color: #000000;
  border: 1px solid #bfbfbf;
  background: #12122c;
}
.rwd-table {
  width: 100% !important;
  margin: auto;
  border: 1px solid #ebedf3;
  border-radius: 6px;
  display: block;
  overflow: hidden;
  overflow-x: auto;
}
.rwd-table tr {
  /* background: #ebedf3; */
  background: white;
}
.rwd-table tr:nth-child(1) {
  background: #e9e9e9;
}

.main-content-grid-box {
  display: flex;
  align-items: center;
}

.color-in {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: transparent;
  width: 50px;
  height: 50px;
  border: none;
  cursor: pointer;
}
.color-in::-webkit-color-swatch {
  border-radius: 50%;
  border: 2px solid #d9d9d9;
}
.color-in::-moz-color-swatch {
  border-radius: 50%;
  border: 2px solid #d9d9d9;
}
.content-grid-box-data {
  bottom: -90px;
  position: absolute;
  left: 50%;
  width: 100%;
  background: #f7623c;
  transform: translateX(-50%);
  padding: 5px;
  color: white;
  display: flex;
  padding: 10px 0;
  align-items: center;
  justify-content: space-evenly;
  font-weight: 600;
  font-size: 14px;
  transition: 0.5s ease;
}
.content-grid-box {
  position: relative;
  overflow: hidden;
  transition: 0.5s ease;
  padding: 10px;
  border: 2px solid #e7e7e7;
  border-radius: 15px;
}

.content-grid-box:hover .content-grid-box-data {
  bottom: 0px;
}

.content-grid-box-actions {
  display: flex;
}
.content-grid > div:first-child {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  gap: 20px;
}
.schemas-color {
  width: 250px;
  height: 350px;
  padding: 10px;
  border: 3px solid #8bc34a;
  border-image: linear-gradient(#f6b73c, #4d9f0c, #e91e63) 30;
  margin-right: 5px;
  border-width: 4px;
}
.table-ress {
  display: flex;
  width: 100%;
  justify-content: space-evenly;
  margin: 25px 0;
}
.content-grid .content-grid-box {
  width: 100%;
  height: 100%;
}
.content-grid .content-grid-box img {
  width: 100%;
  height: 100%;
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
  background: #b3b3b3;
  border-radius: 5px;
}
.v-slide-group__wrapper::v-deep {
  overflow-x: auto;
}
/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #858585;
}

.v-slide-group__wrapper::v-deep {
  overflow-x: auto;
}
@media (max-width: 1080px) {
  .content-grid > div:first-child {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr;
  }
}
@media (max-width: 667px) {
  .content-grid > div:first-child {
    grid-template-columns: 1fr;
    grid-template-rows: 1fr;
  }
}
</style>
