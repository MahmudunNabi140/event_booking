<script setup>
import { ref, onMounted } from "vue";
import BackendLayout from "@/Layouts/BackendLayout.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AlertMessage from "@/Components/AlertMessage.vue";
import { displayResponse, displayWarning } from "@/responseMessage.js";
// const props = defineProps(["event", "id"]);

defineProps({
  events: Object,
});

const bookEvent = (eventId) => {
  Inertia.post(`/book-event/${eventId}`, {}, {
    onSuccess: () => {
      alert('Booking successful!');
    },
    onError: (errors) => {
      alert(errors.message || 'Booking failed.');
    },
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

            <template>
                <div>
                    <h1 class="text-xl font-bold mb-4">Available Events</h1>
                    <div v-for="event in events.data" :key="event.id" class="mb-4 p-4 border rounded">
                        <h2 class="text-lg font-semibold">{{ event.name }}</h2>
                        <p>{{ event.description }}</p>
                        <p>Date: {{ event.date }}</p>
                        <p>Time: {{ event.time }}</p>
                        <p>Available Seats: {{ event.available_seats }}</p>
                        <p>Price: ${{ event.price }}</p>
                        <button @click="bookEvent(event.id)" :disabled="event.available_seats === 0"
                            class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">
                            Book Now
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </BackendLayout>
</template>
