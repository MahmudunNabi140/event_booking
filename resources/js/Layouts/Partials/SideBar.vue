<script setup>
import { reactive, ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import SideBarSubMenu from '@/Components/SideBarSubMenu.vue';
import eventBus from '@/eventBus.js';

const screenWidth = ref(window.innerWidth);

const handleResize = () => {
  screenWidth.value = window.innerWidth;
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize);
});

if (screenWidth <400) {
  //  console.log(screenWidth);
} else {
   // console.log('else');
}

const sideBar = ref(false);

eventBus.on('sidebarToggled', (flag) => {
      sideBar.value = flag;
});

const navSidebar = reactive([
    'flex items-center p-2 space-x-1 duration-500 border-b rounded-md cursor-pointer hover:bg-slate-800 hover:text-slate-200 border-slate-300 dark:border-slate-800'
]);

const sideNavListTrue = ref(['md:hidden lg:block']);
const sideNavListFalse = ref('md:hidden lg:block');


</script>
<template>
    <div :class="{ ' hidden w-[60px] sm:w-[60px] md:w-[60px]': sideBar, 'block w-[260px] sm:w-[60px] lg:w-[260px]': !sideBar }" class="bg-slate-50 dark:bg-slate-900 text-slate-800 md:block hover:w-[260px] relative">
        <div class="w-full flex items-center h-[50px] border-b border-black dark:border-slate-800">
            <h1 class="hidden px-3 text-2xl font-bold md:block">BTRC </h1>
        </div>

        <div style="width: inherit;"
            class="h-[calc(100vh-50px)] overflow-y-auto border-r border-black dark:border-slate-800 shadow-[3px_0px_6px_gray] shadow-gray-800/50">
            <ul class="w-full px-3 mt-2 text-sm">
                <template v-for="(mainMenu, Index) in $page.props.sideMenus" :key="Index">
                    <li :class="{ 'justify-center': !sideBar }" class="" v-if="mainMenu.childrens.length > 0">
                        <SideBarSubMenu align="left">
                            <template #trigger>
                                <div :class="navSidebar">
                                    <!-- <i :data-feather="mainMenu.icon"></i> -->
                                    <FeatherIcon :name="mainMenu.icon" size="1px" class="text-blue-500" />
                                    <!--<span :class="sideBar ? 'hidden' : 'block'" class="truncate">-->
                                    <span class="truncate ">
                                        {{ mainMenu.name }}
                                    </span>
                                </div>
                            </template>

                            <template #content>

                                <ul class="" id="submenu" data-parent="">
                                    <li v-for="(submenu, subIndex) in mainMenu.childrens" :key="subIndex">
                                        <Link :href="route(submenu.route)"
                                            :class="[(route().current(submenu.route) ? 'bg-slate-800 text-slate-200' : 'text-slate-700'), navSidebar, 'pl-6']">
                                        <!-- <i :data-feather="submenu.icon"></i> -->
                                        <FeatherIcon :name="submenu.icon" size="1px" class="text-blue-500" />
                                        <span  class="truncate">
                                            {{ submenu.name }}</span>
                                        </Link>
                                    </li>

                                </ul>
                            </template>
                        </SideBarSubMenu>
                    </li>

                    <li :class="{ 'justify-center': !sideBar }" v-else>
                        <Link :href="route(mainMenu.route)"
                            :class="[(route().current(mainMenu.route) ? 'bg-slate-800 text-slate-200' : 'text-slate-700'), navSidebar]">
                        <FeatherIcon :name="mainMenu.icon" size="1px" class="text-blue-500" />
                        <span  class="truncate">
                            {{ mainMenu.name }}
                        </span>
                        </Link>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>
