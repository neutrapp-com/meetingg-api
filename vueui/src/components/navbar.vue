<template>
  <nav class="navbar">
    <div class="container">
      <div class="block">
      </div>
    </div>
  </nav>
</template>

<script>
export default {
    name: 'Navbar'
}
</script>

<style lang="scss" scoped>
.navbar {
  @apply flex sticky w-full items-center justify-between px-6 h-16 bg-white text-gray-700 border-b border-gray-200 z-10;

  .container {
    @apply flex items-center;

    .block {
      @apply hidden md:block md:flex md:justify-between md:bg-transparent;
    }

    .wishlist {
      @apply flex items-center p-3 font-medium mr-2 text-center bg-gray-300 rounded  hover:bg-gray-400 focus:outline-none focus:bg-gray-400;
    }
  }
}
</style>