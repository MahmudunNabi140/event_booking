<script setup>
import { ref, onMounted } from "vue";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AlertMessage from "@/Components/AlertMessage.vue";
import { displayResponse, displayWarning } from "@/responseMessage.js";
const props = defineProps(["booking", "id","user","event"]);

const form = useForm({
  user_id: props.booking?.user_id ?? "",
  event_id: props.booking?.event_id ?? "",
  _method: props.booking?.id ? "put" : "post"
});


const submit = () => {
  const routeName = props.id
    ? route("backend.booking.update", props.id)
    : route("backend.booking.store");
  form
    .transform(data => ({
      ...data,
      remember: "",
      isDirty: false
    }))
    .post(routeName, {
      onSuccess: response => {
        if (!props.id) form.reset();
        displayResponse(response);
      },
      onError: errorObject => {
        displayWarning(errorObject);
      }
    });
};
</script>

<template>
  <BackendLayout>
    <div
      class="w-full mt-3 transition duration-1000 ease-in-out transform bg-white border border-gray-700 rounded-md shadow-lg shadow-gray-800/50 dark:bg-slate-900">
      <div
        class="flex items-center justify-between w-full text-gray-700 bg-gray-100 rounded-md shadow-md dark:bg-gray-800 dark:text-gray-200 shadow-gray-800/50">
        <div>
          <h1 class="p-4 text-xl font-bold dark:text-white">{{ $page.props.pageTitle }}</h1>
        </div>
        <div class="p-4 py-2"></div>
      </div>

      <form @submit.prevent="submit" class="p-4">
        <AlertMessage />
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4">

          <div class="col-span-1 md:col-span-2">
            <InputLabel for="user_id" value="User" />
            <select id="user_id"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.user_id" placeholder="Select User">
              <option value="">--Select Role--</option>
              <template v-for="user in user">
                <option :value="user.id">{{ user.name }}</option>
              </template>
            </select>
            <InputError class="mt-2" :message="form.errors.user_id" />
          </div>

          <div class="col-span-1 md:col-span-2">
            <InputLabel for="event_id" value="Event" />
            <select id="event_id"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.event_id" placeholder="Select Event">
              <option value="">--Select event--</option>
              <template v-for="event in event">
                <option :value="event.id">{{ event.name }}</option>
              </template>
            </select>
            <InputError class="mt-2" :message="form.errors.event_id" />
          </div>

          



        </div>
        <div class="flex items-center justify-end mt-10">
          <PrimaryButton type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing">{{
              ((props.id ?? false) ? 'Update' : 'Create') }}</PrimaryButton>
        </div>
      </form>
    </div>
  </BackendLayout>
</template>
