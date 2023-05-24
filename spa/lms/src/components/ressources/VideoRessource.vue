<template>
  <div class="content-ressource">
            <div class="container" style="position: relative;">
              <div class="wrapper-ress">
                <div
                  class="img"
                  style="height: 100%;width: 100%;object-fit: cover;"
                >
                  <video width="100%" height="100%" controls>
                    <source
                      :src="
                        url_base +
                          '/assets/schools/' +
                          url_base.split('/')[4] +
                          '/lms/lecons_files/' +
                          
                            ressource.image
                      "
                      type="video/mp4"
                    />
                    <source
                      :src="
                        url_base +
                          '/assets/schools/' +
                          url_base.split('/')[4] +
                          '/lms/lecons_files/' +
                          
                            ressource.image
                      "
                      type="video/ogg"
                    />
                  </video>
                </div>
              </div>
            </div>
          </div>
</template>

<script>
export default {
    props:['url_base','ressource']
}
</script>

<style>

</style>