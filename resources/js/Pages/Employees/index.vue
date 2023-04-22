<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import Pagination from '@/Components/Pagination'

    import {
        reactive, ref
    } from 'vue'
    import {
        Inertia
    } from "@inertiajs/inertia";
    import {
        useForm
    } from "@inertiajs/inertia-vue3";

    import {
        Head
    } from '@inertiajs/inertia-vue3';
    import
        EmployeesPartial
    from '../../Components/Employees/partials/EmployeesPartial.vue';

    const props = defineProps({
        employees: Object,
        errors   : Object
    });
    let errorArray         = reactive({});
    let video_processing   = ref(false);
    let picture_processing = ref(false);
    let loading            = ref(0);

    const config = {
        onUploadProgress: function (progressEvent) {
            const percentCompleted = Math.round(
                (progressEvent.loaded * 100) / progressEvent.total
            );
            loading.value = percentCompleted + '%';
        },
    };
    const form = useForm({
        name: null,
        national_id: null,
        video_introduction: null,
        personal_pics: null,
    });


    function uploadVideo(e) {
        loading.value           = ref(0);
        video_processing.value  = true;
        form.video_introduction = '';
        if (!e.target.files.length)
            return;
        var data = new FormData();
        data.append('media', e.target.files[0]);
        axios.post(route("upload_video"), data, config)
            .then(response => {
                form.video_introduction = response.data.path;
                video_processing.value = false;
                errorArray.value = reactive({});
            })
            .catch(e => {
                errorArray.value = e.response.data;
            });
    };

    function uploadPicture(e) {
        loading.value            = ref(0);
        form.personal_pics       = '';
        picture_processing.value = true;
        if (!e.target.files.length)
            return;
        var data = new FormData();
        data.append('media', e.target.files[0]);
        axios.post(route("upload_picture"), data, config)
            .then(response => {
                form.personal_pics = response.data.path;
                picture_processing.value = false;
                errorArray.value = reactive({});
            })
            .catch(e => {
                errorArray.value = e.response.data;
            });
    };

    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
        document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
        document.getElementById(modalID).classList.toggle("flex");
        document.getElementById(modalID + "-backdrop").classList.toggle("flex");
    }

    function submit() {
        Inertia.post(route("employees.store"), form, {
            onSuccess: () => {
                form.reset();
                toggleModal('defaultModal');
            }
        });
    }

</script>

<template>

    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Employees
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <button @click="toggleModal('defaultModal')"
                        class="block m-5 ml-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        Add New Employee
                    </button>
                    <Employees-Partial :employees="employees"></Employees-Partial>
                    <Pagination class="mt-6" :meta="employees.meta" />
                    <div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center"
                        id="defaultModal">
                        <div class="relative w-auto my-6 mx-auto max-w-3xl">
                            <!--content-->
                            <div
                                class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                                <!--header-->
                                <div
                                    class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">
                                    <h3 class="text-3xl font-semibold">
                                        Add new employee
                                    </h3>
                                    <button
                                        class="p-1 ml-auto bg-transparent border-0 text-gray-300 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                                        @click="toggleModal('defaultModal')">
                                        <span
                                            class="bg-transparent h-6 w-6 text-2xl block outline-none focus:outline-none">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </button>
                                </div>
                                <!--body-->
                                <form @submit.prevent="submit" enctype="multipart/form-data">
                                    <div v-for="(errors, idx) in errorArray" :key="idx">
                                        <div v-for="(message, index) in errors" :key="index">
                                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                                role="alert">
                                                {{ message[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative p-6 flex-auto">
                                        <div class="mb-6">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Name</label>
                                            <input type="text" v-model="form.name" required
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="" />
                                            <div v-if="errors.name" class="text-red-600 text-sm">
                                                {{ errors.name }}</div>
                                        </div>
                                        <div class="mb-6">
                                            <label for="national_id"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">National
                                                Id</label>
                                            <input type="number" v-model="form.national_id" required maxlength="11"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                            <div v-if="errors.national_id" class="text-red-600 text-sm">
                                                {{ errors.national_id }}</div>
                                        </div>
                                        <div class="mb-6">
                                            <label for="video_introduction"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Video
                                                introduction</label>
                                            <input type="file" @input="uploadVideo" required
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                            <div v-if="errors.video_introduction" class="text-red-600 text-sm">
                                                {{ errors.video_introduction }}</div>
                                        </div>
                                        <div class="mb-6">
                                            <label for="personal_pics"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Personal
                                                picture</label>
                                            <input type="file" @input="uploadPicture" required
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                            <div v-if="errors.personal_pics" class="text-red-600 text-sm">
                                                {{ errors.personal_pics }}</div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div id="loading" class="bg-blue-600 h-2.5 rounded-full" :style="'width:' + loading">
                                            </div>
                                        </div>
                                    </div>
                                    <!--footer-->
                                    <div
                                        class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                                        <button
                                            class="text-purple-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                            type="button" @click="toggleModal('defaultModal')">
                                            Close
                                        </button>
                                        <button type="submit"
                                            :disabled="form.progress || video_processing || picture_processing"
                                            :class="form.progress || video_processing || picture_processing ? 'bg-gray-500' : 'bg-purple-500'"
                                            class=" text-white active:bg-purple-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                                            <div v-if="form.progress || video_processing || picture_processing"
                                            class="inline-block h-4 w-4 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] text-primary motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                            role="status">
                                            <span
                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                                                >Loading...</span
                                            >
                                            </div>
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="modal-example-regular-backdrop">
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
