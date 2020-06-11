<template>
    <form class="form-horizontal" @submit.prevent="submit()">
        <div class="alert alert-success" role="alert" v-if="isSubmitted">
            {{ submitSucess }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-group row" v-if="user.department != null">
            <label  class="col-sm-4 col-form-label">Khoa</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" disabled :value="user.department.name">
            </div>
        </div>
        <div class="form-group row">
            <label for="username" class="col-sm-4 col-form-label">Tên người dùng</label>
            <div class="col-sm-8">
                <input type="text" id="username" name="username" autocomplete="off" required class="form-control" placeholder="Họ tên" v-model="form.name">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" name="email" disabled required id="inputEmail3" placeholder="Email" :value="form.email">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Mật khẩu cũ</label>
            <div class="col-sm-8">
                <input type="password" name="current-password"  autocomplete="off" class="form-control"
                       v-bind:class="{'is-valid' : isValidOldPassword && oldPasswordMsg, 'is-invalid' : !isValidOldPassword && oldPasswordMsg}"
                       required id="inputPassword3"
                       v-on:input="checkCurrentPassword" placeholder="Password" v-model="form.oldPassword">
            </div>
        </div>
        <div class="form-group row">
            <label for="new-password" class="col-sm-4 col-form-label">Mật khẩu mới</label>
            <div class="col-sm-8">
                <input type="password" name="new-password" class="form-control" autocomplete="off" required id='new-password' placeholder="Password" v-model="form.newPassword">
            </div>
        </div>
        <div class="form-group row">
            <label for="confirmPassword" class="col-sm-4 col-form-label">Xác nhận mật khẩu mới</label>
            <div class="col-sm-8">
                <input type="password"  class="form-control" required  autocomplete="off" id="confirmPassword" placeholder="Password"
                       v-bind:class="{'is-invalid': invalidNewPassword && form.confirmPassword}"
                       v-model="form.confirmPassword" v-on:input="validatePassword">
                <span class="text-danger" v-if="invalidNewPassword && form.confirmPassword">{{ invalidNewPasswordMsg }}</span>
            </div>
        </div>
        <button type="submit" class="btn btn-info float-right">Tạo người dùng</button>
    </form>
</template>

<script>

    moment.locale('vi')
    const FORM_ACTION = '/users'

    export default {
        props: {
            user : {
                required: true
            }
        },
        data() {
            return {
                form: {
                    name: this.user.name,
                    email: this.user.email,
                    oldPassword: this.user.password,
                    newPassword: null,
                    confirmPassword: null,
                    departmentId: this.user.department_id,
                },
                isValidOldPassword: null,
                oldPasswordMsg: '',
                invalidNewPassword: true,
                invalidNewPasswordMsg: 'Mật khẩu xác nhận không khớp',
                isSubmitted: false,
            }
        },
        methods: {
            checkCurrentPassword: _.debounce(async function(){
                this.oldPasswordMsg = ''
                if (this.form.email == '') {
                    return
                }
                let url = '/users/checkPassword'
                let result = await axios.post(url, this.form)
                this.isValidOldPassword = result.data.status
                this.oldPasswordMsg = result.data.message
                this.clearCheckPassword = false
            }, 500),
            async submit() {
                if (this.invalidNewPassword || this.form.isValidOldPassword) {
                    return
                }
                let url = '/users/' + this.user.id
                let result = axios.put(url, this.form)
                this.isSubmitted = true
            },
            validatePassword: _.debounce(function(){
                this.invalidNewPassword = true
                if (this.form.newPassword === this.form.confirmPassword) {
                    this.invalidNewPassword = false
                    return
                }
            }, 500)
        },
        computed: {
            submitSucess: function() {
                return 'Đã tạo thành công'
            }
        },
        created() {
        }
    }
</script>