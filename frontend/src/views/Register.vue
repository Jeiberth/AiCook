<template>
  <div class="register-page min-vh-100 bg-light">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-lg border-0">
            <div class="card-body p-5">
              <h2 class="card-title text-center mb-4 text-primary">
                <i class="fas fa-user-plus me-2"></i>
                {{ $t('Create Account') }}
              </h2>

              <form @submit.prevent="handleRegister" class="needs-validation" novalidate>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $t('Name') }}</label>
                    <input
                      v-model="form.name"
                      type="text"
                      class="form-control"
                      required
                    >
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $t('Email') }}</label>
                    <input
                      v-model="form.email"
                      type="email"
                      class="form-control"
                      required
                    >
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">{{ $t('Weight') }}</label>
                    <input
                      v-model.number="form.weight"
                      type="number"
                      step="0.1"
                      class="form-control"
                      required
                    >
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">{{ $t('Height') }}</label>
                    <input
                      v-model.number="form.height"
                      type="number"
                      step="0.1"
                      class="form-control"
                      required
                    >
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">{{ $t('Age') }}</label>
                    <input
                      v-model.number="form.age"
                      type="number"
                      class="form-control"
                      required
                    >
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">{{ $t('Goal') }}</label>
                  <select v-model="form.goal" class="form-select" required>
                    <option value="">Select goal...</option>
                    <option value="weight loss">{{ $t('weight loss') }}</option>
                    <option value="weight gain">{{ $t('weight gain') }}</option>
                    <option value="muscle building">{{ $t('muscle building') }}</option>
                    <option value="maintenance">{{ $t('maintenance') }}</option>
                  </select>
                </div>

                <!-- Ingredients Section -->
                <div class="mb-3">
                  <label class="form-label">{{ $t('Ingredients') }}</label>
                  <div class="ingredient-selector mb-2">
                    <div class="row g-2">
                      <div v-for="ingredient in commonIngredients"
                           :key="ingredient"
                           class="col-6 col-md-4 col-lg-3">
                        <div class="form-check">
                          <input
                            :id="`ingredient-${ingredient}`"
                            v-model="form.ingredients"
                            :value="ingredient"
                            type="checkbox"
                            class="form-check-input"
                          >
                          <label :for="`ingredient-${ingredient}`" class="form-check-label text-capitalize">
                            {{ ingredient }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="input-group">
                    <input
                      v-model="customIngredient"
                      type="text"
                      class="form-control"
                      placeholder="Add custom ingredient..."
                    >
                    <button
                      @click="addCustomIngredient"
                      type="button"
                      class="btn btn-outline-primary"
                    >
                      {{ $t('Add Custom') }}
                    </button>
                  </div>
                </div>

                <!-- Dietary Preferences Section -->
                <div class="mb-3">
                  <label class="form-label">{{ $t('Dietary Preferences') }}</label>
                  <div class="row g-2">
                    <div v-for="pref in dietaryPreferences"
                         :key="pref"
                         class="col-6 col-md-4">
                      <div class="form-check">
                        <input
                          :id="`pref-${pref}`"
                          v-model="form.dietary_preferences"
                          :value="pref"
                          type="checkbox"
                          class="form-check-input"
                        >
                        <label :for="`pref-${pref}`" class="form-check-label text-capitalize">
                          {{ pref }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Allergies Section -->
                <div class="mb-3">
                  <label class="form-label">{{ $t('Allergies') }}</label>
                  <div class="row g-2">
                    <div v-for="allergy in commonAllergies"
                         :key="allergy"
                         class="col-6 col-md-4">
                      <div class="form-check">
                        <input
                          :id="`allergy-${allergy}`"
                          v-model="form.allergies"
                          :value="allergy"
                          type="checkbox"
                          class="form-check-input"
                        >
                        <label :for="`allergy-${allergy}`" class="form-check-label text-capitalize">
                          {{ allergy }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Kitchen Equipment Section -->
                <div class="mb-4">
                  <label class="form-label">{{ $t('Kitchen Equipment') }}</label>
                  <div class="row g-2">
                    <div v-for="equipment in kitchenEquipment"
                         :key="equipment"
                         class="col-6 col-md-4">
                      <div class="form-check">
                        <input
                          :id="`equipment-${equipment}`"
                          v-model="form.kitchen_equipment"
                          :value="equipment"
                          type="checkbox"
                          class="form-check-input"
                        >
                        <label :for="`equipment-${equipment}`" class="form-check-label text-capitalize">
                          {{ equipment }}
                        </label>
                      </div>
                    </div>
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
                    <i v-else class="fas fa-user-plus me-2"></i>
                    {{ $t('Create Account Button') }}
                  </button>
                  <router-link to="/login" class="btn btn-outline-secondary">
                    {{ $t('Login') }}
                  </router-link>
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
  name: 'Register',
  setup() {
    const store = useStore()
    const router = useRouter()
    const { locale } = useI18n()

    const form = ref({
      name: '',
      email: '',
      password: '',
      weight: 70,
      height: 170,
      age: 25,
      goal: '',
      ingredients: [],
      dietary_preferences: [],
      allergies: [],
      kitchen_equipment: [],
      language: 'en'
    })

    const customIngredient = ref('')

    const commonIngredients = ref([
      'chicken breast', 'beef', 'pork', 'fish', 'eggs', 'tofu',
      'rice', 'pasta', 'bread', 'potatoes', 'quinoa', 'oats',
      'broccoli', 'spinach', 'carrots', 'tomatoes', 'onions', 'garlic',
      'olive oil', 'butter', 'milk', 'cheese', 'yogurt'
    ])

    const dietaryPreferences = ref([
      'vegetarian', 'vegan', 'gluten-free', 'dairy-free',
      'low-carb', 'keto', 'paleo', 'mediterranean'
    ])

    const commonAllergies = ref([
      'nuts', 'peanuts', 'shellfish', 'fish', 'eggs',
      'milk', 'soy', 'wheat', 'sesame'
    ])

    const kitchenEquipment = ref([
      'oven', 'stovetop', 'microwave', 'blender', 'food processor',
      'air fryer', 'slow cooker', 'grill', 'rice cooker', 'stand mixer'
    ])

    const loading = computed(() => store.getters.loading)
    const error = computed(() => store.getters.error)

    const addCustomIngredient = () => {
      if (customIngredient.value.trim() && !form.value.ingredients.includes(customIngredient.value.trim())) {
        form.value.ingredients.push(customIngredient.value.trim().toLowerCase())
        customIngredient.value = ''
      }
    }

    const handleRegister = async () => {
      try {
        await store.dispatch('register', form.value)
        locale.value = form.value.language
        router.push('/dashboard')
      } catch (error) {
        console.error('Registration failed:', error)
      }
    }

    return {
      form,
      customIngredient,
      commonIngredients,
      dietaryPreferences,
      commonAllergies,
      kitchenEquipment,
      loading,
      error,
      addCustomIngredient,
      handleRegister
    }
  }
}
</script>

<style scoped>
.form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.ingredient-selector {
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  padding: 0.75rem;
}
</style>
