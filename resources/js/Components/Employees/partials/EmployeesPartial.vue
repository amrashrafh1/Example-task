<script setup>
    const props = defineProps({
        employees: Object,
        errors: Object
    });

</script>
<template>
    <div class="relative overflow-x-auto p-5">
        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        National id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Employee Picture
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Employee Video
                    </th>
                </tr>
            </thead>
            <tr v-for="(employee, index) in employees.data" :key="index">
                <td>{{ employee.id }}</td>
                <td>{{ employee.name }}</td>
                <td>{{ employee.national_id }}</td>
                <td><img style="width:120px; height: 120px;" :src="employee.personal_pics" /></td>
                <td><a target="_blank" :href="employee.video_introduction" style="color:blue">View</a></td>
            </tr>
        </table>
    </div>
</template>
