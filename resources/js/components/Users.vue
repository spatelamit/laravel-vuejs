<template>
    <div class="container p5">
        <button type="button" class="btn btn-primary mb-4 float-right" data-toggle="modal" data-target="#myModal" @click="formReset"> new
            users</button>
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="text-sm text-danger" if="errors != ''">
                            <p v-for="error in errors" :key="error">
                                <small>{{ error }}</small>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="recipient-name" placeholder="Name"
                                v-model="form.name">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="recipient-email" placeholder="Email"
                                v-model="form.email">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button v-if="user_id==''" type="button" class="btn btn-success" @click="storeUser">Save</button>
                        <button v-else type="button" class="btn btn-info" @click="updateUser">Update</button>


                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>

            </div>
        </div>
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Last Updated</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="user in users" :key="user.id">
                    <tr>
                        <th scope="row">{{ user.id }}</th>
                        <th scope="row">{{ user.name }}</th>
                        <td>{{ user.email }}</td>
                        <td>{{ user.created_at }}</td>
                        <td>{{ user.updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal" @click="editUser(user)">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger ml-2" data-toggle="modal" @click="deleteUser(user.id)">Delete</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
<script>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
export default {
    setup() {
        const users = ref([]);
        const errors = ref([]);
        const user_id = ref([]);

        const form = reactive({
            name: '',
            email: ''
        });

        const getEmployee = async () => {
            let res = await axios.get('http://localhost:8000/api/users');
            users.value = res.data;
            // console.log(users.value);
        };

        const storeUser = async () => {
            try {
                await axios.post('http://localhost:8000/api/saveuser', form);
                getEmployee();
                formReset();
                $('#myModal').modal('hide');
            } catch (error) {
                if (error.response.status == 422) {
                    var data = [];
                    for (const key in error.response.data.errors) {
                        data.push(error.response.data.errors[key][0]);
                    }
                    errors.value = data;
                } else if (error.response.status == 404) {
                    console.log('Something went wrong');
                }

            }
        }

        const updateUser = async () => {
            try {
                await axios.patch('http://localhost:8000/api/updateuser/' + user_id.value, form);
                getEmployee();
                formReset();
                $('#myModal').modal('hide');
            } catch (error) {
                if (error.response.status == 422) {
                    var data = [];
                    for (const key in error.response.data.errors) {
                        data.push(error.response.data.errors[key][0]);
                    }
                    errors.value = data;
                } else if (error.response.status == 404) {
                    console.log('Something went wrong');
                }

            }
        }
        const deleteUser = async (user_id) => {
            try {
                await axios.delete('http://localhost:8000/api/deleteuser/' + user_id, form);
                getEmployee();
                formReset();
                //$('#myModal').modal('hide');
            } catch (error) {
                if (error.response.status == 422) {
                    var data = [];
                    for (const key in error.response.data.errors) {
                        data.push(error.response.data.errors[key][0]);
                    }
                    errors.value = data;
                } else if (error.response.status == 404) {
                    console.log('Something went wrong');
                }

            }
        }

        

        const editUser = (user) => {

            user_id.value = user.id;
            form.name = user.name;
            form.email = user.email;
        }


        onMounted(getEmployee());

        const formReset = () => {
            user_id.value = '';
            form.name = '';
            form.email = '';
        }

        return {
            users, 
            form, 
            storeUser, 
            updateUser,
            errors, 
            editUser,
            formReset,
            user_id,
            deleteUser
        }
    }
}

</script>