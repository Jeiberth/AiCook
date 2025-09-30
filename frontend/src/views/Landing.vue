<template>
  <div class="landing-page">
    <div class="hero-container">
      <div class="hero-content">
        <div class="logo-section animate-fade-in">
          <img src="/assets/favicon.png" alt="logo" style="width: 80px;  margin: auto; margin-bottom: 30px;">
          <h1 class="hero-title">
            {{ $t('Welcome To AiCook') }}
          </h1>
        </div>

        <p class="hero-subtitle animate-slide-up">
          {{ $t('Your AI-powered cooking companion for personalized recipes and meal planning') }}
        </p>

        <div class="action-buttons animate-fade-in-delay">
          <router-link
            to="/login"
            class="btn btn-primary"
          >
            <i class="fas fa-sign-in-alt"></i>
            <span>{{ $t('Login') }}</span>
          </router-link>
          <router-link
            to="/register"
            class="btn btn-secondary"
          >
            <i class="fas fa-user-plus"></i>
            <span>{{ $t('Register') }}</span>
          </router-link>
        </div>
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
export default {
  name: 'Landing'
}
</script>

<style scoped>
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
}

.landing-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.hero-container {
  max-width: 1200px;
  width: 100%;
  padding: 2rem;
  text-align: center;
  position: relative;
  z-index: 2;
}

.logo-section {
  margin-bottom: 1rem;
}

.logo-icon {
  font-size: 4rem;
  color: var(--brown);
  margin-bottom: 1.5rem;
  display: block;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 700;
  color: var(--black);
  letter-spacing: -0.02em;
  line-height: 1.1;
  margin: 0;
}

.hero-subtitle {
  font-size: 1.25rem;
  color: var(--black);
  opacity: 0.8;
  max-width: 600px;
  margin: 0 auto 3rem;
  line-height: 1.6;
}

.action-buttons {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
  min-width: 160px;
  justify-content: center;
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

.btn:hover::before {
  left: 100%;
}

.btn-primary {
  background: var(--brown);
  color: var(--white);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.3);
}

.btn-primary:hover {
  background: #a55500;
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.4);
}

.btn-secondary {
  background: var(--white);
  color: var(--brown);
  border-color: var(--brown);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.1);
}

.btn-secondary:hover {
  background: var(--brown);
  color: var(--white);
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.3);
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

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

.animate-slide-up {
  animation: slideUp 1s ease-out 0.3s both;
}

.animate-fade-in-delay {
  animation: fadeIn 1s ease-out 0.6s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
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
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1.1rem;
    margin-bottom: 2rem;
  }

  .action-buttons {
    flex-direction: column;
    align-items: center;
  }

  .btn {
    width: 100%;
    max-width: 280px;
  }

}

@media (max-width: 480px) {
  .hero-container {
    padding: 1rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .logo-icon {
    font-size: 3rem;
  }
}
</style>
