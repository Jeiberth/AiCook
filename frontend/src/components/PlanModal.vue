<template>
  <div class="modal-backdrop d-flex align-items-center justify-content-center" @click="closeModal">
    <div class="modal-content-custom" @click.stop>
      <div class="card shadow-lg" style="width: 500px;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fas fa-calendar-plus me-2"></i>
            {{ $t('Create Plan') }}
          </h5>
        </div>

        <div class="card-body">
          <form @submit.prevent="savePlan">
            <div class="mb-3">
              <label class="form-label">{{ $t('Plan Name') }}</label>
              <input
                v-model="planForm.name"
                type="text"
                class="form-control"
                placeholder="e.g., Dinner Tonight"
                required
              >
            </div>

            <div class="mb-3">
              <label class="form-label">{{ $t('Date and Time') }}</label>
              <input
                v-model="planForm.datetime"
                type="datetime-local"
                class="form-control"
                required
              >
            </div>

            <div class="mb-3">
              <div class="card bg-light">
                <div class="card-body p-3">
                  <h6 class="card-title mb-2">{{ recipe?.name }}</h6>
                  <p class="card-text small text-muted mb-1">{{ recipe?.description }}</p>
                  <div class="d-flex justify-content-between text-muted small">
                    <span>
                      <i class="fas fa-clock me-1"></i>
                      {{ recipe?.time }} {{ $t('minutes') }}
                    </span>
                    <span>
                      <i class="fas fa-users me-1"></i>
                      {{ recipe?.servings }} {{ $t('servings') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                <i class="fas fa-save me-2"></i>
                {{ $t('Save') }}
              </button>
              <button type="button" @click="$emit('close')" class="btn btn-outline-secondary">
                {{ $t('Cancel') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'PlanModal',
  props: {
    recipe: {
      type: Object,
      required: true
    }
  },
  emits: ['close', 'save'],
  setup(props, { emit }) {
    const planForm = ref({
      name: '',
      datetime: ''
    })

    const closeModal = () => {
      emit('close')
    }

    const savePlan = () => {
      emit('save', {
        name: planForm.value.name,
        date: new Date(planForm.value.datetime).toISOString()
      })
    }

    onMounted(() => {
      // Set default datetime to now
      const now = new Date()
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset())
      planForm.value.datetime = now.toISOString().slice(0, 16)

      // Set default plan name
      planForm.value.name = `${props.recipe?.name} Plan`
    })

    return {
      planForm,
      closeModal,
      savePlan
    }
  }
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}
</style>
