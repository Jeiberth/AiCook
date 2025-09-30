<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>

    #app {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    :root {
        --white: #ffffffff;
        --cosmic-latte: #fff8e8ff;
           --black: #000000ff;
        --brown: #934b00ff;
    }

    :root {
        --white: #ffffffff;
        --cosmic-latte: #fff8e8ff;
        --black: #000000ff;
        --brown: #934b00ff;
        --danger: #dc3545;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .fade-enter-from, .fade-leave-to {
         opacity: 0;
    }

    .slide-enter-active, .slide-leave-active {
        transition: transform 0.3s ease;
    }

    .slide-enter-from {
        transform: translateX(100%);
    }

    .slide-leave-to {
        transform: translateX(-100%);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn:disabled, .btn[disabled] {
        background-color: #934b00 !important;
        border-color: #fff8e8 !important;
    }

    .btn:hover{
        border-color: transparent !important;
    }

</style>
