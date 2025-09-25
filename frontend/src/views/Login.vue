<template>
  <div class="login-page min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card shadow-lg border-0">
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <h2 class="card-title text-primary">
                  <i class="fas fa-sign-in-alt me-2"></i>
                  {{ $t('Welcome Back') }}
                </h2>
                <p class="text-muted">{{ $t('Sign In') }}</p>
              </div>

              <form @submit.prevent="handleLogin" class="needs-validation" novalidate>
                <div class="mb-3">
                  <label class="form-label">{{ $t('Email') }}</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fas fa-envelope"></i>
                    </span>
                    <input
                      v-model="form.email"
                      type="email"
                      class="form-control"
                      placeholder="Enter your email"
                      required
                    >
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label">{{ $t('Password') }}</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fas fa-lock"></i>
                    </span>
                    <input
                      v-model="form.password"
                      type="password"
                      class="form-control"
                      placeholder="Enter your password"
                      required
                    >
                  </div>
                </div>

                <div v-if="error" class="alert alert-danger mb-3">
                  {{ error }}
                </div>

                <div class="d-grid gap-2">
                  <button
                    type="submit"
                    class="btn btn-primary btn-lg"
                    :disabled="loading"
                  >
                    <i v-if="loading" class="fas fa-spinner fa-spin me-2"></i>
                    <i v-else class="fas fa-sign-in-alt me-2"></i>
                    {{ $t('Login') }}
                  </button>

                  <router-link to="/register" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus me-2"></i>
                    {{ $t('Register') }}
                  </router-link>
                </div>

                <div class="text-center mt-3">
                  <a href="#" class="text-decoration-none">{{ $t('Forgot Password') }}</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'

export default {
  name: 'Login',
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
