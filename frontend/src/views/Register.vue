<template>
  <div class="register-page">
    <overlay :loading="loading" gifName="knife" />
    <div class="register-container">
      <div class="register-card">
        <div class="card-header" v-if="false">
          <div class="logo-section">
            <i class="fas fa-utensils logo-icon"></i>
          </div>
          <h2 class="card-title">{{ $t('Create Account') }}</h2>
          <p class="card-subtitle">{{ $t('Join AiCook and start your culinary journey') }}</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-section">
          <div class="progress-bar">
            <div
              class="progress-fill"
              :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
            ></div>
          </div>
          <div class="step-indicator">
            {{ $t('Step') }} {{ currentStep }} {{ $t('of') }} {{ totalSteps }}
          </div>
        </div>

        <!-- Step 1: Kitchen Equipment -->
        <div v-if="currentStep === 1" class="step-content">
          <div class="step-header">
            <i class="fas fa-kitchen-set step-icon"></i>
            <h3 class="step-title">{{ $t('Kitchen Equipment') }}</h3>
            <p class="step-description">{{ $t('Select the equipment you have available') }}</p>
          </div>

          <div class="selection-grid">
            <div
              v-for="equipment in kitchenEquipment"
              :key="equipment.value"
              class="selection-item"
              :class="{ active: form.kitchen_equipment.includes(equipment.value) }"
              @click="toggleSelection('kitchen_equipment', equipment.value)"
            >
              <i :class="equipment.icon" class="item-icon"></i>
              <span class="item-label">{{ $t(equipment.label) }}</span>
            </div>
          </div>

          <div class="custom-input-section">
            <div class="custom-input-wrapper">
              <input
                v-model="customEquipment"
                type="text"
                class="custom-input"
                :placeholder="$t('Add custom equipment')"
                @keypress.enter="addCustomItem('kitchen_equipment', 'customEquipment')"
              >
              <button
                type="button"
                class="add-custom-btn"
                @click="addCustomItem('kitchen_equipment', 'customEquipment')"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Step 2: Allergies -->
        <div v-if="currentStep === 2" class="step-content">
          <div class="step-header">
            <i class="fas fa-exclamation-triangle step-icon"></i>
            <h3 class="step-title">{{ $t('Allergies') }}</h3>
            <p class="step-description">{{ $t('Tell us about any food allergies you have') }}</p>
          </div>

          <div class="selection-grid">
            <div
              v-for="allergy in commonAllergies"
              :key="allergy.value"
              class="selection-item"
              :class="{ active: form.allergies.includes(allergy.value) }"
              @click="toggleSelection('allergies', allergy.value)"
            >
              <i :class="allergy.icon" class="item-icon"></i>
              <span class="item-label">{{ $t(allergy.label) }}</span>
            </div>
          </div>

          <div class="custom-input-section">
            <div class="custom-input-wrapper">
              <input
                v-model="customAllergy"
                type="text"
                class="custom-input"
                :placeholder="$t('Add custom allergy')"
                @keypress.enter="addCustomItem('allergies', 'customAllergy')"
              >
              <button
                type="button"
                class="add-custom-btn"
                @click="addCustomItem('allergies', 'customAllergy')"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Step 3: Dietary Preferences -->
        <div v-if="currentStep === 3" class="step-content">
          <div class="step-header">
            <i class="fas fa-leaf step-icon"></i>
            <h3 class="step-title">{{ $t('Dietary Preferences') }}</h3>
            <p class="step-description">{{ $t('Choose your dietary lifestyle') }}</p>
          </div>

          <div class="selection-grid">
            <div
              v-for="preference in dietaryPreferences"
              :key="preference.value"
              class="selection-item"
              :class="{ active: form.dietary_preferences.includes(preference.value) }"
              @click="toggleSelection('dietary_preferences', preference.value)"
            >
              <i :class="preference.icon" class="item-icon"></i>
              <span class="item-label">{{ $t(preference.label) }}</span>
            </div>
          </div>

          <div class="custom-input-section">
            <div class="custom-input-wrapper">
              <input
                v-model="customPreference"
                type="text"
                class="custom-input"
                :placeholder="$t('Add custom preference')"
                @keypress.enter="addCustomItem('dietary_preferences', 'customPreference')"
              >
              <button
                type="button"
                class="add-custom-btn"
                @click="addCustomItem('dietary_preferences', 'customPreference')"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Step 4: Ingredients -->
        <div v-if="currentStep === 4" class="step-content">
          <div class="step-header">
            <i class="fas fa-shopping-basket step-icon"></i>
            <h3 class="step-title">{{ $t('Ingredients') }}</h3>
            <p class="step-description">{{ $t('What ingredients do you commonly use?') }}</p>
          </div>

          <div class="selection-grid">
            <div
              v-for="ingredient in commonIngredients"
              :key="ingredient.value"
              class="selection-item"
              :class="{ active: form.ingredients.includes(ingredient.value) }"
              @click="toggleSelection('ingredients', ingredient.value)"
            >
              <i :class="ingredient.icon" class="item-icon"></i>
              <span class="item-label">{{ $t(ingredient.label) }}</span>
            </div>
          </div>

          <div class="custom-input-section">
            <div class="custom-input-wrapper">
              <input
                v-model="customIngredient"
                type="text"
                class="custom-input"
                :placeholder="$t('Add custom ingredient')"
                @keypress.enter="addCustomItem('ingredients', 'customIngredient')"
              >
              <button
                type="button"
                class="add-custom-btn"
                @click="addCustomItem('ingredients', 'customIngredient')"
              >
                <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Step 5: Goal -->
        <div v-if="currentStep === 5" class="step-content">
          <div class="step-header">
            <i class="fas fa-target step-icon"></i>
            <h3 class="step-title">{{ $t('Your Goal') }}</h3>
            <p class="step-description">{{ $t('What would you like to achieve?') }}</p>
          </div>

          <div class="goal-selection">
            <div
              v-for="goal in goals"
              :key="goal.value"
              class="goal-item"
              :class="{ active: form.goal === goal.value }"
              @click="form.goal = goal.value"
            >
              <i :class="goal.icon" class="goal-icon"></i>
              <h4 class="goal-title">{{ $t(goal.label) }}</h4>
              <p class="goal-description">{{ $t(goal.description) }}</p>
            </div>
          </div>
        </div>

        <!-- Step 6: Personal Information -->
        <div v-if="currentStep === 6" class="step-content">
          <div class="step-header">
            <i class="fas fa-user step-icon"></i>
            <h3 class="step-title">{{ $t('Personal Information') }}</h3>
            <p class="step-description">{{ $t('Tell us a bit about yourself') }}</p>
          </div>

          <form @submit.prevent="handleRegister" class="personal-form">
            <div class="form-group">
              <label class="form-label">{{ $t('Name') }}</label>
              <input
                v-model="form.name"
                type="text"
                class="form-input"
                :placeholder="$t('Enter your full name')"
                required
              >
            </div>

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
                minlength="8"
                :placeholder="$t('Create a password')"
                required
              >
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">{{ $t('Weight') }}</label>
                <input
                  v-model.number="form.weight"
                  type="number"
                  step="0.1"
                  class="form-input"
                  :placeholder="$t('Enter weight')"
                  required
                >
              </div>

              <div class="form-group">
                <label class="form-label">{{ $t('Height') }}</label>
                <input
                  v-model.number="form.height"
                  type="number"
                  step="0.1"
                  class="form-input"
                  :placeholder="$t('Enter height')"
                  required
                >
              </div>

              <div class="form-group">
                <label class="form-label">{{ $t('Age') }}</label>
                <input
                  v-model.number="form.age"
                  type="number"
                  class="form-input"
                  :placeholder="$t('Enter age')"
                  required
                >
              </div>
            </div>

            <div v-if="error" class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ error }}
            </div>
          </form>
        </div>

        <!-- Navigation Buttons -->
        <div class="navigation-buttons">
          <button
            v-if="currentStep > 1"
            type="button"
            class="btn btn-secondary"
            @click="previousStep"
          >
            <i class="fas fa-chevron-left"></i>
            <span>{{ $t('Previous') }}</span>
          </button>

          <button
            v-if="currentStep < totalSteps"
            type="button"
            class="btn btn-primary"
            @click="nextStep"
            :disabled="!canProceed"
          >
            <span>{{ $t('Next') }}</span>
            <i class="fas fa-chevron-right"></i>
          </button>

          <button
            v-if="currentStep === totalSteps"
            type="button"
            class="btn btn-primary"
            @click="handleRegister"
            :disabled="loading || !canProceed"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin"></i>
            <i v-else class="fas fa-user-plus"></i>
            <span>{{ $t('Create Account Button') }}</span>
          </button>
        </div>

        <div class="login-link">
          <span>{{ $t('Already have an account?') }}</span>
          <router-link to="/login" class="link">{{ $t('Login') }}</router-link>
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
import overlay from '@/components/overlay.vue'
import Swal from 'sweetalert2'

export default {
  name: 'Register',
  components: {
    overlay,
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const { locale } = useI18n()

    const currentStep = ref(1)
    const totalSteps = 6

    const form = ref({
      name: '',
      email: '',
      password: '',
      weight: null,
      height: null,
      age: null,
      goal: '',
      ingredients: [],
      dietary_preferences: [],
      allergies: [],
      kitchen_equipment: [],
      language: 'en'
    })

    // Custom input refs
    const customEquipment = ref('')
    const customAllergy = ref('')
    const customPreference = ref('')
    const customIngredient = ref('')

    const kitchenEquipment = ref([
      { value: 'oven', label: 'Oven', icon: 'fas fa-fire' },
      { value: 'stovetop', label: 'Stovetop', icon: 'fas fa-burn' },
      { value: 'microwave', label: 'Microwave', icon: 'fas fa-microchip' },
      { value: 'blender', label: 'Blender', icon: 'fas fa-blender' },
      { value: 'food processor', label: 'Food Processor', icon: 'fas fa-cog' },
      { value: 'air fryer', label: 'Air Fryer', icon: 'fas fa-wind' },
      { value: 'slow cooker', label: 'Slow Cooker', icon: 'fas fa-clock' },
      { value: 'grill', label: 'Grill', icon: 'fas fa-fire-flame-curved' },
      { value: 'rice cooker', label: 'Rice Cooker', icon: 'fas fa-seedling' },
      { value: 'stand mixer', label: 'Stand Mixer', icon: 'fas fa-mixer' }
    ])

    const commonAllergies = ref([
      { value: 'nuts', label: 'Nuts', icon: 'fas fa-exclamation-triangle' },
      { value: 'peanuts', label: 'Peanuts', icon: 'fas fa-exclamation-triangle' },
      { value: 'shellfish', label: 'Shellfish', icon: 'fas fa-fish' },
      { value: 'fish', label: 'Fish', icon: 'fas fa-fish' },
      { value: 'eggs', label: 'Eggs', icon: 'fas fa-egg' },
      { value: 'milk', label: 'Milk', icon: 'fas fa-glass-water' },
      { value: 'soy', label: 'Soy', icon: 'fas fa-seedling' },
      { value: 'wheat', label: 'Wheat', icon: 'fas fa-wheat-awn' },
      { value: 'sesame', label: 'Sesame', icon: 'fas fa-circle-dot' }
    ])

    const dietaryPreferences = ref([
      { value: 'vegetarian', label: 'Vegetarian', icon: 'fas fa-leaf' },
      { value: 'vegan', label: 'Vegan', icon: 'fas fa-seedling' },
      { value: 'gluten-free', label: 'Gluten-free', icon: 'fas fa-ban' },
      { value: 'dairy-free', label: 'Dairy-free', icon: 'fas fa-times-circle' },
      { value: 'low-carb', label: 'Low-carb', icon: 'fas fa-weight-scale' },
      { value: 'keto', label: 'Keto', icon: 'fas fa-fire' },
      { value: 'paleo', label: 'Paleo', icon: 'fas fa-mountain' },
      { value: 'mediterranean', label: 'Mediterranean', icon: 'fas fa-olive-branch' }
    ])

    const commonIngredients = ref([
      { value: 'chicken breast', label: 'Chicken Breast', icon: 'fas fa-drumstick-bite' },
      { value: 'beef', label: 'Beef', icon: 'fas fa-cow' },
      { value: 'pork', label: 'Pork', icon: 'fas fa-bacon' },
      { value: 'fish', label: 'Fish', icon: 'fas fa-fish' },
      { value: 'eggs', label: 'Eggs', icon: 'fas fa-egg' },
      { value: 'tofu', label: 'Tofu', icon: 'fas fa-cube' },
      { value: 'rice', label: 'Rice', icon: 'fas fa-seedling' },
      { value: 'pasta', label: 'Pasta', icon: 'fas fa-utensils' },
      { value: 'bread', label: 'Bread', icon: 'fas fa-bread-slice' },
      { value: 'potatoes', label: 'Potatoes', icon: 'fas fa-apple-alt' },
      { value: 'quinoa', label: 'Quinoa', icon: 'fas fa-seedling' },
      { value: 'oats', label: 'Oats', icon: 'fas fa-wheat-awn' },
      { value: 'broccoli', label: 'Broccoli', icon: 'fas fa-tree' },
      { value: 'spinach', label: 'Spinach', icon: 'fas fa-leaf' },
      { value: 'carrots', label: 'Carrots', icon: 'fas fa-carrot' },
      { value: 'tomatoes', label: 'Tomatoes', icon: 'fas fa-apple-alt' },
      { value: 'onions', label: 'Onions', icon: 'fas fa-circle' },
      { value: 'garlic', label: 'Garlic', icon: 'fas fa-circle-dot' },
      { value: 'olive oil', label: 'Olive Oil', icon: 'fas fa-tint' },
      { value: 'butter', label: 'Butter', icon: 'fas fa-square' },
      { value: 'milk', label: 'Milk', icon: 'fas fa-glass-water' },
      { value: 'cheese', label: 'Cheese', icon: 'fas fa-cheese' },
      { value: 'yogurt', label: 'Yogurt', icon: 'fas fa-ice-cream' }
    ])

    const goals = ref([
      {
        value: 'weight loss',
        label: 'Weight Loss',
        description: 'Recipes to help you lose weight healthily',
        icon: 'fas fa-weight-scale'
      },
      {
        value: 'weight gain',
        label: 'Weight Gain',
        description: 'Nutrient-dense recipes for healthy weight gain',
        icon: 'fas fa-plus-circle'
      },
      {
        value: 'muscle building',
        label: 'Muscle Building',
        description: 'High-protein recipes to support muscle growth',
        icon: 'fas fa-dumbbell'
      },
      {
        value: 'maintenance',
        label: 'Maintenance',
        description: 'Balanced recipes to maintain your current weight',
        icon: 'fas fa-balance-scale'
      }
    ])

    const loading = computed(() => store.getters.loading)
    const error = computed(() => store.getters.error)

    const canProceed = computed(() => {
      switch (currentStep.value) {
        case 1:
          return form.value.kitchen_equipment.length > 0
        case 2:
          return true // Allergies are optional
        case 3:
          return true // Dietary preferences are optional
        case 4:
          return form.value.ingredients.length > 0
        case 5:
          return form.value.goal !== ''
        case 6:
          return form.value.name && form.value.email && form.value.password &&
                 form.value.weight && form.value.height && form.value.age
        default:
          return true
      }
    })

    const toggleSelection = (field, value) => {
      const index = form.value[field].indexOf(value)
      if (index > -1) {
        form.value[field].splice(index, 1)
      } else {
        form.value[field].push(value)
      }
    }

    // const addCustomItem = (field, customField) => {
    //   const value = eval(customField).value.trim()
    //   if (value && !form.value[field].includes(value.toLowerCase())) {
    //     form.value[field].push(value.toLowerCase())
    //     eval(customField).value = ''
    //   }
    // }

    // Add a generic icon to custom options, for example fa-utensils for kitchen equipment.
    const genericIcons = {
        kitchen_equipment: 'fas fa-utensils',
        allergies: 'fas fa-exclamation-circle',
        dietary_preferences: 'fas fa-leaf',
        ingredients: 'fas fa-apple-alt'
    }

    const addCustomItem = (field, customField) => {
        const value = eval(customField).value.trim()
        if (value && !form.value[field].includes(value.toLowerCase())) {
            // Add to selected values
            form.value[field].push(value.toLowerCase())

            // Add to options array with generic icon, using the reactive ref for that field's options
            switch (field) {
            case 'kitchen_equipment':
                kitchenEquipment.value.push({
                    value: value.toLowerCase(),
                    label: value,
                    icon: genericIcons.kitchen_equipment
                })
                break
            case 'allergies':
                commonAllergies.value.push({
                    value: value.toLowerCase(),
                    label: value,
                    icon: genericIcons.allergies
                })
                break
            case 'dietary_preferences':
                dietaryPreferences.value.push({
                    value: value.toLowerCase(),
                    label: value,
                    icon: genericIcons.dietary_preferences
                })
                break
            case 'ingredients':
                commonIngredients.value.push({
                    value: value.toLowerCase(),
                    label: value,
                    icon: genericIcons.ingredients
                })
                break
            }
            // Clear the input field
            eval(customField).value = ''
        }
    }


    const nextStep = () => {
      if (currentStep.value < totalSteps && canProceed.value) {
        currentStep.value++
      }
    }

    const previousStep = () => {
      if (currentStep.value > 1) {
        currentStep.value--
      }
    }

    const handleRegister = async () => {
      try {
        if(form.value.password.length < 8) {
            await Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 8 characters long!',
            })
            return
        }
        await store.dispatch('register', form.value)
        locale.value = form.value.language
        router.push('/dashboard')
      } catch (error) {
        console.error('Registration failed:', error)
      }
    }

    return {
      currentStep,
      totalSteps,
      form,
      customEquipment,
      customAllergy,
      customPreference,
      customIngredient,
      kitchenEquipment,
      commonAllergies,
      dietaryPreferences,
      commonIngredients,
      goals,
      loading,
      error,
      canProceed,
      toggleSelection,
      addCustomItem,
      nextStep,
      previousStep,
      handleRegister,
    }
  }
}
</script>

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
  margin-bottom: 1.5rem;
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
