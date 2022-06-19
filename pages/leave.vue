<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-card outlined>
          <v-card-text>
            <h2>
              <v-icon medium class="mr-2">mdi-calendar-range </v-icon
              >รายการวันหยุด
            </h2>
            <h4 v-if="btneditandremove" class="red--text">ไอดี: {{ id }}</h4>
            <v-divider class="mt-4 mb-4" />
            <v-row>
              <v-col cols="12" sm="6">
                <v-autocomplete
                  v-if="textboxshow"
                  v-model="doctor"
                  :items="doctors"
                  item-text="title"
                  item-value="id"
                  dense
                  outlined
                  label="เจ้าหน้าที่"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" sm="6">
                <!-- <v-text-field
                  v-model="code"
                  label="รหัสUSER PMK"
                  outlined
                  v-if="textboxshow"
                ></v-text-field> -->
                <v-menu
                  v-if="textboxshow"
                  v-model="menu"
                  :close-on-content-click="true"
                  max-width="290"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      solo
                      :value="computedDateFormattedMomentjs"
                      clearable
                      outlined
                      dense
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      @click:clear="leavedate = null"
                    ></v-text-field>
                    <!-- clearable ปุ่ม กากบาท -->
                  </template>
                  <v-date-picker locale="th-TH" v-model="leavedate">
                    <!-- @change="menu = false" กรณีไม่ใช้ปุ่มกด ใส่ใน v-date-picker -->
                    <v-spacer></v-spacer>

                    <!-- <v-btn text color="#6A67CE" @click="menu = false">
                      ตกลง
                    </v-btn> -->
                  </v-date-picker>
                </v-menu>
              </v-col>

              <v-col cols="12" align="end">
                <v-btn
                  large
                  v-if="btnadd"
                  class="red--text text--lighten-5"
                  color="teal lighten-1"
                  flat
                  @click="adddata()"
                >
                  <v-icon medium>mdi-content-save </v-icon>บันทึก</v-btn
                >
                <v-btn
                  large
                  v-if="btneditandremove"
                  color="#0e49b5"
                  @click="updatedata()"
                  dark
                >
                  <v-icon medium>mdi-content-save-edit </v-icon>
                  <h4>แก้ไข</h4></v-btn
                >
                <v-btn
                  large
                  v-if="btneditandremove"
                  color="#c56183"
                  @click="removedata()"
                  dark
                >
                  <v-icon medium>mdi-delete-forever </v-icon>
                  <h4>ลบ</h4></v-btn
                >
                <v-btn
                  large
                  v-if="btncancel"
                  color="#b088f9"
                  class="red--text text--lighten-5"
                  flat
                  @click="cleartext"
                >
                  <v-icon medium>mdi-autorenew </v-icon>ยกเลิก</v-btn
                >
              </v-col></v-row
            >
            <v-row>
              <v-col cols="12" align="end">
                <v-btn
                  large
                  v-if="btnmain"
                  class="red--text text--lighten-5"
                  color="#7579e7"
                  flat
                  @click="showadd"
                >
                  <v-icon medium>mdi-file-plus-outline </v-icon>เพิ่ม</v-btn
                >
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" align="end">
                <v-toolbar card>
                  <v-text-field
                    v-model="search"
                    flat
                    solo
                    prepend-icon="mdi-home-search-outline "
                    placeholder="ค้นหา"
                    hide-details
                    class="hidden-sm-and-down"
                  ></v-text-field>
                </v-toolbar>
              </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
              <v-col cols="12">
                <v-data-table
                  height="50vh"
                  v-model="selected"
                  :headers="headers"
                  :search="search"
                  :items="items"
                  class="elevation-1 mousepointer"
                  @click:row="handleClick"
                >
                </v-data-table>
                {{ computedDateFormattedMomentjs }}
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import axios from 'axios'
import moment from 'moment'
export default {
  name: 'leave',
  data: () => ({
    search: '',

    selected: [],
    headers: [
      {
        text: '#',
        value: 'id',
        hidden: true,
      },

      // {
      //   text: 'ไอดี',
      //   value: 'resourceId',
      // },
      {
        text: 'เจ้าหน้าที่',
        value: 'title',
      },
      {
        text: 'วันที่หยุด',
        value: 'leavedate',
      },
    ],
    items: [],
    doctor: '',
    doctors: '',
    id: '',
    title: '',
    resourceId: '',
    leavedate: '',
    textboxshow: '',
    btneditandremove: false,
    btnmain: true,
    btnadd: false,
    btncancel: false,
  }),
  mounted() {
    this.fetch_leave_doctor()
    this.fetch_doctor()
  },
  computed: {
    computedDateFormattedMomentjs() {
      return this.leavedate ? moment(this.leavedate).format('DD-MM-Y') : ''
    },
  },
  methods: {
    //แสดงข้อมูลร้านค้า
    async fetch_leave_doctor() {
      await axios
        .get(`${this.$axios.defaults.baseURL}leave_doctor.php`)
        .then((response) => {
          this.items = response.data
        })
    },
    async fetch_doctor() {
      await axios
        .get(`${this.$axios.defaults.baseURL}doctor_list.php`)
        .then((response) => {
          this.doctors = response.data
        })
    },

    // click table
    handleClick(value) {
      // this.dialog = true
      // this.$emit('show-dialog', { dialog: this.dialog, storeid: value.storeid })

      this.id = value.id
      this.doctor = value.resourceId //เอารหัสมาแทน ใช้กับ autocomplete เปลี่ยนเป็นข้อความ
      this.resourceId = value.resourceId
      this.leavedate = value.leavedate

      this.btneditandremove = true
      this.textboxshow = true
      this.btncancel = true
      this.btnadd = false
      this.btnmain = false
    },
    //show textbox and btn
    showadd() {
      this.btnadd = true
      this.btncancel = true
      this.textboxshow = true
      this.btnmain = false
    },
    //เพิ่มข้อมูล
    adddata() {
      if (!this.doctor || !this.leavedate) {
        this.$swal({
          title: 'แจ้งเตือน',
          text: 'ระบุข้อมูลไม่ครบ',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } else {
        axios
          .post(`${this.$axios.defaults.baseURL}leave_doctor_add.php`, {
            // id: this.id,
            doctor: this.doctor,
            leavedate: this.leavedate,
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
              this.fetch_leave_doctor()
              this.cleartext()
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
    },
    //ลบ ข่อมูล
    removedata() {
      if (!this.id) {
        this.$swal({
          title: 'แจ้งเตือน',
          text: 'ไม่พบข้อมูล',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } else {
        this.$swal({
          title: 'คุณแน่ใจว่าต้องการลบข้อมูลนี้?',
          text: 'ไอดี: ' + this.id,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#51adcf',
          cancelButtonColor: '#686d76',
          confirmButtonText: 'ลบ',
          cancelButtonText: 'ยกเลิก',
        }).then((result) => {
          if (result.isConfirmed) {
            axios
              .put(`${this.$axios.defaults.baseURL}leave_doctor_delete.php`, {
                id: this.id,
              })
              .then((response) => {
                this.message = response.data
                if (this.message[0].message === 'ลบข้อมูลสำเร็จ') {
                  this.$swal({
                    title: 'สถานะการลบ',
                    text: this.message[0].message,
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                  })
                  this.fetch_leave_doctor()
                  this.cleartext()
                } else {
                  this.$swal({
                    title: 'สถานะการลบ',
                    text: this.message[0].message,
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                  })
                }
              })
          }
        })
      }
    },
    //แก้ไข ข้อมูล
    updatedata: function () {
      alert(this.leavedate)
      if (!this.id) {
        this.$swal({
          title: 'แจ้งเตือน',
          text: 'ไม่พบข้อมูล',
          icon: 'error',
          confirmButtonText: 'ตกลง',
        })
      } else {
        axios
          .put(`${this.$axios.defaults.baseURL}leave_doctor_update.php`, {
            id: this.id,
            resourceId: this.doctor,
            leavedate: this.leavedate,
          })
          .then((response) => {
            this.message = response.data
            if (this.message[0].message === 'แก้ไขข้อมูลสำเร็จ') {
              this.$swal({
                title: 'สถานะการแก้ไข',
                text: this.message[0].message,
                icon: 'success',
                confirmButtonText: 'ตกลง',
              })
              this.fetch_doctor()
              this.cleartext()
            } else {
              this.$swal({
                title: 'สถานะการแก้ไข',
                text: this.message[0].message,
                icon: 'error',
                confirmButtonText: 'ตกลง',
              })
            }
          })
      }
    },
    cleartext() {
      this.btnadd = false
      this.btncancel = false
      this.textboxshow = false
      this.btnmain = true
      this.btneditandremove = false
      this.id = ''
      this.doctor = ''
      this.leavedate = ''
      this.resourceId = ''
    },
    refresh_drugstore() {
      this.fetch_doctor()
    },
  },
}
</script>
<style scoped>
.mousepointer {
  cursor: pointer;
}
</style>