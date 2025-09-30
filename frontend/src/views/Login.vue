<!-- Login.vue -->

<template>
  <overlay :loading="loading" gifName="knife" />
  <div class="login-page">
    <div class="login-container">
      <div class="login-card">
        <!-- Header -->
        <div class="card-header">
          <div class="logo-section">
            <i class="fas fa-sign-in-alt logo-icon"></i>
          </div>
          <h2 class="card-title">{{ $t('Welcome Back') }}</h2>
          <p class="card-subtitle">{{ $t('Sign in to your account') }}</p>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="login-form">
          <div class="form-group">
            <label class="form-label">{{ $t('Email') }}</label>
            <input
              v-model="form.email"
              type="email"
              class="form-input"
              :placeholder="$t('Enter your email')"
              required
            >
          </div>

          <div class="form-group">
            <label class="form-label">{{ $t('Password') }}</label>
            <input
              v-model="form.password"
              type="password"
              class="form-input"
              :placeholder="$t('Enter your password')"
              required
            >
          </div>

          <div v-if="error" class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            {{ error }}
          </div>

          <!-- Buttons -->
          <div class="navigation-buttons">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="loading"
            >
              <i v-if="loading" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-sign-in-alt"></i>
              <span>{{ $t('Login') }}</span>
            </button>

            <router-link to="/register" class="btn btn-secondary">
              <i class="fas fa-user-plus"></i>
              <span>{{ $t('Register') }}</span>
            </router-link>
          </div>

          <!-- Forgot Password -->
          <div class="login-link" v-if="false">
            <a href="#" class="link">{{ $t('Forgot Password?') }}</a>
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
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import overlay from '@/components/overlay.vue'

export default {
  name: 'Login',
  components: {
    overlay,
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const { locale } = useI18n()

    const form = ref({
      email: '',
      password: ''
    })

    const loading = computed(() => store.getters.loading)
    const error = computed(() => store.getters.error)

    const handleLogin = async () => {
      try {
        await store.dispatch('login', form.value)
        const user = store.getters.user
        if (user && user.language) {
          locale.value = user.language
        }
        router.push('/dashboard')
      } catch (error) {
        console.error('Login failed:', error)
      }
    }

    return {
      form,
      loading,
      error,
      handleLogin
    }
  }
}
</script>

<style scoped>
/* Inherit same styling system from Register page */
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.login-container {
  width: 100%;
  max-width: 500px;
  animation: slideUp 0.6s ease-out;
}

.login-card {
  @extend .register-card;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
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

</style>

<style scoped>
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
}

.register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.register-container {
  width: 100%;
  max-width: 800px;
  animation: slideUp 0.6s ease-out;
}

.register-card {
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  padding: 3rem 2.5rem;
  border: 1px solid rgba(147, 75, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.register-card::before {
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

.progress-section {
  margin-bottom: 2.5rem;
}

.progress-bar {
  width: 100%;
  height: 6px;
  background: rgba(147, 75, 0, 0.2);
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 1rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--brown), #a55500);
  border-radius: 3px;
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.step-indicator {
  text-align: center;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--brown);
}

.step-content {
  min-height: 400px;
  animation: fadeInStep 0.4s ease-out;
}

.step-header {
  text-align: center;
  margin-bottom: 2rem;
}

.step-icon {
  font-size: 2.5rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.step-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
}

.step-description {
  font-size: 1rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
}

.selection-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.selection-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1.5rem 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 16px;
  background: var(--white);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-align: center;
}

.selection-item:hover {
  border-color: var(--brown);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.1);
}

.selection-item.active {
  border-color: var(--brown);
  background: var(--cosmic-latte);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.2);
}

.item-icon {
  font-size: 1.5rem;
  color: var(--brown);
  margin-bottom: 0.75rem;
}

.item-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--black);
  text-transform: capitalize;
}

.custom-input-section {
  margin-bottom: 2rem;
}

.custom-input-wrapper {
  display: flex;
  gap: 0.5rem;
  max-width: 400px;
  margin: 0 auto;
}

.custom-input {
  flex: 1;
  padding: 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 12px;
  font-size: 0.9rem;
  background: var(--white);
  color: var(--black);
  transition: all 0.3s ease;
  outline: none;
}

.custom-input:focus {
  border-color: var(--brown);
  box-shadow: 0 0 0 4px rgba(147, 75, 0, 0.1);
}

.add-custom-btn {
  padding: 1rem;
  background: var(--brown);
  color: var(--white);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 48px;
}

.add-custom-btn:hover {
  background: #a55500;
  transform: translateY(-1px);
}

.goal-selection {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.goal-item {
  padding: 2rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 20px;
  background: var(--white);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-align: center;
}

.goal-item:hover {
  border-color: var(--brown);
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.15);
}

.goal-item.active {
  border-color: var(--brown);
  background: var(--cosmic-latte);
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.25);
}

.goal-icon {
  font-size: 2.5rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.goal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.75rem 0;
}

.goal-description {
  font-size: 0.9rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
  line-height: 1.5;
}

.personal-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
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

.error-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem;
  background: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.3);
  border-radius: 12px;
  color: #dc3545;
  font-size: 0.9rem;
  font-weight: 500;
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

.login-link {
  text-align: center;
  margin-top: 2rem;
  font-size: 0.9rem;
  color: var(--black);
  opacity: 0.7;
}

.link {
  color: var(--brown);
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.5rem;
  transition: all 0.3s ease;
}

.link:hover {
  text-decoration: underline;
  opacity: 0.8;
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

@keyframes fadeInStep {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .register-card {
    padding: 2rem 1.5rem;
    margin: 1rem;
  }

  .card-title {
    font-size: 1.75rem;
  }

  .logo-icon {
    font-size: 2.5rem;
  }

  .selection-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
  }

  .selection-item {
    padding: 1rem 0.75rem;
  }

  .goal-selection {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .goal-item {
    padding: 1.5rem;
  }

  .form-row {
    grid-template-columns: 1fr;
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
  .register-card {
    padding: 1.5rem 1rem;
  }

  .step-content {
    min-height: 350px;
  }

  .selection-grid {
    grid-template-columns: 1fr 1fr;
  }

  .custom-input-wrapper {
    flex-direction: column;
  }

  .add-custom-btn {
    min-width: auto;
  }
}
</style>
