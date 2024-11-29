<script setup>
import { ref, onMounted } from "vue";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AlertMessage from "@/Components/AlertMessage.vue";
import { displayResponse, displayWarning } from "@/responseMessage.js";
const props = defineProps(["event", "id"]);

const form = useForm({
  name: props.event?.name ?? "",
  description: props.event?.description ?? "",
  date: props.event?.date ?? "",
  time: props.event?.time ?? "",
  location: props.event?.location ?? "",
  available_seats: props.event?.available_seats ?? "",
  price: props.event?.price ?? "",
  _method: props.event?.id ? "put" : "post"
});


const submit = () => {
  const routeName = props.id
    ? route("backend.event.update", props.id)
    : route("backend.event.store");
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
            <InputLabel for="name" value="Name" />
            <input id="name"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.name" type="text" placeholder="Name" />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="col-span-1 md:col-span-2">
            <InputLabel for="description" value="Description" />
            <input id="description"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.description" type="text" placeholder="Description" />
            <InputError class="mt-2" :message="form.errors.description" />
          </div>

          <div class="col-span-1 md:col-span-2">
            <InputLabel for="date" value="Date" />
            <input id="date"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.date" type="date" placeholder="Date" />
            <InputError class="mt-2" :message="form.errors.date" />
          </div>
          <div class="col-span-1 md:col-span-2">
            <InputLabel for="time" value="time" />
            <input id="time"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.time" type="time" placeholder="time" />
            <InputError class="mt-2" :message="form.errors.time" />
          </div>
          <div class="col-span-1 md:col-span-2">
            <InputLabel for="location" value="Location" />
            <input id="location"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.location" type="text" placeholder="Location" />
            <InputError class="mt-2" :message="form.errors.location" />
          </div>
          <div class="col-span-1 md:col-span-2">
            <InputLabel for="available_seats" value="Available seats" />
            <input id="available_seats"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.available_seats" type="text" placeholder="Available Seats" />
            <InputError class="mt-2" :message="form.errors.available_seats" />
          </div>
          <div class="col-span-1 md:col-span-2">
            <InputLabel for="price" value="price" />
            <input id="price"
              class="block w-full p-2 text-sm rounded-md shadow-sm border-slate-300 dark:border-slate-500 dark:bg-slate-700 dark:text-slate-200 focus:border-indigo-300 dark:focus:border-slate-600"
              v-model="form.price" type="number" placeholder="price" min="1" />
            <InputError class="mt-2" :message="form.errors.price" />
          </div>

        </div>
        <div class="flex items-center justify-end mt-10">
          <PrimaryButton type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing">{{ ((props.id ?? false) ? 'Update' : 'Create') }}</PrimaryButton>
        </div>
      </form>
    </div>
  </BackendLayout>
</template>
