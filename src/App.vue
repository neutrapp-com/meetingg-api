<template>
  <div class="listbutton">
    <square-button icon="videocam" color="#ff742e" title="New Meeting" description="Setup new meeting" />
    <square-button icon="add-circle" color="#0e78f9" title="Join Meeting" description="Via invitation link" />
    <square-button icon="calendar-outline" color="#0e78f9" title="Schedule" description="Plan your meetings" />
    <square-button icon="desktop-outline" color="#0e78f9" title="Share  Screen" description="Show your work" />
  </div>
  <router-view/>
</template>

<script>
import 'vue-ionicons/ionicons.scss';

import SquareButton from './components/partials/cards/SquareButton.vue'

export default {
  name: "App",
  components: { SquareButton },
}
</script>

<style src="./assets/css/index.css"></style>


<style lang="scss">
  .listbutton{
    display: flex;

    > div {
      margin-right: 100px;
    }
  }
</style>
