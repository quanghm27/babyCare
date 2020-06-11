<template>
    <form class="form-horizontal" @submit.prevent="submit()">
        <div class="alert alert-success" role="alert" v-if="isSubmitted">
            {{ submitSucess }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-group row">
            <label for="department" class="col-sm-4 col-form-label">Khoa</label>
            <div class="col-sm-8">
                <select id="department" class="form-control" required v-model="form.departmentId">
                    <option value="-" class="form-control">---Hãy chọn một khoa---</option>
                    <option class="form-control" v-for="item in departments" :value="item.id">{{ item.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="username" class="col-sm-4 col-form-label">Tên người dùng</label>
            <div class="col-sm-8">
                <input type="text" id="username" name="username" required class="form-control" placeholder="Họ tên" v-model="form.name">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" v-bind:class="{'is-invalid': invalidEmail && form.email, 'is-valid': !invalidEmail && form.email}" name="email" required id="inputEmail3" placeholder="Email" v-model="form.email" v-on:input="validateEmail">
                <span class="text-danger" v-if="invalidEmail && emailMessage">{{ emailMessage }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Mật khẩu</label>
            <div class="col-sm-8">
                <input type="password" name="password" class="form-control" required id="inputPassword3" placeholder="Password" v-model="form.password">
            </div>
        </div>
        <div class="form-group row">
            <label for="confirmPassword" class="col-sm-4 col-form-label">Xác nhận mật khẩu</label>
            <div class="col-sm-8">
                <input type="password"  class="form-control" required id="confirmPassword" placeholder="Password" v-model="form.confirmPassword" v-on:input="validatePassword">
                <span class="text-danger" v-if="invalidPassword && form.confirmPassword">{{ invalidPasswordMsg }}</span>
            </div>
        </div>
        <button type="submit" class="btn btn-info float-right">Tạo người dùng</button>
    </form>
</template>

<script>

    moment.locale('vi')
    const FORM_ACTION = '/users'

    export default {
        data() {
            return {
                form: {
                    name: null,
                    email: null,
                    password: null,
                    confirmPassword: null,
                    departmentId: '-',
                },
                departments: [],
                invalidPassword: true,
                invalidPasswordMsg: 'Mật khẩu xác nhận không khớp',
                invalidEmail: true,
                emailMessage: '',
                isSubmitted: false,
            }
        },
        methods: {
            async getDepartment() {
                let url = '/api/departments'
                let result = await axios.get(url)
                if (result.data.status) {
                    this.departments = result.data.data
                }
            },
            async submit() {
                if (this.invalidPassword || this.form.departmentId == '-') {
                    return
                }
                let url = '/users'
                let result = axios.post(url, this.form)
                this.isSubmitted = true
            },
            validatePassword: _.debounce(function(){
                this.invalidPassword = true
                if (this.form.password === this.form.confirmPassword) {
                    this.invalidPassword = false
                    return
                }
            }, 500),
            validateEmail: _.debounce(async function(){
                this.invalidEmail = true
                const emailRegex = /^([A-Za-z0-9_\-.+])+@([A-Za-z0-9_\-.])+\.([A-Za-z]{2,})$/
                if (emailRegex.test(this.form.email)) {
                    this.invalidEmail = false
                }
                let url = '/users/checkEmail'
                let result = await axios.post(url, {email: this.form.email})
                this.invalidEmail = !result.data.status
                this.emailMessage = result.data.message
            }, 500)
        },
        computed: {
            submitSucess: function() {
                return 'Đã tạo thành công'
            }
        },
        created() {
            this.getDepartment()
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