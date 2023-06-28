<template>
  <div class="content-ressource table">
    <div
      class="wrapper-ress"
      style="justify-content: flex-start !important;width: 100% !important;margin: 0 !important;"
    >
      <div class="guide" style="margin-bottom: 10px;">
        {{ ressource.answer }}
      </div>
      <div style="height: 60vh;overflow: auto;padding-top: 30px;width: 100%;">
        <table class="table table-bordered mt-2" style="overflow: auto;">
          <thead>
            <tr>
              <th
                class="text-center"
                style="font-size: 1.5rem;color: #171656;min-width: 200px"
                v-for="(col, y) in ressource.content.rows[0].row"
                :key="y"
              >
                {{ col.col }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, i) in ressource.content.rows"
              v-if="i > 0"
              :key="i"
            >
              <td
                v-for="(col, y) in ressource.content.rows[i].row"
                :key="y"
                style="min-width: 200px; "
              >
                <div style="font-size: 2rem;">
                  {{ col.col }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
};
</script>

<style lang="scss">
.guide {
  font-size: 2rem;
  color: #171656;
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
.v-data-table__wrapper {
  width: 100%;
}
.rwd-table tbody tr th {
  min-height: 50px;
  display: block;
}
.rwd-table tr {
  /* background: #ebedf3; */
  background: white;
}
.rwd-table tr:nth-child(1) {
  background: #e9e9e9;
}
::-webkit-scrollbar {
  width: 5px;
  height: 3px;
}
</style>
