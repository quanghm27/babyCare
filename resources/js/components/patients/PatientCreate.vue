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
                    <input id="sharekey" type="text" v-bind:class="{'is-invalid' : invalidShareKey, 'is-valid': validShareKey}" class="form-control" required autocomplete="off"
                           v-model="form.shareKey" @keyup="validateShareKey()" @keydown="clearValidateShareKey()">
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
                    <input id="roomNo" type="number" class="form-control" v-model="form.roomNo" required autocomplete="off">
                </div>
            </div>
            <div class="col-3">
                <label for="bedNo">Số giường</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-bed"></i></span>
                    </div>
                    <input id="bedNo" type="number" class="form-control" v-model="form.bedNo" required autocomplete="off">
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
                    <input id="name" type="text"class="form-control" v-model="form.name" required autocomplete="off">
                </div>
            </div>
            <div class="col-5">
                <label for="phoneNo">Số điện thoại</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                    <input id="phoneNo" type="text" class="form-control" v-model="form.phoneNo" required autocomplete="off">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-4">
                <label>Ngày nhập viện</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control" v-mask="{mask: '99/99/9999'}" v-model="form.joinDate">
                </div>
            </div>
            <div class="col-6">
                <label for="meidcalId">Mã y tế</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-medkit"></i></span>
                    </div>
                    <input id="meidcalId" type="text" class="form-control" v-model="form.medicalNo" required autocomplete="off">
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
                    <input type="text" class="form-control" v-mask="'99°C'" v-model="form.minTemp">
                </div>
            </div>
            <div class="col-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-danger">Cao nhất</button>
                    </div>
                    <input type="text" class="form-control" v-mask="'99°C'" v-model="form.maxTemp">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12">
                <label>Ghi chú</label>
                <textarea class="form-control" rows="3" placeholder="Nhập ghi chú ..." v-model="form.note"></textarea>
            </div>
        </div>
        <input type="hidden" v-model="form.deviceUserId"/>
        <input type="hidden" v-model="form.expiredTime"/>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Tạo hồ sơ</button>
        </div>
    </form>
</template>
<script>

moment.locale('vi')
const URL_VALIDATE_SHAREKEY = 'http://bbc-api.gl-sci.tech/api/Common/GetShareKey/'
const URL_CHECK_EXIST_SHAREKEY = '/checkShareKey'
const FORM_ACTION = '/patients'

export default {
    data() {
        return {
            form: {
                name: '',
                phoneNo: '',
                shareKey: '',
                medicalNo: '',
                bedNo: '',
                roomNo: '',
                joinDate: moment().format('DD/MM/YYYY'),
                minTemp: 35,
                maxTemp: 38,
                note:'',
                deviceUserId: '',
                expiredTime:''
            },
            invalidShareKey: false,
            validShareKey: false,
            shareKeyError: '',
            isSubmitted: false,
        }
    },
    methods: {
        async validateShareKey() {
            if (!this.form.shareKey) {
                return
            }
            let key = this.form.shareKey.trim()

            // Check key valid on main system
            let url = URL_VALIDATE_SHAREKEY + key
            let result = await axios.get(url)
            if (!result.data.status && result.data.message == 'SHAREKEY_INVALID') {
                this.invalidShareKey = true
                this.shareKeyError = 'Không tồn tại mã này'
                return
            }
            let deviceUserId = result.data.data.deviceUserID
            let params = {
                deviceUserId: deviceUserId
            }

            // Check profile exist
            let profile = await axios.get(URL_CHECK_EXIST_SHAREKEY, {params})
            if (!profile.data.status) {
                this.invalidShareKey = true
                this.shareKeyError = profile.data.message
                return
            }

            this.form.deviceUserId = deviceUserId
            this.form.expiredTime = result.data.expiredTime
            this.form.avatar = result.data.data.imageUrl
            this.validShareKey = true
        },
        clearValidateShareKey() {
            this.invalidShareKey = false
            this.shareKeyError = ''
        },
        async submit() {
            if (this.invalidShareKey) {
                return
            }
            this.form.joinDate = moment(this.form.joinDate)
            let result = await axios.post(FORM_ACTION, this.form)
            if (result.data.status) {
                this.isSubmitted = true
            }
        }
    },
    computed: {
        submitSucess: function() {
            return 'Đã tạo hồ sơ thành công'
        }
    },
    fiters: {
        celsius: function (value) {
            return value + '°C';
        },
        percent: function (value) {
            return value + '%';
        }
    }
}
</script>
<style>

</style>