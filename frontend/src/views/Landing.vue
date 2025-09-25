<template>
  <div class="landing-page min-vh-100 d-flex align-items-center justify-content-center bg-gradient">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="hero-content">
            <h1 class="display-2 fw-bold text-white mb-4 animate-fade-in">
              {{ $t('Welcome To AiCook') }}
            </h1>
            <p class="lead text-white mb-5 animate-slide-up">
              Your AI-powered cooking companion for personalized recipes and meal planning
            </p>
            <div class="d-flex justify-content-center gap-4 flex-wrap">
              <router-link
                to="/login"
                class="btn btn-primary btn-lg px-5 py-3 animate-bounce-in"
                style="animation-delay: 0.2s"
              >
                <i class="fas fa-sign-in-alt me-2"></i>
                {{ $t('Login') }}
              </router-link>
              <router-link
                to="/register"
                class="btn btn-outline-light btn-lg px-5 py-3 animate-bounce-in"
                style="animation-delay: 0.4s"
              >
                <i class="fas fa-user-plus me-2"></i>
                {{ $t('Register') }}
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Landing'
}
</script>

<style scoped>
.landing-page {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

.animate-slide-up {
  animation: slideUp 1s ease-out 0.3s both;
}

.animate-bounce-in {
  animation: bounceIn 0.8s ease-out both;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
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

@keyframes bounceIn {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
