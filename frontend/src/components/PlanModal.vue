<template>
  <overlay :loading="loading" gifName="knife" />
  <div class="modal-backdrop" @click="closeModal">
    <div class="modal-container" @click.stop>
      <div class="modal-card">
        <!-- Header -->
        <div class="card-header">
          <div class="logo-section">
            <i class="fas fa-calendar-plus logo-icon"></i>
          </div>
          <h2 class="card-title">{{ isEdit ? $t('Edit Plan') : $t('Create Plan') }}</h2>
          <p class="card-subtitle">{{ isEdit ? $t('Update your scheduled recipe') : $t('Schedule your recipe') }}</p>
        </div>

        <!-- Plan Form -->
        <form @submit.prevent="savePlan" class="plan-form">
          <div class="form-group">
            <label class="form-label">{{ $t('Plan Name') }}</label>
            <input
              v-model="planForm.name"
              type="text"
              class="form-input"
              :placeholder="$t('e.g., Dinner Tonight')"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">{{ $t('Date and Time') }}</label>
            <input
              v-model="planForm.datetime"
              type="datetime-local"
              class="form-input"
              required
            >
          </div>

          <!-- Recipe Card -->
          <div class="recipe-card-section" v-if="recipe">
            <div class="recipe-card">
              <div class="recipe-content">
                <h6 class="recipe-title">{{ recipe?.name }}</h6>
                <p class="recipe-description">{{ recipe?.description }}</p>
                <div class="recipe-meta">
                  <span class="meta-item">
                    <i class="fas fa-clock me-1"></i>
                    {{ recipe?.time }} {{ $t('minutes') }}
                  </span>
                  <span class="meta-item">
                    <i class="fas fa-users me-1"></i>
                    {{ recipe?.servings }} {{ $t('servings') }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="navigation-buttons">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="loading"
            >
              <i v-if="loading" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-save"></i>
              <span>{{ isEdit ? $t('Update Plan') : $t('Save Plan') }}</span>
            </button>

            <button
              type="button"
              @click="closeModal"
              class="btn btn-secondary"
            >
              <i class="fas fa-times"></i>
              <span>{{ $t('Cancel') }}</span>
            </button>

            <button
              v-if="isEdit"
              type="button"
              @click="deletePlan"
              class="btn btn-danger"
            >
              <i class="fas fa-trash"></i>
              <span>{{ $t('Delete') }}</span>
            </button>
          </div>
        </form>
      </div>

      <div class="hero-decoration">
        <div class="floating-element element-1"></div>
        <div class="floating-element element-2"></div>
        <div class="floating-element element-3"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import overlay from '@/components/overlay.vue'

export default {
  name: 'PlanModal',
  components: {
    overlay,
  },
  props: {
    recipe: {
      type: Object,
      default: null
    },
    plan: {
      type: Object,
      default: null
    },
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'save', 'delete'],
  setup(props, { emit }) {
    const loading = ref(false)
    const planForm = ref({
      name: '',
      datetime: ''
    })

    // Initialize form with existing plan data if editing
    if (props.isEdit && props.plan) {
      const planDate = new Date(props.plan.date)
      planDate.setMinutes(planDate.getMinutes() - planDate.getTimezoneOffset())

      planForm.value = {
        name: props.plan.name,
        datetime: planDate.toISOString().slice(0, 16)
      }
    } else if (props.recipe) {
      // Set default values for new plan
      const now = new Date()
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset())

      planForm.value = {
        name: `${props.recipe?.name} Plan`,
        datetime: now.toISOString().slice(0, 16)
      }
    }

    const closeModal = () => {
      emit('close')
    }

    const savePlan = async () => {
        loading.value = true
        emit('save', {
            name: planForm.value.name,
            date: new Date(planForm.value.datetime).toISOString(),
            done: () => { loading.value = false }
        })
    }

    const deletePlan = () => {
      if (confirm('Are you sure you want to delete this plan?')) {
        emit('delete', props.plan)
      }
    }

    return {
      planForm,
      loading,
      closeModal,
      savePlan,
      deletePlan
    }
  }
}
</script>

<style scoped>
/* Add this new button style to your existing CSS */
.btn-danger {
  background: #dc3545;
  color: var(--white);
  box-shadow: 0 8px 32px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover:not(:disabled) {
  background: #c82333;
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(220, 53, 69, 0.4);
}

/* Rest of the CSS remains the same as the previous PlanModal */
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  z-index: 1050;
  animation: fadeIn 0.3s ease-out;
}

.modal-container {
  width: 100%;
  max-width: 500px;
  animation: slideUp 0.4s ease-out;
  position: relative;
  z-index: 2;
}

.modal-card {
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  padding: 3rem 2.5rem;
  border: 1px solid rgba(147, 75, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.modal-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--brown), #a55500);
}

.card-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.logo-section {
  margin-bottom: 0rem;
}

.logo-icon {
  font-size: 3rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.card-title {
  font-size: 2rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.01em;
}

.card-subtitle {
  font-size: 1rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
}

.plan-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--black);
  margin: 0;
  opacity: 0.8;
}

.form-input {
  padding: 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 12px;
  font-size: 1rem;
  background: var(--white);
  color: var(--black);
  transition: all 0.3s ease;
  outline: none;
}

.form-input:focus {
  border-color: var(--brown);
  box-shadow: 0 0 0 4px rgba(147, 75, 0, 0.1);
  transform: translateY(-1px);
}

.form-input::placeholder {
  color: var(--black);
  opacity: 0.5;
}

.recipe-card-section {
  margin: 1rem 0;
}

.recipe-card {
  background: var(--cosmic-latte);
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 16px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.recipe-card:hover {
  border-color: var(--brown);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.1);
}

.recipe-content {
  text-align: center;
}

.recipe-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.75rem 0;
}

.recipe-description {
  font-size: 0.9rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0 0 1rem 0;
  line-height: 1.5;
}

.recipe-meta {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  color: var(--brown);
  font-weight: 600;
}

.navigation-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2.5rem;
  gap: 1rem;
}

.btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn:hover:not(:disabled)::before {
  left: 100%;
}

.btn-primary {
  background: var(--brown);
  color: var(--white);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.3);
}

.btn-primary:hover:not(:disabled) {
  background: #a55500;
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.4);
}

.btn-secondary {
  background: var(--cosmic-latte);
  color: var(--brown);
  border-color: rgba(147, 75, 0, 0.3);
}

.btn-secondary:hover {
  background: var(--brown);
  color: var(--white);
  border-color: var(--brown);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.3);
}

.hero-decoration {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 1;
}

.floating-element {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--brown), #a55500);
  opacity: 0.1;
  animation: float 6s ease-in-out infinite;
}

.element-1 {
  width: 200px;
  height: 200px;
  top: 10%;
  right: 10%;
  animation-delay: 0s;
}

.element-2 {
  width: 150px;
  height: 150px;
  bottom: 20%;
  left: 15%;
  animation-delay: 2s;
}

.element-3 {
  width: 100px;
  height: 100px;
  top: 50%;
  right: 20%;
  animation-delay: 4s;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

@media (max-width: 768px) {
  .modal-card {
    padding: 2rem 1.5rem;
    margin: 1rem;
  }

  .card-title {
    font-size: 1.75rem;
  }

  .logo-icon {
    font-size: 2.5rem;
  }

  .recipe-meta {
    flex-direction: column;
    gap: 0.5rem;
  }

  .navigation-buttons {
    flex-direction: column;
    gap: 1rem;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .modal-card {
    padding: 1.5rem 1rem;
  }

  .modal-backdrop {
    padding: 1rem;
  }

  .recipe-card {
    padding: 1rem;
  }
}
</style>
