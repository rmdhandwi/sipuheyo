<script setup lang="ts">
import { openSidebar } from "./store";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
</script>

<template>
    <header class="relative z-10 h-16 w-full items-center md:h-20">
        <div class="relative mx-auto flex h-full flex-col justify-center px-3">
            <div class="relative flex w-full items-center pl-1 sm:ml-0 sm:pr-2">
                <div class="container relative left-0 flex w-3/4">
                    <div class="group relative flex h-full w-full items-center">
                        <button
                            type="button"
                            aria-expanded="false"
                            aria-label="Toggle sidenav"
                            class="text-4xl focus:outline-none lg:hidden"
                            @click="openSidebar"
                        >
                            &#8801;
                        </button>
                        <div
                            class="mt-5 pl-5 flex items-center h-full text-white text-2xl font-medium lg:pl-6"
                        >
                            PUSKESMAS HEBEYBHULU YOKA
                        </div>
                    </div>
                </div>
                <div
                    class="relative ml-5 flex w-9/12 items-center justify-end p-1 sm:right-auto sm:mr-0 md:w-1/4"
                >
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}
                                            <svg
                                                class="ms-2 -me-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template
                                    #content
                                    class="flex flex-col bg-white"
                                >
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
