<template>
  <div v-if="loading" class="overlay">
    <img :src="`/assets/${gifName}.gif`" alt="Loading..." class="loader-gif" />
    <div class="dots">
      <span class="dot" v-for="n in 3" :key="n">●</span>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  loading: { type: Boolean, required: true },
  gifName: { type: String, required: true }
})
</script>

<style scoped>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 12px;
  z-index: 9999;
}

.loader-gif {
  max-width: 150px;
  max-height: 150px;
  object-fit: contain;
  background: transparent;
  animation: flicker 0.8s infinite; /* simulate faster flicker */
}

@keyframes flicker {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
}

/* Dots container */
.dots {
  display: flex;
  gap: 8px;
}

/* Individual dot animation */
.dot {
  color: white;
  font-size: 24px;
  animation: moveDots 1.2s infinite;
  user-select: none;
}

/* Offset animation delay for each dot */
.dot:nth-child(2) {
  animation-delay: 0.2s;
}

.dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes moveDots {
  0%, 80%, 100% { transform: translateX(0); opacity: 0.3; }
  40% { transform: translateX(5px); opacity: 1; }
}
</style>
