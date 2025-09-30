<template>
  <overlay :loading="loading" gifName="knife" />
  <div class="dashboard min-vh-100 bg-light">
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
      <div class="container-fluid">
        <span class="navbar-brand">
          <i class="fas fa-utensils me-2"></i>
          {{ $t('Welcome To AiCook') }}
        </span>
        <div class="navbar-nav ms-auto">
          <button
            @click="logout"
            class="btn btn-outline-light btn-sm"
          >
            <i class="fas fa-sign-out-alt me-2"></i>
            {{ $t('Logout') }}
          </button>
        </div>
      </div>
    </nav>

    <div class="container-fluid mt-4">
      <!-- Tab Navigation -->
      <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            @click="activeTab = 'planner'"
            class="nav-link"
            :class="{ active: activeTab === 'planner' }"
            type="button"
          >
            <i class="fas fa-calendar-alt me-2"></i>
            {{ $t('Meal Planner') }}
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            @click="activeTab = 'explore'"
            class="nav-link"
            :class="{ active: activeTab === 'explore' }"
            type="button"
          >
            <i class="fas fa-search me-2"></i>
            {{ $t('Explore') }}
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            @click="activeTab = 'profile'"
            class="nav-link"
            :class="{ active: activeTab === 'profile' }"
            type="button"
          >
            <i class="fas fa-user me-2"></i>
            {{ $t('Profile') }}
          </button>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Meal Planner Tab -->
        <div v-if="activeTab === 'planner'" class="tab-pane active">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">
                <i class="fas fa-calendar-week me-2"></i>
                {{ $t('Week') }} {{ currentWeek }}
              </h5>
              <div class="btn-group">
                <button @click="previousWeek" class="btn btn-outline-primary btn-sm">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button @click="nextWeek" class="btn btn-outline-primary btn-sm">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div v-for="day in weekDays" :key="day.name" class="col-lg-2 col-md-3 col-sm-6 mb-4">
                  <div class="day-card h-100">
                    <h6 class="day-header text-center mb-3">
                      {{ $t(day.name) }}
                      <small class="d-block text-muted">{{ day.date }}</small>
                    </h6>
                    <div class="day-plans">
                      <RecipePlanComponent
                        v-for="plan in getDayPlans(day.date)"
                        :key="plan.id"
                        :plan="plan"
                        @edit="editPlan"
                        @delete="deletePlan"
                        @view="viewRecipe"
                      />
                      <div v-if="getDayPlans(day.date).length === 0" class="text-center text-muted">
                        <i class="fas fa-plus-circle fs-3 mb-2"></i>
                        <p class="small">No plans yet</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Explore Tab -->
        <div v-if="activeTab === 'explore'" class="tab-pane active">
          <div class="row">
            <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-4">
                    <i class="fas fa-search me-2"></i>
                    {{ $t('Explore') }}
                  </h5>

                  <form @submit.prevent="searchRecipes" class="mb-4">
                    <div class="row g-3">
                      <div class="col-12">
                        <label class="form-label">{{ $t('What do you want') }}</label>
                        <input
                          v-model="searchForm.extra_instruction"
                          type="text"
                          class="form-control"
                          placeholder="e.g., something spicy and quick"
                        >
                      </div>

                      <div class="col-md-3">
                        <label class="form-label">{{ $t('Portion Size') }}</label>
                        <input
                          v-model.number="searchForm.portion_size"
                          type="number"
                          class="form-control"
                          min="1"
                          max="10"
                        >
                      </div>

                      <div class="col-md-3">
                        <label class="form-label">{{ $t('Type') }}</label>
                        <select v-model="searchForm.type" class="form-select">
                          <option value="">Any type</option>
                          <option value="breakfast">{{ $t('breakfast') }}</option>
                          <option value="lunch">{{ $t('lunch') }}</option>
                          <option value="dinner">{{ $t('dinner') }}</option>
                          <option value="snack">{{ $t('snack') }}</option>
                          <option value="dessert">{{ $t('dessert') }}</option>
                          <option value="main dish">{{ $t('main dish') }}</option>
                          <option value="beverage">{{ $t('beverage') }}</option>
                        </select>
                      </div>

                      <div class="col-md-3">
                        <label class="form-label">{{ $t('Cooking Time') }}</label>
                        <input
                          v-model.number="searchForm.cooking_time"
                          type="number"
                          class="form-control"
                          min="5"
                          max="180"
                        >
                      </div>

                      <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                          <input
                            v-model="searchForm.allow_suggestions_with_missing_ingredients"
                            type="checkbox"
                            class="form-check-input"
                            id="allowMissing"
                          >
                          <label for="allowMissing" class="form-check-label">
                            {{ $t('Allow Missing Ingredients') }}
                          </label>
                        </div>
                      </div>

                      <div class="col-12">
                        <button
                          type="submit"
                          class="btn btn-primary btn-lg w-100"
                          :disabled="loading"
                        >
                          <i v-if="loading" class="fas fa-spinner fa-spin me-2"></i>
                          <i v-else class="fas fa-magic me-2"></i>
                          {{ $t("Let's Cook") }}
                        </button>
                      </div>
                    </div>
                  </form>

                  <!-- Search Results -->
                  <div v-if="searchResults.length > 0" class="search-results">
                    <h6 class="mb-3">Recipe Suggestions:</h6>
                    <div class="row g-3">
                      <div v-for="recipe in searchResults" :key="recipe.name" class="col-md-6">
                        <RecipeComponent
                          :recipe="recipe"
                          :is-saved="false"
                          @click="viewRecipe(recipe)"
                          @save="saveRecipe"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">
                    <i class="fas fa-heart me-2 text-danger"></i>
                    {{ $t('Saved Recipes') }}
                  </h6>
                </div>
                <div class="card-body">
                  <div v-if="savedRecipes.length === 0" class="text-center text-muted py-4">
                    <i class="fas fa-heart-broken fs-3 mb-2"></i>
                    <p class="small mb-0">No saved recipes yet</p>
                  </div>
                  <div v-else>
                    <RecipeComponent
                      v-for="recipe in savedRecipes"
                      :key="recipe.id"
                      :recipe="recipe"
                      :is-saved="true"
                      class="mb-3"
                      @click="viewRecipe(recipe)"
                      @delete="deleteRecipe"
                      @plan="openPlanModal"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Tab -->
        <div v-if="activeTab === 'profile'" class="tab-pane active">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>
                    {{ $t('Profile') }}
                  </h5>
                </div>
                <div class="card-body">
                  <form @submit.prevent="updateProfile">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $t('Name') }}</label>
                        <input
                          v-model="profileForm.name"
                          type="text"
                          class="form-control"
                        >
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $t('Email') }}</label>
                        <input
                          v-model="profileForm.email"
                          type="email"
                          class="form-control"
                          readonly
                        >
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $t('Weight') }}</label>
                        <input
                          v-model.number="profileForm.weight"
                          type="number"
                          step="0.1"
                          class="form-control"
                        >
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $t('Height') }}</label>
                        <input
                          v-model.number="profileForm.height"
                          type="number"
                          step="0.1"
                          class="form-control"
                        >
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $t('Age') }}</label>
                        <input
                          v-model.number="profileForm.age"
                          type="number"
                          class="form-control"
                        >
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $t('Goal') }}</label>
                        <select v-model="profileForm.goal" class="form-select">
                          <option value="weight loss">{{ $t('weight loss') }}</option>
                          <option value="weight gain">{{ $t('weight gain') }}</option>
                          <option value="muscle building">{{ $t('muscle building') }}</option>
                          <option value="maintenance">{{ $t('maintenance') }}</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">{{ $t('Language') }}</label>
                        <select v-model="profileForm.language" class="form-select" @change="changeLanguage">
                          <option value="en">English</option>
                          <option value="fr">Français</option>
                          <option value="es">Español</option>
                        </select>
                      </div>
                    </div>

                    <!-- Token Display -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="card bg-light">
                          <div class="card-body text-center">
                            <i class="fas fa-coins text-warning fs-3 mb-2"></i>
                            <h6>{{ $t('Recipe Tokens') }}</h6>
                            <span class="badge bg-warning text-dark fs-6">{{ user?.recipe_tokens || 0 }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card bg-light">
                          <div class="card-body text-center">
                            <i class="fas fa-question-circle text-info fs-3 mb-2"></i>
                            <h6>{{ $t('Question Tokens') }}</h6>
                            <span class="badge bg-info fs-6">{{ user?.question_tokens || 0 }}</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div v-if="error" class="alert alert-danger mb-3">
                      {{ error }}
                    </div>

                    <div class="d-grid">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="loading"
                      >
                        <i v-if="loading" class="fas fa-spinner fa-spin me-2"></i>
                        <i v-else class="fas fa-save me-2"></i>
                        {{ $t('Update Profile') }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Full Recipe View Modal -->
    <FullRecipeView
      v-if="selectedRecipe"
      :recipe="selectedRecipe"
      :is-saved="isRecipeSaved(selectedRecipe)"
      @close="selectedRecipe = null"
      @save="saveRecipe"
      @delete="deleteRecipe"
      @plan="openPlanModal"
      @ask-question="askQuestion"
    />

    <!-- Plan Modal -->
    <PlanModal
      v-if="showPlanModal"
      :recipe="recipeForPlan"
      @close="showPlanModal = false"
      @save="createPlan"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import ApiService from '../services/ApiService'
import RecipeComponent from '../components/RecipeComponent.vue'
import RecipePlanComponent from '../components/RecipePlanComponent.vue'
import FullRecipeView from '../components/FullRecipeView.vue'
import PlanModal from '../components/PlanModal.vue'
import overlay from '@/components/overlay.vue'

export default {
  name: 'Dashboard',
  components: {
    RecipeComponent,
    RecipePlanComponent,
    FullRecipeView,
    PlanModal,
    overlay
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const { locale } = useI18n()

    const activeTab = ref('planner')
    const currentWeek = ref(1)
    const selectedRecipe = ref(null)
    const showPlanModal = ref(false)
    const recipeForPlan = ref(null)
    const searchResults = ref([])

    const searchForm = ref({
      extra_instruction: '',
      portion_size: 2,
      type: '',
      cooking_time: 30,
      allow_suggestions_with_missing_ingredients: false
    })

    const profileForm = ref({
      name: '',
      email: '',
      weight: 0,
      height: 0,
      age: 0,
      goal: '',
      language: 'en'
    })

    const weekDays = computed(() => {
      const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
      const today = new Date()
      const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay() + 1))

      return days.map((day, index) => {
        const date = new Date(startOfWeek)
        date.setDate(startOfWeek.getDate() + index)
        return {
          name: day,
          date: date.toISOString().split('T')[0]
        }
      })
    })

    const user = computed(() => store.getters.user)
    const savedRecipes = computed(() => store.getters.savedRecipes)
    const mealPlans = computed(() => store.getters.mealPlans)
    const loading = computed(() => store.getters.loading)
    const error = computed(() => store.getters.error)

    const getDayPlans = (date) => {
      return mealPlans.value.filter(plan => {
        const planDate = new Date(plan.date).toISOString().split('T')[0]
        return planDate === date
      })
    }

    const isRecipeSaved = (recipe) => {
      return savedRecipes.value.some(r => r.name === recipe.name)
    }

    const initializeProfile = () => {
      if (user.value) {
        profileForm.value = {
          name: user.value.name || '',
          email: user.value.email || '',
          weight: user.value.weight || 0,
          height: user.value.height || 0,
          age: user.value.age || 0,
          goal: user.value.goal || '',
          language: user.value.language || 'en'
        }
      }
    }

    const logout = async () => {
      await store.dispatch('logout')
      router.push('/')
    }

    const previousWeek = () => {
      currentWeek.value = Math.max(1, currentWeek.value - 1)
    }

    const nextWeek = () => {
      currentWeek.value += 1
    }

    const searchRecipes = async () => {
      try {
        const response = await ApiService.generateRecipes(searchForm.value)
        searchResults.value = response.data || []
      } catch (error) {
        console.error('Search failed:', error)
        store.commit('SET_ERROR', error.response?.data?.message || 'Search failed')
      }
    }

    const viewRecipe = (recipe) => {
      selectedRecipe.value = recipe
    }

    const saveRecipe = async (recipe) => {
      try {
        await store.dispatch('saveRecipe', {
          name: recipe.name,
          description: recipe.description,
          time: recipe.time,
          servings: recipe.servings,
          ingredients: recipe.ingredients,
          instructions: recipe.instructions
        })
      } catch (error) {
        console.error('Save recipe failed:', error)
      }
    }

    const deleteRecipe = async (recipe) => {
      try {
        await store.dispatch('deleteRecipe', recipe.id)
      } catch (error) {
        console.error('Delete recipe failed:', error)
      }
    }

    const openPlanModal = (recipe) => {
      recipeForPlan.value = recipe
      showPlanModal.value = true
    }

    const createPlan = async (planData) => {
      try {
        await store.dispatch('createPlan', {
          name: planData.name,
          date: planData.date,
          recipe_id: recipeForPlan.value.id
        })
        showPlanModal.value = false
        recipeForPlan.value = null
      } catch (error) {
        console.error('Create plan failed:', error)
      }
    }

    const editPlan = async (plan, newData) => {
      try {
        await store.dispatch('updatePlan', {
          planId: plan.id,
          planData: newData
        })
      } catch (error) {
        console.error('Update plan failed:', error)
      }
    }

    const deletePlan = async (plan) => {
      try {
        await store.dispatch('deletePlan', plan.id)
      } catch (error) {
        console.error('Delete plan failed:', error)
      }
    }

    const updateProfile = async () => {
      try {
        await store.dispatch('updateUser', profileForm.value)
      } catch (error) {
        console.error('Update profile failed:', error)
      }
    }

    const changeLanguage = () => {
      locale.value = profileForm.value.language
    }

    const askQuestion = async (questionData) => {
      try {
        const response = await ApiService.askQuestion({
          recipe_id: selectedRecipe.value.id,
          question: questionData.question
        })
        return response.data.answer
      } catch (error) {
        console.error('Ask question failed:', error)
        throw error
      }
    }

    onMounted(() => {
      initializeProfile()
    })

    watch(user, () => {
      initializeProfile()
    }, { deep: true })

    return {
      activeTab,
      currentWeek,
      selectedRecipe,
      showPlanModal,
      recipeForPlan,
      searchResults,
      searchForm,
      profileForm,
      weekDays,
      user,
      savedRecipes,
      mealPlans,
      loading,
      error,
      getDayPlans,
      isRecipeSaved,
      logout,
      previousWeek,
      nextWeek,
      searchRecipes,
      viewRecipe,
      saveRecipe,
      deleteRecipe,
      openPlanModal,
      createPlan,
      editPlan,
      deletePlan,
      updateProfile,
      changeLanguage,
      askQuestion
    }
  }
}
</script>

<style scoped>
.day-card {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  padding: 1rem;
  background: white;
  min-height: 200px;
}

.day-header {
  border-bottom: 1px solid #dee2e6;
  padding-bottom: 0.5rem;
}

.nav-tabs .nav-link {
  border: none;
  color: #6c757d;
}

.nav-tabs .nav-link.active {
  background-color: #0d6efd;
  color: white;
  border-radius: 0.375rem;
}

.search-results {
  border-top: 1px solid #dee2e6;
  padding-top: 1rem;
}
</style>
