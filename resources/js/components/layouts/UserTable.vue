<template>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th></th>
            <th>Tên</th>
            <th>Tên đăng nhập</th>
            <th>Phân Loại</th>
            <th>Ngày tạo</th>
            <th>Ngày Cập nhật</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in userList">
            <td style="width: 5%">
                <div style="display: inline-block;">
                    <a :href="item.editAction">Chỉnh sửa</a>  |
                    <a href="#" class="text-danger" v-confirm="{
                        loader: true,
                        ok: dialog => deleteUser(dialog, item.id),
                        cancel: cancelcallback,
                        message: 'Xác nhận xóa khoa ?'}">Xóa
                    </a>
                </div>
            </td>
            <td><div>{{ item.name }}</div></td>
            <td><div>{{ item.email }}</div></td>
            <td>
                <span class="badge" v-bind:class="{'badge-success': item.role == 'operator',
                                                    'badge-danger': item.role == 'admin'
                 }">{{ item.role }}</span>
            </td>
            <td>{{ item.created_at }}</td>
            <td>{{ item.updated_at }}</td>
        </tr>
        </tbody>
    </table>
</template>
<script>
    export default {
        props: ['users'],
        data() {
            return {
                userList: this.users,
            }
        },
        created() {
            this.userList.map(function(item) {
                item.editAction = '/users/' + item.id + '/edit'
                item.updated_at = moment(item.updated_at).format('DD/MM/YYYY HH:mm')
                item.created_at = moment(item.created_at).format('DD/MM/YYYY HH:mm')
                item.role = item.roles[0].name
                return item;
            })
        },
        methods: {
            async deleteUser(dialog, id) {
                let url = '/users/' + id
                let result = await axios.delete(url)
                if (result.data.status) {
                    dialog.close()
                    location.reload()
                }
            },
            cancelcallback() {
                console.log('cancel')
                return
            }
        }
    }
</script>