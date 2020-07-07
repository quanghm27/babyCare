<template>
    <form @submit.prevent="submit()">
        <div class="alert alert-success" role="alert" v-if="isSubmitted">
            {{ submitSucess }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-group row">
            <div class="col-4">
                <label for="sharekey">Mã chia sẻ</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-code"></i></span>
                    </div>
                    <input :disabled="!edit" id="sharekey" type="text" v-bind:class="{'is-invalid' : invalidShareKey, 'is-valid': validShareKey}" class="form-control" required autocomplete="off"
                           v-model="form.sharekey" @keyup="validateShareKey()" @keydown="clearValidateShareKey()">
                    <span class="text-danger" v-if="invalidShareKey">{{ shareKeyError }}</span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-3">
                <label for="roomNo">Số phòng</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-hospital-o"></i></span>
                    </div>
                    <input :disabled="!edit" id="roomNo" type="number" class="form-control" v-model="form.roomNo" required autocomplete="off">
                </div>
            </div>
            <div class="col-3">
                <label for="bedNo">Số giường</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-bed"></i></span>
                    </div>
                    <input :disabled="!edit" id="bedNo" type="number" class="form-control" v-model="form.bedNo" required autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-7">
                <label for="name">Họ Tên</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input :disabled="!edit" id="name" type="text"class="form-control" v-model="form.name" required autocomplete="off">
                </div>
            </div>
            <div class="col-5">
                <label for="phoneNo">Số điện thoại</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                    <input :disabled="!edit" id="phoneNo" type="text" class="form-control" v-mask="'###-###-####'" v-model="form.phone_number" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-7">
                <label>Ngày nhập viện</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input :disabled="!edit" type="text" v-mask="'##/##/#### ##:##'" class="form-control" v-model="form.joinDate">
                </div>
            </div>
            <div class="col-5">
                <label for="meidcalId">Mã y tế</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-medkit"></i></span>
                    </div>
                    <input :disabled="!edit" id="meidcalId" type="text" class="form-control" v-model="form.medical_number" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-5">
            <label>Khoảng nhiệt độ an toàn</label>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info">Thấp nhất</button>
                    </div>
                    <input :disabled="!edit" type="text" class="form-control" v-mask="'##°C'" v-model="form.minTemp">
                </div>
            </div>
            <div class="col-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-danger">Cao nhất</button>
                    </div>
                    <input :disabled="!edit" type="text" class="form-control" v-mask="'##°C'" v-model="form.maxTemp">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12">
                <label>Ghi chú</label>
                <textarea :disabled="!edit" class="form-control" rows="3" placeholder="Nhập ghi chú ..." v-model="form.note"></textarea>
            </div>
        </div>
        <input type="hidden" v-model="form.deviceUserId"/>
        <input type="hidden" v-model="form.expiredTime"/>
        <div class="form-group"  v-if="edit">
            <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
        </div>
    </form>
</template>
<script>

moment.locale('vi')
const URL_VALIDATE_SHAREKEY = 'http://bbc-api.gl-sci.tech/api/Common/GetShareKey/'
const URL_CHECK_EXIST_SHAREKEY = '/checkEditShareKey'
const FORM_ACTION = '/patients/'

export default {
    props: ['patient', 'edit'],
    data() {
        return {
            form: {
                name: this.patient.name,
                phone_number: this.patient.phone_number,
                sharekey: this.patient.sharekey,
                medical_number: this.patient.medical_number,
                bedNo: this.patient.bedNo,
                roomNo: this.patient.roomNo,
                joinDate: moment(this.patient.joinDate).format('DD/MM/YYYY HH:mm'),
                minTemp: this.patient.minTemp,
                maxTemp: this.patient.maxTemp,
                note: this.patient.note,
                deviceUserId: this.patient.deviceUserId,
                expiredTime: this.patient.expiredTime
            },
            invalidShareKey: false,
            validShareKey: false,
            shareKeyError: '',
            isSubmitted: false,
        }
    },
    methods: {
        async validateShareKey() {
            if (!this.form.sharekey) {
                return
            }
            let key = this.form.sharekey.trim()

            // Check key valid on main system
            let url = URL_VALIDATE_SHAREKEY + key
            let result = await axios.get(url)
            if (!result.data.status && result.data.message == 'SHAREKEY_INVALID') {
                this.invalidShareKey = true
                this.shareKeyError = 'Không tồn tại mã này'
                return
            }
            let deviceUserId = result.data.data.deviceUserID

            // Check sharekey belong to patient
            if (deviceUserId !== this.form.deviceUserId) {
                this.invalidShareKey = true
                this.shareKeyError = 'Mã chia sẻ thuộc về hồ sơ khác'
                return
            }

            this.form.expiredTime = result.data.data.expiredTime
            let params = {
                deviceUserId: deviceUserId,
                shareKey: key
            }

            // Check profile exist
            let profile = await axios.get(URL_CHECK_EXIST_SHAREKEY, {params})
            if (!profile.data.status) {
                this.invalidShareKey = true
                this.shareKeyError = profile.data.message
                return
            }
            this.validShareKey = true
        },
        clearValidateShareKey() {``
            this.invalidShareKey = false
            this.shareKeyError = ''
        },
        async submit() {
            if (this.invalidShareKey) {
                return
            }
            // unmask before submit
            let params = {...this.form}
            // unmask before submit
            params.minTemp = _.replace(this.form.minTemp, '°C', '')
            params.maxTemp = _.replace(this.form.maxTemp, '°C', '')
            params.phoneNo = _.replace(this.form.phoneNo, /-/g, '')
            params.joinDate = moment(this.form.joinDate, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm')

            let result = await axios.put(FORM_ACTION + this.patient.id, params)
            if (result.data.status) {
                this.isSubmitted = true
            }
        }
    },
    computed: {
        submitSucess: function() {
            return 'Cập nhât hồ sơ thành công'
        }
    }
}
</script>
<style>

</style>