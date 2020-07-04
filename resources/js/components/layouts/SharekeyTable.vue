<template>
    <div>
        <table class="table table-hover text-nowrap">
            <thead>
            <tr>
                <th></th>
                <th>Tên bệnh nhân</th>
                <th>Mã y tế</th>
                <th>Phòng bệnh</th>
                <th>Mã chia sẻ</th>
                <th>Ngày nhập viện</th>
                <th>Ngày xuất viện</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in sharekeyList">
                <td style="width: 5%">
                    <div style="display: inline-block;">
                        <a class="text-gray text-bold":href="item.detailAction" v-if="item.releaseDate">Chi tiết</a>
                        <a class="text-primary text-bold":href="item.editAction" v-else>Chỉnh sửa</a>
                    </div>
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.medical_number }}</td>
                <td>{{ item.roomNo}}</td>
                <td>
                    <div>{{ item.sharekey }}
                        <span class="badge badge-success" v-show="!item.isExpired"> {{ 'Còn hạn (' + item.expiredTime + ')'}}</span>
                        <span class="badge badge-danger" v-show="item.isExpired">Hết hạn</span>
                    </div>
                </td>
                <td>{{ item.joinDate }}</td>
                <td>
                    <div v-if="item.releaseDate != null">
                        {{item.releaseDate}}
                    </div>
                    <div v-else>
                        ---
                    </div>
                </td>
                <td>
                    <div v-if="item.releaseDate != null">
                        <a href="#" class="text-danger" @click="showDialogRevertRealse(item.id)">Đã xuất viện</a>
                    </div>
                    <div v-else>
                        <!--<a href="#" class="text-danger" data-toggle="modal" data-target="#notReleaseModal">Chưa xuất viện</a>-->
                        <a href="#" class="text-danger" @click="showDialogRealse(item.id)">Chưa xuất viện</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="revertReleaseModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quản lý bệnh nhân</h5>
                    </div>
                    <div class="modal-body">
                        Bệnh nhân chưa xuất viện?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="revertRelease()">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quản lý bệnh nhân</h5>
                    </div>
                    <div class="modal-body">
                        Xác nhận ngày bệnh nhân xuất viện?
                        <div class="form-group row mt-3">
                            <div class="col-7">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <datetime format="DD/MM/YYYY H:i" v-model="releaseDatetime" ></datetime>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox"  id="remember" v-model="isReturnDevice">
                                    <label for="remember" class="form-check-label">
                                        Trả thiết bị?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Ngày giờ, checkbox-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" :disabled="!isReturnDevice" @click="release()">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import datetime from 'vuejs-datetimepicker'

    export default {
        props: ['sharekeys'],
        components: {
            datetime
        },
        data() {
            return {
                isReturnDevice: false,
                releaseDatetime: moment().format('DD/MM/YYYY HH:mm'),
                sharekeyList: this.sharekeys,
            }
        },
        created() {
            this.sharekeyList.map(function(item) {
                item.editAction = '/sharekeys/' + item.sharekey + '/edit';
                item.detailAction = '/sharekeys/' + item.sharekey + '/detail';
                item.isExpired = moment(item.expiredTime).isBefore(moment())
                item.expiredTime = moment(item.expiredTime).fromNow()
                item.joinDate = moment(item.joinDate).format('DD/MM/YYYY HH:mm')
                item.releaseDate = item.releaseDate ? moment(item.releaseDate).format('DD/MM/YYYY HH:mm') : null
                item.updated_at = moment(item.updated_at).format('DD/MM/YYYY HH:mm')
                item.created_at = moment(item.created_at).format('DD/MM/YYYY HH:mm')
                return item;
            })
        },
        mounted() {

        },
        methods: {
            showDialogRealse(sharekeyId) {
                $('#releaseModal').modal('show')
                $('#releaseModal').data('sharekey-id', sharekeyId)
                let now = moment().format('DD/MM/YYYY HH:mm')
                $('#releaseTime').val(now)
            },
            showDialogRevertRealse(sharekeyId) {
                $('#revertReleaseModal').modal('show')
                $('#revertReleaseModal').data('sharekey-id', sharekeyId)
            },
            async release() {
                let id = $('#releaseModal').data('sharekey-id')
                let url = '/release'
                let releaseTime = moment(this.releaseDatetime, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm')
                let params = {id: id, releaseTime: releaseTime}
                let result = await axios.post(url, params)
                if (result.data.status) {
                    location.reload()
                }
            },
            async revertRelease() {
                let id = $('#revertReleaseModal').data('sharekey-id')
                let url = '/release'
                let params = {id: id}
                let result = await axios.post(url, params)
                if (result.data.status) {
                    location.reload()
                }
            },
            formatDate() {
                return moment().format('DD/MM/YYYY HH:mm')
            },
            async okCallback(dialog, id) {
                let url = '/release'
                let params = {id: id}
                let result = await axios.post(url, params)
                if (result.data.status) {
                    dialog.close()
                    location.reload()
                }
            },
            cancelcallback() {
                console.log('Clicked on cancel');
            }
        }
    }
</script>