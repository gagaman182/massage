<template>
  <v-row>
    <v-col cols="12">
      <v-toolbar height="200" color="#1363DF">
        <v-toolbar-title class="text-h5 white--text ml-6 mt-10">
          ระบบจองบริการแพทย์แผนไทย บริการนวด เพื่อการบำบัดรักษาโรค
        </v-toolbar-title>
      </v-toolbar>

      <!-- <v-card color="#1363DF">
        <v-card-title class="headline"> ระบบจองบริการแพทย์แผนไทย </v-card-title>
      </v-card> -->
    </v-col>
    <v-col cols="12">
      <v-card>
        <v-card-text class="pl-10 pr-10 pt-10 pb-10 eventmouse">
          <FullCalendar :options="calendarOptions" v-if="dateshow" />
        </v-card-text>
      </v-card>

      <!-- <caa /> -->
    </v-col>
  </v-row>
</template>

<script >
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'
import resourceTimelinePlugin from '@fullcalendar/resource-timeline'
import thLocale from '@fullcalendar/core/locales/th'
import moment from 'moment'

// import caa from '~/components/caa.vue'
export default {
  name: 'IndexPage',
  data() {
    return {
      dateuse: new Date().toISOString().substr(0, 10),
      dateshow: false,
      calendarOptions: {
        plugins: [resourceTimelinePlugin, interactionPlugin],
        initialView: 'resourceTimeline',
        nowIndicator: true,
        editable: true,
        timeZone: 'local',
        locales: thLocale,
        locale: 'th',
        // scrollTime: '7:00', //scroll เริ่ม
        slotMinTime: '08:30:00', //เวลาเริ่ม
        slotMaxTime: '19:00:00', //เวลาสิ้นสุด
        slotDuration: '01:10',
        //aspectRatio: 2.2, //ความสูงพอดีหน้าจอ
        height: 'auto', //ความสูงตามข้อมูล

        selectable: true, //ใช้กับ event select
        //---------------event-------------------
        //dateClick: this.clickevent, //click ปฎิทิน
        select: this.selectevent,
        // โชว์แสดง เมนูข้างบน
        headerToolbar: {
          start: 'title',
          center: '',
          end: '', //
        },
        slotMinWidth: 10, //กว้าง
        //กำหนด ให้โชว์เฉพาะวันปัจจุบัน
        visibleRange: {
          start: '',
          end: '',
        },
        resourceAreaColumns: [
          {
            field: 'title',
            headerContent: 'รายชื่อหมอนวด',
          },
          {
            field: 'occupancy',
            headerContent: 'สถานะการทำงาน',
          },
        ],
        resources: [
          { id: 'a', title: 'Auditorium A', occupancy: 40 },
          { id: 'b', title: 'Auditorium B', occupancy: 60 },
          { id: 'c', title: 'Auditorium C', occupancy: 40 },
          { id: 'd', title: 'Auditorium D', occupancy: 40 },
        ],
      },
    }
  },
  mounted() {
    this.dateshow = true
    this.calendarOptions.visibleRange.start = this.dateuse
    this.calendarOptions.visibleRange.end = this.dateuse
  },
  components: {
    FullCalendar,
  },
  methods: {
    clickevent: function (arg) {
      //alert(moment(arg.dateStr).locale('th').format('Y-M-D HH:mm:ss'))
      console.log(arg)
      if (moment(arg.dateStr).locale('th').format('HH:mm:ss') == '12:00:00') {
        alert('ไม่ได้')
      }
    },
    selectevent: function (info) {
      //   start,
      // end,
      // startStr,
      // endStr,
      // allDay,
      // jsEvent,
      // view,
      // resource
      // alert(info.start)
      if (moment(info.start).locale('th').format('HH:mm:ss') == '12:00:00') {
        alert('ไม่ได้')
      }
    },
  },

  // computed: {
  //   computedDateFormattedMomentjs() {
  //     return this.datestart
  //       ? moment(this.datestart).locale('th').format('LL')
  //       : ''
  //   },
  // },
}
</script>
<style scoped>
.eventmouse {
  cursor: pointer;
}
/* div {
  font-family: 'Anton', sans-serif;
  font-family: 'Kanit', sans-serif;
  font-family: 'Quicksand', sans-serif;
  font-family: 'Sarabun', sans-serif;
  font-family: 'Trirong', serif;
} */
</style>
