<template>
  <div class="recipe-view-backdrop" @click="$emit('close')">
    <div class="recipe-view-container" @click.stop>
      <div class="recipe-card">
        <div class="card-header">
          <div class="header-content">
            <h2 class="card-title">{{ recipe.name }}</h2>
            <p class="card-subtitle">{{ recipe.description }}</p>
          </div>
          <button @click="$emit('close')" class="btn-close" :title="$t('Close')">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="card-body">
          <div class="recipe-layout">
            <div class="main-content">
              <div class="recipe-meta">
                <div class="meta-item">
                  <i class="fas fa-clock meta-icon"></i>
                  <div class="meta-text">
                    <strong>{{ recipe.time }}</strong>
                    <span>{{ $t('minutes') }}</span>
                  </div>
                </div>
                <div class="meta-item">
                  <i class="fas fa-users meta-icon"></i>
                  <div class="meta-text">
                    <strong>{{ recipe.servings }}</strong>
                    <span>{{ $t('servings') }}</span>
                  </div>
                </div>
                <div class="meta-item">
                  <i
                    class="meta-icon"
                    :class="
                      readyToCook
                        ? 'fa-check-circle text-success'
                        : 'fa-solid fa-triangle-exclamation'
                    "
                  ></i>
                  <div class="meta-text">
                    <strong v-if="readyToCook">{{ $t('Ready to cook') }}</strong>
                    <strong v-else>{{ missingIngredients }} {{ $t('missing') }}</strong>
                    <span>{{ $t('ingredients') }}</span>
                  </div>
                </div>
              </div>

              <div class="content-section">
                <h3 class="section-title">
                  <i class="fas fa-list-ul"></i> {{ $t('Instructions') }}
                </h3>
                <ol class="instructions-list">
                  <li
                    v-for="(instruction, index) in recipe.instructions"
                    :key="`inst-${index}`"
                  >
                    {{ instruction }}
                  </li>
                </ol>
              </div>

              <div class="content-section">
                <h3 class="section-title">
                  <i class="fas fa-question-circle"></i> {{ $t('Ask About This Recipe') }}
                </h3>
                <div v-if="!isSaved">
                    <p style="background-color: #fff8e8; padding: 20px; border-radius: 16px;">{{ $t('Have a question about this recipe? Save it!') }}<i class="fas fa-heart ms-2" style="color: #ff6b6b;"></i></p>
                </div>
                <div class="qa-input-group" v-if="isSaved">
                  <input
                    v-model="questionText"
                    type="text"
                    class="form-input"
                    :placeholder="$t('e.g., Can I substitute an ingredient?')"
                    @keyup.enter="askQuestion"
                  />
                  <button
                    @click="askQuestion"
                    class="btn btn-primary"
                    style="width: 50px;"
                    :disabled="!questionText.trim() || askingQuestion"
                  >
                    <i v-if="askingQuestion" class="fas fa-spinner fa-spin"></i>
                    <i v-else class="fas fa-paper-plane"></i>
                  </button>
                </div>
                <div v-if="questions.length > 0" class="qa-list">
                  <div v-for="(qa, index) in questions" :key="`qa-${index}`" class="qa-item">
                    <p class="qa-question">
                      <i class="fas fa-question"></i> {{ qa.question }}
                    </p>
                    <p class="qa-answer"><i class="fas fa-reply"></i> {{ qa.answer }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="sidebar-content">
              <div class="content-section">
                 <h3 class="section-title">
                  <i class="fas fa-cogs"></i> {{ $t('Actions') }}
                </h3>
                <div class="action-buttons">
                   <button
                    v-if="!isSaved"
                    @click="$emit('save', recipe)"
                    class="btn btn-secondary"
                  >
                    <i class="fas fa-heart"></i> {{ $t('Save') }}
                  </button>
                   <button
                    v-if="isSaved"
                    @click="$emit('plan', recipe)"
                    class="btn btn-secondary"
                  >
                    <i class="fas fa-calendar-plus"></i> {{ $t('Create Plan') }}
                  </button>
                  <button
                    v-if="isSaved"
                    @click="$emit('delete', recipe)"
                    class="btn btn-danger"
                  >
                    <i class="fas fa-trash"></i> {{ $t('Delete') }}
                  </button>
                </div>
              </div>

              <div class="content-section">
                <h3 class="section-title">
                  <i class="fas fa-shopping-cart"></i> {{ $t('Ingredients') }}
                </h3>
                <ul class="ingredients-list">
                  <li
                    v-for="(ingredient, index) in recipe.ingredients"
                    :key="`ing-${index}`"
                    :class="{ 'has-ingredient': getIngredientStatus(ingredient) }"
                  >
                    <span>{{ getIngredientText(ingredient) }}</span>
                    <i
                      class="fas"
                      :class="
                        getIngredientStatus(ingredient) ? 'fa-check-circle' : 'fa-times-circle'
                      "
                    ></i>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import ApiService from '../services/ApiService';

export default {
  name: 'FullRecipeView',
  props: {
    recipe: {
      type: Object,
      required: true,
    },
    isSaved: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['close', 'save', 'delete', 'plan', 'ask-question'],
  setup(props, { emit }) {
    const questionText = ref('');
    const questions = ref(props.recipe.questions || []);
    const askingQuestion = ref(false);

    const readyToCook = computed(() => {
      if (!props.recipe.ingredients) return true;
      return props.recipe.ingredients.every((ingredient) =>
        typeof ingredient === 'object' ? ingredient.have : true
      );
    });

    const missingIngredients = computed(() => {
      if (!props.recipe.ingredients) return 0;
      return props.recipe.ingredients.filter(
        (ingredient) => (typeof ingredient === 'object' ? !ingredient.have : false)
      ).length;
    });

    const getIngredientText = (ingredient) => {
      return typeof ingredient === 'object' ? ingredient.ing : ingredient;
    };

    const getIngredientStatus = (ingredient) => {
      return typeof ingredient === 'object' ? ingredient.have : true;
    };

    const askQuestion = async () => {
      if (!questionText.value.trim()) return;

      askingQuestion.value = true;
      try {

        console.log(props.recipe)

        const response = await ApiService.askQuestion({
            recipe_id: props.recipe.id,
            question: questionText.value
        })
        const answer = response.data.answer

        questions.value.unshift({
          question: questionText.value,
          answer: answer,
        });
        questionText.value = '';
      } catch (error) {
        console.error('Failed to ask question:', error);
        questions.value.unshift({
          question: questionText.value,
          answer: "Sorry, I couldn't get an answer right now.",
        });
      } finally {
        askingQuestion.value = false;
      }
    };

    return {
      questionText,
      questions,
      askingQuestion,
      readyToCook,
      missingIngredients,
      getIngredientText,
      getIngredientStatus,
      askQuestion,
    };
  },
};
</script>

<style scoped>
/* Adopting the design system variables from login.vue */
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
  --danger: #dc3545;
  --success: #198754;
  --warning: #ffc107;
}

/* Main Backdrop and Transitions */
.recipe-view-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(10px);
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s ease-out;
}

.recipe-view-container {
  width: 100%;
  max-width: 1100px;
  max-height: 90vh;
  margin: 2rem;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Card Styling (Inspired by login.vue) */
.recipe-card {
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(147, 75, 0, 0.1);
  position: relative;
  overflow: hidden;
  height: 100%;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  animation: slideUp 0.5s ease-out;
}

.recipe-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--brown), #a55500);
}

/* Card Header */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 2rem 2.5rem;
  border-bottom: 1px solid rgba(147, 75, 0, 0.1);
  flex-shrink: 0;
}
.card-title {
  font-size: 2.25rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.02em;
}
.card-subtitle {
  font-size: 1rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
  max-width: 60ch;
}
.btn-close {
  background: transparent;
  border: none;
  font-size: 1.75rem;
  color: var(--black);
  opacity: 0.5;
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 0.5rem;
  line-height: 1;
}
.btn-close:hover {
  opacity: 1;
  transform: rotate(90deg);
  color: var(--brown);
}

/* Card Body & Layout */
.card-body {
  overflow-y: auto;
  padding: 1.5rem 2.5rem;
  flex-grow: 1;
}
.recipe-layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2.5rem;
}
.section-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--brown);
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 2px solid var(--cosmic-latte);
  padding-bottom: 0.75rem;
}
.content-section {
    margin-bottom: 2.5rem;
}

/* Recipe Meta */
.recipe-meta {
  display: flex;
  gap: 1.5rem;
  background: var(--cosmic-latte);
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 2.5rem;
}
.meta-item {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.meta-icon {
  font-size: 2rem;
  color: var(--brown);
}
.meta-item .text-success { color: var(--success); }
.meta-item .text-warning { color: var(--warning); }
.meta-text {
  display: flex;
  flex-direction: column;
}
.meta-text strong {
  font-size: 1.1rem;
  font-weight: 700;
}
.meta-text span {
  font-size: 0.9rem;
  opacity: 0.7;
}

/* Instructions & Ingredients Lists */
.instructions-list {
  padding-left: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.instructions-list li {
  font-size: 1rem;
  line-height: 1.6;
  padding-left: 0.5rem;
}
.ingredients-list {
  list-style: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.ingredients-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-radius: 12px;
  background: var(--cosmic-latte);
  border: 1px solid rgba(147, 75, 0, 0.2);
  font-weight: 500;
  transition: all 0.3s ease;
}
.ingredients-list li .fas {
  font-size: 1.1rem;
}
.ingredients-list li.has-ingredient {
  background: #e3f9e9;
  border-color: rgba(25, 135, 84, 0.3);
}
.ingredients-list li.has-ingredient .fa-check-circle {
  color: var(--success);
}
.ingredients-list li:not(.has-ingredient) {
  opacity: 0.8;
}
.ingredients-list li:not(.has-ingredient) .fa-times-circle {
  color: var(--danger);
}

/* Q&A Section */
.qa-input-group {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}
.qa-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  max-height: 300px;
  overflow-y: auto;
  padding-right: 0.5rem;
}
.qa-item {
  background: var(--cosmic-latte);
  border-radius: 12px;
  padding: 1rem 1.25rem;
  border-left: 4px solid var(--brown);
}
.qa-question {
  font-weight: 600;
  margin: 0 0 0.5rem 0;
}
.qa-answer {
  margin: 0;
  opacity: 0.8;
}
.qa-item i {
  margin-right: 0.5rem;
  color: var(--brown);
  opacity: 0.6;
}

/* Shared Form & Button Styles (from login.vue) */
.form-input {
  flex-grow: 1;
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
}
.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.btn {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  cursor: pointer;
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-primary {
  background: var(--brown);
  color: var(--white);
  box-shadow: 0 4px 16px rgba(147, 75, 0, 0.2);
}
.btn-primary:hover:not(:disabled) {
  background: #a55500;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(147, 75, 0, 0.3);
}
.btn-secondary {
  background: var(--white);
  color: var(--brown);
  border-color: rgba(147, 75, 0, 0.3);
}
.btn-secondary:hover:not(:disabled) {
  background: var(--cosmic-latte);
  border-color: var(--brown);
  transform: translateY(-2px);
}
.btn-danger {
  background: var(--white);
  color: var(--danger);
  border-color: rgba(220, 53, 69, 0.4);
}
.btn-danger:hover:not(:disabled) {
    background: var(--danger);
    color: var(--white);
    border-color: var(--danger);
}
/* Keyframe Animations */
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

/* Responsive Design: Fullscreen on Mobile */
@media (max-width: 992px) {
  .recipe-layout {
    grid-template-columns: 1fr;
  }
  .sidebar-content {
    grid-row: 1; /* Move sidebar to the top on smaller screens */
  }
}

@media (max-width: 768px) {
  .recipe-view-container {
    max-height: 100vh;
    margin: 0;
  }
  .recipe-card {
    border-radius: 0;
    height: 100vh;
    max-height: 100vh;
  }
  .card-header {
    padding: 1.5rem;
  }
  .card-title {
    font-size: 1.75rem;
  }
  .card-body {
    padding: 1.5rem;
  }
  .recipe-meta {
    flex-direction: column;
    padding: 1rem;
    gap: 1rem;
    align-items: flex-start;
  }
  .action-buttons {
    flex-direction: row;
    flex-wrap: wrap;
  }
  .btn {
    flex-grow: 1;
  }
}
</style>
