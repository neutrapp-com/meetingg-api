<template>
  <nav
    class="flex sticky w-full items-center justify-between px-6 h-16 bg-white text-gray-700 border-b border-gray-200 z-10"
  >
    <div class="flex items-center">
      <div class="hidden md:block md:flex md:justify-between md:bg-transparent">
        <button
          title="Wishlist"
          class="flex items-center p-3 font-medium mr-2 text-center bg-gray-300 rounded  hover:bg-gray-400 focus:outline-none focus:bg-gray-400"
        >
          <svg
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            stroke="currentColor"
            viewBox="0 0 24 24"
            class="w-6 h-6 mr-2"
          >
            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
          </svg>
          <span>Wishlist</span>
        </button>
        <button
          class="flex items-center p-3 font-medium mr-2 text-center bg-gray-300 rounded  hover:bg-gray-400 focus:outline-none focus:bg-gray-400"
        >
          <svg
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
            class="h-6 w-6"
          >
            <path
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
            ></path>
          </svg>
        </button>
        <button
          class="flex items-cente p-3 font-medium mr-2 text-center bg-gray-300 rounded  hover:bg-gray-400 focus:outline-none focus:bg-gray-400"
        >
          <svg
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
            class="w-6 h-6"
          >
            <path
              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </button>
       
      </div>
    </div>
  </nav>
</template>

<script>
export default {
    name: 'Navbar',
  data() {
    return {
      isOpen: false
    };
  },
  methods: {
    drawer() {
      this.isOpen = !this.isOpen;
    }
  },
  watch: {
    isOpen: {
      immediate: true,
      handler(isOpen) {
        if (process.client) {
          if (isOpen) document.body.style.setProperty("overflow", "hidden");
          else document.body.style.removeProperty("overflow");
        }
      }
    }
  },
  mounted() {
    document.addEventListener("keydown", e => {
      if (e.keyCode == 27 && this.isOpen) this.isOpen = false;
    });
  }
};
</script>