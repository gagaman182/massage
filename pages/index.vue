<template>
  <v-row>
    <v-col cols="12">
      <v-card outlined>
        <v-card-text>
          <v-toolbar height="100" color="#577BC1" class="white--text">
            <v-icon class="icons"  color="white">
              mdi-microsoft-excel
            </v-icon>
            <v-toolbar-title >
               ระบบจองบริการแพทย์แผนไทย บริการนวด เพื่อการบำบัดรักษาโรค
            </v-toolbar-title>
           </v-toolbar>
        </v-card-text>
     </v-card>
    </v-col>
    <v-col cols="12" >
      <v-card outlined>
        <v-card-text>
        <v-alert border="left" colored-border color="#EEE6CE" elevation="2">
          <div class="d-flex flex-row">
            <h3 class="font-weight-black subhead mr-4 pt-2">วันที่เปิดให้จอง</h3>
            <v-chip color="#8FBDD3" text-color="white" class="mt-0">
              <h3 class="font-weight-black ">{{ opddate_now }}</h3>
            </v-chip>
            
          </div>
        </v-alert>
        <v-alert border="left" colored-border color="#577BC1" elevation="2">
             <v-icon class="icons mr-2"  >
             mdi-account-voice 
            </v-icon>
            <!-- <h3 class="font-weight-black subhead">วันที่ออกปฏิบัติงาน</h3> -->
              <u>ขั้นตอนการจอง</u></br>
              <p class="ml-9 mb-0 mt-0 pt-0 pb-0">สามารถเลือกช่วงเวลาที่ต้องการจองแล้วตอบตกลงเพื่อยืนยันการจอง</p></br>
              <p class="ml-9 mb-0 mt-0 pt-0 pb-0">สามารถจองนวดได้ในวันปัจจุบันเท่านั้น</p></br>
              <p class="ml-9 mb-0 mt-0 pt-0 pb-0 red--text">ไม่สามารถจองในเวลาเที่ยง และต้องจองล่วงหน้าก่อนเวลานวด </p></br>
        </v-alert>
        </v-card-text>
      </v-card>
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
    </v-col>
    <v-row>
      <v-dialog v-model="dialog_process" hide-overlay persistent width="300">
        <v-card color="#C499BA" dark>
          <v-card-text>
            กำลังโหลดข้อมูล...
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card> </v-dialog
    ></v-row>
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
      opddate_now: new Date().toLocaleDateString('th-TH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long',
      }),
      dateuse: new Date().toISOString().substr(0, 10),
      dateshow: false,
      doctor: '',
      hn: '1/49 -test ',
      idcard: '3999999000000',
      id_delete: '',
      datetime_check: '',
      message: '',
      dialog_process: false,
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
          //   //title: 'ทดสอบ',
          //   title: 'Rs',
          //   start: '2022-06-18 08:30:00',
          //   end: '2022-06-18 16:40:00',
          //   // backgroundColor: 'red',
          //   // borderColor: 'black',
          //   // textColor: 'blue',
          //   // color: 'black',
          // },
        ],
        eventContent: function (arg) {
          console.log(arg)

          // let italicEl = document.createElement('i')
          // if (arg.event.extendedProps.isUrgent) {
          //   italicEl.innerHTML = 'urgent event'
          // } else {
          //   italicEl.innerHTML = 'normal event'
          // }
          // let arrayOfDomNodes = [italicEl]
          // return { domNodes: arrayOfDomNodes }
        },
        // eventClassNames: function (arg) {
        //   console.log(arg)
        //   if (arg.event.extendedProps.isUrgent) {
        //     return ['urgent']
        //   } else {
        //     return ['normal']
        //   }
        // },
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
    this.dialog_process = true
    this.calendarOptions.visibleRange.start = this.dateuse
    this.calendarOptions.visibleRange.end = this.dateuse
    this.fecth_doctor()

    this.fecth_event()
    this.dialog_process = false
  },
  components: {
    FullCalendar,
  },

  created() {
    this.timer = setInterval(this.refresh_settimeout, 5000)
  },
  methods: {
    //เลือกช่องว่าง
    clickevent: function (arg) {
      //alert(moment(arg.dateStr).locale('th').format('Y-M-D HH:mm:ss'))
      console.log(arg)

      if (moment(arg.dateStr).locale('th').format('HH:mm:ss') == '12:00:00') {
        alert('ไม่ได้')
      }
    },
    //เลือกช่องว่าง
    selectevent: function (info) {
      this.dialog_process = true
      // console.log(info)
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
      // this.check_event(
      //   moment(info.start).locale('th').format('Y-M-D HH:mm:ss'),
      //   info.resource.id
      // )
      // alert(moment(info.start).locale('th').format('Y-M-D HH:mm:ss'))
      // alert(moment(info.end).locale('th').format('Y-M-D HH:mm:ss'))
      // alert(info.resource.id)

      this.datetime_check = ''
      axios
        .post(`${this.$axios.defaults.baseURL}check_event_duplicate.php`, {
          starttime: moment(info.start).locale('th').format('Y-M-D HH:mm:ss'),
          endtime: moment(info.end).locale('th').format('Y-M-D HH:mm:ss'),
          id: info.resource.id,
          state: '1',
        })
        .then((response) => {
          // alert(JSON.stringify(response.data))
          // คืนค่ามาเป็น this.datetime_check จาก function check_event ส่งค่า เวลากับ id คนนวดไปเช็ค
          // เช็คว่ามีการจองแล้วหรือยัง
          if (response.data == '') {
            if (
              // check เวลาเที่ยง ไม่ให้จอง
              moment(info.start).locale('th').format('HH:mm:ss') !=
                '12:00:00' &&
              //check ว่าถ้าเกินเวลามาแล้วไม่รับ
              moment().locale('th').format('HH:mm:ss') <=
                moment(info.start).locale('th').format('HH:mm:ss')
            ) {
              this.datetime_check = ''
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
                showCloseButton: true,
              }).then((result) => {
                if (result.isConfirmed) {
                  axios
                    .post(`${this.$axios.defaults.baseURL}event_add.php`, {
                      hn: this.hn,
                      start: moment(info.start)
                        .locale('th')
                        .format('Y-M-D HH:mm:ss'),
                      end: moment(info.end)
                        .locale('th')
                        .format('Y-M-D HH:mm:ss'),
                      resourceid: info.resource.id,
                      idcard: this.idcard,
                      state: '1', //สถานะ 1 ปกติ 2 ลบ
                    })
                    .then((response) => {
                      this.message = response.data
                      if (this.message[0].message === 'เพิ่มข้อมูลสำเร็จ') {
                        this.$swal({
                          title: 'สถานะการจอง',
                          text: จองสำเร็จ,
                          icon: 'success',
                          confirmButtonText: 'ปิด',
                          confirmButtonColor: '#47B5FF',
                        })
                        this.fecth_event()
                        this.dialog_process = false
                      } else {
                        this.$swal({
                          title: 'สถานะการจอง',
                          text: ไม่สามารถจองได้,
                          icon: 'error',
                          confirmButtonText: 'ปิด',
                          confirmButtonColor: '#47B5FF',
                        })
                      }
                    })
                }
              })
            } else {
              this.$swal({
                title: 'แจ้งเตือน',
                text: 'ไม่สามรถจองได้เนื่องจากเวลาปัจจุบันเกินช่วงเวลาที่จะจองหรือมีการจองในเวลาพักเที่ยง',
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#47B5FF',
              })
              this.datetime_check = ''
              this.dialog_process = false
            }
          } else {
            this.$swal({
              title: 'แจ้งเตือน',
              text: 'ช่วงเวลานี้มีการจองแล้ว',
              icon: 'error',
              confirmButtonText: 'ตกลง',
              confirmButtonColor: '#47B5FF',
            })
            this.datetime_check = ''
            this.dialog_process = false
          }
        })
    },
    //เลือกรายการที่มีอยู่แล้วมาแสดง
    eventClick: function (info) {
      // alert(info.event.title)

      // alert(moment(info.event.start).locale('th').format('HH:mm'))
      // alert(JSON.stringify(info.event))
      this.dialog_process = true
      axios
        .post(`${this.$axios.defaults.baseURL}check_id_delete.php`, {
          id: info.event.id,
        })
        .then((response) => {
          this.id_delete = response.data

          // alert(id_delete[0].idcard)
          // alert(id_delete[0].hn)

          if (
            this.id_delete[0].idcard == this.idcard &&
            this.id_delete[0].hn == this.hn
          ) {
            this.$swal({
              title: '<strong>สถานะ <u>จองแล้ว</u></strong>',
              text:
                'ช่วงเวลา ' +
                moment(info.event.start).locale('th').format('HH:mm') +
                '-' +
                moment(info.event.end).locale('th').format('HH:mm') +
                ' ' +
                'ท่านต้องการจะลบหรือไม่',
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
              confirmButtonText: 'เลิกจอง',
              confirmButtonColor: '#47B5FF',
              cancelButtonText: 'ยกเลิก',
              cancelButtonColor: '#d33',
              showCloseButton: true,
            }).then((result) => {
              if (result.isConfirmed) {
                axios
                  .put(`${this.$axios.defaults.baseURL}event_delete.php`, {
                    id: info.event.id,
                    hn: this.hn,
                  })
                  .then((response) => {
                    this.message = response.data
                    if (this.message[0].message === 'แก้ไขข้อมูลสำเร็จ') {
                      this.$swal({
                        title: 'สถานะการยกเลิก',
                        text: 'ยกเลิกการจองสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#47B5FF',
                      })
                      this.fecth_event()
                      this.dialog_process = false
                    } else {
                      this.$swal({
                        title: 'สถานะการเพิ่ม',
                        text: this.message[0].message,
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#47B5FF',
                      })
                      this.dialog_process = false
                    }
                  })
              }
            })
          } else {
            // alert(info.event.id)
            this.$swal({
              title: '<strong>สถานะ <u>จองแล้ว</u></strong>',
              text:
                'ช่วงเวลา ' +
                moment(info.event.start).locale('th').format('HH:mm') +
                '-' +
                moment(info.event.end).locale('th').format('HH:mm'),

              icon: 'info',
              confirmButtonText: 'ตกลง',
              confirmButtonColor: '#47B5FF',
              showCloseButton: true,
            })
            this.dialog_process = false
          }
        })
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
    // checkว่าในช่วงเวลานี้มีการจองไว้แล้วยัง
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
          alert(this.datetime_check)
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
