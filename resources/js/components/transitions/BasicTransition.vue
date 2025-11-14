<template>
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-1 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-1 opacity-0"
        mode="out-in"
        appear
    >
        <slot></slot>
    </transition>
</template>
