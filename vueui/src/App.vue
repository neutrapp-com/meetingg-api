<template>
  <navbar />
    <div>
      <div class="listbutton">
        <square-button icon="videocam" color="#ff742e" title="New Meeting" description="Setup new meeting" />
        <square-button icon="add-circle" color="#0e78f9" title="Join Meeting" description="Via invitation link" />
        <square-button  icon="calendar-outline" color="#0e78f9" title="Schedule" description="Plan your meetings" />
        <square-button icon="desktop-outline" color="#0e78f9" title="Share  Screen" description="Show your work" />
        <square-button icon="desktop-outline" color="#0e78f9" title="Share  Screen" description="Show your work" />
        <square-button icon="desktop-outline" color="#0e78f9" title="Share  Screen" description="Show your work" />
      </div>

      <div class="listwidgets">
          <widget-date-time />
          <widget-meeting />
      </div>
    </div>
</template>

<script>

import SquareButton from './components/cards/SquareButton.vue'
import WidgetDateTime from './components/widgets/WidgetDateTime.vue';
import WidgetMeeting from './components/widgets/WidgetMeeting.vue';
import Navbar from './components/navbar.vue';

export default {
  name: "App",
  components: { SquareButton, WidgetDateTime, Navbar, WidgetMeeting },
  acitons : {
  }
}
</script>

<style lang="scss">
  .listbutton,.listwidgets{
    display: flex;

    > a , > div {
      @apply m-3;
    }
  }
</style>
