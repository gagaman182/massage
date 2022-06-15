<template>
  <v-row>
    <v-col cols="12">
      <v-toolbar height="150" color="#1363DF">
        <v-toolbar-title class="text-h5 white--text ml-6 mt-2">
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
          <FullCalendar
            :options="calendarOptions"
            v-if="dateshow"
            ref="calendar"
          />
        </v-card-text>
      </v-card>

      <!-- <caa /> -->
    </v-col>
  </v-row>
</template>

<script >
import axios from 'axios'
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
      doctor: '',
      hn: '1/49 -test ',
      idcard: '3999999000000',
      datetime_check: '',
      message: '',
      calendarOptions: {
        plugins: [resourceTimelinePlugin, interactionPlugin],
        initialView: 'resourceTimeline',
        // nowIndicator: true,// แสดงเส่นแดงเวลาปัจจุบัน
        editable: true,
        timeZone: 'local',
        locales: thLocale,
        locale: 'th',
        aspectRatio: 2, //ความสูง
        // contentHeight: 600,
        // contentHeight: 'auto',

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
        //เลือก event ที่มีอยู่แล้ว
        eventClick: this.eventClick,

        // โชว์แสดง เมนูข้างบน
        headerToolbar: {
          start: 'title',
          center: '',
          end: '', //ปุ่ม
          // end: 'resourceTimelineDay', //ปุ่ม
        },
        // views: {
        //   resourceTimelineDay: {
        //     buttonText: 'แสดงเฉพาะวันที่ปัจจุบัน', //เปลี่ยนชื่อปุ่ม
        //     slotDuration: '00:15',
        //   },
        // },
        slotMinWidth: 10, //กว้าง
        //กำหนด ให้โชว์เฉพาะวันปัจจุบัน
        visibleRange: {
          start: '',
          end: '',
        },
        resourceOrder: 'doctor_id', //เรียงตาม
        resourceAreaColumns: [
          // {
          //   field: 'id',
          //   headerContent: 'รายชื่อเจ้าหน้าที่นวด',
          // },
          {
            field: 'title',
            headerContent: 'สถานะการทำงาน',
          },
        ],
        events: [
          // {
          //   resourceId: '1',
          //   id: '1111',
          //   title: 'ทดสอบ',
          //   start: '2022-06-13 08:30:00',
          //   end: '2022-06-13 09:40:00',
          // },
        ],

        resources: [
          // { id: '1', title: '1111' },
          // { id: '2', title: '2222' },
        ],
      },
    }
  },
  // beforeMount() {
  //   this.fecth_doctor()
  // },
  mounted() {
    this.dateshow = true
    this.calendarOptions.visibleRange.start = this.dateuse
    this.calendarOptions.visibleRange.end = this.dateuse
    this.fecth_doctor()
    this.fecth_event()
  },
  components: {
    FullCalendar,
  },

  created() {
    this.timer = setInterval(this.refresh_settimeout, 5000)
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
      console.log(info)
      //   start,
      // end,
      // startStr,
      // endStr,
      // allDay,
      // jsEvent,
      // view,
      // resource

      // alert(moment(info.start).locale('th').format('HH:mm:ss'))
      // alert(moment(info.end).locale('th').format('HH:mm:ss'))
      //alert(moment(info.start).locale('th').format('Y-M-D HH:mm:ss'))
      //ตรวจสอบก่อนว่ามีการจองในช่วงเวลานี้แล้วยัง
      this.check_event(
        moment(info.start).locale('th').format('Y-M-D HH:mm:ss'),
        info.resource.id
      )
      //คืนค่ามาเป็น this.datetime_check จาก function check_event ส่งค่า เวลากับ id คนนวดไปเช็ค
      if (!this.datetime_check) {
        if (
          moment(info.start).locale('th').format('HH:mm:ss') != '12:00:00'
          //  &&
          // moment().locale('th').format('HH:mm:ss') <=
          // moment(info.start).locale('th').format('HH:mm:ss')
        ) {
          // alert('ไม่ได้')
          //console.log(info.resource)
          this.$swal({
            title: 'การจองสิทธินวด',
            text:
              'ท่านต้องการจองสิทธิช่วงเวลา' +
              moment(info.start).locale('th').format('HH:mm') +
              ' น ' +
              ' ถึง ' +
              moment(info.end).locale('th').format('HH:mm') +
              ' น หรือไม่',
            footer:
              '<h3>' +
              'HN' +
              ' : ' +
              this.hn +
              '   ' +
              'เลขบัตรประชาชน' +
              ' : ' +
              this.idcard +
              '</h3>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'จองสิทธิ',
            confirmButtonColor: '#069A8E',
            cancelButtonText: 'ยกเลิก',
            cancelButtonColor: '#d33',
          }).then((result) => {
            if (result.isConfirmed) {
              // alert('title:' + this.hn)
              // alert(
              //   'start:' +
              //     moment(info.start).locale('th').format('Y-M-D HH:mm:ss')
              // )
              // alert(
              //   'end:' + moment(info.end).locale('th').format('Y-M-D HH:mm:ss')
              // )
              // alert('resourceid:' + info.resource.id)
              // alert('idcard:' + this.idcard)
              // alert(JSON.stringify(info))
              // this.event_add_data()

              axios
                .post(`${this.$axios.defaults.baseURL}event_add.php`, {
                  hn: this.hn,
                  start: moment(info.start)
                    .locale('th')
                    .format('Y-M-D HH:mm:ss'),
                  end: moment(info.end).locale('th').format('Y-M-D HH:mm:ss'),
                  resourceid: info.resource.id,
                  idcard: this.idcard,
                  state: '1', //สถานะ 1 ปกติ 2 ลบ
                })
                .then((response) => {
                  this.message = response.data
                  if (this.message[0].message === 'เพิ่มข้อมูลสำเร็จ') {
                    this.$swal({
                      title: 'สถานะการเพิ่ม',
                      text: this.message[0].message,
                      icon: 'success',
                      confirmButtonText: 'ตกลง',
                    })
                    this.fecth_event()
                  } else {
                    this.$swal({
                      title: 'สถานะการเพิ่ม',
                      text: this.message[0].message,
                      icon: 'error',
                      confirmButtonText: 'ตกลง',
                    })
                  }
                })
            }
          })
        } else {
          this.$swal({
            title: 'แจ้งเตือน',
            text: 'ไม่สามรถนัดได้ในเวลา 12.00 น หรือ เวลาปัจจุบันเกินช่วงเวลาที่จะนัด',
            icon: 'error',
            confirmButtonText: 'ตกลง',
          })
        }
      } else {
        this.$swal({
          title: 'แจ้งเตือน',
          text: 'ช่วงเวลานี้มีการนัดแล้ว',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      }
    },

    eventClick: function (info) {
      // alert(info.event.title)

      alert(moment(info.event.start).locale('th').format('HH:mm'))
      //alert(JSON.stringify(info.event))
      // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY)
      // alert('View: ' + info.view.type)
    },
    //doctor
    async fecth_doctor() {
      await axios
        .get(`${this.$axios.defaults.baseURL}doctor.php`)
        .then((response) => {
          this.calendarOptions.resources = response.data
        })
    },
    //event
    async fecth_event() {
      await axios
        .get(`${this.$axios.defaults.baseURL}event.php`)
        .then((response) => {
          this.calendarOptions.events = response.data
        })
    },
    // refresh calendar
    refresh_settimeout() {
      // setInterval(this.fecth_event(), 15000)
      this.fecth_event()
    },
    async check_event(datetime, id) {
      // alert(time)
      // alert(id)
      await axios
        .post(`${this.$axios.defaults.baseURL}check_event_duplicate.php`, {
          datetime: datetime,
          id: id,
        })
        .then((response) => {
          this.datetime_check = response.data[0].id
          // alert(this.datetime_check)
        })
    },
  },
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
