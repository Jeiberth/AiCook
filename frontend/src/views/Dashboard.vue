<!-- src/views/Dashboard.vue -->
<template>
  <overlay :loading="globalLoading" gifName="knife" />
  <overlay :loading="cooking" gifName="pan" />
  <div class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header -->
      <header class="dashboard-header">
        <div class="brand">
          <img src="/assets/favicon.png" alt="logo" style="width: 40px;">
          <span class="ms-1">AiCook</span>
        </div>
        <div class="user-actions">
          <span class="welcome-text d-none d-md-block">{{ $t('Welcome') }}, {{ user?.name }}</span>
          <button @click="logout" class="btn btn-secondary">
            <i class="fas fa-sign-out-alt"></i>
            <span class="d-none d-md-inline">{{ $t('Logout') }}</span>
          </button>
        </div>
      </header>

      <!-- Main Content Card -->
      <div class="dashboard-card">
        <!-- Tab Navigation -->
        <nav class="tab-navigation" >
          <button
            @click="activeTab = 'planner'"
            :class="{ active: activeTab === 'planner' }"
            :aria-selected="activeTab === 'planner'"
            role="tab"
          >
            <i class="fas fa-calendar-alt"></i> {{ $t('Meal Planner') }}
          </button>
          <button
            @click="activeTab = 'explore'"
            :class="{ active: activeTab === 'explore' }"
            :aria-selected="activeTab === 'explore'"
            role="tab"
          >
            <i class="fas fa-search"></i> {{ $t('Explore') }}
          </button>
          <button
            @click="activeTab = 'profile'"
            :class="{ active: activeTab === 'profile' }"
            :aria-selected="activeTab === 'profile'"
            role="tab"
          >
            <i class="fas fa-user"></i> {{ $t('Profile') }}
          </button>
        </nav>

        <!-- Tab Content -->
        <main class="tab-content">
          <!-- Meal Planner Tab -->
          <section v-if="activeTab === 'planner'" role="tabpanel">
            <div class="planner-header">
              <h2 class="section-title">{{ $t('Your Weekly Plan') }}</h2>
              <div class="week-navigator">
                <button @click="previousWeek" class="btn btn-secondary nav-btn btleft" :title="$t('previousWeek')">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <div class="date-range">
                  <i class="fas fa-calendar-week"></i>
                  <span>{{ weekDateRange }}</span>
                </div>
                <button @click="nextWeek" class="btn btn-secondary nav-btn btright" :title="$t('nextWeek')">
                  <i class="fas fa-chevron-right"></i>
                </button>
                <button @click="goToToday" class="btn btn-secondary today-btn d-none d-md-block">
                  {{ $t('Today') }}
                </button>
              </div>
            </div>
            <!-- <div class="planner-grid">
              <div v-for="day in weekDays" :key="day.date" class="day-card">
                <h3 class="day-title">
                  {{ $t(day.name) }}
                  <span class="day-date">{{ day.shortDate }}</span>
                </h3>
                <div class="day-plans">
                  <RecipePlanComponent
                    v-for="plan in getDayPlans(day.date)"
                    :key="plan.id"
                    :plan="plan"
                    @edit="editPlan"
                    @delete="deletePlan"
                    @view="viewRecipe"
                  />
                  <div
                    v-if="getDayPlans(day.date).length === 0"
                    class="no-plans-placeholder"
                  >
                    <i class="fa-solid fa-drumstick-bite" style="font-size: 24px;"></i>
                    <span>{{ $t('noPlansYet') }}</span>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="row" style="display: flex; justify-content: flex-start;">
              <div v-for="day in weekDays" :key="day.date" class="col-12 col-sm-6 col-md-4 col-lg-3 p-2">
                <div class="day-card">
                    <h3 class="day-title">
                    {{ $t(day.name) }}
                    <span class="day-date">{{ day.shortDate }}</span>
                    </h3>
                    <div class="day-plans">
                        <RecipePlanComponent
                            v-for="plan in getDayPlans(day.date)"
                            :key="plan.id"
                            :plan="plan"
                            @edit="editPlan"
                            @delete="deletePlan"
                            @view="viewRecipe"
                        />
                    <div
                        v-if="getDayPlans(day.date).length === 0"
                        class="no-plans-placeholder"
                    >
                        <i class="fa-solid fa-drumstick-bite" style="font-size: 24px;"></i>
                    </div>
                    </div>
                </div>
              </div>
            </div>

          </section>

          <!-- Explore Tab -->
          <section v-if="activeTab === 'explore'" role="tabpanel">
             <div class="explore-tabs" v-if="savedRecipes.length != 0">
                <button
                  @click="exploreSubTab = 'explore';"
                  :class="{ active: exploreSubTab === 'explore' }"
                >
                  <i class="fas fa-magic"></i> {{ $t('Explore') }}
                </button>
                <button
                  @click="exploreSubTab = 'saved';"
                  :class="{ active: exploreSubTab === 'saved' }"
                >
                  <i class="fas fa-bookmark"></i> {{ $t('Saved Recipes') }}
                </button>
              </div>

              <!-- Explore Sub-Tab Content -->
              <div v-if="exploreSubTab == 'explore'">
                <!-- <div class="explore-banner">

                  <div class="banner-content">
                    <h2 class="section-title">{{ $t('Find Your Next Meal') }}</h2>
                    <p>{{ $t('exploreSubtitle') }}</p>
                    <div class="token-display">
                        <i class="fas fa-coins"></i>
                        <strong>{{ user?.recipe_tokens || 0 }}</strong>
                        <span>{{ $t('Recipe Tokens Remaining') }}</span>
                    </div>
                  </div>
                </div> -->

                <form @submit.prevent="searchRecipes" class="search-form personal-form">
                    <div class="explore-banner" style="margin-bottom: 0px !important; cursor: pointer;" @click="openSearch = !openSearch">
                        <div class="banner-content" style="justify-items: center; text-align: center;">
                            <h2 class="section-title" style="margin-bottom: 0px !important">{{ $t('Find Your Next Meal') }}</h2>
                            <div class="token-display" style="margin-top: 7px !important">
                                <i class="fas fa-coins"></i>
                                <strong>{{ user?.recipe_tokens || 0 }}</strong>
                                <span>{{ $t('Recipe Tokens Remaining') }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="searchResults.length < 0 || openSearch" >
                        <div class="form-group mb-3">
                            <label class="form-label">{{ $t('What do you want') }}</label>
                            <input v-model="searchForm.extra_instruction" type="text" class="form-input" :placeholder="$t('e.g. Something with chicken, no milk, quick and fancy')"/>
                        </div>
                        <div class="form-row mb-3">
                            <div class="form-group">
                            <label class="form-label">{{ $t('Portion Size') }}</label>
                            <input v-model.number="searchForm.portion_size" type="number" class="form-input" min="1" max="10" />
                            </div>
                            <div class="form-group">
                            <label class="form-label">{{ $t('Type') }}</label>
                            <select v-model="searchForm.type" class="form-input">
                                <option value="">{{ $t('anyType') }}</option>
                                <option value="breakfast">{{ $t('breakfast') }}</option>
                                <option value="lunch">{{ $t('lunch') }}</option>
                                <option value="dinner">{{ $t('dinner') }}</option>
                                <option value="snack">{{ $t('snack') }}</option>
                            </select>
                            </div>
                            <div class="form-group">
                            <label class="form-label">{{ $t('Max Cooking Time (min)') }}</label>
                            <input v-model.number="searchForm.cooking_time" type="number" class="form-input" min="5" max="180" />
                            </div>
                        </div>
                        <div class="form-check-group mb-3">
                            <input v-model="searchForm.allow_suggestions_with_missing_ingredients" type="checkbox" id="allowMissing" class="form-check-input"/>
                            <label for="allowMissing" class="form-check-label">{{ $t('Allow Missing Ingredients') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary" :disabled="isSearching" style=" justify-self: center; width: 100%; text-align: center; justify-content: center;">
                            <i v-if="isSearching" class="fas fa-spinner fa-spin"></i><i v-else class="fas fa-magic"></i>
                            <span>{{ $t("Let's Cook") }}</span>
                        </button>
                    </div>
                </form>
                <div v-if="searchResults.length > 0" class="search-results">
                  <h3 class="subsection-title">{{ $t('Recipe Suggestions') }}</h3>
                  <div class="recipe-grid">
                    <RecipeComponent v-for="recipe in searchResults" :key="recipe.name" :recipe="recipe" :is-saved="false" @click="viewRecipe(recipe)" @save="saveRecipe" />
                  </div>
                </div>
              </div>

              <!-- Saved Recipes Sub-Tab Content -->
              <div v-if="exploreSubTab == 'saved'">
                 <h2 class="section-title">{{ $t('Your Saved Recipes') }}</h2>
                <div v-if="savedRecipes.length === 0" class="no-items-placeholder">
                  <i class="fas fa-bookmark"></i>
                  <span>{{ $t('noSavedRecipes') }}</span>
                  <p>{{ $t('exploreAndSavePrompt') }}</p>
                </div>
                <div v-else class="saved-recipes-list recipe-grid">
                  <RecipeComponent v-for="recipe in savedRecipes" :key="recipe.id" :recipe="recipe" :is-saved="true" @click="viewRecipe(recipe)" @delete="deleteRecipe" @plan="openPlanModal"/>
                </div>
              </div>
          </section>

          <!-- Profile Tab -->
          <section v-if="activeTab === 'profile'" role="tabpanel">
            <h2 class="section-title">{{ $t('Your Profile & Preferences') }}</h2>
            <form @submit.prevent="updateProfile" class="profile-form personal-form">
              <div class="form-row">
                 <div class="form-group"><label class="form-label">{{ $t('Name') }}</label><input v-model="profileForm.name" type="text" class="form-input"/></div>
                 <div class="form-group"><label class="form-label">{{ $t('Email') }}</label><input v-model="profileForm.email" type="email" class="form-input" readonly/></div>
              </div>
               <div class="form-row">
                 <div class="form-group"><label class="form-label">{{ $t('Weight (kg)') }}</label><input v-model.number="profileForm.weight" type="number" step="0.1" class="form-input"/></div>
                 <div class="form-group"><label class="form-label">{{ $t('Height (cm)') }}</label><input v-model.number="profileForm.height" type="number" step="0.1" class="form-input"/></div>
                 <div class="form-group"><label class="form-label">{{ $t('Age') }}</label><input v-model.number="profileForm.age" type="number" class="form-input"/></div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">{{ $t('Goal') }}</label>
                  <select v-model="profileForm.goal" class="form-input">
                    <option value="weight loss">{{ $t('weight loss') }}</option>
                    <option value="weight gain">{{ $t('weight gain') }}</option>
                    <option value="muscle building">{{ $t('muscle building') }}</option>
                    <option value="maintenance">{{ $t('maintenance') }}</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">{{ $t('Language') }}</label>
                  <select v-model="profileForm.language" class="form-input" @change="changeLanguage">
                     <option value="en">English</option><option value="fr">Français</option><option value="es">Español</option>
                  </select>
                </div>
              </div>
              <div class="preferences-grid">
                <EditableTagList :title="$t('Dietary Preferences')" v-model:items="profileForm.dietary_preferences" :placeholder="$t('e.g., Vegan, Low-Carb')"/>
                <EditableTagList :title="$t('Allergies')" v-model:items="profileForm.allergies" :placeholder="$t('e.g., Peanuts, Shellfish')"/>
                <EditableTagList :title="$t('Kitchen Equipment')" v-model:items="profileForm.kitchen_equipment" :placeholder="$t('e.g., Air Fryer, Blender')"/>
                <EditableTagList :title="$t('Available Ingredients')" v-model:items="profileForm.ingredients" :placeholder="$t('e.g., Olive oil, Chicken breast')"/>
              </div>
              <div class="token-info-grid">
                  <div class="token-card"><i class="fas fa-coins text-warning"></i><h6>{{ $t('Recipe Tokens') }}</h6><span class="token-count">{{ user?.recipe_tokens || 0 }}</span></div>
                  <div class="token-card"><i class="fas fa-question-circle text-info"></i><h6>{{ $t('Question Tokens') }}</h6><span class="token-count">{{ user?.question_tokens || 0 }}</span></div>
              </div>
              <button type="submit" class="btn btn-primary" :disabled="isUpdatingProfile" style="justify-content: center;">
                <i v-if="isUpdatingProfile" class="fas fa-spinner fa-spin"></i><i v-else class="fas fa-save"></i>
                <span>{{ $t('Update Profile') }}</span>
              </button>
            </form>
          </section>
        </main>
      </div>
    </div>
    <FullRecipeView v-if="selectedRecipe" :recipe="selectedRecipe" :is-saved="isRecipeSaved(selectedRecipe)" @close="selectedRecipe = null" @save="saveRecipe" @delete="deleteRecipe" @plan="openPlanModal" @ask-question="askQuestion"/>
    <PlanModal v-if="showPlanModal" :recipe="recipeForPlan" @close="showPlanModal = false" @save="createPlan"/>
  </div>
</template>


<script setup>

    import { ref, computed, onMounted, watch } from 'vue';
    import { useStore } from 'vuex';
    import { useRouter } from 'vue-router';
    import { useI18n } from 'vue-i18n';
    import Swal from 'sweetalert2';
    import ApiService from '../services/ApiService';

    // Import Components
    import RecipeComponent from '../components/RecipeComponent.vue';
    import RecipePlanComponent from '../components/RecipePlanComponent.vue';
    import FullRecipeView from '../components/FullRecipeView.vue';
    import PlanModal from '../components/PlanModal.vue';
    import EditableTagList from '../components/EditableTagList.vue';
    import overlay from '@/components/overlay.vue';

    const store = useStore();
    const router = useRouter();
    const { t, locale } = useI18n();

    // --- State Refs ---
    const activeTab = ref('planner');
    const currentWeekStart = ref(getStartOfWeek(new Date()));
    const selectedRecipe = ref(null);
    const showPlanModal = ref(false);
    const recipeForPlan = ref(null);
    const searchResults = ref([]);
    const isSearching = ref(false);
    const isUpdatingProfile = ref(false);
    const exploreSubTab = ref('explore');
    const openSearch  = ref(true);

    // --- Form Refs ---
    const searchForm = ref({
      extra_instruction: '',
      portion_size: 2,
      type: '',
      cooking_time: 30,
      allow_suggestions_with_missing_ingredients: false
    });

    const profileForm = ref({
        name: '',
        email: '',
        weight: 0,
        height: 0,
        age: 0,
        goal: '',
        dietary_preferences: [],
        allergies: [],
        kitchen_equipment: [],
        ingredients: [],
        language: 'en',
    });

    // --- Computed Properties ---
    const globalLoading = computed(() => store.getters.loading);
    const user = computed(() => store.getters.user);
    const savedRecipes = computed(() => store.getters.savedRecipes);
    const mealPlans = computed(() => store.getters.mealPlans);

    const weekDateRange = computed(() => {
        const start = currentWeekStart.value;
        const end = new Date(start);
        end.setDate(start.getDate() + 6);
        const options = { month: 'short', day: 'numeric' };
        return `${start.toLocaleDateString(undefined, options)} - ${end.toLocaleDateString(undefined, options)}, ${start.getFullYear()}`;
    });

    const weekDays = computed(() => {
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        const start = currentWeekStart.value;
        return days.map((day, index) => {
            const date = new Date(start);
            date.setDate(start.getDate() + index);
            return {
                name: day,
                date: date.toISOString().split('T')[0],
                shortDate: date.toLocaleDateString(undefined, { day: '2-digit' }),
            };
        });
    });

    // --- Helper Functions ---
    function getStartOfWeek(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    }

    const showSuccessToast = (messageKey) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: t(messageKey),
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    };

    const showErrorAlert = (messageKey) => {
        Swal.fire({
            icon: 'error',
            title: t('errorTitle'),
            text: t(messageKey),
        });
    };

    // --- Planner Navigation ---
    const previousWeek = () => {
        const newDate = new Date(currentWeekStart.value);
        newDate.setDate(newDate.getDate() - 7);
        currentWeekStart.value = newDate;
    };

    const nextWeek = () => {
        const newDate = new Date(currentWeekStart.value);
        newDate.setDate(newDate.getDate() + 7);
        currentWeekStart.value = newDate;
    };

    const goToToday = () => {
        currentWeekStart.value = getStartOfWeek(new Date());
    };

    // --- API Methods ---
    const logout = async () => {
        router.push('/');
        await store.dispatch('logout');
    };

    const updateProfile = async () => {
        isUpdatingProfile.value = true;
        try {
            await store.dispatch('updateUser', profileForm.value);
            showSuccessToast('profileUpdateSuccess');
        } catch (error) {
            showErrorAlert('profileUpdateError');
        } finally {
            isUpdatingProfile.value = false;
        }
    };

    const cooking = ref(false);
    const searchRecipes = async () => {
        isSearching.value = true;
        cooking.value = true;
        try {
            const response = await ApiService.generateRecipes(searchForm.value);
            searchResults.value = response.data || [];
            openSearch.value = false;
            if (searchResults.value.length === 0) {
                showSuccessToast('noRecipesFound');
            }
        } catch (error) {
            showErrorAlert('searchFailed');
        } finally {
            isSearching.value = false;
            cooking.value = false;
        }
    };



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

    const viewRecipe = (recipe) => {
      selectedRecipe.value = recipe
    }

    const isRecipeSaved = (recipe) => {
      return savedRecipes.value.some(r => r.name === recipe.name)
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

    const getDayPlans = (date) => {
      return mealPlans.value.filter(plan => {
        const planDate = new Date(plan.date).toISOString().split('T')[0]
        return planDate === date
      })
    }

    // const saveRecipe = async (recipe) => {
    //   try {
    //     await store.dispatch('saveRecipe', {
    //       name: recipe.name,
    //       description: recipe.description,
    //       time: recipe.time,
    //       servings: recipe.servings,
    //       ingredients: recipe.ingredients,
    //       instructions: recipe.instructions
    //     })
    //   } catch (error) {
    //     console.error('Save recipe failed:', error)
    //   }
    // }

    const saveRecipe = async (recipe) => {
        try {
            const response = await store.dispatch('saveRecipe', {
                name: recipe.name,
                description: recipe.description,
                time: recipe.time,
                servings: recipe.servings,
                ingredients: recipe.ingredients,
                instructions: recipe.instructions
            });

            // Get the saved recipe with ID from the response
            const savedRecipe = response.data.recipe;

            // Update the selectedRecipe with the ID
            if (selectedRecipe.value && selectedRecipe.value.name === recipe.name) {
                selectedRecipe.value = { ...selectedRecipe.value, id: savedRecipe.id };
            }

            // Also update the searchResults if this recipe is in there
            const searchResultIndex = searchResults.value.findIndex(r => r.name === recipe.name);
            if (searchResultIndex !== -1) {
                searchResults.value[searchResultIndex] = { ...searchResults.value[searchResultIndex], id: savedRecipe.id };
            }

        } catch (error) {
            console.error('Save recipe failed:', error);
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

    const initializeProfile = () => {
        if (user.value) {
            profileForm.value = {
                name: user.value.name || '',
                email: user.value.email || '',
                weight: user.value.weight || 0,
                height: user.value.height || 0,
                age: user.value.age || 0,
                goal: user.value.goal || 'maintenance',
                language: user.value.language || 'en',
                dietary_preferences: user.value.dietary_preferences || [],
                allergies: user.value.allergies || [],
                kitchen_equipment: user.value.kitchen_equipment || [],
                ingredients: user.value.ingredients || [],
            };
        }
    };

    onMounted(initializeProfile);
    watch(user, initializeProfile, { deep: true });
</script>


<style scoped>
/* --- Import shared styles from login.vue logic --- */
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
  --danger: #dc3545;
}

/* --- Main page layout --- */
.dashboard-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  padding: 1rem;
}
.dashboard-container {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  animation: slideUp 0.6s ease-out;
}

/* --- Header --- */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
  margin-bottom: 1rem;
}
.brand { font-size: 1.5rem; font-weight: 700; color: var(--brown); display: flex; align-items: center; gap: 0.75rem; }
.user-actions { display: flex; align-items: center; gap: 1rem; }

/* --- Main Card --- */
.dashboard-card {
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(147, 75, 0, 0.1);
  position: relative;
  overflow: hidden;
  padding: 1.5rem;
}
.dashboard-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: linear-gradient(90deg, var(--brown), #a55500);
}

/* --- Tab Navigation --- */
.tab-navigation {
  display: flex; gap: 0.5rem; border-bottom: 2px solid var(--cosmic-latte);
  margin-bottom: 1rem; overflow: hidden;
}
.tab-navigation button {
  background: none; border: none; padding: 1rem; font-size: 1rem;
  font-weight: 600; color: var(--black); opacity: 0.6; cursor: pointer;
  transition: all 0.3s ease; border-bottom: 3px solid transparent;
  transform: translateY(2px); flex-shrink: 0;
}
.tab-navigation button.active { opacity: 1; color: var(--brown); border-bottom-color: var(--brown); }
.tab-navigation button:hover:not(.active) { background: var(--cosmic-latte); border-radius: 8px 8px 0 0; }

/* --- Section Styling --- */
.section-title { font-size: 1.75rem; font-weight: 700; color: var(--black); margin-bottom: 1.5rem; }
.subsection-title { font-size: 1.25rem; margin-top: 2rem; margin-bottom: 1rem; font-weight: 700; }

/* --- Planner --- */
.planner-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.week-navigator { display: flex; align-items: center; gap: 0.5rem; background: var(--cosmic-latte); padding: 0.5rem; border-radius: 12px; }
.date-range { font-weight: 600; padding: 0 1rem; display: flex; align-items: center; gap: 0.5rem; }
.planner-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem; }
.day-card { background: #fdfdfd; border: 1px solid rgba(0,0,0,0.05); border-radius: 16px; padding: 1rem;     height: 100%;}
.day-title { font-size: 1rem; font-weight: 700; margin-bottom: 1rem; display: flex; justify-content: space-between; }
.day-date { font-weight: 500; opacity: 0.6; }
.no-plans-placeholder { text-align: center; padding: 2rem 0; opacity: 0.5; }

/* --- Explore --- */
.explore-tabs { display: flex; border-bottom: 2px solid var(--cosmic-latte); margin-bottom: 1.5rem; }
.explore-tabs button { background: none; border: none; padding: 0.75rem 1.25rem; font-weight: 600; opacity: 0.6; }
.explore-tabs button.active { opacity: 1; color: var(--brown); box-shadow: 0 2px 0 var(--brown); }
.explore-banner { position: relative; border-radius: 16px; overflow: hidden; padding: 1.5rem 1rem; background: var(--cosmic-latte); margin-bottom: 2rem; }
.banner-content { position: relative; z-index: 2; }
.token-display { background: rgba(255,255,255,0.7); backdrop-filter: blur(5px); display: inline-flex; padding: 0.5rem 1rem; border-radius: 20px; align-items: center; gap: 0.5rem; margin-top: 1rem; }
.recipe-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }

/* --- Profile & Forms --- */
.personal-form { display: flex; flex-direction: column; gap: 1.5rem; margin: auto; }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-row { display: grid; grid-template-columns: 1fr; gap: 1rem; }
.form-label { font-size: 0.9rem; font-weight: 600; color: var(--black); opacity: 0.8; }
.form-input { padding: 1rem; border: 2px solid rgba(147, 75, 0, 0.2); border-radius: 12px; font-size: 1rem; }
.form-input:focus { border-color: var(--brown); box-shadow: 0 0 0 4px rgba(147, 75, 0, 0.1); }
.form-check-group { display: flex; align-items: center; gap: 0.5rem; }
.preferences-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
.token-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.token-card { background: var(--cosmic-latte); border-radius: 16px; padding: 1.5rem; text-align: center; }
.token-card i { font-size: 2rem; margin-bottom: 0.5rem; }
.token-count { font-size: 1.5rem; font-weight: 700; color: var(--brown); }

/* --- Mobile Responsiveness --- */
@media (min-width: 768px) {
  .dashboard-card { padding: 2.5rem; }
  .form-row { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
  .preferences-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 992px) {
  .planner-grid { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
}

@media (max-width: 767px) {
  .dashboard-page { padding: 0.5rem; }
  .dashboard-header { border-radius: 16px; padding: 1rem; }
  .dashboard-card { padding: 1rem; }
  .tab-navigation button { padding: 0.75rem; font-size: 0.9rem; }
  .planner-grid { grid-template-columns: 1fr; }
}
</style>



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

@media (max-width: 768px) {
  .btleft {
    width: 10px !important;
  }

  .btright {
    width: 10px !important;
  }
}

@media (max-width: 475px) {
  .date-range {
    padding: 0px
  }

}


</style>

