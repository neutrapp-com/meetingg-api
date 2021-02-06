<template>
  <navbar />
    <div>
      <div class="listbutton">
        <square-button icon="videocam" color="#ff742e" title="New Meeting" description="Setup new meeting" />
        <square-button icon="add-circle" color="#0e78f9" title="Join Meeting" description="Via invitation link" />
        <square-button icon="calendar-outline" color="#0e78f9" title="Schedule" description="Plan your meetings" />
        <square-button icon="desktop-outline" color="#0e78f9" title="Share  Screen" description="Show your work" />
      </div>

      <div class="listwidgets">
          <widget-date-time />
          <widget-meeting />
      </div>

      <div class="currentTest" style="background-color: black">
          <text-button text="Start" color="#0e78f9" fontColor="#FFFFFF"/>
          <text-button text="Copy invitation"/>
          <text-button text="Join from Room"/>

          <icon-button icon="https://icons-for-free.com/iconfiles/png/512/svg+create+edit+edit+file+office+pencil+writing+creativ+icon-1320185158722776676.png"/>
          <icon-button icon="https://image.flaticon.com/icons/png/512/61/61848.png"/>

          <participant-card icon="https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg" fullname="petersonne jean-claude de la vega"/>
          <participant-card icon="https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg" fullname="Michel barder"/>

          <meeting-id numberId="707 904 6594"/>
      </div>

    </div>
</template>

<script>

import SquareButton from './components/cards/SquareButton.vue'
import WidgetDateTime from './components/widgets/WidgetDateTime.vue';
import WidgetMeeting from './components/widgets/WidgetMeeting.vue';
import Navbar from './components/navbar.vue';

import TextButton from './components/cards/TextButton.vue';
import IconButton from './components/cards/IconButton.vue';
import ParticipantCard from './components/cards/ParticipantCard.vue';
import MeetingId from './components/cards/MeetingId.vue';

export default {
  name: "App",
  components: { SquareButton, WidgetDateTime, Navbar, WidgetMeeting, TextButton, IconButton, ParticipantCard, MeetingId },
  acitons : {
  }
}
</script>

<style lang="scss">
  .listbutton,.listwidgets,.currentTest{
    display: flex;

    > a , > div {
      @apply m-3;
    }
  }
</style>
